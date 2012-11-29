<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumnLink display link to object
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumnLink extends sfWidgetPagerColumn
{
	
	public function configure($options = array(), $attributes = array()) {
		
		$this->addRequiredOption('module');
		$this->addRequiredOption('action');
		
		// Method name for string val
		$this->addOption('toStringMethod', '__toString');
		
		parent::configure($options, $attributes);
	}
	
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		
		$url_params = array();
		$url_params['module'] = $this->getOption('module');
		$url_params['action'] = $this->getOption('action');
		$url_params = array_merge($url_params, $value->identifier());
		
		$toStringMethod = $this->getOption('toStringMethod');
		
		$link = $this->renderContentTag('a', $value->$toStringMethod(), array('href' => url_for($url_params)));
		
		return $this->renderContentTag('td', $link, $attributes);
	}
	
	
}





