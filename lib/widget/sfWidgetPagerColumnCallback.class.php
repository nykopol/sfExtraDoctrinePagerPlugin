<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumnCallback widget for callback columns
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumnCallback extends sfWidgetPagerColumn
{
	
	public function configure($options = array(), $attributes = array()) {
		
		$this->addRequiredOption('callback');
		
		parent::configure($options, $attributes);
	}
	
	
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		
		$content = $this->getContent($name, $value);
		
		/**
		* TODO : quand la valeur d'un attribut est null il ne faut pas la remplacer par l'objet !
		* ça pose problème pour la gestion des libelles vide
		*/
		if(null === $content){
			$content = $value;
		}
		
		return call_user_func($this->getOption('callback'), $content, $value);
		
	}
	
}





