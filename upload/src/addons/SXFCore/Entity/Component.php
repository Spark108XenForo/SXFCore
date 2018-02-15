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
	
	public function getDeveloper()
	{
		$phraseName = $this->getPhraseDeveloper();
		$phrase = \XF::phrase($phraseName);
		
		if ($phraseName == $phrase)
		{
			return null;
		}
		
		return $phrase;
	}
	
	public function getErrors(&$errors = [])
	{
		return $errors;
	}
	
	public function getWarnings(&$warnings = [])
	{
		if ($this->addon_dependencies)
		{
			$warnings[] = \XF::phrase('sxfcore_component_disabled_warning_addon_dependencies', [
				'count' => count($this->addon_dependencies)
			]);
		}
		
		return $warnings;
	}
	
	public function getPhraseName()
	{
		return 'sxfcore_component_' . $this->component_id;
	}
	
	public function getPhraseDescription()
	{
		return 'sxfcore_component_' . $this->component_id . '_description';
	}
	
	public function getPhraseDeveloper()
	{
		return 'sxfcore_component_' . $this->component_id . '_developer';
	}
	
	public function setAddonDependencies(\XF\AddOn\AddOn $addOn)
	{
		$addOnId = $addOn->addon_id;
		
		if (!$this->addon_dependencies)
		{
			$this->addon_dependencies = [$addOnId];
		}
		else if (!in_array($addOnId, $this->addon_dependencies))
		{
			$this->addon_dependencies[] = $addOnId;
		}
		
		$this->fastUpdate('addon_dependencies', $this->addon_dependencies);
	}
	
	public function deleteAddonDependencies(\XF\AddOn\AddOn $addOn)
	{
		$addOnId = $addOn->addon_id;
		
		if (!$this->addon_dependencies)
		{
			return;
		}
		else if (in_array($addOnId, $this->addon_dependencies))
		{
			$this->addon_dependencies = array_diff($this->addon_dependencies, [$addOnId]);
			
			$this->fastUpdate('addon_dependencies', $this->addon_dependencies);
		}
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
			'developer' => true,
			'errors' => true,
			'warnings' => true
		];
		
		$structure->relations = [];
		
		return $structure;
	}
}