<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumnDate widget for date columns
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumnDate extends sfWidgetPagerColumn
{
	
	public function configure($options = array(), $attributes = array()) {
		
		$this->addOption('format', 'd/m/Y');
		
		parent::configure($options, $attributes);
	}
	
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		
		$format = $this->getOption('format');
		
		$content = $value->get($name) ? $value->getDateTimeObject($name)->format($format) : '';
		
		return $this->renderContentTag('td', $content, $attributes);
	}
	
	
}





