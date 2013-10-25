<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.controllers
 * @version			1.0.0
 * @license			MIT
 */
class SlugHookComponent extends Object {
/**
 * 登録フック
 *
 * @var array
 * @access public
 */
	var $registerHooks = array(
		'startup', 'shutdown', 'beforeRender', 'afterBlogPostAdd', 'afterBlogPostEdit');
/**
 * コントローラー
 *
 * @var Controller
 * @access public
 */
	var $controller = null;
/**
 * startup
 * 
 * @param Controller $controller 
 * @return void
 * @access public
 */
	function startup($controller) {
		
		if(!empty($controller->params['plugin'])) {
			if($controller->params['plugin'] == 'blog') {
				
				// ブログ記事ページ表示の際に、記事NOをスラッグに置き換える
				if($controller->action == 'archives') {
					$SlugModel = ClassRegistry::init('Slug.Slug');
					$slug = urldecode($controller->params['pass']['0']);
					
					$conditions = array(
						'Slug.name' => $slug,
						'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id'],
					);
					$data = $SlugModel->find('first', array('conditions' => $conditions));
					if($data && $data['Slug']['status']) {
						$controller->params['pass'][0] = $data['Slug']['blog_post_no'];
					}
				}
				
			}
		}
		
	}
/**
 * shutdown
 * 
 * @param Controller $controller 
 * @return void
 * @access public
 */
	function shutdown($controller) {

		// 最近の投稿を表示する際に実行
		if($controller->action == 'get_recent_entries') {

			if(!empty($controller->output['recentEntries'])) {
				$SlugModel = ClassRegistry::init('Slug.Slug');

				foreach ($controller->output['recentEntries'] as $key => $post) {
					$conditions = array(
						'Slug.blog_post_no'		=> $post['BlogPost']['no'],
						'Slug.blog_content_id'	=> $controller->blogContent['BlogContent']['id']
					);
					$data = $SlugModel->find('first', array('conditions' => $conditions));
					if($data && $data['Slug']['status']) {
						$controller->output['recentEntries'][$key]['BlogPost']['no'] = $data['Slug']['name'];
					}
				}
			}

		}

	}
/**
 * beforeRender
 * 
 * @param Controller $controller 
 * @return void
 * @access public
 */
	function beforeRender($controller) {

		// ブログ記事編集画面で実行
		if($controller->name == 'BlogPosts') {

			if($controller->action == 'admin_edit') {
				$SlugModel = ClassRegistry::init('Slug.Slug');
				$conditions = array(
					'Slug.blog_post_id' => $controller->data['BlogPost']['id']
				);
				$data = $SlugModel->find('first', array('conditions' => $conditions));
				if($data) {
					$controller->data['Slug'] = $data['Slug'];
				}
			}

		}

		// blogPosts、ブログのindex、ブログのarchives で実行
		if(!empty($controller->params['plugin'])) {
			if($controller->params['plugin'] == 'blog') {
				if($controller->action == 'posts' || $controller->action == 'index' || $controller->action == 'archives') {
					
					$SlugModel = ClassRegistry::init('Slug.Slug');
					foreach ($controller->viewVars['posts'] as $key => $post) {
						$conditions = array(
							'Slug.blog_post_id' => $post['BlogPost']['id'],
							'Slug.blog_content_id' => $post['BlogPost']['blog_content_id'],
						);
						$data = $SlugModel->find('first', array('conditions' => $conditions));
						if($data && $data['Slug']['status']) {
							$controller->viewVars['posts'][$key]['BlogPost']['no'] = $data['Slug']['name'];
						}
					}
					
				}
			}
		}
		
	}
/**
 * afterBlogPostAdd
 *
 * @param Controller $controller
 * @return void
 * @access public
 */
	function afterBlogPostAdd($controller) {

		// ブログ記事保存時にエラーがなければ保存処理を実行
		if(empty($controller->BlogPost->validationErrors)) {
			$this->_slugSaving($controller);
		}

	}
/**
 * afterBlogPostEdit
 *
 * @param Controller $controller
 * @return void
 * @access public
 */
	function afterBlogPostEdit($controller) {

		// ブログ記事保存時にエラーがなければ保存処理を実行
		if(empty($controller->BlogPost->validationErrors)) {
			$this->_slugSaving($controller);
		}

	}
/**
 * スラッグ情報を保存する
 * 
 * @param Controller $controller 
 * @return void
 * @access private
 */
	function _slugSaving($controller) {

		$SlugModel = ClassRegistry::init('Slug.Slug');
		$controller->data['Slug']['blog_content_id'] = $controller->data['BlogPost']['blog_content_id'];
		$controller->data['Slug']['blog_post_id'] = $controller->data['BlogPost']['id'];
		$controller->data['Slug']['blog_post_no'] = $controller->data['BlogPost']['no'];

		if(empty($controller->data['Slug']['id'])) {
			$SlugModel->create($controller->data['Slug']);
		} else {
			$SlugModel->set($controller->data['Slug']);
		}
		// TODO バリデーションエラー時の処理を考える
		if(!$SlugModel->save()) {
			// $SlugModel->validationErrors;
		}

	}

}