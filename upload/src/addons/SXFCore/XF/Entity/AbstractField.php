<?php

namespace SXFCore\XF\Entity;

abstract class AbstractField extends XFCP_AbstractField
{
	protected static function setupDefaultStructure(Structure $structure, $table, $shortName, array $options = [])
	{
		parent::setupDefaultStructure($structure, $table, $shortName, $options);
		
		1
		
		$options = array_replace([
			'groups' => [],
			'has_user_group_viewable' => false
		], $options);
		
		if ($options['has_user_group_viewable'])
		{
			$structure->columns['viewable_user_group_ids'] = ['type' => self::LIST_COMMA, 'default' => [-1]];
		}
	}
}