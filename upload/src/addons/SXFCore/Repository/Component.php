<?php

namespace SXFCore\Repository;

use XF\Mvc\Entity\Repository;

class Component extends Repository
{
	public function findComponentsForList($where = [])
	{
		if (!$this->hasTableComponent())
		{
			return false;
		}
		
		return $this->finder('SXFCore:Component')->where($where)->order('component_id');
	}
	
	public function getComponentEnabledList($where = [])
	{
		if (!$this->hasTableComponent())
		{
			return false;
		}
		
		return $this->findComponentsForList($where)
				->fetch()
				->pluckNamed('enabled', 'component_id');
	}
	
	public function isEnabled($componentKey)
	{
		if (!$this->hasTableComponent())
		{
			return false;
		}
		
		$component = $this->getComponent($componentKey);
		
		if ($component && $component->enabled)
		{
			return true;
		}
		
		return false;
	}
	
	public function getComponent($componentKey)
	{
		if (!$this->hasTableComponent())
		{
			return false;
		}
		
		$component = $this->findComponentsForList([
			'component_id' => $componentKey
		])->fetchOne();
		
		if (!$component)
		{
			return null;
		}
		
		return $component;
	}
	
	public function setAddonDependencies($componentKey, \XF\AddOn\AddOn $addOn)
	{
		if (!$this->hasTableComponent())
		{
			return false;
		}
		
		$component = $this->getComponent($componentKey);
		
		if ($component)
		{
			$component->setAddonDependencies($addOn);
		}
	}
	
	public function deleteAddonDependencies($componentKey, \XF\AddOn\AddOn $addOn)
	{
		if (!$this->hasTableComponent())
		{
			return false;
		}
		
		$component = $this->getComponent($componentKey);
		
		if ($component)
		{
			$component->deleteAddonDependencies($addOn);
		}
	}
	
	public function hasTableComponent()
	{
		return $this->db()->getSchemaManager()->tableExists('sxfcore_component');
	}
}