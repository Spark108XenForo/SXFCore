<?php

namespace SXFCore\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);
		
		$componentRepo = \XF::repository('SXFCore:Component');
		
		if ($componentRepo->isEnabled('user_gender'))
		{
			$structure->columns += [
				'sxfcore_gender' => ['type' => self::STR, 'nullable' => true, 'allowedValues' => ['male', 'female']]
			];
		}
		
		return $structure;
	}
	
	
	public function isFollower(\XF\Entity\User $user)
	{
		$userFolow = \XF::finder('XF:UserFollow')->where([
			'follow_user_id' => $this->user_id,
			'user_id' => $user->user_id
		])->fetchOne();
		
		return $userFolow ? true : false;
	}
}