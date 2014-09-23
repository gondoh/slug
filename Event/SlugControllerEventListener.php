<?php
/**
 * [ControllerEventListener] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
class SlugControllerEventListener extends BcControllerEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'initialize',
		'startup',
		'beforeRender',
		'shutdown'
	);
	
/**
 * コントローラー
 *
 * @var Controller
 */
	public $controller = null;
	
/**
 * Slugヘルパー
 * 
 * @var SlugHelper
 */
	public $Slug = null;
	
/**
 * Slug設定情報
 * 
 * @var array
 */
	public $slugConfigs = array();
	
/**
 * Slug情報
 * 
 * @var Object
 */
	public $SlugModel = null;
	
/**
 * Slug設定情報
 * 
 * @var Object
 */
	public $SlugConfigModel = null;
	
/**
 * constructer
 * 
 * @return void
 */
	private function __construct() {
		parent::__construct();
		
		if (ClassRegistry::isKeySet('Slug.SlugConfig')) {
			$this->SlugConfigModel = ClassRegistry::getObject('Slug.SlugConfig');
		} else {
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
 * @param CakeEvent $event
 */
	public function initialize(CakeEvent $event) {
		$Controller = $event->subject();
		// BlogHelper の不在エラーが出るため読込
		$Controller->helpers[] = 'Blog.Blog';
		// Slugヘルパーの追加
		$Controller->helpers[] = 'Slug.Slug';		
	}
	
/**
 * startup
 * 
 * @param CakeEvent $event
 * @return void
 */
	public function startup(CakeEvent $event) {
		$Controller = $event->subject();
		// ブログ記事へのリンクをクリックした際に実行
		// ブログ記事ページ表示の際に、記事NOをスラッグに置き換える
		if (!empty($Controller->request->params['plugin'])) {
			if ($Controller->request->params['plugin'] == 'blog') {

				// TODO prefix付だとエラーになるから書き換える
				if ($Controller->request->params['action'] == 'archives') {

					// $slug = urldecode($Controller->request->params['pass']['0']);
					foreach ($Controller->request->params['pass'] as $key => $param) {
						$Controller->request->params['pass'][$key] = urldecode($param);
					}

					$slug = '';
					$paramsCount = count($Controller->request->params['pass']);
					if ($paramsCount == 4) {
						$postDayBegin = $Controller->request->params['pass']['0'] . '/' . $Controller->request->params['pass']['1'] . '/' . $Controller->request->params['pass']['2'];
						$postDayEnd = $Controller->request->params['pass']['0'] . '/' . $Controller->request->params['pass']['1'] . '/' . $Controller->request->params['pass']['2'] . ' 23:59:59';
						$slug = $Controller->request->params['pass']['3'];
					} elseif ($paramsCount == 3) {
						$postDayBegin = $Controller->request->params['pass']['0'] . '/' . $Controller->request->params['pass']['1'] . '/' . '01';
						$postDayEnd = date($Controller->request->params['pass']['0'] . '-' . $Controller->request->params['pass']['1'] . '-t') . ' 23:59:59';
						$slug = $Controller->request->params['pass']['2'];
					} elseif ($paramsCount == 2) {
						// TODO カテゴリ名を含む場合のブログ記事詳細
						$slug = $Controller->request->params['pass']['1'];
					} else {
						$slug = $Controller->request->params['pass']['0'];
					}
					
					if ($this->slugConfigs['SlugConfig']['permalink_structure'] === '2' || $this->slugConfigs['SlugConfig']['permalink_structure'] === '3') {
						// 記事ID or 記事ID（6桁）
						$conditions = array(
							'Slug.blog_post_id' => intval($slug),
							'Slug.blog_content_id' => $Controller->blogContent['BlogContent']['id'],
						);
					} else {
						// スラッグ、/2012/12/01/sample-post/、/2012/12/sample-post/
						$conditions = array(
							'Slug.name' => $slug,
							'Slug.blog_content_id' => $Controller->blogContent['BlogContent']['id'],
						);
					}
					
					if (!empty($conditions)) {
						$data = $this->SlugModel->find('first', array('conditions' => $conditions));
						// ブログ記事NOをURLの引数と見立てている
						if ($data) {
							$Controller->request->params['pass'][0] = $data['Slug']['blog_post_no'];
						}
					}
					
				}
				
			}
		}
		
	}
	
/**
 * beforeRender
 * 
 * @param CakeEvent $event
 * @return void
 */
	public function beforeRender(CakeEvent $event) {
		$Controller = $event->subject();
		if ($Controller->name == 'BlogPosts') {
			// ブログ記事編集・追加画面で実行
			// TODO startup で処理したかったが、$Controller->data に入れるとそれを全て上書きしてしまうのでダメだった
			if ($Controller->request->params['action'] == 'admin_edit' || $Controller->request->params['action'] == 'admin_add') {
				// スラッグ設定データを記事編集画面に追加
				$this->slugConfigs = $this->SlugConfigModel->find('first',
					array(
						'conditions' => array('SlugConfig.blog_content_id' => $Controller->BlogContent->id))
				);
				$Controller->data['SlugConfig'] = $this->slugConfigs['SlugConfig'];
			}
		}
		
		if ($Controller->name == 'BlogContents') {
			// ブログ設定編集画面にスラッグ設定情報を送る
			if ($Controller->request->params['action'] == 'admin_edit') {
				$this->slugConfigs = $this->SlugConfigModel->findByBlogContentId($Controller->BlogContent->id);
				$Controller->data['SlugConfig'] = $this->slugConfigs['SlugConfig'];
				$Controller->set('permalink_structure', $this->Slug->addSampleShow($this->SlugConfigModel->permalink_structure));
			}
			// ブログ追加画面にスラッグ設定情報を送る
			if ($Controller->request->params['action'] == 'admin_add') {
				$defalut = $this->SlugConfigModel->getDefaultValue();
				$Controller->data['SlugConfig'] = $defalut['SlugConfig'];
				$Controller->set('permalink_structure', $this->Slug->addSampleShow($this->SlugConfigModel->permalink_structure));
			}
		}
		
		// blogPosts、ブログのindex、ブログのarchives で実行
		// プレビュー時に未定義エラーが出るため判定
		if (!empty($Controller->params['plugin'])) {
			if ($Controller->params['plugin'] == 'blog') {
				if ($Controller->request->params['action'] == 'posts' || $Controller->request->params['action'] == 'index' || $Controller->request->params['action'] == 'archives') {
					foreach ($Controller->viewVars['posts'] as $key => $post) {
						$Controller->viewVars['posts'][$key]['BlogPost']['no'] = $this->Slug->getSlugName($post['Slug'], $post['BlogPost']);
					}
				}
				
				// 公開側ブログ記事詳細表示時、archives除外設定を行っている場合、
				// /BLOG/archives/detail にアクセスされた場合は notFound にする
				// TODO この仕様で良いのかどうかはのちのちの意見で再考していく必要あり
				if ($Controller->request->params['action'] == 'archives') {
					$paramsCount = count($Controller->params['pass']);
					if ($paramsCount <= 2) {
						if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
							$regex = '/\/archives\//';
							if (preg_match($regex, $Controller->params['url']['url'])) {
								$Controller->notFound();
							}
						}
					}
				}
			}
		}
		
	}
	
/**
 * shutdown
 * 
 * @param CakeEvent $event
 * @return void
 */
	public function shutdown(CakeEvent $event) {
		$Controller = $event->subject();
		// blogPosts で実行
		//  ・requestAction で実行と bcBaser->link 未使用のため、出力内容を直接書き換えている
		if ($Controller->plugin == 'blog') {
			if ($Controller->request->params['action'] == 'posts') {
				$Controller->output = $this->Slug->convertOutputArchivesLink($Controller->output);
			}
		}
	}
	
}
