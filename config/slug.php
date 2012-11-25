<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.config
 * @version			1.0.0
 * @license			MIT
 */
/**
 * システムナビ
 */
$config['BcApp.adminNavi.slug'] = array(
		'name'		=> 'slug プラグイン',
		'contents'	=> array(
			array('name' => '一覧表示',
				'url' => array(
					'admin' => true,
					'plugin' => 'slug',
					'controller' => 'slugs',
					'action' => 'index')
			)
	)
);
