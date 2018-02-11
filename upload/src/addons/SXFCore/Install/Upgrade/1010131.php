<?php

namespace SXFCore\Install\Upgrade;

use XF\Db\Schema\Alter;

class Version1010131 extends AbstractUpgrade
{
	public function step1()
	{
		$this->importTable('sxfcore_component');
	}
}