<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPager Widget class for pagers
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPager extends sfWidget
{
	
	// instance sfWidgetPagerSchema
	protected $parent;
	
	
	public function getParent() {
		return $this->parent;
	}

	public function setParent($parent){
		if(!$parent instanceof sfWidgetPagerSchema){
			throw new sfException("Widget parent must be an instance of sfWidgetPagerSchema");
		}
		
		$this->parent = $parent;
	}

		
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
	  return '';
    //return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
  }

}