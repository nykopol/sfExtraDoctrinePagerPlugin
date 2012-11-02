<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumnDoctrineRelation widget for doctrine relation columns
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumnDoctrineRelation extends sfWidgetPagerColumn
{
	
	public function configure($options = array(), $attributes = array()) {
		
		$this->addOption('model', '');
		$this->addOption('local', '');
		$this->addOption('foreign', '');
		
		parent::configure($options, $attributes);
	}
	
}





