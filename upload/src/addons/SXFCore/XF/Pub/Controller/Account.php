<?php

namespace SXFCore\XF\Pub\Controller;

class Account extends XFCP_Account
{
	protected function accountDetailsSaveProcess(\XF\Entity\User $visitor)
	{
		$form = parent::accountDetailsSaveProcess($visitor);
		
		if ($this->getComponentRepo()->isEnabled('user_gender'))
		{
			$input = $this->filter([
				'sxfcore_gender' => 'str'
			]);
			
			if (!$input['sxfcore_gender'])
			{
				$input['sxfcore_gender'] = null;
			}
			
			$form->basicEntitySave($visitor, $input);
		}
		
		return $form;
	}

	public function actionAccountDetails()
	{
		$view = parent::actionAccountDetails();

		if ($this->getComponentRepo()->isEnabled('user_field_hide'))
		{
			$visitor = \XF::visitor();

			if ($this->isPost())
			{
				if ($visitor->canEditProfile())
				{
					$fieldHides = $this->filter('sxfcorefield_hide', 'array');

					foreach ($fieldHides as $key => $value)
					{
						$fieldValue = \XF::finder('XF:UserFieldValue')->where([
							'field_id' => $key,
							'user_id' => $visitor->user_id
						])->fetchOne();
						
						if ($fieldValue)
						{
							$fieldValue->fastUpdate('sxfcore_hide_field', $value);
						}
					}
				}
				
				return $view;
			}

			$fieldValues = \XF::finder('XF:UserFieldValue')->where('user_id', $visitor->user_id)->fetch();
			
			$sxfcoreFields = [];
			foreach ($fieldValues as $fieldValue)
			{
				$sxfcoreFields[$fieldValue->field_id] = $fieldValue;
			}
			
			$view->setParams([
				'sxfcore_fields' => $sxfcoreFields
			]);
		}
		
		return $view;
	}
	
	/**
	 * @return \SXFCore\Repository\Component
	 */
	protected function getComponentRepo()
	{
		return $this->repository('SXFCore:Component');
	}
}