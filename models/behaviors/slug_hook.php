<?php
/**
 * [HookBehavior] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.models
 * @license			MIT
 */
class SlugHookBehavior extends ModelBehavior {
/**
 * 登録フック
 *
 * @var array
 * @access public
 */
	var $registerHooks = array(
			'BlogPost'	=> array('afterDelete', 'beforeFind')
	);
/**
 * afterDelete
 * 
 * @param Object $model
 * @return void
 * @access public
 */
	function afterDelete($model) {

		// ブログ記事削除時、そのブログ記事が持つスラッグを削除する
		if($model->alias == 'BlogPost') {
			$SlugModel = ClassRegistry::init('Slug.Slug');
			$data = $SlugModel->find('first', array('conditions' => array('Slug.blog_post_id' => $model->id)));
			if($data) {
				if(!$SlugModel->delete($data['Slug']['id'])) {
					$this->log('ID:' . $data['Slug']['id'] . 'のスラッグの削除に失敗しました。');
				}
			}
		}
		
	}
/**
 * beforeFind
 * 
 * @param Object $model
 * @param array $query
 * @return array
 */
	function beforeFind(&$model, $query) {

		if($model->alias == 'BlogPost') {
			// ブログ記事取得の際にスラッグ情報も併せて取得する
			$association = array(
				'Slug' => array(
					'className' => 'Slug.Slug',
					'foreignKey' => 'blog_post_id'
				)
			);
			$model->bindModel(array('hasOne' => $association));

			// 最近の投稿、ブログ記事前後移動を find する際に実行
			// TODO get_recent_entries に呼ばれる find 判定に、より良い方法があったら改修する
			if(count($query['fields']) === 2) {
				if(($query['fields']['0'] == 'no') && ($query['fields']['1'] == 'name')) {
					$query['fields'][] = 'id';
					$query['fields'][] = 'posts_date';
					$query['recursive'] = 2;
				}
			}
		}

		return $query;

	}

}
