<?php
/**
 * slug プラグイン用
 * データベース初期化
 */
	$this->Plugin->initDb('slug');
/**
 * ブログ情報を元にスラッグ設定データを作成する
 *   ・スラッグ設定データがないブログ用のデータのみ作成する
 * 
 */
	App::import('Model', 'Blog.BlogContent');
	$BlogContentModel = new BlogContent();
	// $BlogContentModel = ClassRegistry::init('Blog.BlogContent');
	$blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
	if($blogContentDatas) {

		App::import('Model', 'Slug.SlugConfig');
		$SlugConfigModel = new SlugConfig();
		foreach ($blogContentDatas as $key => $blog) {
			$slugConfigData = $SlugConfigModel->findByBlogContentId($key);
			$savaData = array();
			if(!$slugConfigData) {
				$savaData['SlugConfig']['blog_content_id'] = $key;
				$savaData['SlugConfig']['permalink_structure'] = 0;
				$savaData['SlugConfig']['ignore_archives'] = false;
				$SlugConfigModel->create($savaData);
				$SlugConfigModel->save($savaData, false);
			}
		}

	}
