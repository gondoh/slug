<?php
/**
 * slugモデル
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.controllers
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
	var $plugin = 'slug';
/**
 * バリデーション
 *
 * @var array
 * @access public
 */
	var $validate = array(
		'name' => array(
			'maxLength' => array(
				'rule'		=> array('maxLength', 255),
				'message'	=> 'スラッグは255文字以内で入力してください。'
			),
			'checkNameStatus' => array(
				'rule'		=>	array('checkNameStatus'),
				'message'	=> '有効の際はスラッグを入力して下さい。'
			),
			'duplicate' => array(
				'rule'		=>	array('duplicate', 'name'),
				'message'	=> '既に登録のあるスラッグです。'
			)
		)
	);
/**
 * カスタムバリデーション
 * スラッグ名が未入力で有効チェックが入ってる場合はエラーとする
 * 
 * @param array $checkData
 * @return boolean 
 */
	function checkNameStatus($checkData) {

		if(empty($this->data[$this->alias]['name']) && $this->data[$this->alias]['status']) {
			return false;
		}
		return true;

	}

}
