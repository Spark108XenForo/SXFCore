<?php

namespace SXFCore\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{
	public function get($data)
	{
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
				$table->addColumn('sxfcore_hide_field', 'ENUM')->values('hide', 'subscriber', 'authorized', 'all')->setDefault('hide');
			},
			'drop_alter' => function(Alter $table)
			{
				$table->dropColumns('sxfcore_hide_field');
			}
		];
		
		return $data;
	}
}