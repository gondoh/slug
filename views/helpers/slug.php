<?php
/**
 * [Helper] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @license			MIT
 */
class SlugHelper extends AppHelper {
/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	var $helpers = array('Blog', 'Html');
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
		$this->slugConfigs = $SlugConfigModel->findExpanded();
	}
/**
 * スラッグ用URLを生成する
 * 
 * @param array $data
 * @return string
 * @access public
 */
	function getSlugUrl($slug, $data){

		$actionName = '/archives';
		if($this->slugConfigs['ignore_archives'] === '1') {
			$actionName = '';
		}

		if($this->slugConfigs['permalink_structure'] === '1') {
			// スラッグ
			return $actionName . '/' . $slug['name'];

		} elseif($this->slugConfigs['permalink_structure'] === '2') {
			// 記事ID
			return $actionName . '/' . $data['id'];

		} elseif($this->slugConfigs['permalink_structure'] === '3') {
			// 記事ID（6桁）
			return $actionName . '/' . sprintf('%06d', $data['id']);

		} elseif($this->slugConfigs['permalink_structure'] === '4') {
			// /2012/12/01/sample-post/
			return $actionName . '/' . date('Y/m/d', strtotime($data['posts_date'])) . '/' . $slug['name'];

		} elseif($this->slugConfigs['permalink_structure'] === '5') {
			// /2012/12/sample-post/
			return $actionName . '/' . date('Y/m', strtotime($data['posts_date'])) . '/' . $slug['name'];

		} else {
			return $actionName . '/' . $slug['name'];

		}

	}
/**
 * スラッグ入力欄の表示判定を行う
 * 
 * @param string $data
 * @return boolean
 */
	function jedgeAppearInputSlug($data) {

		if(!$data) {
			return true;
		} elseif($data === '1' || $data === '4' || $data === '5') {
			// 記事タイトル or /2012/12/01/sample-post/ or /2012/12/sample-post/
			return true;
		}

		return false;

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
	function category($post = array(), $options = array()) {

		$out = $this->Blog->getCategory($post, $options);
		if($this->slugConfigs['ignore_archives'] === '1') {
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
	function postContent($post, $moreText = true, $moreLink = false, $cut = false) {

		if($moreLink === true) {
			$moreLink = '≫ 続きを読む';
		}
		$out =	'<div class="post-body">'.$post['BlogPost']['content'].'</div>';
		if($moreText && $post['BlogPost']['detail']) {
			$out .=	'<div id="post-detail">'.$post['BlogPost']['detail'].'</div>';
		}
		if($cut) {
			$out = mb_substr(strip_tags($out), 0, $cut, 'UTF-8');
		}
		if($moreLink && trim($post['BlogPost']['detail']) && trim($post['BlogPost']['detail']) != "<br>") {
			$moreLinkHtml = '<p class="more">'.$this->Html->link($moreLink, array('admin'=>false,'plugin'=>'', 'controller'=>$this->Blog->blogContent['name'],'action'=>'archives', $post['BlogPost']['no'],'#'=>'post-detail'), null,null,false).'</p>';
			if($this->slugConfigs['ignore_archives'] === '1') {
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
	function getCategoryList($categories, $depth=3, $count = false, $options = array()) {

		$out = $this->Blog->_getCategoryList($categories,$depth, 1, $count, $options);
		if($this->slugConfigs['ignore_archives'] === '1') {
			$pattern = '/href\=\"(.+?)\/archives\/(.+?)\"/';
			$out = preg_replace($pattern, 'href="$1' . '/$2' . '"', $out);
		}
		return $out;

	}

}
