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
	
	/**
	 * @return \SXFCore\Repository\Component
	 */
	protected function getComponentRepo()
	{
		return $this->repository('SXFCore:Component');
	}
}