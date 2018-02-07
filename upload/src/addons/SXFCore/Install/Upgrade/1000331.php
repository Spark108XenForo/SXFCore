<?php

namespace SXFCore\Install\Upgrade;

use XF\Db\Schema\Alter;

class Version1000331 extends AbstractUpgrade
{
	public function step1()
	{
		$this->importTable('xf_thread_field');
	}
}