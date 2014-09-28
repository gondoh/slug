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
		'Html.afterGetLink',
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
 * slug設定モデル
 * 
 * @var Object
 */
	public $SlugConfigModel = null;
	
/**
 * Construct 
 * 
 */
	public function __construct() {
		parent::__construct();
		
		if (ClassRegistry::isKeySet('Slug.SlugConfig')) {
			$this->SlugConfigModel = ClassRegistry::getObject('Slug.SlugConfig');
		} else {
			$this->SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
		}
		
		if (ClassRegistry::isKeySet('PluginContent')) {
			$this->PluginContent = ClassRegistry::getObject('PluginContent');
		} else {
			$this->PluginContent = ClassRegistry::init('PluginContent');
		}
		
		if (ClassRegistry::isKeySet('Blog.BlogPost')) {
			$this->BlogPostModel = ClassRegistry::getObject('Blog.BlogPost');
		} else {
			$this->BlogPostModel = ClassRegistry::init('Blog.BlogPost');
		}
		
		//$this->View = ClassRegistry::getObject('view');
		App::import('Helper', 'BcHtml');
		$this->BcHtml = new BcHtmlHelper(new View());
		
		App::import('Helper', 'Slug.Slug');
		$this->Slug = new SlugHelper(new View());
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
				if ($event->data['id'] == 'BlogContentAdminEditForm') {
					$event->data['out'] = $event->data['out'] . $Form->element('Slug.slug_config_form');
				}
			}
			// ブログ追加画面にスラッグ設定欄を表示する
			if ($Form->request->params['action'] == 'admin_add'){
				if ($event->data['id'] == 'BlogContentAdminAddForm') {
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
	public function htmlAfterGetLink(CakeEvent $event) {
		$View = $event->subject();
		$url = $event->data['url'];
		if (!BcUtil::isAdminSystem()) {
			if (is_array($url)) {
				$this->linkUrl = $this->BcHtml->url($url);
				$url = $this->linkUrl;
			}
			$parseUrl = Router::parse($url);
			
			$pluginContent = $this->PluginContent->currentPluginContent($url);
			// beforeFind が機能しないために取得し直している
			if ($pluginContent && $pluginContent['PluginContent']['plugin'] == 'blog') {
				$pluginContent = $this->PluginContent->find('first', array(
					'conditions' => array(
						'PluginContent.name' => $pluginContent['PluginContent']['name'],
						'PluginContent.plugin' => $pluginContent['PluginContent']['plugin'])));
				$this->slugConfigs = $this->SlugConfigModel->find('first', array(
					'conditions' => array(
						'SlugConfig.id' => $pluginContent['PluginContent']['content_id']
					),
					'recursive' => -1
				));
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
		// URLのaction以降の引数が1つより多い場合に判定を実施する
		if ($countPass === 1 || $countPass === 2) {
			// 判定する引数を設定する
			switch ($countPass) {
				case 1:
					$no = $parseUrl['pass']['0'];
					break;
				
				case 2:
					$no = $parseUrl['pass']['1'];
					break;
				
				default:
					break;
			}
			
			// single以外のarchivesへのリンク判定を行う
			$judgeArchives = false;
			if (in_array($parseUrl['pass']['0'], $this->Slug->blogArchivesTypes)) {
				$judgeArchives = true;
			}
			
			// singleへのリンクの場合に実施する
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
				// リンクURLからブログ記事情報を判定する
				$data = $this->BlogPostModel->find('first', array(
					'conditions' => $conditions,
					'recursive' => 1));
				if ($data) {
					// リンクURLがブログ記事であればスラッグ情報を取得する
					$no = $this->Slug->getSlugName($data['Slug'], $data['BlogPost']);
				}
				unset($data);
			}
		}
		
		$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';
		if (!empty($no)) {
			// single以外のarchivesへのリンクの場合、種類別でURLを調整する
			if ($judgeArchives) {
				switch ($parseUrl['pass']['0']) {
					case 'category':
						// カテゴリへのリンクの際は category/ を付加する
						$no = $this->Slug->blogArchivesTypes[0] . DS . $no;
						break;
					
					case 'tag':
						// タグへのリンクの際は tag/ を付加する
						$no = $this->Slug->blogArchivesTypes[1] . DS . $no;
						break;
					
					case 'date';
						// 年別へのリンクの際は date/ を付加する
						$no = $this->Slug->blogArchivesTypes[2] . DS . $no;
						break;
					
					case 'author':
						// ユーザーへのリンクの際は author/ を付加する
						$no = $this->Slug->blogArchivesTypes[3] . DS . $no;
						break;
					
					default:
						break;
				}
			}
			if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
				// リンクURLからarchivesの文字列を除外する
				$out = preg_replace($pattern, 'href="$1' . DS . $no . '"', $out);
			} else {
				$out = preg_replace($pattern, 'href="$1' . '/archives/' . $no . '"', $out);
			}
		} else {
			if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
				// リンクURLからarchivesの文字列を除外する
				$out = preg_replace($pattern, 'href="$1' . DS . '$2' . '"', $out);
			}
		}
		
		return $out;
	}
	
}
