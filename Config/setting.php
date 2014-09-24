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
// アクション名「archives」を省略する処理
// HINT /baser/config/routes.php
/**
 * beforeDispatch実行のためプラグイン.フィルターを追加
 */
Configure::write('Dispatcher.filters', array_merge(Configure::read('Dispatcher.filters'), array('Slug.SlugFilter')));
