<?php

namespace SXFMP\Install\Upgrade;

use SXFMP\Install\Install;

abstract class AbstractUpgrade
{
	protected $app;
	protected $install;
	
	public function __construct(\XF\App $app)
	{
		$this->app = $app;
		$this->install = new Install($app);
	}
	
	protected function getInstall()
	{
		return $this->install;
	}
	
	protected function importTable($dataKey, $tableName = null, $query = true)
	{
		$this->install->importTable($dataKey, $tableName, $query);
	}
	
	protected function importData($dataKey, $tableName = null)
	{
		$this->install->importData($dataKey, $tableName);
	}
	
	/**
	 * @return \XF\Db\AbstractAdapter
	 */
	protected function db()
	{
		return $this->app->db();
	}

	/**
	 * @return \XF\Db\SchemaManager
	 */
	protected function schemaManager()
	{
		return $this->db()->getSchemaManager();
	}
}