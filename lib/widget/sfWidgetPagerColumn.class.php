<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerColumn base class for widgetColumns
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerColumn extends sfWidgetPager
{
	
	/**
	 * String used for head and foot columns
	 * @var type string
	 */
	protected $label;
	
	
	/*
	 * ---------------------
	 * Gestion de l'affichage
	 * ---------------------
	 */
	
	public function renderHeader($name, $value = null, $attributes = array(), $errors = array()){

		return $this->renderContentTag('th', $this->getGenereatedLabel($name), $attributes);
		
	}
	
	public function renderSortableHeader($name, $value = null, $attributes = array(), $errors = array()){
		
		$link = $this->renderSortableLink($name, $value, $attributes, $errors);
		
		return $this->renderContentTag('th', $link, $attributes);
		
	}
	
	public function renderFooter($name, $value = null, $attributes = array(), $errors = array()){
		
		return $this->renderContentTag('th', $this->getGenereatedLabel($name), $attributes);
		
	}
	
	public function renderSortableFooter($name, $value = null, $attributes = array(), $errors = array()){
		
		$link = $this->renderSortableLink($name, $value, $attributes, $errors);
		
		return $this->renderContentTag('th', $link, $attributes);
		
	}
	
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		
		return $this->renderContentTag('td', $this->getContent($name, $value), $attributes);
		
	}
	
	public function renderSortableLink($name, $value = null, $attributes = array(), $errors = array()){
		
		$sort = $this->getParent()->getSort();
		$sort_type = strtolower($this->getParent()->getSortType());
		$format_url = $this->getParent()->getSortUrlFormat();
		$active = ($sort == $name) ? true : false;
		
		$url = strtr($format_url, array('%sort%' => $name, '%sort_type%' => ($name == $sort ? ($sort_type == 'asc' ? 'desc' : 'asc') : 'asc')));
		$url = url_for( $url );
		$anchor = $this->getGenereatedLabel($name);
		
		return $this->getParent()->getFormatter()->formatSortLink($url, $anchor, $sort, $sort_type, $active);
		
	}
	
	
	
	
	
	/*
	 * -----------------------
	 * Gestion des labels
	 * -----------------------
	 */
	
	
	public function getLabel() {
		return $this->label;
	}

	public function setLabel($label) {
		$this->label = (string) $label;
	}
	
	public function getGenereatedLabel($name){
		$label = $this->getLabel();
		
		if(null === $label){
			$label = sfInflector::humanize($name);
		}
		
		return $label;
	}



	/*
	 * -----------------------
	 * gestion du contenu
	 * -----------------------
	 */
	
	public function getContent($name, $value){
		
		if($value instanceof Doctrine_Record){
			
			return ($value->offsetExists($name) || $value->hasRelation($name)) ? $value->get($name) : null;
		
		}else{
			
			return array_key_exists($name, $value) ? $value[$name] : null;
			
		}
		
	}
	
	
	
	
}





