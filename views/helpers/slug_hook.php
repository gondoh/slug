<?php
/**
 * [HookHelper] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @license			MIT
 */
class SlugHookHelper extends AppHelper {
/**
 * 登録フック
 *
 * @var array
 * @access public
 */
	var $registerHooks = array('afterFormInput', 'afterBaserGetLink', 'beforeRender');
/**
 * ビュー
 * 
 * @var View 
 */
	var $View = null;
/**
 * slug設定情報
 * 
 * @var array
 * @access public
 */
	var $slugConfigs = array();
/**
 * Construct 
 * 
 */
	function __construct() {
		parent::__construct();
		$SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
		$this->slugConfigs = $SlugConfigModel->findExpanded();
		$this->View = ClassRegistry::getObject('view');
	}
/**
 * afterFormInput
 * タイトル入力欄の下にスラッグ入力欄を表示する
 * 
 * @param string $form
 * @param string $fieldName
 * @param string $out
 * @return string 
 */
	function afterFormInput($form, $fieldName, $out) {
		if($form->params['controller'] == 'blog_posts'){
			if($this->action == 'admin_add' || $this->action == 'admin_edit'){
				if($fieldName == 'BlogPost.name') {
					$out = $out . $this->View->element('admin/slug_form', array('plugin' => 'slug'));
				}
			}
		}
		return $out;
	}
/**
 * afterBaserGetLink
 * 
 * @param Object $html
 * @param string $url
 * @param string $out
 * @return string 
 */
	function afterBaserGetLink(&$html, $url, $out) {

		if(empty($this->params['admin'])) {

			$parseUrl = Router::parse($url);
			$PluginContent = ClassRegistry::init('PluginContent');

			if($parseUrl['controller'] == 'blog') {

				if($PluginContent) {
					$conditions = array(
						'fields' => array('name', 'plugin', 'content_id'),
						'conditions' => array(
							'PluginContent.name' => $parseUrl['controller'],
							'PluginContent.plugin' => 'blog'
						)
					);
					$pluginContent = $PluginContent->find('first', $conditions);
					$out = $this->convertOutputArchivesLink($out, $parseUrl['pass'], $pluginContent['PluginContent']);
				}

			} elseif($parseUrl['action'] == 'archives') {

				// blogPost() ではプラグイン名が入ってこないためチェックする
				if($PluginContent) {
					$conditions = array(
						'fields' => array('name', 'plugin', 'content_id'),
						'conditions' => array(
							'PluginContent.name' => $parseUrl['controller']
						)
					);
					$pluginContents = $PluginContent->find('all', $conditions);
					if($pluginContents) {
						foreach ($pluginContents as $key => $value) {
							if($value['PluginContent']['plugin'] == 'blog') {
								$pluginContent = $value;
								break;
							}
						}
						if($pluginContent) {
							$out = $this->convertOutputArchivesLink($out, $parseUrl['pass'], $pluginContent['PluginContent']);
						}
					}
				}

			}

			/*if($this->params['plugin'] == 'blog') {
				if($this->params['controller'] == 'blog') {
					//if($this->params['action'] == 'archives') {
						if($parseUrl['plugin'] == 'blog') {
							if($parseUrl['controller'] == 'blog') {
								if($parseUrl['action'] == 'archives') {
									$out = $this->convertOutputArchivesLink($out, $parseUrl['pass'], $pluginContent['PluginContent']);
								}
							}
						}
					//}
					if($this->params['action'] == 'archives') {
						$out = $this->convertOutputArchivesLink($out, $parseUrl['pass']);
					}
				}
			} else {
				// ブログ記事へのリンクを変更する
				if($pluginContent['PluginContent']['plugin'] == 'blog') {
					$out = $this->convertOutputArchivesLink($out, $parseUrl['pass'], $pluginContent['PluginContent']);
				}
			}*/

		}

		return $out;

	}
/**
 * archives除外設定が有効な場合は、archives を省いたURLリンクを生成して返す
 * 
 * @param string $out
 * @param array $pass
 * @param array $pluginContent
 * @return string 
 */
	function convertOutputArchivesLink($out = '', $pass = array(), $pluginContent = array()) {

		if($this->slugConfigs['ignore_archives'] === '1') {

			$countPass = count($pass);
			$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';

			if($countPass === 1 || $countPass === 2) {
				if($countPass === 1) {
					$no = $pass['0'];
				} elseif($countPass === 2) {
					$no = $pass['1'];
				}
				$SlugModel = ClassRegistry::init('Slug.Slug');
				if($pluginContent) {
					$blogContentId = $pluginContent['content_id'];
				} else {
					$blogContentId = $this->View->viewVars['blogContent']['BlogContent']['id'];
				}
				$conditions = array(
					'Slug.blog_content_id'	=> $blogContentId,
					'Slug.blog_post_no'		=> $no
				);
				$data = $SlugModel->find('first', array('conditions' => $conditions));
				// ブログ記事 No が入ってきてスラッグが取得できた場合
				// ※ ブログ記事前後移動
				if($data) {
					$no = $data['Slug']['name'];
				}
				$out = preg_replace($pattern, 'href="$1' . '/$2' . '"', $out);
				//$out = preg_replace($pattern, 'href="$1' . '/' . $no . '"', $out);
			} else {
				$out = preg_replace($pattern, 'href="$1' . '/$2' . '"', $out);
			}

		} else {
			/*
			$countPass = count($pass);
			$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';

			if($countPass === 1) {
				$no = $pass['0'];
				$SlugModel = ClassRegistry::init('Slug.Slug');
				if($pluginContent) {
					$blogContentId = $pluginContent['content_id'];
				} else {
					$blogContentId = $this->View->viewVars['blogContent']['BlogContent']['id'];
				}
				$conditions = array(
					'Slug.blog_content_id'	=> $blogContentId,
					'Slug.blog_post_no'		=> $no
				);
				$data = $SlugModel->find('first', array('conditions' => $conditions));
				// ブログ記事 No が入ってきてスラッグが取得できた場合
				// ※ ブログ記事前後移動
				if($data) {
					$no = $data['Slug']['name'];
				}
				$out = preg_replace($pattern, 'href="$1' . '/archives/' . $no . '"', $out);
			} else {
				$out = preg_replace($pattern, 'href="$1' . '/archives/$2' . '"', $out);
			}
			*/
		}

		return $out;

	}

	public function beforeRender() {

		$hoge = 0;
	}

}
