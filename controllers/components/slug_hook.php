<?php
/**
 * [Component] slug
 *
 * @copyright		Copyright 2012 - 2013, materializing.
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
/**
 * slug設定情報
 * 
 * @var Object
 * @access public
 */
	var $SlugConfigModel = null;
/**
 * constructer
 * 
 * @return void
 * @access private
 */
	function __construct() {
		parent::__construct();

		if (ClassRegistry::isKeySet('Slug.SlugConfig')) {
			$this->SlugConfigModel = ClassRegistry::getObject('Slug.SlugConfig');
		}else {
			$this->SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
		}
		$this->slugConfigs = $this->SlugConfigModel->read();
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
					} elseif($paramsCount == 2) {
						// TODO カテゴリ名を含む場合のブログ記事詳細
						$slug = $controller->params['pass']['1'];
					} else {
						$slug = $controller->params['pass']['0'];
					}

					if($this->slugConfigs['SlugConfig']['permalink_structure'] === '2' || $this->slugConfigs['SlugConfig']['permalink_structure'] === '3') {
						// 記事ID or 記事ID（6桁）
						$conditions = array(
							'Slug.blog_post_id' => intval($slug),
							'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id'],
						);
					} else {
						// スラッグ、/2012/12/01/sample-post/、/2012/12/sample-post/
						$conditions = array(
							'Slug.name' => $slug,
							'Slug.blog_content_id' => $controller->blogContent['BlogContent']['id'],
						);
					}

					if(!empty($conditions)) {
						$data = $this->SlugModel->find('first', array('conditions' => $conditions));
						// ブログ記事NOをURLの引数と見立てている
						if($data) {
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
				$controller->data['SlugConfig'] = $this->slugConfigs['SlugConfig'];
			}

			// Ajaxコピー処理時に実行
			//   ・Ajax削除時は、内部的に Model->delete が呼ばれているため afterDelete で処理可能
			if($controller->action == 'admin_ajax_copy') {
				// ブログ記事コピー保存時にエラーがなければ保存処理を実行
				if(empty($controller->BlogPost->validationErrors)) {
					$slugDate = array();
					$slugDate['Slug']['blog_content_id'] = $controller->viewVars['data']['BlogPost']['blog_content_id'];
					$slugDate['Slug']['blog_post_no'] = $controller->viewVars['data']['BlogPost']['no'];
					$slugDate['Slug']['name'] = $controller->viewVars['data']['BlogPost']['name'];
					$slugDate['Slug']['blog_post_id'] = $controller->viewVars['data']['BlogPost']['id'];

					// 重複スラッグを探索して、重複していれば重複個数＋１をつける
					$duplicateDatas = $this->SlugModel->searchDuplicateSlug($slugDate);
					if($duplicateDatas) {
						$slugDate['Slug']['name'] = $this->SlugModel->makeSlugName($duplicateDatas, $slugDate);
					}

					$this->SlugModel->create($slugDate);
					$this->SlugModel->save($slugDate, false);
					// キャッシュの削除を行わないと、登録したスラッグがブログ記事編集画面に反映されない
					clearAllCache();
				}
			}

		}

		if($controller->name == 'BlogContents') {

			if($controller->action == 'admin_add') {
				// ブログ保存時にエラーがなければ保存処理を実行
				if(empty($controller->BlogContent->validationErrors)) {
					$saveData = array();
					$saveData['SlugConfig']['blog_content_id'] = $controller->BlogContent->getLastInsertId();
					$saveData['SlugConfig']['permalink_structure'] = 0;
					$saveData['SlugConfig']['ignore_archives'] = false;

					$this->SlugConfigModel->create($saveData);
					$this->SlugConfigModel->save($saveData, false);
				}
			}

			// Ajaxコピー処理時に実行
			//   ・Ajax削除時は、内部的に Model->delete が呼ばれているため afterDelete で処理可能
			if($controller->action == 'admin_ajax_copy') {
				// ブログコピー保存時にエラーがなければ保存処理を実行
				if(empty($controller->BlogContent->validationErrors)) {
					$slugConfigDate = $this->SlugConfigModel->findByBlogContentId($controller->params['pass']['0']);
					// もしスラッグ設定の初期データ作成を行ってない事を考慮して判定している
					$saveData = array();
					if($slugConfigDate) {
						$saveData['SlugConfig']['blog_content_id'] = $controller->viewVars['data']['BlogContent']['id'];
						$saveData['SlugConfig']['permalink_structure'] = $slugConfigDate['SlugConfig']['permalink_structure'];
						$saveData['SlugConfig']['ignore_archives'] = $slugConfigDate['SlugConfig']['ignore_archives'];
					} else {
						$saveData['SlugConfig']['blog_content_id'] = $controller->viewVars['data']['BlogContent']['id'];
						$saveData['SlugConfig']['permalink_structure'] = 0;
						$saveData['SlugConfig']['ignore_archives'] = false;
					}

					$this->SlugConfigModel->create($saveData);
					$this->SlugConfigModel->save($saveData, false);
					// キャッシュの削除を行わないと、登録したスラッグ設定がスラッグ編集画面に反映されない
					clearAllCache();
				}
			}

		}

		// blogPosts、ブログのindex、ブログのarchives で実行
		// プレビュー時に未定義エラーが出るため判定
		if(!empty($controller->params['plugin'])) {
			if($controller->params['plugin'] == 'blog') {
				if($controller->action == 'posts' || $controller->action == 'index' || $controller->action == 'archives') {
					foreach ($controller->viewVars['posts'] as $key => $post) {
						$controller->viewVars['posts'][$key]['BlogPost']['no'] = $this->Slug->getSlugName($post['Slug'], $post['BlogPost']);
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

		// blogPosts で実行
		//  ・requestAction で実行と bcBaser->link 未使用のため、出力内容を直接書き換えている
		if($controller->plugin == 'blog') {
			if($controller->action == 'posts') {
				$controller->output = $this->Slug->convertOutputArchivesLink($controller->output);
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
			// 重複スラッグを探索して、重複していれば重複個数＋１をつける
			$duplicateDatas = $this->SlugModel->searchDuplicateSlug($controller->data);
			if($duplicateDatas) {
				$controller->data['Slug']['name'] = $this->SlugModel->makeSlugName($duplicateDatas, $controller->data);
			}
		} else {
			$controller->data['Slug']['blog_post_id'] = $controller->BlogPost->id;
			// 重複スラッグを探索して、重複していれば重複個数＋１をつける
			$duplicateDatas = $this->SlugModel->searchDuplicateSlug($controller->data, $controller->data['Slug']['id']);
			if($duplicateDatas) {
				$controller->data['Slug']['name'] = $this->SlugModel->makeSlugName($duplicateDatas, $controller->data);
			}
		}

		if(empty($controller->data['Slug']['id'])) {
			$this->SlugModel->create($controller->data['Slug']);
		} else {
			$this->SlugModel->set($controller->data['Slug']);
		}

		if(!$this->SlugModel->save($controller->data['Slug'], false)) {
			$this->log('ブログ記事ID：' . $controller->data['Slug']['blog_post_id'] . 'のスラッグ情報保存に失敗しました。');
		}

	}

}
