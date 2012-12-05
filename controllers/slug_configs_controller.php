<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.controllers
 * @version			1.1.0
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

			$data = $this->SlugConfig->findExpanded();
			$this->data = array('SlugConfig' => $data);

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

		$this->set('permalink_structure', $this->SlugConfig->permalink_structure);
		$this->set('active_all_slugs', $this->SlugConfig->active_all_slugs);
		$this->set('ignore_archives', $this->SlugConfig->ignore_archives);
		$this->pageTitle = 'スラッグプラグイン設定';
		$this->help = 'slugs_index';

	}

}
