<?php
/**
 * [Helper] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
class SlugHelper extends AppHelper {
/**
 * ヘルパー
 *
 * @var array
 */
	public $helpers = array('bcBaser', 'Blog', 'BcHtml', 'Html');
	
/**
 * SlugConfigモデル
 * 
 * @var Object
 */
	public $SlugConfigModel = null;
	
/**
 * Slug設定情報
 * 
 * @var array
 */
	public $slugConfigs = array();
	
/**
 * ブログ記事データ
 * 
 * @var array
 */
	public $blogPostData = array();
	
/**
 * ブログ記事一覧表示の際に、URL書換えを実施するアクション判定
 * 
 * @var array
 */
	public $blogArchives = array('posts', 'index', 'archives');
	
/**
 * ブログアーカイブ
 * 
 * @var array
 */
	public $blogArchivesTypes = array(
		'category', 'tag', 'date', 'author'
	);
	
/**
 * Construct
 * 
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		
		if (ClassRegistry::isKeySet('Slug.SlugConfig')) {
			$this->SlugConfigModel = ClassRegistry::getObject('Slug.SlugConfig');
		} else {
			$this->SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
		}
	}
	
/**
 * スラッグを定義する
 * 
 * @param array $slug
 * @param array $post
 * @return string
 */
	public function getSlugName($slug, $post) {
		$this->slugConfigs = $this->SlugConfigModel->find('first', array(
			'conditions' => array(
				'SlugConfig.blog_content_id' => $slug['blog_content_id']
			),
			'recursive' => -1
		));
		
		if (empty($slug['name'])) {
			$slug['name'] = $post['no'];
		}
		
		switch ($this->slugConfigs['SlugConfig']['permalink_structure']) {
			case 1:	// スラッグ
				$this->slugName = $slug['name'];
				break;
				
			case 2:	// 記事ID
				$this->slugName = $post['id'];
				break;
				
			case 3:	// 記事ID（6桁）
				$this->slugName = sprintf('%06d', $post['id']);
				break;
				
			case 4:	// /2012/12/01/sample-post/
				$this->slugName = date('Y/m/d', strtotime($post['posts_date'])) . '/' . $slug['name'];
				break;
				
			case 5:	// /2012/12/sample-post/
				$this->slugName = date('Y/m', strtotime($post['posts_date'])) . '/' . $slug['name'];
				break;
				
			case 6:	// カテゴリ/スラッグ
				$BlogCategory = ClassRegistry::init('Blog.BlogCategory');
				if (empty($post['blog_category_id'])) {
					$post['blog_category_id'] = '';
				}
				$categoryDate = $BlogCategory->find('first', array(
					'conditions' => array(
						'BlogCategory.id' => $post['blog_category_id']
					),
					'fields' => array('id', 'name', 'title'),
					'recursive' => -1
				));
				if ($categoryDate) {
					$slug['name'] = $categoryDate['BlogCategory']['name'] . DS . $slug['name'];
				}
				$this->slugName = $slug['name'];
				break;
				
			default:
				$this->slugName = $post['no'];
				break;
		}
		
		return $this->slugName;
	}
	
/**
 * スラッグ用URLを生成する
 * 
 * @param array $slug
 * @param array $post
 * @return string
 */
	public function getSlugUrl($slug, $post){
		$this->slugConfigs = $this->SlugConfigModel->find('first', array(
			'conditions' => array(
				'SlugConfig.blog_content_id' => $post['blog_content_id']
			),
			'recursive' => -1
		));
		
		$actionName = '/archives';
		// スラッグ設定情報でarchives除外設定を有効化している場合は、URLからarchivesを除外する
		if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
			$actionName = '';
		}
		
		$slugUrl = '';
		// スラッグ設定情報に設定しているブログ記事URLの形式に、URLを設定する
		switch ($this->slugConfigs['SlugConfig']['permalink_structure']) {
			case '1':
				// スラッグ
				$slugUrl = $actionName . '/' . $slug['name'];
				break;
			
			case '2':
				// 記事ID
				$slugUrl = $actionName . '/' . $post['id'];
				break;
			
			case '3':
				// 記事ID（6桁）
				$slugUrl = $actionName . '/' . sprintf('%06d', $post['id']);
				break;
			
			case '4':
				// /2012/12/01/sample-post/
				$slugUrl = $actionName . '/' . date('Y/m/d', strtotime($post['posts_date'])) . '/' . $slug['name'];
				break;
			
			case '5':
				// /2012/12/sample-post/
				$slugUrl = $actionName . '/' . date('Y/m', strtotime($post['posts_date'])) . '/' . $slug['name'];
				break;
			
			default:
				// デフォルト
				$slugUrl = $actionName . '/' . $post['no'];
				break;
		}
		return $slugUrl;
	}
	
/**
 * archives除外設定が有効な場合は、archives を省いたURLリンクを生成して返す
 * 
 * @param string $out
 * @return string
 */
	public function convertOutputArchivesLink($out = '') {
		$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';
		if ($out) {
			if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
				$out = preg_replace($pattern, 'href="$1' . DS . '$2' . '"', $out);
			}
		}
		return $out;
	}
	
