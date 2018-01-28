<?php

namespace SXFCore\Install;

use SXFCore\Install;

abstract class AbstractSetup extends \XF\AddOn\AbstractSetup
{
	protected $mySql;
	
	public function __construct(\XF\AddOn\AddOn $addOn, \XF\App $app)
	{
		parent::__construct($addOn, $app);
		
		$mySqlClass = "\\{$this->addOn->addon_id}\\Install\\Data\\MySql";
		
		$this->mySql = new $mySqlClass();
	}
	
	protected function _preInstall()
	{
		
	}
	
	protected function _postInstall()
	{
		
	}
	
	protected function _preUpgrade()
	{
		
	}
	
	protected function _postUpgrade()
	{
		
	}
	
	protected function _preUninstall()
	{
		
	}
	
	protected function _postUninstall()
	{
		
	}
	
	public function getData($dataKey = null)
	{
		return $this->mySql->getData($dataKey);
	}
	
	public function importTable($dataKey, $tableName = null, $query = true)
	{
		$data = $this->getData($dataKey);
		
		if (is_null($data)) return;
		
		if (is_null($tableName))
		{
			$tableName = $dataKey;
		}
		
		$sm = $this->schemaManager();
		
		if (isset($data['create_callback']))
		{
			$sm->createTable($tableName, $data['create_callback']);
		}
		
		if (isset($data['alter_callback']))
		{
			$sm->alterTable($tableName, $data['alter_callback']);
		}
		
		if ($query)
		{
			$this->importData($dataKey, $tableName);
		}
	}
	
	public function importData($dataKey, $tableName = null)
	{
		$data = $this->getData($dataKey);
		
		if (empty($data['query'])) return;
		
		if (is_null($tableName))
		{
			$tableName = $dataKey;
		}
		
		if (!is_array($data['query']))
		{
			$data['query'] = [$data['query']];
		}
		
		foreach ($data['query'] as $query)
		{
			$query = str_ireplace('[table]', $tableName, $query);
			
			$this->db()->query($query);
		}
	}
	
	public function getPossibleUpgradeFileNames()
	{
		$searchDir = \XF::getAddOnDirectory() . '/' . $this->addOn->addon_id . '/Install/Upgrade';

		$upgrades = [];
		foreach (glob($searchDir . '/*.php') AS $file)
		{
			$file = basename($file);

			$versionId = intval($file);
			if (!$versionId)
			{
				continue;
			}

			$upgrades[$versionId] = $searchDir . '/' . $file;
		}

		ksort($upgrades, SORT_NUMERIC);

		return $upgrades;
	}

	public function getRemainingUpgradeVersionIds($lastCompletedVersion)
	{
		$upgrades = $this->getPossibleUpgradeFileNames();
		$offset = 0;

		foreach ($upgrades AS $upgrade => $file)
		{
			if ($upgrade > $lastCompletedVersion)
			{
				return array_slice($upgrades, $offset, null, true);
			}

			$offset++;
		}

		return [];
	}

	public function getNextUpgradeVersionId($lastCompletedVersion)
	{
		$upgrades = $this->getRemainingUpgradeVersionIds($lastCompletedVersion);
		reset($upgrades);
		return key($upgrades);
	}

	public function getNewestUpgradeVersionId()
	{
		$upgrades = $this->getRemainingUpgradeVersionIds(0);
		end($upgrades);
		return key($upgrades);
	}

	/**
	 * @param integer $versionId
	 * @param App $app
	 *
	 * @return AbstractUpgrade
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getUpgrade($versionId)
	{
		$versionId = intval($versionId);
		if (!$versionId)
		{
			throw new \InvalidArgumentException('No upgrade version ID specified.');
		}

		$upgrades = $this->getPossibleUpgradeFileNames();
		if (isset($upgrades[$versionId]))
		{
			require_once($upgrades[$versionId]);
			$class = '\\' . $this->addon . '\\Install\\Upgrade\\Version' . $versionId;
			return new $class($this->app);
		}

		throw new \InvalidArgumentException('Could not find the specified upgrade.');
	}

	public function getCurrentVersion()
	{
		if ($this->currentVersion === null)
		{
			$existingVersion = $this->db()->fetchOne("
				SELECT version_id
				FROM xf_addon
				WHERE addon_id = '{$this->addOn->addon_id}'
			");

			$this->currentVersion = $existingVersion ? $existingVersion : 0;
		}

		return $this->currentVersion;
	}
	
	public function install(array $stepParams = [])
	{
		$this->_preInstall();
		
		$dataMySql = $this->getData();
		
		foreach ($dataMySql as $dataKey => $data)
		{
			if (empty($data['import']) || $data['import'] !== true) continue;

			$this->importTable($dataKey, null, true);
		}
		
		$this->_postInstall();
	}
	
	public function upgrade(array $stepParams = [])
	{
		$this->_preUpgrade();
		
		$currentVersion = $this->getCurrentVersion();
		$nextVersionIds = $this->getRemainingUpgradeVersionIds($currentVersion);
		
		foreach ($nextVersionIds AS $versionId => $file)
		{
			$upgrade = $this->getUpgrade($versionId);
			
			for ($i = 1; true; $i++)
			{
				$step = 'step' . $i;
				
				if (method_exists($upgrade, $step))
			    {
					$upgrade->$step();
					
					continue;
				}
				
				break;
			}
		}
		
		$this->_postUpgrade();
	}
	
	public function uninstall(array $stepParams = [])
    {
		$this->_preUninstall();
		
		$sm = $this->schemaManager();
		
		foreach ($this->getData() AS $dataKey => $data)
		{
			if (isset($data['alter_callback']))
			{
				if (isset($data['alter_drop_callback']))
				{
					$sm->alterTable($dataKey, $data['alter_drop_callback']);
				}
				
				continue;
			}
			
			if (empty($data['import']) || $data['import'] !== true) continue;
			
			$sm->dropTable($dataKey);
		}
		
		$this->_postUninstall();
	}
}