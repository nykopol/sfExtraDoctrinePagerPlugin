<?php
/*
 * This file is part of the sfExtraDoctrinePagerPlugin package.
 * (c) 2012 Desmyter Johan <desmyter.johan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrineBuildPagerTask task classe for doctrine:build-pagers
 *
 * @package    sfExtraDoctrinePagerPlugin
 * @author     Desmyter Johan <desmyter.johan@gmail.com>
 */
class sfDoctrineBuildPagerTask extends sfDoctrineBaseTask
{
	/**
	* @see sfTask
	*/
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
			new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
			new sfCommandOption('model-dir-name', null, sfCommandOption::PARAMETER_REQUIRED, 'The model dir name', 'model'),
			new sfCommandOption('pager-dir-name', null, sfCommandOption::PARAMETER_REQUIRED, 'The pager dir name', 'form'),
			new sfCommandOption('generator-class', null, sfCommandOption::PARAMETER_REQUIRED, 'The generator class', 'sfExtraDoctrinePagerGenerator'),
		));

		$this->namespace = 'doctrine';
		$this->name = 'build-pagers';
		$this->briefDescription = 'Creates pager classes for the current model';

		$this->detailedDescription = <<<EOF
The [doctrine:build-pagers|INFO] task creates pager classes from the schema:

[./symfony doctrine:build-pagers|INFO]

This task creates pager classes based on the model. The classes are created
in [lib/pager/doctrine|COMMENT].

This task never overrides custom classes in [lib/pager/doctrine|COMMENT].
It only replaces base classes generated in [lib/pager/doctrine/base|COMMENT].
EOF;
	}

	/**
	* @see sfTask
	*/
	protected function execute($arguments = array(), $options = array()){
		$this->logSection('doctrine', 'generating pager classes');
			$databaseManager = new sfDatabaseManager($this->configuration);
			$generatorManager = new sfGeneratorManager($this->configuration);
			$generatorManager->generate($options['generator-class'], array(
			'model_dir_name' => $options['model-dir-name'],
			'pager_dir_name'  => $options['pager-dir-name'],
		));

		$properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);

		$constants = array(
			'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
			'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here'
		);

		// customize php and yml files
		$finder = sfFinder::type('file')->name('*.php');
		$this->getFilesystem()->replaceTokens($finder->in(sfConfig::get('sf_lib_dir').'/pager/'), '##', '##', $constants);

		$this->reloadAutoload();
	}
}
