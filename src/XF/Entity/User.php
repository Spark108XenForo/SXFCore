<?php

namespace SXFCore\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);
		
		$structure->columns += [
			'sxfcore_gender' => ['type' => self::STR, 'nullable' => true, 'allowedValues' => ['male', 'female']]
		];
		
		return $structure;
	}
}