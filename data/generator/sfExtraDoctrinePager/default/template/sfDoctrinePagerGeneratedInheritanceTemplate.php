<?php
/*
This file is part of sfExtraDoctrinePagerPlugin.

sfExtraDoctrinePagerPlugin is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

sfExtraDoctrinePagerPlugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
 */
?>
[?php

/**
 * <?php echo $this->modelName ?> pager base class.
 *
 * @method <?php echo $this->modelName ?> getObject() Returns the current pager's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage sfExtraDoctrinePagerPlugin
 * @author     ##AUTHOR_NAME##
 */
abstract class Base<?php echo $this->modelName ?>Pager extends <?php echo $this->getPagerClassToExtend().PHP_EOL ?>
{

	public function __construct($class = '<?php echo $this->modelName ?>', $maxPerPage = 10, $options = array()) {
		return parent::__construct($class, $maxPerPage, $options);
	}

	protected function setupInheritance(){
		parent::setupInheritance();
		
<?php foreach ($this->getColumns() as $column): ?>
		$this->widgetSchema['<?php echo $column->getFieldName() ?>'] = new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>);
<?php endforeach; ?>
<?php foreach ($this->getForeignRelations() as $relation): ?>
		$this->widgetSchema['<?php echo $this->underscore($relation['alias']) ?>'] = new sfWidgetPagerColumnDoctrineRelation(array(
				'model' => '<?php echo $relation['table']->getOption('name') ?>', 
				'local' => '<?php echo $relation->getLocalColumnName() ?>', 
				'foreign' => '<?php echo $relation->getForeignColumnName() ?>' ))
<?php endforeach; ?>

	}

}
