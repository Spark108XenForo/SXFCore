<?php

namespace SXFCore\XF\Entity;

use XF\Mvc\Entity\Structure;

class UserFieldValue extends XFCP_UserFieldValue
{
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);

		$componentRepo = \XF::repository('SXFCore:Component');
		
		if ($componentRepo->isEnabled('user_field_hide'))
		{
			$structure->columns['sxfcore_hide_field'] = ['type' => self::STR, 'default' => 'hide',
				'allowedValue' => ['hide', 'subscriber', 'authorized', 'all']
			];
		
			$structure->relations += [
				'User' => [
					'entity' => 'XF:User',
					'type' => self::TO_ONE,
					'conditions' => 'user_id',
					'primary' => true
				]
			];
		}
		
		return $structure;
	}
}