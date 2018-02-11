<?php

namespace SXFCore\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Component extends Entity
{
	public function getTitle()
	{
		return \XF::phrase($this->getPhraseName());
	}
	
	public function getDescription()
	{
		return \XF::phrase($this->getPhraseDescription());
	}
	
	public function getError()
	{
		return null;
	}
	
	public function getPhraseName()
	{
		return 'sxfcore_component_' . $this->component_id;
	}
	
	public function getPhraseDescription()
	{
		return 'sxfcore_component_' . $this->component_id . '_description';
	}
	
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'sxfcore_component';
		$structure->shortName = 'SXFCore:Component';
		$structure->primaryKey = 'component_id';
		
		$structure->columns = [
			'component_id' => ['type' => self::BINARY, 'MaxLength' => 50, 'required' => true],
			'addon_dependencies' => ['type' => self::SERIALIZED],
			'component_dependencies' => ['type' => self::SERIALIZED],
			'enabled' => ['type' => self::BOOL, 'default' => true]
		];
		
		$structure->getters = [
			'title' => true,
			'description' => true,
			'error' => true
		];
		
		$structure->relations = [];
		
		return $structure;
	}
}