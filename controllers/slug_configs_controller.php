<?php
/**
 * [Controller] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.controllers
 * @license			MIT
 */
/**
 * Include files
 */
App::import('Controller', 'Slug.SlugApp');
class SlugConfigsController extends SlugAppController {
/**
 * コントローラー名
 * 
 * @var string
 * @access public
 */
	var $name = 'SlugConfigs';
/**
 * モデル
 * 
 * @var array
 * @access public
 */
	var $uses = array('Slug.SlugConfig');
/**
 * ぱんくずナビ
 *
 * @var string
 * @access public
 */
	var $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'スラッグ設定管理', 'url' => array('plugin' => 'slug', 'controller' => 'slug_configs', 'action' => 'index'))
	);
/**
 * beforeFilter
 *
 * @return	void
 * @access 	public
 */
	function beforeFilter() {

		parent::beforeFilter();

	}
/**
 * [ADMIN] スラッグ設定一覧
 * 
 * @return void
 * @access public
 */
	function admin_index() {

		$this->set('permalink_structure', $this->addSampleShow($this->SlugConfig->permalink_structure));

		$this->pageTitle = 'スラッグ設定一覧';
		$this->search = 'slug_configs_index';
		$this->help = 'slug_configs_index';

		parent::admin_index();

	}
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 * @access public
 */
	function admin_edit($id = null) {

		$this->set('permalink_structure', $this->addSampleShow($this->SlugConfig->permalink_structure));

		$this->pageTitle = 'スラッグ設定編集';

		parent::admin_edit($id);

	}
/**
 * [ADMIN] 削除
 *
 * @param int $id
 * @return void
 * @access public
 */
	function admin_delete($id = null) {

		parent::admin_delete($id);

	}
/**
 * 各ブログ別のスラッグ設定データを作成する
 *   ・スラッグ設定データがないブログ用のデータのみ作成する
 * 
 * @return void
 * @access public
 */
	function admin_first() {

		if($this->data) {

			$count = 0;
			if($this->blogContentDatas) {
				foreach ($this->blogContentDatas as $blog) {

					$slugConfigData = $this->SlugConfig->findByBlogContentId($blog['BlogContent']['id']);
					if(!$slugConfigData) {
						$this->data['SlugConfig']['blog_content_id'] = $blog['BlogContent']['id'];
						$this->data['SlugConfig']['permalink_structure'] = 0;
						$this->data['SlugConfig']['ignore_archives'] = false;
						$this->SlugConfig->create($this->data);
						if(!$this->SlugConfif->save($this->data, false)) {
							$this->log(sprintf('ブログID：%s の登録に失敗しました。', $blog['BlogContent']['id']));
						} else {
							$count++;
						}
					}

				}
			}

			$this->Session->setFlash(sprintf('%s 件のスラッグ設定を登録しました。', $count));
			$this->redirect(array('controller' => 'slug_configs', 'action' => 'index'));

		}

		$this->pageTitle = 'スラッグ設定データ作成';

	}
/**
 * 一覧用の検索条件を生成する
 *
 * @param array $data
 * @return array $conditions
 * @access protected
 */
	function _createAdminIndexConditions($data) {

		$conditions = array();
		$blogContentId = '';

		if(isset($data['SlugConfig']['blog_content_id'])) {
			$blogContentId = $data['SlugConfig']['blog_content_id'];
		}

		unset($data['_Token']);
		unset($data['SlugConfig']['blog_content_id']);

		// 条件指定のないフィールドを解除
		foreach($data['SlugConfig'] as $key => $value) {
			if($value === '') {
				unset($data['SlugConfig'][$key]);
			}
		}

		if($data['SlugConfig']) {
			$conditions = $this->postConditions($data);
		}

		if($blogContentId) {
			$conditions = array(
				'SlugConfig.blog_content_id' => $blogContentId
			);
		}

		if($conditions) {
			return $conditions;
		} else {
			return array();
		}

	}

}
