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
			'BlogPost'	=> array('beforeValidate')
	);
/**
 * beforeValidate
 * 
 * @param Model $model
 * @return boolean
 * @access public
 */
	function beforeValidate($model) {

		if($model->alias == 'BlogPost') {
			$SlugModel = ClassRegistry::init('Slug.Slug');
			return $SlugModel->validates($model->data);
			/*if(!$this->checkNameStatus($model->data)) {
				return false;
			}*/
		}

		return true;

	}

}
