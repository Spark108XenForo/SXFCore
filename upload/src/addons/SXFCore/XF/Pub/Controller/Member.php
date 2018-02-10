<?php

namespace SXFCore\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
	public function actionAbout(ParameterBag $params)
	{
		$view = parent::actionAbout($params);
		
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
}