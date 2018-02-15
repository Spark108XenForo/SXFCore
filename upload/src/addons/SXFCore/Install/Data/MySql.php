<?php

namespace SXFCore\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{
	public function get($data)
	{
		$data['sxfcore_component'] = [
			'import' => true,
			'create' => function (Create $table)
			{
				$table->addColumn('component_id', 'VARBINARY', 50);
				$table->addColumn('addon_dependencies', 'MEDIUMBLOB')->nullable();
				$table->addColumn('component_dependencies', 'MEDIUMBLOB')->nullable();
				$table->addColumn('enabled', 'TINYINT')->setDefault(1);
				
				$table->addPrimaryKey('component_id');
			},
			'create_query' => "
				INSERT INTO [table] 
					(`component_id`, `addon_dependencies`, `component_dependencies`, `enabled`) 
				VALUES
					(0x7468726561645f6669656c645f68696465, 0x613a303a7b7d, 0x613a303a7b7d, 1),
					(0x757365725f6669656c645f68696465, 0x613a303a7b7d, 0x613a303a7b7d, 1),
					(0x757365725f67656e646572, 0x613a303a7b7d, 0x613a303a7b7d, 1),
					(0x6F74686572, 0x613a303a7b7d, 0x613a303a7b7d, 1);
			"
		];
		
		$data['xf_user'] = [
			'import' => true,
			//'create' => '',
			//'create_query' => '',
			'create_alter' => function(Alter $table)
			{
				$table->addColumn('sxfcore_gender', 'enum')->values(['male','female'])->nullable();
			},
			//'drop_query' => '',
			'drop_alter' => function(Alter $table)
			{
				$table->dropColumns('sxfcore_gender');
			}
		];
		
		$data['xf_thread_field'] = [
			'import' => true,
			'create_alter' => function(Alter $table)
			{
				$table->addColumn('viewable_user_group_ids', 'BLOB');
			},
			'drop_alter' => function(Alter $table)
			{
				$table->dropColumns('viewable_user_group_ids');
			}
		];
		
		$data['xf_user_field_value'] = [
			'import' => true,
			'create_alter' => function(Alter $table)
			{
				$table->addColumn('sxfcore_hide_field', 'ENUM')->values(['hide', 'subscriber', 'authorized', 'all'])->setDefault('hide');
			},
			'drop_alter' => function(Alter $table)
			{
				$table->dropColumns('sxfcore_hide_field');
			}
		];
		
		return $data;
	}
}