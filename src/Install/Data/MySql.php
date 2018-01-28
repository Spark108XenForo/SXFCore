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
		
		return $data;
	}
}