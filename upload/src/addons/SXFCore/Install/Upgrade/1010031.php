<?php

namespace SXFCore\Install\Upgrade;

use XF\Db\Schema\Alter;

class Version1010031 extends AbstractUpgrade
{
	public function step1()
	{
		$this->importTable('xf_user_field_value');
	}
}