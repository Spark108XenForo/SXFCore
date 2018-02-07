<?php

namespace SXFCore\XF\Entity;

use XF\Mvc\Entity\Structure;

class ThreadField extends XFCP_ThreadField
{
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);

		$structure->columns['viewable_user_group_ids'] = ['type' => self::LIST_COMMA, 'default' => [-1]];
		
		return $structure;
	}
}