<?php
/**
 * slugモデル
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.models
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
 * belongsTo
 * 
 * @var array
 * @access @public
 */
	var $belongsTo = array(
		'BlogPost' => array(
			'className'	=> 'Blog.BlogPost',
			'foreignKey' => 'blog_post_id'
			)
		);
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
			'checkDuplicateSlug' => array(
				'rule'		=>	array('checkDuplicateSlug'),
				'message'	=> '同一ブログに登録のあるスラッグです。'
			)
		)
	);
/**
 * カスタムバリデーション
 * 同一ブログ内でスラッグが重複する場合はエラーとする
 * 
 * @return boolean 
 * @access public
 */
	function checkDuplicateSlug() {

		$result = true;
		if($this->data) {
			$datas = $this->find('all', array(
				'conditions' => array(
					'Slug.name' => $this->data['Slug']['name'],
					'Slug.blog_content_id' => $this->data['Slug']['blog_content_id']
				),
				'recursive' => -1
			));
			if($datas) {
				$result = false;
				// 編集対応のため、重複スラッグが存在する場合でも、同じ id のものはOKとみなす
				foreach ($datas as $key => $data) {
					if($this->data['Slug']['id'] == $data['Slug']['id']) {
						$result = true;
						break;
					}
				}
			}
		}

		return $result;

	}

}
