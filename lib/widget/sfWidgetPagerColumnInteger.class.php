<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumnInteger widget for integer columns
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumnInteger extends sfWidgetPagerColumn
{
	
	
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		
		$integer = (int) $this->getContent($name, $value);
		
		return $this->renderContentTag('td', $integer, $attributes);
	}
	
	
}





