<?php
/**
 * [Component] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.controllers
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
		'initialize', 'startup', 'beforeRender', 'afterBlogPostAdd', 'afterBlogPostEdit', 'shutdown');
/**
 * コントローラー
 *
 * @var Controller
 * @access public
 */
	var $controller = null;
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
 * slug情報
 * 
 * @var Object
 * @access public
 */
	var $SlugModel = null;

	var $slugName = '';
/**
 * constructer
 * 
 * @return void
 * @access private
 */
	function __construct() {
		parent::__construct();

		$SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
		$this->slugConfigs = $SlugConfigModel->findExpanded();
		$this->SlugModel = ClassRegistry::init('Slug.Slug');

		App::import('Helper', 'Slug.Slug');
		$this->Slug = new SlugHelper();

	}
/**
 * initialize
 * 
 * @param Controller $controller 
 */
	function initialize(&$controller) {
		// BlogHelper の不在エラーが出るため読込
		$controller->helpers[] = 'Blog.Blog';
		// Slugヘルパーの追加
		$controller->helpers[] = 'Slug.Slug';		
	}
/**
 * startup
 * 
 * @param Controller $controller 
 * @return void
 * @access public
 */
	function startup(&$controller) {

		if($controller->name == 'BlogPosts') {
			// ブログ記事編集・追加画面で実行
			if($controller->action == 'admin_edit' || $controller->action == 'admin_add') {
				$association = array(
					'Slug' => array(
						'className' => 'Slug.Slug',
						'foreignKey' => 'blog_post_id'
					)
				);
				$controller->BlogPost->bindModel(array('hasOne' => $association));
			}
		}

		// ブログ記事へのリンクをクリックした際に実行
		// ブログ記事ページ表示の際に、記事NOをスラッグに置き換える
		if(!empty($controller->params['plugin'])) {
			if($controller->params['plugin'] == 'blog') {

				if($controller->action == 'archives') {

					// $slug = urldecode($controller->params['pass']['0']);
					foreach ($controller->params['pass'] as $key => $param) {
						$controller->params['pass'][$key] = urldecode($param);
					}

					$slug = '';
					$paramsCount = count($controller->params['pass']);
					if($paramsCount == 4) {
						$postDayBegin = $controller->params['pass']['0'] . '/' . $controller->params['pass']['1'] . '/' . $controller->params['pass']['2'];
						$postDayEnd = $controller->params['pass']['0'] . '/' . $controller->params['pass']['1'] . '/' . $controller->params['pass']['2'] . ' 23:59:59';
						$slug = $controller->params['pass']['3'];
					} elseif($paramsCount == 3) {
						$postDayBegin = $controller->params['pass']['0'] . '/' . $controller->params['pass']['1'] . '/' . '01';
						$postDayEnd = date($controller->params['pass']['0'] . '-' . $controller->params['pass']['1'] . '-t') . ' 23:59:59';
						$slug = $controller->params['pass']['2'];
					} else {
						$slug = $controller->params['pass']['0'];
					}

					if($this->slugConfigs['permalink_structure'] === '1') {
						// スラッグ
						$conditions = array(
							'Slug.name' => $slug,
							'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id'],
						);
					} elseif($this->slugConfigs['permalink_structure'] === '2' || $this->slugConfigs['permalink_structure'] === '3') {
						// 記事ID or 記事ID（6桁）
						$conditions = array(
							'Slug.blog_post_id' => intval($slug),
							'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id'],
						);
					} elseif($this->slugConfigs['permalink_structure'] === '4') {
						if($paramsCount >= 2) {
							// /2012/12/01/sample-post/
							$conditions = array(
								'Slug.name' => $slug,
								'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id']
							);
						}
					} elseif($this->slugConfigs['permalink_structure'] === '5') {
						if($paramsCount >= 2) {
							// /2012/12/sample-post/
							$conditions = array(
								'Slug.name' => $slug,
								'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id']
							);
						}
					} else {
						$conditions = array(
							'Slug.name' => $slug,
							'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id'],
						);
					}

					if(!empty($conditions)) {
						$data = $this->SlugModel->find('first', array('conditions' => $conditions));
						// ブログ記事NOをURLの引数と見立てている
						if($data && $data['Slug']['status']) {
							$controller->params['pass'][0] = $data['Slug']['blog_post_no'];
						}
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

		if($controller->name == 'BlogPosts') {
			// ブログ記事編集・追加画面で実行
			// TODO startup で処理したかったが、$controller->data に入れるとそれを全て上書きしてしまうのでダメだった
			if($controller->action == 'admin_edit' || $controller->action == 'admin_add') {
				$controller->data['SlugConfig'] = $this->slugConfigs;
			}
		}

		// blogPosts、ブログのindex、ブログのarchives で実行
		// プレビュー時に未定義エラーが出るため判定
		if(!empty($controller->params['plugin'])) {
			if($controller->params['plugin'] == 'blog') {
				if($controller->action == 'posts' || $controller->action == 'index' || $controller->action == 'archives') {
					foreach ($controller->viewVars['posts'] as $key => $post) {
						$slugName = $this->Slug->getSlugName($post['Slug'], $post['BlogPost']);
						$controller->viewVars['posts'][$key]['BlogPost']['no'] = $slugName;
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
		// ※get_recent_entries では no と name しか取得してないため、beforeFind で id等 を取得している
		if($controller->action == 'get_recent_entries') {
			if(!empty($controller->output['recentEntries'])) {
				foreach ($controller->output['recentEntries'] as $key => $post) {
					$slugName = $this->Slug->getSlugName($post['Slug'], $post['BlogPost']);
					$controller->output['recentEntries'][$key]['BlogPost']['no'] = $slugName;
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

		$controller->data['Slug']['blog_content_id'] = $controller->data['BlogPost']['blog_content_id'];
		$controller->data['Slug']['blog_post_no'] = $controller->data['BlogPost']['no'];

		// スラッグが未入力の場合は、ブログ記事タイトルを設定する
		if(!$controller->data['Slug']['name']) {
			$controller->data['Slug']['name'] = $controller->data['BlogPost']['name'];
		}

		if($controller->action == 'admin_add') {
			$controller->data['Slug']['blog_post_id'] = $controller->BlogPost->getLastInsertId();
			// 重複スラッグを探索して、重複していれば No をつける
			// TODO Noをカウントする仕様に改修する
			$data = $this->SlugModel->find('first', array(
				'conditions' => array(
					'Slug.name' => $controller->data['Slug']['name']
				))
			);
			if($data) {
				$controller->data['Slug']['name'] = $controller->data['Slug']['name'] . '-2';
			}
			unset($data);
		} else {
			$controller->data['Slug']['blog_post_id'] = $controller->BlogPost->id;
		}

		if(empty($controller->data['Slug']['id'])) {
			$this->SlugModel->create($controller->data['Slug']);
		} else {
			$this->SlugModel->set($controller->data['Slug']);
		}
		// TODO バリデーションエラー時の処理を考える
		if(!$this->SlugModel->save()) {
			// $SlugModel->validationErrors;
		}

	}

}
