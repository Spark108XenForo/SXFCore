<?php

namespace SXFCore\XF\Entity;

use XF\Mvc\Entity\Structure;

class ThreadField extends XFCP_ThreadField
{
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);

		$componentRepo = \XF::repository('SXFCore:Component');
		
		if ($componentRepo->isEnabled('thread_field_hide'))
		{
			$structure->columns['viewable_user_group_ids'] = ['type' => self::LIST_COMMA, 'default' => [-1]];
		}
		
		return $structure;
	}
}