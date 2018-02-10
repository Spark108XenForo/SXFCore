<?php

namespace SXFCore\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class User extends XFCP_User
{
	public function actionEdit(ParameterBag $params)
	{
		$view = parent::actionEdit($params);
		
		$fieldValues = \XF::finder('XF:UserFieldValue')->where('user_id', $params->user_id)->fetch();
		
		$sxfcoreFields = [];
		foreach ($fieldValues as $fieldValue)
		{
			$sxfcoreFields[$fieldValue->field_id] = $fieldValue;
		}
		
		$view->setParams([
			'sxfcore_fields' => $sxfcoreFields
		]);
		
		return $view;
	}
	
	public function userSaveProcess(\XF\Entity\User $user)
	{
		$form = parent::userSaveProcess($user);
		
		$fieldHides = $this->filter('sxfcorefield_hide', 'array');
				
		foreach ($fieldHides as $key => $value)
		{
			$fieldValue = \XF::finder('XF:UserFieldValue')->where([
				'field_id' => $key,
				'user_id' => $user->user_id
			])->fetchOne();
					
			if ($fieldValue)
			{
				$fieldValue->fastUpdate('sxfcore_hide_field', $value);
			}
		}
		
		return $form;
	}
}