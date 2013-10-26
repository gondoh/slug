<?php
/**
 * [HookHelper] slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug
 * @license			MIT
 */
class SlugHookHelper extends AppHelper {
/**
 * 登録フック
 *
 * @var array
 * @access public
 */
	var $registerHooks = array('afterFormInput', 'afterFormCreate', 'afterBaserGetLink', 'afterElement');
/**
 * ビュー
 * 
 * @var View 
 */
	var $View = null;
/**
 * slugヘルパー
 * 
 * @var SlugHelper
 * @access public
 */
	var $Slug = null;
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
		$this->slugConfigs = $SlugConfigModel->read();
		$this->View = ClassRegistry::getObject('view');

		App::import('Helper', 'Slug.Slug');
		$this->Slug = new SlugHelper();
	}
/**
 * afterFormInput
 * 
 * @param Object $form
 * @param string $fieldName
 * @param string $out
 * @return string 
 */
	function afterFormInput($form, $fieldName, $out) {

		// ブログ記事編集画面のタイトル入力欄の下にスラッグ入力欄を表示する
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
 * afterFormCreate
 * 
 * @param Object $form
 * @param string $id
 * @param string $out
 * @return string
 */
	function afterFormCreate($form, $id, $out) {

		if($form->params['controller'] == 'blog_contents'){
			// ブログ設定編集画面にスラッグ設定欄を表示する
			if($this->action == 'admin_edit'){
				if($id == 'BlogContentEditForm') {
					$out = $out . $this->View->element('admin/slug_config_form', array('plugin' => 'slug'));
				}
			}
			// ブログ追加画面にスラッグ設定欄を表示する
			if($this->action == 'admin_add'){
				if($id == 'BlogContentAddForm') {
					$out = $out . $this->View->element('admin/slug_config_form', array('plugin' => 'slug'));
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
	function afterBaserGetLink($html, $url, $out) {

		if(empty($this->params['prefix']) || ($this->params['prefix'] != 'admin')) {

			$parseUrl = Router::parse($url);
			$PluginContent = ClassRegistry::init('PluginContent');
			$pluginContent = $PluginContent->currentPluginContent($url);
			// beforeFind が機能しないために取得し直している
			if($pluginContent) {
				$pluginContent = $PluginContent->find('first', array(
					'conditions' => array(
						'PluginContent.name' => $pluginContent['PluginContent']['name'],
						'PluginContent.plugin' => $pluginContent['PluginContent']['plugin'])));
				$out = $this->convertOutputArchivesLink($out, $parseUrl, $pluginContent['PluginContent']);
			}

		}

		return $out;

	}
/**
 * archives除外設定が有効な場合は、archives を省いたURLリンクを生成して返す
 * 
 * @param string $out
 * @param array $parseUrl
 * @param array $pluginContent
 * @return string 
 */
	function convertOutputArchivesLink($out = '', $parseUrl = array(), $pluginContent = array()) {

		$countPass = count($parseUrl['pass']);

		if($countPass === 1 || $countPass === 2) {
			if($countPass === 1) {
				$no = $parseUrl['pass']['0'];
			} elseif($countPass === 2) {
				$no = $parseUrl['pass']['1'];
			}

			$judgeArchives = false;
			if($parseUrl['pass']['0'] == 'category' || $parseUrl['pass']['0'] == 'tag' || $parseUrl['pass']['0'] == 'date') {
				$judgeArchives = true;
			}

			if(!$judgeArchives) {
				if($pluginContent) {
					$blogContentId = $pluginContent['content_id'];
				} else {
					$blogContentId = $this->View->viewVars['blogContent']['BlogContent']['id'];
				}

				$conditions = array(
					'BlogPost.blog_content_id'	=> $blogContentId,
					'BlogPost.no'		=> $no
				);
				$BlogPostModel = ClassRegistry::init('Blog.BlogPost');
				$data = $BlogPostModel->find('first', array(
					'conditions' => $conditions,
					'recursive' => 1));
				if($data) {
					$no = $this->Slug->getSlugName($data['Slug'], $data['BlogPost']);
				}
				unset($BlogPostModel);
				unset($data);
			}

		}

		$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';
		if(!empty($no)) {
			if($judgeArchives) {
				// カテゴリへのリンクの際は category/ を付加する
				if($parseUrl['pass']['0'] == 'category') {
					$no = 'category' . DS . $no;
				}
				// タグへのリンクの際は tag/ を付加する
				if($parseUrl['pass']['0'] == 'tag') {
					$no = 'tag' . DS . $no;
				}
				// 年別へのリンクの際は date/ を付加する
				if($parseUrl['pass']['0'] == 'date') {
					$no = 'date' . DS . $no;
				}
			}
			if($this->slugConfigs['SlugConfig']['ignore_archives']) {
				$out = preg_replace($pattern, 'href="$1' . DS . $no . '"', $out);
			} else {
				$out = preg_replace($pattern, 'href="$1' . '/archives/' . $no . '"', $out);
			}
		} else {
			if($this->slugConfigs['SlugConfig']['ignore_archives']) {
				$out = preg_replace($pattern, 'href="$1' . DS . '$2' . '"', $out);
			}
		}

		return $out;

	}
/**
 * afterElement
 * 
 * @param string $name
 * @param string $out
 * @return string 
 */
	function afterElement($name, $out) {

		if(empty($this->params['prefix']) || ($this->params['prefix'] != 'admin')) {
			// プレビュー時に Undefined index が出るため判定
			if(!empty($this->params['plugin'])) {
				if($this->params['plugin'] == 'blog') {

					if(preg_match('/^paginations\/.*/', $name)) {
						if($this->slugConfigs['SlugConfig']['ignore_archives']) {
							if($this->params['action'] == 'archives') {
								$pattern = '/href\=\"(.+?)\/archives\/(.+?)\"/';
								$out = preg_replace($pattern, 'href="$1' . '/$2' . '"', $out);
							}
						}
					}

				}
			}
		}

		return $out;

	}
/**
 * beforeElement：未使用：コード内コメント参照
 * 
 * @param type $name
 * @param type $params
 * @param type $loadHelpers
 * @param type $subDir
 * @return array $params
 */
	function beforeElement($name, $params, $loadHelpers, $subDir) {

		if(empty($this->params['prefix']) || ($this->params['prefix'] != 'admin')) {
			// if($name == 'paginations/simple' || $name == 'paginations/default') {
			if(preg_match('/^paginations\/.*/', $name)) {
				if($this->params['action'] == 'archives') {
					// ここで action を省略しても、最終的に Router:LINE:800 で index が付けられてしまう
					// unset($this->View->passedArgs['action']);
					// $this->View->passedArgs['action'] = '';
				}
			}

		}

		return $params;

	}

}
