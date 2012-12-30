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
class SlugsController extends BaserPluginAppController {
/**
 * コントローラー名
 * 
 * @var string
 * @access public
 */
	var $name = 'Slugs';
/**
 * モデル
 * 
 * @var array
 * @access public
 */
	var $uses = array('Slug.Slug');
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
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'スラッグ管理', 'url' => array('plugin' => 'slug', 'controller' => 'slugs', 'action' => 'index'))
	);
/**
 * [ADMIN] スラッグ一覧表示
 * 
 * @return void
 * @access public
 */
	function admin_index() {

		$default = array('named' => array(
			'num' => $this->siteConfigs['admin_list_num'])
		);
		$this->setViewConditions('Slug', array('default' => $default));
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

		$this->pageTitle = '設定済スラッグ一覧';
		$this->search = 'slugs_index';
		$this->help = 'slugs_index';

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
			$this->data = $this->Slug->read(null, $id);
		} else {
			$this->Slug->set($this->data);
			if ($this->Slug->save()) {
				$this->Session->setFlash('更新が完了しました。');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('入力エラーです。内容を修正して下さい。');
			}
		}

		$this->pageTitle = 'スラッグ編集';
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
		if($this->Slug->delete($id)) {
			$this->Session->setFlash('NO.' . $id . 'のデータを削除しました。');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('データベース処理中にエラーが発生しました。');
		}
		$this->redirect(array('action' => 'index'));

	}
/**
 * [ADMIN][AJAX] 重複スラッグをチェックする
 * blog_content_id が異なるものは重複とみなさない
 * 
 * @return void
 * @access public
 */
	function admin_ajax_check_name() {

		Configure::write('debug', 0);
		$this->layout = null;

		$result = false;
		if($this->data) {
			$datas = $this->Slug->find('all', array(
				'conditions' => array(
					'Slug.name' => $this->data['Slug']['name'],
					'Slug.blog_content_id' => $this->data['Slug']['blog_content_id']
				),
				'recursive' => -1
			));
			if($datas) {
				$result = true;
				// 編集対応のため、重複スラッグが存在する場合でも、同じ id のものはOKとみなす
				foreach ($datas as $key => $data) {
					if($this->data['Slug']['id'] == $data['Slug']['id']) {
						$result = false;
						break;
					}
				}
			}
		}

		$this->set('result', $result);
		$this->render('ajax_result');

	}
/**
 * [ADMIN] 無効状態にする
 * 
 * @param int $id
 * @return void
 * @access public
 */
	function admin_unpublish($id) {

		if(!$id) {
			$this->Session->setFlash('この処理は無効です。');
			$this->redirect(array('action' => 'index'));
		}
		if($this->_changeStatus($id, false)) {
			$this->Session->setFlash('「無効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash('処理に失敗しました。');
		$this->redirect(array('action' => 'index'));

	}
/**
 * [ADMIN] 有効状態にする
 * 
 * @param int $id
 * @return void
 * @access public
 */
	function admin_publish($id) {

		if(!$id) {
			$this->Session->setFlash('この処理は無効です。');
			$this->redirect(array('action' => 'index'));
		}
		if($this->_changeStatus($id, true)) {
			$this->Session->setFlash('「有効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash('処理に失敗しました。');
		$this->redirect(array('action' => 'index'));

	}
/**
 * ステータスを変更する
 * 
 * @param int $id
 * @param boolean $status
 * @return boolean 
 */
	function _changeStatus($id, $status) {
		
		$data = $this->Slug->find('first', array('conditions' => array('Slug.id' => $id), 'recursive' => -1));
		$data['Slug']['status'] = $status;
		if($status) {
			$data['Slug']['status'] = true;
		} else {
			$data['Slug']['status'] = false;
		}
		$this->Slug->set($data);
		if($this->Slug->save()) {
			return true;
		} else {
			return false;
		}

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
		if(isset($data['Slug']['name'])) {
			$name = $data['Slug']['name'];
		}
		
		unset($data['_Token']);
		unset($data['Slug']['name']);

		// 条件指定のないフィールドを解除
		foreach($data['Slug'] as $key => $value) {
			if($value === '') {
				unset($data['Slug'][$key]);
			}
		}

		if($data['Slug']) {
			$conditions = $this->postConditions($data);
		}

		if($name) {
			$conditions[] = array(
				'Slug.name LIKE' => '%'.$name.'%'
			);
		}

		if($conditions) {
			return $conditions;
		} else {
			return array();
		}

	}

}
