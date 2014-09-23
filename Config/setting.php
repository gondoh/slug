<?php
/**
 * [Config] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
/**
 * システムナビ
 */
$config['BcApp.adminNavi.slug'] = array(
		'name'		=> 'slug プラグイン',
		'contents'	=> array(
			array('name' => 'スラッグ一覧',
				'url' => array(
					'admin' => true,
					'plugin' => 'slug',
					'controller' => 'slugs',
					'action' => 'index')
			),
			array('name' => 'スラッグ設定一覧',
				'url' => array(
					'admin' => true,
					'plugin' => 'slug',
					'controller' => 'slug_configs',
					'action' => 'index')
			)
	)
);
