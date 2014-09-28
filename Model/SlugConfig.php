<?php
/**
 * [Model] SlugConfig モデル
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
class SlugConfig extends BcPluginAppModel {
/**
 * ModelName
 * 
 * @var string
 */
	public $name = 'SlugConfig';
	
/**
 * PluginName
 * 
 * @var string
 */
	public $plugin = 'Slug';
	
/**
 * belongsTo
 * 
 * @var array
 */
	public $belongsTo = array(
		'BlogContent' => array(
			'className'	=> 'Blog.BlogContent',
			'foreignKey' => 'blog_content_id'
		)
	);
	
/**
 * 現在の archives 除外設定
 * 
 * @var boolean
 */
	public $ignore_archives = false;
	
/**
 * 表示設定値
 *
 * @var array
 */
	public $permalink_structure = array(
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
 */
	public function getDefaultValue() {
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
 */
	public function setIgnoreArchives($blogContentId = null) {
		if ($blogContentId) {
			$slugConfigData = $this->findByBlogContentId($blogContentId);
			$this->id = $slugConfigData['SlugConfig']['id'];
			$this->ignore_archives = $slugConfigData['SlugConfig']['ignore_archives'];
		}
	}
	
}
