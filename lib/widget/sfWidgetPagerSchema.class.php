<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetPagerSchema pager schema use to manage pagers
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfWidgetPagerSchema extends sfWidgetPager implements ArrayAccess
{
	
	/**
	 * @var array Colones du pager
	 */
	protected $columns = array();
	/**
	 * @var array Liste des noms de colonnes par ordre d'affichage
	 */
	protected $positions = array();
	
	
	/**
	 * @var array Liste des colonnes qui peuvent etre ordonnées
	 */
	protected $sortables = array();
	/**
	 * @var string Nom de la colonne utilisé pour ordonner les resultats
	 */
	protected $sort;
	/**
	 * @var string Type d'ordre (asc|desc) 
	 */
	protected $sort_type;
	/**
	 * @var string format d'url pour header sort 
	 */
	protected $url_format;
	
	
	
	/**
	 * @var boolean Rend le thead du tabler
	 */
	protected $useHeader = true;
	/**
	 * @var boolean Rend le tfoot du tabler
	 */
	protected $useFooter = false;
	
	
	/**
	 * @var sfWidgetPagerSchemaFormatter Formatter du tabler
	 */
	protected $formatter;
	
	
	/**
	 * @var sfExtraDoctrinePager Instance du pager
	 */
	protected $pager;
	
	
	/**
	 * @var type  Text quand il n'y a pas de résultat
	 */
	protected $noResultLabel = 'No results found';
	


	
	/**
	 * @see sfWidget::__construct($options = array(), $attributes = array())
	 * @param array $options
	 * @param array $attributes 
	 */
	public function __construct($options = array(), $attributes = array()) {
		
		$class_formatter = sfConfig::has('app_sfextradoctrinepager_class_widget_formatter') ? sfConfig::get('app_sfextradoctrinepager_class_widget_formatter') : 'sfWidgetPagerSchemaFormatterTable';
		
		$this->setFormatter( new $class_formatter( $this ) );
		
		parent::__construct($options, $attributes);
	}
	
	
	public function getPager() {
		return $this->pager;
	}

	public function setPager($pager) {
		if(!$pager instanceof sfPager){
			throw new sfException("pager must be an instance of sfPager");
		}
		
		$this->pager = $pager;
	}

		
	
	/*
	 * -----------------------
	 * Gestion des colonnes
	 * -----------------------
	 */
	
	public function setColumns(array $columns){
		
		foreach($columns as $name => $widget){
			$this->offsetSet($name, $widget);
		}
		
		$columns_names = array_keys($columns);
		foreach($this->columns as $name => $widget){
			
			if(!in_array($name, $columns_names)){
				$this->offsetUnset($name);
			}
			
		}
	}
	
	public function getColumns() {
		return $this->columns;
	}
	
	public function getNbColumns(){
		return count($this->columns);
	}

	public function setColumn(string $name, $widget){
		
		$this->offsetSet($name, $widget);
		
	}
	
	public function getColumn($name){
		return $this->offsetGet($name);
	}
	
	public function useColumns(array $columns, $ordered = true){
		
		$ordered_widgets = array();
		
		foreach($columns as $name){
			
			if(!$this->offsetExists($name)){
				throw new sfException(sprintf("Widget %s is not defined in %s", $name, get_class($this)));
			}
			
			if($ordered)
				$ordered_widgets[] = $name;
			
		}
		
		foreach( array_diff( array_keys($this->columns), $columns ) as $name){
			
			$this->offsetUnset($name);
			
		}
		
		if($ordered)
			$this->positions = $ordered_widgets;
		
	}
	
	public function getPositions(){
		
		return $this->positions;
		
	}
	
	
	/*
	 * ------------------
	 * Configuration des colonnes Sort
	 * ------------------
	 */
	
	public function setSortableColumns(array $sortable_columns){
		$this->sortables = $sortable_columns;
	}
	
	public function getSortableColumns(){
		return $this->sortables;
	}
	
	public function setSortableColumn($name){
		$this->sortables = array_unique( array_merge($this->sortables, array($name)) );
	}
	
	public function unsetSortableColumn($name){
		$key = array_search($name, $this->sortables);
		if($key)
			unset($this->sortables[$key]);
	}
	
	
	
	public function getSort(){
		return $this->sort;
	}

	public function setSort($sort){
		$this->sort = $sort;
	}

	public function getSortType() {
		return $this->sort_type;
	}

	public function setSortType($sort_type) {
		if(!in_array(strtolower($sort_type), array('asc', 'desc'))){
			throw new sfException(sprintf("%s is not a valide sort type."));
		}
		
		$this->sort_type = $sort_type;
	}

	public function isSortable($column){
		return in_array($column, $this->sortables) ? true : false;
	}
	
	
	public function getSortUrlFormat(){
		return $this->url_format;
	}
	
	public function setSortUrlFormat($url_format){
		$this->url_format = $url_format;
	}
	
	
	
	/*
	 * -----------------------
	 * Gestion du rendu
	 * -----------------------
	 */

	/**
	 * Rendu de l'ensemble du tabler
	 * 
	 * @param type $name
	 * @param type $values
	 * @param type $attributes
	 * @param type $errors
	 * @return string code HTML du tabler
	 */
	public function render($name, $values = null, $attributes = array(), $errors = array()) {
		if (null === $values){
			$values = array();
		}

		if (!is_array($values) && !$values instanceof ArrayAccess){
			throw new InvalidArgumentException('You must pass an array of values to render a widget schema');
		}
		
		$thead = '';
		$tbody = '';
		$tfoot = '';
		
		if($this->getUseHeader()){
			$head_row = $this->_render('header', $name, $values, $attributes, $errors);
			$thead = $this->formatter->formatThead( $head_row );
		}
		
		$body_rows = $this->_render('body', $name, $values, $attributes, $errors);
		$tbody = $this->formatter->formatTbody( $body_rows );
		
		if($this->useFooter){
			$foot_row = $this->_render('footer', $name, $values, $attributes, $errors);
			$tfoot = $this->formatter->formatTfoot( $foot_row );
		}
		
		return $this->formatter->formatTable($tbody, $thead, $tfoot);
	}
	
	/**
	 * Rend un partie du pager (header, body, footer) en utilisant en priorité la classe render<$_part>
	 * du pager et si elle existe pas, la méthode du sfWidgetPagerSchema
	 * 
	 * @param type $_part
	 * @param type $name
	 * @param type $values
	 * @param type $attributes
	 * @param type $errors
	 * @return string 
	 */
	protected function _render($_part, $name, $values = null, $attributes = array(), $errors = array()){
		if((!$this->getUseHeader() && $_part == 'header') || (!$this->getUseFooter() && $_part == 'footer'))
			return '';
		
		$method = lcfirst(sfInflector::camelize('render_'.$_part));
		if(method_exists($this->getPager(), $method)){
			$render = $this->getPager()->$method($name, $values, $attributes, $errors);
		}else{
			$render = $this->$method($name, $values, $attributes, $errors);
		}
		
		return $render;
	}
	
	public function renderHeader($name, $values = null, $attributes = array(), $errors = array()){
		
		if(!$this->getUseHeader())
			return '';
		
		$headers = array();
		foreach($this->positions as $name){
			$widget = $this[$name];
			
			$widgetAttributes = isset($attributes['header'][$name]) ? $attributes['header'][$name] : array();

			if($this->isSortable($name)){
				$content = $widget->renderSortableHeader($name, $values, $attributes, $errors);
			}else{
				$content = $widget->renderHeader($name, $values, $attributes, $errors);
			}

			$headers[] = $content;
		}
		
		return $this->formatter->formatRowTr( implode("\n", $headers), $values );
		
	}
	
	public function renderFooter($name, $values = null, $attributes = array(), $errors = array()){
		
		if(!$this->getUseFooter())
			return '';
		
		$footers = array();
		foreach($this->positions as $name){
			$widget = $this[$name];
			
			$widgetAttributes = isset($attributes['footer'][$name]) ? $attributes['footer'][$name] : array();

			if($this->isSortable($name)){
				$content = $widget->renderSortableFooter($name, $values, $attributes, $errors);
			}else{
				$content = $widget->renderFooter($name, $values, $attributes, $errors);
			}

			$footers[] = $content;
		}
		
		return $this->formatter->formatRowTr( implode("\n", $footers), $values );
		
	}
	
	
	public function renderBody($name, $values = null, $attributes = array(), $errors = array()){
		
		$bodys = array();
		
		if(!count($values)){
			$bodys[] = $this->_render('noResults', $name, $values, $attributes, $errors);
		}else{
		
			foreach($values as $row){
				$widgetAttributes = isset($attributes[$name]) ? $attributes[$name] : array();
				$rendered = $this->_render('row', $name, $row, $attributes, $errors);
				$bodys[] = $this->formatter->formatRowTr( $rendered, $row );
			}
			
		}
		
		return implode("\n", $bodys);
		
	}
	
	
	public function renderRow($name, $values = null, $attributes = array(), $errors = array()){
		
		if(!is_array($values) && !$values instanceof Doctrine_Record){
			throw new sfException("The value must be an array or an instance of Doctrine_Record.");
		}
		
		$columns = array();
		
		foreach($this->positions as $name){
			
			$widget = $this[$name];
			$widgetAttributes = isset($attributes[$name]) ? $attributes[$name] : array();
			
			$content = $widget->render($name, $values, $widgetAttributes, $errors);
			
			$columns[] = $content;
			
		}
		
		return implode("\n", $columns);
		
	}
	
	public function renderNoResults($name, $values = null, $attributes = array(), $errors = array()){
		
		return $this->formatter->formatRowTr('<td colspan="'.$this->getNbColumns().'"><p class="infotip">'.$this->noResultLabel.'</p></td>', $values);
		
	}
	
	
	/*
	 * -----------------------
	 * Utilisation ou pas de thead ou tfoot
	 * -----------------------
	 */
	
	public function getUseHeader() {
		return $this->useHeader;
	}

	public function getUseFooter() {
		return $this->useFooter;
	}

	public function setUseHeader($useHeader) {
		$this->useHeader = (boolean) $useHeader;
	}

	public function setUseFooter($useFooter) {
		$this->useFooter = (boolean) $useFooter;
	}

	
	
	
	/*
	 * ------------------------
	 * Gestion du formatter
	 * ------------------------
	 */
	
	
	public function getFormatter() {
		return $this->formatter;
	}

	public function setFormatter($formatter) {
		if(is_string($formatter)){
			$class_name = $formatter;
			$formatter = new $class_name($this);
		}
		$this->formatter = $formatter;
	}

		
	
	/*
	 * -------------------------
	 * Gestion de labels
	 * -------------------------
	 */
	
	public function setLabels(array $labels){
		
		foreach($labels as $name => $label){
			$this->setLabel($name, $label);
		}
		
	}
	
	public function getLabels(){
		
		$labels = array();
		foreach($this->columns as $name => $column){
			$labels[$name] = $column->getLabel();
		}
		
		return $labels;
		
	}
	
	public function setLabel($name, $label){
		if(!$this->offsetExists($name)){
			throw new sfException(sprintf("Unknown column name : %s", $name));
		}
		
		$this->columns[$name]->setLabel($label);
	}
	
	public function getLabel($name){
		if(!$this->offsetExists($name)){
			throw new sfException(sprintf("Unknown column name : %s", $name));
		}
		
		return $this->columns[$name]->getLabel();
	}

	/**
	 * Retourne le text affiché dans le cas ou il n'y a pas de résultats
	 * @return type 
	 */
	public function getNoResultLabel() {
		return $this->noResultLabel;
	}

	/**
	 *	Défini le text affiché dans le cas ou il n'y a pas de résultats
	 * @param type $noResultLabel 
	 */
	public function setNoResultLabel($noResultLabel) {
		$this->noResultLabel = $noResultLabel;
	}

	


	/*
	 * ---------------------
	 * Imprementation de ArrayAccess
	 * ---------------------
	 */
	
	
	public function offsetExists($name){
		return isset($this->columns[$name]);
	}

	public function offsetGet($name){
		return isset($this->columns[$name]) ? $this->columns[$name] : null;
	}

	public function offsetSet($name, $widget){
		if (!$widget instanceof sfWidgetPagerColumn){
			throw new InvalidArgumentException('A field must be an instance of sfWidgetPagerColumn.');
		}

		if (!isset($this->columns[$name])){
			$this->positions[] = (string) $name;
		}

		$this->columns[$name] = clone $widget;
		$this->columns[$name]->setParent($this);
	}

	public function offsetUnset($name){
		unset($this->columns[$name]);
		if (false !== $position = array_search((string) $name, $this->positions)){
			unset($this->positions[$position]);

			$this->positions = array_values($this->positions);
		}
	}
}