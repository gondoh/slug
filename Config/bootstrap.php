<?php
/**
 * [Config] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
// TODO アクション名「archives」を省略する処理
// HINT /baser/config/routes.php
/**
 * beforeDispatch実行のためプラグイン.フィルターを追加
 */
Configure::write('Dispatcher.filters', array_merge(Configure::read('Dispatcher.filters'), array('Slug.SlugFilter')));
