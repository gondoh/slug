<?php
/**
 * slug 基底コントローラ
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug
 * @license			MIT
 */
class SlugAppController extends BaserPluginAppController {
/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	var $helpers = array('Blog.Blog');
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
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index'))
	);
/**
 * ブログコンテンツデータ
 * 
 * @var array
 * @access public
 */
	var $blogContentDatas = array();
/**
 * beforeFilter
 *
 * @return	void
 * @access 	public
 */
	function beforeFilter() {

		parent::beforeFilter();
		$judgeSlugConfigUse = false;
		$datas = $this->SlugConfig->find('all', array('recursive' => -1));
		if($datas) {
			$judgeSlugConfigUse = true;
		} else {
			$this->Session->setFlash('「スラッグ設定データ」にてスラッグ設定用のデータを作成して下さい。');
		}
		$this->set('judgeSlugConfigUse', $judgeSlugConfigUse);

		// ブログ情報を取得
		$BlogContentModel = ClassRegistry::init('Blog.BlogContent');
		$this->blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));

	}
/**
 * [ADMIN] 一覧表示
 * 
 * @return void
 * @access public
 */
	function admin_index() {

		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions($this->modelClass, array('default' => $default));

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


		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);

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
			$this->{$this->modelClass}->id = $id;
			$this->data = $this->{$this->modelClass}->read();
		} else {
			$this->{$this->modelClass}->set($this->data);
			if ($this->{$this->modelClass}->save($this->data)) {
				$this->Session->setFlash('更新が完了しました。');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('入力エラーです。内容を修正して下さい。');
			}
		}

		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);

		$this->render('form');

	}
/**
 * [ADMIN] 削除
 *
 * @param int $id
 * @return void
 * @access public
 */
	function admin_delete($id = null) {

		if(!$id) {
			$this->Session->setFlash('無効な処理です。');
			$this->redirect(array('action' => 'index'));
		}
		if($this->{$this->modelClass}->delete($id)) {
			$this->Session->setFlash('NO.' . $id . 'のデータを削除しました。');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('データベース処理中にエラーが発生しました。');
		}
		$this->redirect(array('action' => 'index'));

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

}
