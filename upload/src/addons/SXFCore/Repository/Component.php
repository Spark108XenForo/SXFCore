<?php

namespace SXFCore\Repository;

use XF\Mvc\Entity\Repository;

class Component extends Repository
{
	public function findComponentsForList()
	{
		return $this->finder('SXFCore:Component')->order('component_id');
	}
	
	public function getComponentEnabledList()
	{
		return $this->findComponentsForList()
				->fetch()
				->pluckNamed('enabled', 'component_id');
	}
	
	public function isEnabled($componentKey)
	{
		$componentEnabledList = $this->getComponentEnabledList();
		
		if (isset($componentEnabledList[$componentKey]) && $componentEnabledList[$componentKey])
		{
			return true;
		}
		
		return false;
	}
}