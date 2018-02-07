<?php

namespace SXFCore\XF\Pub\Controller;

class Account extends XFCP_Account
{
	protected function accountDetailsSaveProcess(\XF\Entity\User $visitor)
	{
		$form = parent::accountDetailsSaveProcess($visitor);
		
		$input = $this->filter([
			'sxfcore_gender' => 'str'
		]);
		
		if (!$input['sxfcore_gender'])
		{
			$input['sxfcore_gender'] = null;
		}
		
		$form->basicEntitySave($visitor, $input);
		
		return $form;
	}
}