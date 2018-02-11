<?php

namespace SXFCore\XF\Template;

use XF\App;
use XF\Language;

class Templater extends XFCP_Templater
{
	public function __construct(App $app, Language $language, $compiledPath)
	{
		parent::__construct($app, $language, $compiledPath);
			
		$data = [
			'sxfCoreComponents' => $this->getComponentRepo()->getComponentEnabledList()
		];
		
		$this->addDefaultParams($data);
	}
	
	public function fnUserBlurb($templater, &$escape, $user, $attributes = [])
	{
		if ($this->getComponentRepo()->isEnabled('user_gender'))
		{
			if (!$user instanceof \XF\Entity\User)
			{
				return '';
			}

			$blurbParts = [];

			$blurbParts[] = $this->fnUserTitle($this, $escape, $user);
			if ($user->Profile->age)
			{
				$blurbParts[] = $user->Profile->age;
			}
			if ($user->Profile->location)
			{
				$location = \XF::escapeString($user->Profile->location);
				$location = '<a href="' . $this->app->router('public')->buildLink('misc/location-info', null, ['location' => $location]) . '" class="u-concealed">' . $location. '</a>';
				$blurbParts[] = \XF::phrase('from_x_location', ['location' => new \XF\PreEscaped($location)])->render();
			}
			
			if ($user->sxfcore_gender == 'male')
			{
				$blurbParts[] = \XF::phrase('sxfcore_gender_male');
			}
			
			if ($user->sxfcore_gender == 'female')
			{
				$blurbParts[] = \XF::phrase('sxfcore_gender_female');
			}

			$tag = $this->processAttributeToRaw($attributes, 'tag');
			if (!$tag)
			{
				$tag = 'div';
			}

			$class = $this->processAttributeToRaw($attributes, 'class', '%s', true);
			$unhandledAttrs = $this->processUnhandledAttributes($attributes);

			return "<{$tag} class=\"{$class}\" dir=\"auto\" {$unhandledAttrs}>"
				. implode(' <span role="presentation" aria-hidden="true">&middot;</span> ', $blurbParts)
				. "</{$tag}>";
		}
		
		return parent::fnUserBlurb($templater, $escape, $user, $attributes);
	}
	
	/**
	 * @return \SXFCore\Repository\Component
	 */
	protected function getComponentRepo()
	{
		return $this->app->repository('SXFCore:Component');
	}
}