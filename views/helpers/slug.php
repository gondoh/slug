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
 * スラッグ用URLを生成する
 * 
 * @param array $data
 * @return string
 * @access public
 */
	function getSlugUrl($slug, $data){

		$SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
		$SlugConfigs = $SlugConfigModel->findExpanded();

		$actionName = '/archives';
		if($SlugConfigs['ignore_archives'] === '1') {
			$actionName = '';
		}
		if($SlugConfigs['permalink_structure'] === '1') {
			// 記事タイトル
			return $actionName . '/' . $data['name'];

		} elseif($SlugConfigs['permalink_structure'] === '2') {
			// 記事ID
			return $actionName . '/' . $data['id'];

		} elseif($SlugConfigs['permalink_structure'] === '3') {
			// 記事ID（6桁）
			return $actionName . '/' . sprintf('%06d', $data['id']);

		} elseif($SlugConfigs['permalink_structure'] === '4') {
			// /2012/12/01/sample-post/
			return $actionName . '/' . date('Y/m/d', strtotime($data['posts_date'])) . '/' . $data['name'];

		} elseif($SlugConfigs['permalink_structure'] === '5') {
			// /2012/12/sample-post/
			return $actionName . '/' . date('Y/m', strtotime($data['posts_date'])) . '/' . $data['name'];

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

}
