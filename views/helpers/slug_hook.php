<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @version			1.0.0
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

}
