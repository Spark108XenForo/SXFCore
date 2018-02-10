<?php

namespace SXFCore\XF\Admin\Controller;

class UserField extends XFCP_UserField
{
	protected function fieldSaveProcess(\XF\Entity\AbstractField $field)
	{
		$form = parent::fieldSaveProcess($field);
		
		$form->basicEntitySave($field, $this->filter(['sxfcore_hide_field' => 'bool']));
		
		return $form;
	}
}