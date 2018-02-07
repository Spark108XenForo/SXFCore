<?php

namespace SXFCore\Install\Upgrade;

abstract class AbstractUpgrade
{
	protected $setup;
	
	protected $app;
	
	public function __construct(\SXFCore\Install\AbstractSetup $setup, \XF\App $app)
	{
		$this->setup = $setup;
		$this->app = $app;
	}
	
	protected function getSetup()
	{
		return $this->setup;
	}
	
	protected function importTable($dataKey, $tableName = null, $query = true)
	{
		$this->setup->importTable($dataKey, $tableName, $query);
	}
	
	protected function dropTable($dataKey, $tableName = null, $query = true)
	{
		$this->setup->dropTable($dataKey, $tableName, $query);
	}
	
	protected function queryData($dataKey, $tableName = null, $drop = false)
	{
		$this->setup->queryData($dataKey, $tableName, $drop);
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