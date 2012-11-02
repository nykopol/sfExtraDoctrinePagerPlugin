<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumnTime widget for time columns
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumnTime extends sfWidgetPagerColumn
{
	
	public function configure($options = array(), $attributes = array()) {
		
		parent::configure($options, $attributes);
	}
	
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		$hms = $value->get($name) ? explode(':', $value->get($name)) : '';
		if(is_array($hms)){
			$value = $hms[0].'h'.$hms[1];
		}else{
			$value = '';
		}
		
		return $this->renderContentTag('td', $value, $attributes);
	}
	
	
}





