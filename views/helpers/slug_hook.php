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
	var $registerHooks = array('afterFormInput');
/**
 * ビュー
 * 
 * @var View 
 */
	var $View = null;
/**
 * Construct 
 * 
 */
	function __construct() {
		parent::__construct();
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
/* TODO ブログ記事の「前の記事、次の記事」のリンクを変更する
	function afterBaserGetLink(&$html, $url, $out) {

		if(empty($this->params['admin'])) {
			if($this->params['plugin'] == 'blog') {
				if($this->params['controller'] == 'blog') {
					if($this->params['action'] == 'archives') {

						$parseUrl = Router::parse($url);

						$SlugModel = ClassRegistry::init('Slug.Slug');
						$blogContentId = $this->View->viewVars['blogContent']['BlogContent']['id'];
						$conditions = array(
							'Slug.blog_content_id'	=> $blogContentId,
							'Slug.blog_post_no'		=> $parseUrl['pass']['0']
						);
						$data = $SlugModel->find('first', array('conditions' => $conditions));
						if($data) {
							$parseUrl['pass']['0'] = $data['Slug']['name'];
							$url = Router::url($parseUrl);
						}

					}
				}
			}
		}

		return $out;

	}
*/
}
