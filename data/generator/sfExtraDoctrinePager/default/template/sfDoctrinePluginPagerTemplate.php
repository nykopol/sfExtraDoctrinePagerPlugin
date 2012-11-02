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
 * <?php echo $this->table->getOption('name') ?> pager.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage sfExtraDoctrinePagerPlugin
 * @author     ##AUTHOR_NAME##
 */
class <?php echo $this->table->getOption('name') ?>Pager extends Plugin<?php echo $this->table->getOption('name') ?>Pager
{
<?php if ($parent = $this->getParentModel()): ?>
	/**
	* @see <?php echo $parent ?>Pager
	*/
	public function configure(){
	
		parent::configure();
	
	}
<?php else: ?>
	public function configure(){
	
	}
<?php endif; ?>
}
