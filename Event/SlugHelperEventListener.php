<?php
/**
 * [HelperEventListener] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
class SlugHelperEventListener extends BcHelperEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'Form.afterInput',
		'Form.afterCreate',
		'afterBaserGetLink',
		'afterElement'
	);
	
/**
 * ビュー
 * 
 * @var View 
 */
	public $View = null;
	
/**
 * slugヘルパー
 * 
 * @var SlugHelper
 */
	public $Slug = null;
	
/**
 * slug設定情報
 * 
 * @var array
 */
	public $slugConfigs = array();
	
/**
 * Construct 
 * 
 */
	public function __construct() {
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
 * @param CakeEvent $event
 * @return string 
 */
	public function formAfterInput(CakeEvent $event) {
		$Form = $event->subject();
		// ブログ記事編集画面のタイトル入力欄の下にスラッグ入力欄を表示する
		if($Form->request->params['controller'] == 'blog_posts'){
			if($Form->request->params['action'] == 'admin_add' || $Form->request->params['action'] == 'admin_edit'){
				if($event->data['fieldName'] == 'BlogPost.name') {
					$event->data['out'] = $event->data['out'] . $Form->element('Slug.slug_form');
				}
			}
		}
		return $event->data['out'];
	}
	
/**
 * afterFormCreate
 * 
 * @param CakeEvent $event
 * @return string
 */
	public function formAfterCreate(CakeEvent $event) {
		$Form = $event->subject();
		if ($Form->request->params['controller'] == 'blog_contents'){
			// ブログ設定編集画面にスラッグ設定欄を表示する
			if ($Form->request->params['action'] == 'admin_edit'){
				if ($event->data['id'] == 'BlogContentEditForm') {
					$event->data['out'] = $event->data['out'] . $Form->element('Slug.slug_config_form');
				}
			}
			// ブログ追加画面にスラッグ設定欄を表示する
			if ($Form->request->params['action'] == 'admin_add'){
				if ($event->data['id'] == 'BlogContentAddForm') {
					$event->data['out'] = $event->data['out'] . $Form->element('Slug.slug_config_form');
				}
			}
		}
		return $event->data['out'];
	}
	
/**
 * afterBaserGetLink
 * 
 * @param CakeEvent $event
 * @param string $url
 * @param string $out
 * @return string 
 */
	public function afterBaserGetLink(CakeEvent $event, $url) {
		$View = $event->subject();
		if (empty($View->request->params['prefix']) || ($View->request->params['prefix'] != 'admin')) {
			$parseUrl = Router::parse($url);
			$PluginContent = ClassRegistry::init('PluginContent');
			$pluginContent = $PluginContent->currentPluginContent($url);
			// beforeFind が機能しないために取得し直している
			if ($pluginContent) {
				$pluginContent = $PluginContent->find('first', array(
					'conditions' => array(
						'PluginContent.name' => $pluginContent['PluginContent']['name'],
						'PluginContent.plugin' => $pluginContent['PluginContent']['plugin'])));
				$event->data['out'] = $this->convertOutputArchivesLink($event->data['out'], $parseUrl, $pluginContent['PluginContent']);
			}
		}
		return $event->data['out'];
	}
	
/**
 * archives除外設定が有効な場合は、archives を省いたURLリンクを生成して返す
 * 
 * @param string $out
 * @param array $parseUrl
 * @param array $pluginContent
 * @return string 
 */
	public function convertOutputArchivesLink($out = '', $parseUrl = array(), $pluginContent = array()) {
		$countPass = count($parseUrl['pass']);
		
		if ($countPass === 1 || $countPass === 2) {
			if ($countPass === 1) {
				$no = $parseUrl['pass']['0'];
			} elseif ($countPass === 2) {
				$no = $parseUrl['pass']['1'];
			}
			
			$judgeArchives = false;
			if ($parseUrl['pass']['0'] == 'category' || $parseUrl['pass']['0'] == 'tag' || $parseUrl['pass']['0'] == 'date') {
				$judgeArchives = true;
			}
			
			if (!$judgeArchives) {
				if ($pluginContent) {
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
				if ($data) {
					$no = $this->Slug->getSlugName($data['Slug'], $data['BlogPost']);
				}
				unset($BlogPostModel);
				unset($data);
			}
		}
		
		$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';
		if (!empty($no)) {
			if ($judgeArchives) {
				// カテゴリへのリンクの際は category/ を付加する
				if ($parseUrl['pass']['0'] == 'category') {
					$no = 'category' . DS . $no;
				}
				// タグへのリンクの際は tag/ を付加する
				if ($parseUrl['pass']['0'] == 'tag') {
					$no = 'tag' . DS . $no;
				}
				// 年別へのリンクの際は date/ を付加する
				if ($parseUrl['pass']['0'] == 'date') {
					$no = 'date' . DS . $no;
				}
			}
			if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
				$out = preg_replace($pattern, 'href="$1' . DS . $no . '"', $out);
			} else {
				$out = preg_replace($pattern, 'href="$1' . '/archives/' . $no . '"', $out);
			}
		} else {
			if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
				$out = preg_replace($pattern, 'href="$1' . DS . '$2' . '"', $out);
			}
		}
		
		return $out;
	}
	
/**
 * afterElement
 * 
 * @param CakeEvent $event
 * @return string 
 */
	public function afterElement(CakeEvent $event) {
		$View = $event->subject();
		if (empty($View->request->params['prefix']) || ($View->request->params['prefix'] != 'admin')) {
			// プレビュー時に Undefined index が出るため判定
			if (!empty($View->request->params['plugin'])) {
				if ($View->request->params['plugin'] == 'blog') {
					
					if (preg_match('/^paginations\/.*/', $event->data['name'])) {
						if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
							if ($View->request->params['action'] == 'archives') {
								$pattern = '/href\=\"(.+?)\/archives\/(.+?)\"/';
								$event->data['out'] = preg_replace($pattern, 'href="$1' . '/$2' . '"', $event->data['out']);
							}
						}
					}
					
				}
			}
		}
		return $event->data['out'];
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
	public function beforeElement($name, $params, $loadHelpers, $subDir) {
		if (empty($this->request->params['prefix']) || ($this->request->params['prefix'] != 'admin')) {
			// if($name == 'paginations/simple' || $name == 'paginations/default') {
			if (preg_match('/^paginations\/.*/', $name)) {
				if ($this->request->params['action'] == 'archives') {
					// ここで action を省略しても、最終的に Router:LINE:800 で index が付けられてしまう
					// unset($this->View->passedArgs['action']);
					// $this->View->passedArgs['action'] = '';
				}
			}
		}
		return $params;
	}
	
}
