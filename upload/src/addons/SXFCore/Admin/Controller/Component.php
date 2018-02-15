<?php

namespace SXFCore\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Component extends AbstractController
{
	public function actionIndex()
	{
		$componentRepo = $this->getComponentRepo();
		
		$viewParams = [
			'components' => $componentRepo->findComponentsForList()->fetch()
		];
		
		return $this->view(null, 'sxfcore_component_list', $viewParams);
	}
	
	public function actionToggle()
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		
		return $plugin->actionToggle('SXFCore:Component', 'enabled');
	}
	
	public function actionAddOns(ParameterBag $params)
	{
		$component = $this->assertComponentExists($params->component_id);
		
		$addOns = [];
		
		if ($component->addon_dependencies)
		{
			foreach ($component->addon_dependencies as $addOnId)
			{
				$addOn = \XF::finder('XF:AddOn')->where('addon_id', $addOnId)->fetchOne();
				
				if ($addOn)
				{
					$addOns[] = $addOn;
				}
			}
		}
		
		$viewParams = [
			'addOns' => $addOns
		];
		
		if (empty($addOns))
		{
			return $this->message(\XF::phrase('no_items_matched_your_filter'));
		}
		
		return $this->view(null, 'sxfcore_component_addon_list', $viewParams);
	}
	
	public function actionComponents(ParameterBag $params)
	{
		return $this->message(\XF::phrase('no_items_matched_your_filter'));
	}
	
	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \SXFCore\Entity\Component
	 */
	protected function assertComponentExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('SXFCore:Component', $id, $with, $phraseKey);
	}
	
	/**
	 * @return \SXFCore\Repository\Component
	 */
	protected function getComponentRepo()
	{
		return $this->repository('SXFCore:Component');
	}
}