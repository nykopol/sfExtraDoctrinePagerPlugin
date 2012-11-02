sfExtraDoctrinePagerPlugin
======

Symfony 1.4 plugin aimed to build pagers in the same way as forms

What is sfExtraDoctrinePagerPlugin ?
------------------------------------

sfExtraDoctrinePagerPlugin is a symfony 1.4 plugin. (see http://symfony.com/legacy)

This plugin is a transposition of the symfony form system to pagers. The aim of sfExtraDoctrinePagerPlugin is
to simplify the generation of pagers.

Requirements
------------

 * Symfony 1.4
 * Doctrine 1.2

Installation
------------

 * Place source files in you plugins/ directory in a sfExtraDoctrinePagerPlugin/ folder.
 * Activate the plugin in config/ProjectConfiguration.class.php
   <pre>class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array(
      'sfExtraDoctrinePagerPlugin', 
		...
		));
		}
}</pre>
 * Then build pager classes :
	<pre>php symfony doctrine:build-pagers</pre>