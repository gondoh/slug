<?php 
/* SVN FILE: $Id$ */
/* SlugConfigs schema generated on: 2013-01-03 08:01:03 : 1357167843*/
class SlugConfigsSchema extends CakeSchema {
	var $name = 'SlugConfigs';

	var $file = 'slug_configs.php';

	var $connection = 'plugin';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $slug_configs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'blog_content_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 1),
		'permalink_structure' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 1),
		'ignore_archives' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>