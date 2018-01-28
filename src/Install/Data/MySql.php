<?php

namespace SXFCore\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{
	public function getData($dataKey = null)
	{
		$data = [];
		
		$data['xf_user'] = [
			'import' => true,
			'alter_callback' => function(Alter $table)
			{
				$table->addColumn('sxfcore_gender', 'enum')->values(['male','female'])->nullable();
			},
			'alter_drop_callback' => function(Alter $table)
			{
				$table->dropColumns('sxfcore_gender');
			}
		];
		
		if ($dataKey !== null)
		{
			return $data[$dataKey];
		}
		
		return $data;
	}
}