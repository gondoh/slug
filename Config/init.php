<?php
/**
 * [Config] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
/**
 * データベース初期化
 */
	$this->Plugin->initDb('plugin', 'Slug');
/**
 * ブログ情報を元にスラッグ設定データを作成する
 *   ・スラッグ設定データがないブログ用のデータのみ作成する
 * 
 */
	App::uses('BlogContent', 'Blog.Model');
	$BlogContentModel = new BlogContent();
	$blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
	if ($blogContentDatas) {
		CakePlugin::load('Slug');
		App::uses('SlugConfig', 'Slug.Model');
		$SlugConfigModel = new SlugConfig();

		foreach ($blogContentDatas as $key => $blog) {
			$slugConfigData = $SlugConfigModel->findByBlogContentId($key);
			$savaData = array();
			if (!$slugConfigData) {
				$savaData['SlugConfig']['blog_content_id'] = $key;
				$savaData['SlugConfig']['permalink_structure'] = 0;
				$savaData['SlugConfig']['ignore_archives'] = false;
				$SlugConfigModel->create($savaData);
				$SlugConfigModel->save($savaData, false);
			}
		}
	}
/**
 * ブログ記事情報を元にデータを作成する
 *   ・データがないブログ用のデータのみ作成する
 * 
 */
	App::uses('BlogPost', 'Blog.Model');
	$BlogPostModel = new BlogPost();
	$posts = $BlogPostModel->find('all', array('recursive' => -1));
	if ($posts) {
		CakePlugin::load('Slug');
		App::uses('Slug', 'Slug.Model');
		$SlugModel = new Slug();
		foreach ($posts as $key => $post) {
			$slugData = $SlugModel->findByBlogPostId($post['BlogPost']['id']);
			$savaData = array();
			if (!$slugData) {
				$savaData['Slug']['blog_post_id'] = $post['BlogPost']['id'];
				$savaData['Slug']['blog_content_id'] = $post['BlogPost']['blog_content_id'];
				$savaData['Slug']['blog_post_no'] = $post['BlogPost']['no'];
				$SlugModel->create($savaData);
				$SlugModel->save($savaData, false);
			}
		}
	}
