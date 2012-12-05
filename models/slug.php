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
		'name' => array(
			'maxLength' => array(
				'rule'		=> array('maxLength', 255),
				'message'	=> 'スラッグは255文字以内で入力してください。'
			),
			'checkNameStatus' => array(
				'rule'		=>	array('checkNameStatus'),
				'message'	=> '有効の際はスラッグを入力して下さい。'
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
/**
 * 初期値取得
 *
 * @access public
 * @return array
 */
	function getDefaultValue() {
		$array = array(
			'Slug' => array(
				'status' => 1
			)
		);
		return $array;
	}

}
