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
class SlugConfigsController extends BaserPluginAppController {
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
 * コンポーネント
 * 
 * @var     array
 * @access  public
 */
	var $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');
/**
 * サブメニューエレメント
 *
 * @var array
 * @access public
 */
	var $subMenuElements = array('slug');
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
 * slug プラグイン設定
 * 
 * @return void
 * @access public
 */
	function admin_index() {

		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions('SlugConfig', array('default' => $default));

		$conditions = $this->_createAdminIndexConditions($this->data);
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num']
		);
		$datas = $this->paginate();
		if($datas) {
			$this->set('datas',$datas);
		}

		// ブログ情報を取得
		$BlogContentModel = ClassRegistry::init('Blog.BlogContent');
		$blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
		$this->set('blogContentDatas', array('0' => '指定しない') + $blogContentDatas);

		$this->set('permalink_structure', $this->addSampleShow($this->SlugConfig->permalink_structure));
		$this->set('ignore_archives', $this->SlugConfig->ignore_archives);

		$this->pageTitle = 'スラッグ設定一覧';
		$this->search = 'slug_configs_index';
		$this->help = 'slug_configs_index';

	}
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 * @access public
 */
	function admin_edit($id = null) {

		if(!$id) {
			$this->Session->setFlash('無効な処理です。');
			$this->redirect(array('action' => 'index'));			
		}
		if(empty($this->data)) {
			$this->SlugConfig->id = $id;
			$this->data = $this->SlugConfig->read();
		} else {
			$this->SlugConfig->set($this->data);
			if ($this->SlugConfig->save()) {
				$this->Session->setFlash('更新が完了しました。');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('入力エラーです。内容を修正して下さい。');
			}
		}

		// ブログ情報を取得
		$BlogContentModel = ClassRegistry::init('Blog.BlogContent');
		$blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
		$this->set('blogContentDatas', array('0' => '指定しない') + $blogContentDatas);

		$this->set('permalink_structure', $this->addSampleShow($this->SlugConfig->permalink_structure));
		$this->set('ignore_archives', $this->SlugConfig->ignore_archives);

		$this->pageTitle = 'スラッグ設定編集';
		$this->render('form');

	}
/**
 * スラッグ構造の表示例を調整する
 * 
 * @param array $array
 * @return array
 * @access public
 */
	function addSampleShow($array = array()) {

		foreach ($array as $key => $value) {
			if($key == 0) {
				$array[$key] = $value . '（標準のブログ記事NO）';
			}
			if($key == 4) {
				$array[$key] = $value . '（/' . date('Y/m/d') . '/sample-post' . '）';
			}
			if($key == 5) {
				$array[$key] = $value . '（/' . date('Y/m') . '/sample-post' . '）';
			}
		}
		return $array;

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

			// ブログ情報を取得
			$BlogContentModel = ClassRegistry::init('Blog.BlogContent');
			$blogContentDatas = $BlogContentModel->find('all', array('recursive' => -1));

			$count = 0;
			foreach ($blogContentDatas as $blog) {

				$slugConfigData = $this->SlugConfig->findByBlogContentId($blog['BlogContent']['id']);
				if(!$slugConfigData) {
					$this->data['SlugConfig']['blog_content_id'] = $blog['BlogContent']['id'];
					$this->data['SlugConfig']['permalink_structure'] = 0;
					$this->data['SlugConfig']['ignore_archives'] = 0;
					$this->SlugConfig->create($this->data);
					if(!$this->SlugConfif->save($this->data, false)) {
						$this->log(sprintf('ブログID：%s の登録に失敗しました。', $blog['BlogContent']['id']));
					} else {
						$count++;
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
		$name = '';
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
