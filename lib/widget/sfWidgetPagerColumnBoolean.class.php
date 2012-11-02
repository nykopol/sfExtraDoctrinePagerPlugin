<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumnBoolean widget for boolean columns
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumnBoolean extends sfWidgetPagerColumn
{
	
	public function configure($options = array(), $attributes = array()) {
		
		$this->addOption('display', array('0', '1'));
		
		parent::configure($options, $attributes);
	}
	
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		
		$display = $this->getOption('display');
		
		if(!is_array($display)){
			throw new sfException("display option must be an array");
		}
		
		$boolean = (bool) $this->getContent($name, $value);
		
		return $this->renderContentTag('td', $display[ $boolean ? 1 : 0 ], $attributes);
	}
	
	
}





