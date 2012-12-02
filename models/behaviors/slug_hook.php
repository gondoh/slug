<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.models
 * @version			1.0.0
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
			'BlogPost'	=> array('beforeValidate', 'afterDelete', 'beforeFind')
	);
/**
 * beforeValidate
 * 
 * @param Object $model
 * @return boolean
 * @access public
 */
	function beforeValidate($model) {

		if($model->alias == 'BlogPost') {
			// $SlugModel = ClassRegistry::init('Slug.Slug');
			// return $SlugModel->validates($model->data);
		}

		return true;

	}
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
	function beforeFind($model, $query) {

		if($model->alias == 'BlogPost') {
			// 最近の投稿を find する際に実行
			// TODO get_recent_entries に呼ばれる find 判定に、より良い方法があったら改修する
			if(count($query['fields']) === 2) {
				if(($query['fields']['0'] == 'no') && ($query['fields']['1'] == 'name')) {
					$query['fields'][] = 'id';
					$query['fields'][] = 'posts_date';
				}
			}
		}
		return $query;

	}

}
