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
		array('name' => 'スラッグ管理', 'url' => array('plugin' => 'slug', 'controller' => 'slugs', 'action' => 'index'))
	);
/**
 * slug プラグイン設定
 * 
 * @return void
 * @access public
 */
	function admin_index() {

		if(!$this->data) {

			$this->data = array('SlugConfig' => $this->SlugConfig->findExpanded());

		} else {

			$this->SlugConfig->set($this->data);
			if($this->SlugConfig->validates()) {

				if($this->SlugConfig->saveKeyValue($this->data)) {
					$this->Session->setFlash('保存しました。');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('保存に失敗しました。');
					$this->redirect(array('action' => 'index'));
				}

			} else {

				$this->Session->setFlash('入力値にエラーがあります。');

			}
			
		}

		$this->set('permalink_structure', $this->addSampleShow($this->SlugConfig->permalink_structure));
		$this->set('ignore_archives', $this->SlugConfig->ignore_archives);
		$this->pageTitle = 'スラッグプラグイン設定';

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