/**
 * スラッグ入力欄の表示判定を行う
 * 
 * @param string $data
 * @return boolean
 */
	public function judgeAppearInputSlug($data) {
		$judgeUseSlugInput = false;
		// p($this->_View->Slug->SlugConfigModel->permalink_structure);
		switch ($data) {
			case '1':
				// 記事タイトル
				$judgeUseSlugInput = true;
				break;
			
			case '4':
				// /2012/12/01/sample-post/
				$judgeUseSlugInput = true;
				break;
			
			case '5':
				// /2012/12/sample-post/
				$judgeUseSlugInput = true;
				break;
			
			default:
				break;
		}
		return $judgeUseSlugInput;
	}
	
/**
 * スラッグ構造の表示例を調整する
 * 
 * @param array $array
 * @return array
 */
	public function addSampleShow($array = array()) {
		foreach ($array as $key => $value) {
			if ($key == 0) {
				$array[$key] = $value . '（標準のブログ記事NO）';
			}
			if ($key == 4) {
				$array[$key] = $value . '（/' . date('Y/m/d') . '/sample-post' . '）';
			}
			if ($key == 5) {
				$array[$key] = $value . '（/' . date('Y/m') . '/sample-post' . '）';
			}
		}
		return $array;
	}
	
/**
 * ブログ記事がスラッグデータを持っているか判定する
 * 
 * @param array $data
 * @return boolean
 */
	public function judgeContentsSearchUrl($data = array()) {
		if ($data['Content']['model'] == 'BlogPost') {
			$BlogPostModel = ClassRegistry::init('BlogPost');
			$this->blogPostData = $BlogPostModel->find('first', array('conditions' => array(
				'BlogPost.id' => $data['Content']['model_id']
			)));
			if ($this->blogPostData['Slug']) {
				return true;
			}
		}
		return false;
	}
	
/**
 * 検索結果ページ用のURLを生成する
 * 
 * @param array $data
 * @return string
 */
	public function getContentsSearchUrl($data = array()) {
		$blogLink = '';
		if ($data['Content']['model'] == 'BlogPost') {
			$bcBaser = new BcBaserHelper();
			$blogLink = $bcBaser->getUri('/' . $this->blogPostData['BlogContent']['name'] . $this->getSlugUrl($this->blogPostData['Slug'], $this->blogPostData['BlogPost']));
		}
		return $blogLink;
	}
	
/**
 * $blog->category() で取得したURLを書き換える
 * echo $slug->category($post) で利用する
 * TODO $blog->category($post) ではフック箇所がないための暫定処置
 * 　コア側が、リンク生成に BcBaserHelper() を使う仕様に変わると不要になると思われる。。
 * 
 * @param array $post
 * @param array $options
 * @return string 
 */
	public function category($post = array(), $options = array()) {
		$out = $this->Blog->getCategory($post, $options);
		if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
			$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';
			$out = preg_replace($pattern, 'href="$1' . '/$2' . '"', $out);
		}
		return $out;
	}
	
/**
 * $blog->postContent() の代わりに利用し「続きを読む」のURLを書き換える
 * echo $slug->postContent() で利用する
 * TODO $blog->postContent() ではフック箇所がないための暫定処置
 * 　コア側が、リンク生成に BcBaserHelper() を使う仕様に変わると不要になると思われる。。
 * 
 * @param array $post
 * @param boolean $moreText
 * @param boolean $moreLink
 * @param boolean $cut
 * @return string 
 */
	public function postContent($post, $moreText = true, $moreLink = false, $cut = false) {
		if ($moreLink === true) {
			$moreLink = '≫ 続きを読む';
		}
		$out =	'<div class="post-body">'.$post['BlogPost']['content'].'</div>';
		if ($moreText && $post['BlogPost']['detail']) {
			$out .=	'<div id="post-detail">'.$post['BlogPost']['detail'].'</div>';
		}
		if ($cut) {
			$out = mb_substr(strip_tags($out), 0, $cut, 'UTF-8');
		}
		if ($moreLink && trim($post['BlogPost']['detail']) && trim($post['BlogPost']['detail']) != "<br>") {
			$moreLinkHtml = '<p class="more">'.$this->Html->link($moreLink, array('admin'=>false,'plugin'=>'', 'controller'=>$this->Blog->blogContent['name'],'action'=>'archives', $post['BlogPost']['no'],'#'=>'post-detail'), null,null,false).'</p>';
			if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
				$pattern = '/href\=\"(.+)\/archives\/(.+)\"/';
				$moreLinkHtml = preg_replace($pattern, 'href="$1' . '/$2' . '"', $moreLinkHtml);
			}
			$out .= $moreLinkHtml;
		}
		return $out;
	}
	
/**
 * $blog->getCategoryList() の代わりに利用し、カテゴリへのリンクURLを書き換える
 * echo $slug->getCategoryList() で利用する
 * TODO $blog->getCategoryList() ではフック箇所がないための暫定処置
 * 　コア側が、リンク生成に BcBaserHelper() を使う仕様に変わると不要になると思われる。。
 * 
 * @param array $categories
 * @param int $depth
 * @param boolean $count
 * @param array $options
 * @return string
 */
	public function getCategoryList($categories, $depth=3, $count = false, $options = array()) {
		$out = $this->Blog->_getCategoryList($categories,$depth, 1, $count, $options);
		if ($this->slugConfigs['SlugConfig']['ignore_archives']) {
			$pattern = '/href\=\"(.+?)\/archives\/(.+?)\"/';
			$out = preg_replace($pattern, 'href="$1' . '/$2' . '"', $out);
		}
		return $out;
	}
	
}
