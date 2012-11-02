<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfExtraDoctrinePager is the main class of pagers
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfExtraDoctrinePager extends sfDoctrinePager
{
	
	/**
	 *
	 * @var sfWidgetPagerSchema instance of sfWidgetPagerSchema
	 */
	protected $widgetSchema    = null;
	
	/**
	 *
	 * @var boolean init() method has been called or not
	 */
	protected $initialized = false;
	
	/**
	 *
	 * @var array() Options du pager 
	 */
	protected $options = array();
	protected $requiredOptions = array();
	
	
	protected $foreignKeys;
	protected $sort;
	protected $urlBase;
	protected $classTable;
	protected $formFilter;
	protected $filter;
	
	
	
	
	public function __construct($class, $maxPerPage = 10, $options = array()) {
		
		$this->widgetSchema    = new sfWidgetPagerSchema();
		$this->widgetSchema->setPager( $this );
		
		$this->options = $options;

		$this->setup();
		
		$this->validateRequiredOptions();
		
		sfOutputEscaper::markClassesAsSafe(array('sfExtraDoctrinePager'));
		
		parent::__construct($class, $maxPerPage);
		
	}
	
	
	/*
	 * ------------------------
	 * Gestion du widgetSchema
	 * ------------------------
	 */
	
	
	public function getWidgetSchema() {
		return $this->widgetSchema;
	}

	public function setWidgetSchema($widgetSchema) {
		if(!$widgetSchema instanceof sfWidgetPagerSchema){
			throw new InvalidArgumentException("Widget schema must be an instance of sfWidgetPagerSchema");
		}
		
		$this->widgetSchema = $widgetSchema;
		$this->widgetSchema->setPager( $this );
	}

	public function useColumns(array $columns, $ordered = true){
		return $this->widgetSchema->useColumns($columns, $ordered);
	}
	
	
	
	/*
	 * ------------------------
	 * Gestion du rendu
	 * ------------------------
	 */
	
	public function __toString(){
		
		return $this->render();
		
	}
	
	public function render($name = '', $attributes = array(), $errors = array()){
		
		if(!$this->initialized){
			throw new sfException(sprintf("%s must be initialized before rendering.", get_class($this)));
		}
		
		$values = $this->getResults();
		
		return $this->widgetSchema->render($name, $values, $attributes, $errors);
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function init(){
		
		$this->classTable = $this->getClassTable();
		
		$this->display_columns = $this->classTable->getColumnNames();
		
		$export = $this->classTable->getExportableFormat();
		$this->foreignKeys = $export['options']['foreignKeys'];
		
		$this->configure();
		
		if(!$this->query)
			$this->buildQuery();
		
		$this->initialized = true;
		
		return parent::init();
		
	}

	
	
	
	
	
	/*
	 * ------------------------
	 * Gestion des Sort
	 * ------------------------
	 */

	public function setSort($sort, $sort_type){
		if(!$sort)
			return;
		
		if(!in_array(strtolower($sort_type), array('asc', 'desc'))){
			throw new sfException(sprintf("%s is not a valid sort type.", $sort_type));
		}
		
		$this->widgetSchema->setSort($sort);
		$this->widgetSchema->setSortType($sort_type);
	}
	
	public function getSort(){
		return $this->widgetSchema->getSort();
	}
	
	public function getSortType(){
		return $this->widgetSchema->getSortType();
	}
	
	public function isSortable($column){
		return $this->widgetSchema->isSortable($column);
	}
	
	public function setSortUrlFormat($url_format){
		$this->widgetSchema->setSortUrlFormat($url_format);
	}
	
	public function getSortUrlFormat(){
		return $this->getSortUrlFormat();
	}
	
	
	
	/*
	 * ------------------------
	 * Gestion des filtres
	 * ------------------------
	 */
	
	
	public function setFilter($filter){
		$this->filter = $filter;
	}
	public function getFilter(){
		return $this->filter;
	}
	
	public function setFormFilter($formFilter){
		if(!$formFilter instanceof sfFormFilter){
			throw new sfException("setFormFilter() require an instance of sfFormFilter for the first parameter");
		}
		
		$this->formFilter = $formFilter;
	}
	
	public function getFormFilter(){
		return $this->formFilter;
	}
	
	
	
	
	
	public function buildQuery(){
		if(!$this->formFilter){
			$this->setQuery( $this->getQuery() );
			return true;
		}
		
		$query = $this->formFilter->buildQuery( $this->getFilter() );
		
		if($this->getSort()){
			$sort_column = $this->getSort();
			
			if(!$this->getClassTable()->hasColumn($sort_column)){
				if($this->getClassTable()->hasRelation($sort_column)){
					$relation = $this->getClassTable()->getRelation($sort_column);
					$sort_column = $relation->getLocalColumnName();
				}else{
					
					throw new sfException(sprintf("Unknown field %s on %s", $sort_column, get_class($this->getClassTable())));
					
				}
			}
			$query->addOrderBy($sort_column.' '.$this->getSortType());
		}
		
		$this->setQuery($query);
	}
	
	public function getClassTable(){
		if(!$this->classTable)
			$this->classTable = Doctrine_Core::getTable($this->getClass());
		
		return $this->classTable;
	}
	
	
	/*
	 * ------------------------
	 * Options
	 * ------------------------
	 */
	
	public function getOptions() {
		return $this->options;
	}

	/**
	* Gets an option value.
	*
	* @param string $name    The option name
	* @param mixed  $default The default value (null by default)
	*
	* @param mixed  The default value
	*/
	public function getOption($name, $default = null) {
		return isset($this->options[$name]) ? $this->options[$name] : $default;
	}

	public function setOptions($name, $value) {
		$this->options[$name] = $value;
	}

	public function addRequiredOption($name){
		$this->requiredOptions[] = $name;
	}
	
	public function getRequiredOptions(){
		return $this->requiredOptions;
	}
	
	protected function validateRequiredOptions(){
		$missing_options = array();
		foreach($this->requiredOptions as $name){
			if(!array_key_exists($name, $this->options)){
				$missing_options[] = $name;
			}
		}
		
		if(!empty($missing_options)){
			throw new sfException(sprintf('%s require missing options : %s', get_class($this), implode(', ', $missing_options)));
		}
		return true;
	}
	
	/*
	 * ---------------------
	 * Implémentation de ArrayAccess
	 * ---------------------
	 */
	
	public function offsetExists($name){
		return $this->widgetSchema->offsetExists($name);
	}

	public function offsetGet($name){
		return $this->widgetSchema->offsetGet($name);
	}

	public function offsetSet($name, $widget){
		return $this->widgetSchema->offsetSet($name, $widget);
	}

	public function offsetUnset($name){
		return $this->widgetSchema->offsetUnset($name);
	}
	
}