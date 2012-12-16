<?php
/**
 * slugConfig モデル
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.models
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
		'0' => 'デフォルト',
		'1'	=> 'スラッグ',
		'2' => 'ブログ記事ID',
		'3' => 'ブログ記事ID（6桁表記）',
		'4'	=> '日付とスラッグ',
		'5'	=> '年月とスラッグ'
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
