<?php

namespace SXFCore\XF\Entity;

use XF\Mvc\Entity\Structure;

class UserField extends XFCP_UserField
{
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);

		$structure->columns['sxfcore_hide_field'] = ['type' => self::BOOL, 'default' => false];
		
		return $structure;
	}
}