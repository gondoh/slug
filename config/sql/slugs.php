<?php 
/* SVN FILE: $Id$ */
/* Slugs schema generated on: 2012-11-25 05:11:26 : 1353789446*/
class SlugsSchema extends CakeSchema {
	var $name = 'Slugs';

	var $file = 'slugs.php';

	var $connection = 'plugin';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $slugs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'blog_post_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'blog_content_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'blog_post_no' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'status' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>