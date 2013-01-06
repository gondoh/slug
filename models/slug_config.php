<?php
/**
 * slugConfig モデル
 *
 * @copyright		Copyright 2012 - 2013, materializing.
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
 * 現在の archives 除外設定
 * 
 * @var string
 * @access public
 */
	var $ignore_archives = false;
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
		// TODO カテゴリ名＋スラッグ機能の作成
		//'6' => 'カテゴリとスラッグ'
	);
/**
 * 初期値を取得する
 *
 * @return array
 * @access public
 */
	function getDefaultValue() {

		$data = array(
			'SlugConfig' => array(
				'permalink_structure' => 0,
				'ignore_archives' => false
			)
		);
		return $data;

	}
/**
 * ブログコンテンツIDを元に、archives 除外設定をセットする
 * 
 * @param int $blogContentId
 * @return void
 * @access public
 */
	function setIgnoreArchives($blogContentId = null) {

		if($blogContentId) {
			$slugConfigData = $this->findByBlogContentId($blogContentId);
			$this->id = $slugConfigData['SlugConfig']['id'];
			$this->ignore_archives = $slugConfigData['SlugConfig']['ignore_archives'];
		}

	}

}
