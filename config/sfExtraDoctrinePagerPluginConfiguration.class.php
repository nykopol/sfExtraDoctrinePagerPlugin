<?php
/**
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfExtraDoctrinePagerPluginConfiguration is the configuration class
 *
 * @package sfExtraDoctrinePagerPlugin
 * @author Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfExtraDoctrinePagerPluginConfiguration extends sfPluginConfiguration
{

	/**
	 * Initialize the plugin
	 * 
	 * @return void
	 */	
	public function initialize(){
		
		if(!sfConfig::get('extra_doctrine_plugin_lib_dir')){
			sfConfig::set('extra_doctrine_plugin_lib_dir', dirname(__FILE__).'/../lib');
		}
		
	}
	
	/**
	* Returns options for Doctrine schema builder.
	*
	* @return array
	*/
	public function getModelBuilderOptions(){
		$options = array(
			'generateBaseClasses'  => true,
			'generateTableClasses' => true,
			'packagesPrefix'       => 'Plugin',
			'suffix'               => '.class.php',
			'baseClassesDirectory' => 'base',
			'baseClassName'        => 'sfDoctrineRecord',
		);

		// for BC
		$options = array_merge($options, sfConfig::get('doctrine_pager_builder_options', array()));

		// filter options through the dispatcher
		$options = $this->dispatcher->filter(new sfEvent($this, 'doctrine.filter_pager_builder_options'), $options)->getReturnValue();

		return $options;
	}

	 /**
	 * Return CLI config
	 */
	public function getCliConfig(){
		$config = array(
			'data_fixtures_path' => array_merge(array(sfConfig::get('sf_data_dir').'/fixtures'), $this->configuration->getPluginSubPaths('/data/fixtures')),
			'models_path'        => sfConfig::get('sf_lib_dir').'/model/doctrine',
			'migrations_path'    => sfConfig::get('sf_lib_dir').'/migration/doctrine',
			'sql_path'           => sfConfig::get('sf_data_dir').'/sql',
			'yaml_schema_path'   => sfConfig::get('sf_config_dir').'/doctrine',
			'pager_path'        => sfConfig::get('sf_lib_dir').'/pager/doctrine',
		);

		// filter config through the dispatcher
		$config = $this->dispatcher->filter(new sfEvent($this, 'doctrine.filter_cli_config'), $config)->getReturnValue();

		return $config;
	}

}
