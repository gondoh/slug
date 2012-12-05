<?php
/**
 * slugConfig モデル
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.models
 * @version			1.1.0
 * @license			MIT
 */
class SlugConfig extends BaserPluginAppModel {
/**
 * モデル名
 * 
 * @var string
 * @access public
 */
	var $name = 'SlugConfig';
/**
 * プラグイン名
 * 
 * @var string
 * @access public
 */
	var $plugin = 'Slug';
/**
 * 表示設定値
 *
 * @var array
 * @access public
 */
	var $permalink_structure = array(
		'0' => '自由入力',
		'1'	=> 'ブログ記事タイトル',
		'2' => 'ブログ記事ID',
		'3' => 'ブログ記事ID（6桁）',
		'4'	=> '日付とブログ記事タイトル（/2012/12/01/sample-post/）',
		'5'	=> '年月とブログ記事タイトル（/2012/12/sample-post/）'
	);
	var $active_all_slugs = array(
		'0' => '有効化しない',
		'1' => '有効化する'
	);
	var $ignore_archives = array(
		'0' => '省略しない',
		'1' => '省略する'
	);

}
