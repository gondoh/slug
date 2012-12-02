<?php
/**
 * slugモデル
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.models
 * @version			1.0.0
 * @license			MIT
 */
class Slug extends BaserPluginAppModel {
/**
 * モデル名
 * 
 * @var string
 * @access public
 */
	var $name = 'Slug';
/**
 * プラグイン名
 * 
 * @var string
 * @access public
 */
	var $plugin = 'Slug';
/**
 * バリデーション
 *
 * @var array
 * @access public
 */
	var $validate = array(
		'name'=>array(
			array(
				'rule'		=> array('maxLength', 255),
				'message'	=> 'スラッグは255文字以内で入力してください。'
			),
			array(
				'rule'		=>	array('duplicate', 'name'),
				'message'	=> '既に登録のあるスラッグです。'
			)
		)
	);
/**
 * beforeValidate
 * 
 * @return boolean
 * @access public
 */
	function beforeValidate() {

		parent::beforeValidate();

		if(empty($this->data[$this->alias]['name']) && $this->data[$this->alias]['status']) {
			$this->invalidate('name', '有効の際はスラッグを入力して下さい。');
			return false;
		}

		return true;

	}

}
