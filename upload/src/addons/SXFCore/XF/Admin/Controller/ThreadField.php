<?php

namespace SXFCore\XF\Admin\Controller;

class ThreadField extends XFCP_ThreadField
{
	protected function fieldSaveProcess(\XF\Entity\AbstractField $field)
	{
		$form = parent::fieldSaveProcess($field);
		
		$input = [];
		
		if (isset($field->viewable_user_group_ids))
		{
			$editableUserGroups = $this->filter('viewable_user_group', 'str');
			if ($editableUserGroups == 'all')
			{
				$input['viewable_user_group_ids'] = [-1];
			}
			else
			{
				$input['viewable_user_group_ids'] = $this->filter('viewable_user_group_ids', 'array-uint');
			}
		}
		
		$form->basicEntitySave($field, $input);
		
		return $form;
	}
}