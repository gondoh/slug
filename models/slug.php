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
			$datas = $this->searchDuplicateSlug($this->data);
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
/**
 * 重複スラッグを探索してその結果を返す
 *   ・create の際は第１引数のみ。update の際は第２引数も指定する
 * 
 * @param array $data
 * @param int $id
 * @return mixed
 * @access public
 */
	function searchDuplicateSlug($data = array(), $id = null) {

		if(!$id) {
			$duplicateDatas = $this->find('all', array(
				'conditions' => array(
					'Slug.name' => $data['Slug']['name'],
					'Slug.blog_content_id' => $data['Slug']['blog_post_id']
				),
				'recursive' => -1
			));
		} else {
			$duplicateDatas = $this->find('all', array(
				'conditions' => array(
					'NOT' => array('Slug.id' => $id),
					'Slug.name' => $data['Slug']['name'],
					'Slug.blog_content_id' => $data['Slug']['blog_content_id']
				),
				'recursive' => -1
			));
		}
		return $duplicateDatas;

	}
/**
 * 重複スラッグデータをもとに、登録用のスラッグを作成して返す
 * 
 * @param array $duplicateDatas
 * @param array $data
 * @return string
 * @access public
 */
	function makeSlugName($duplicateDatas = array(), $data = array()) {

		if($duplicateDatas) {
			$countData = count($duplicateDatas);
			$countData = $countData + 1;
			$data['Slug']['name'] = $data['Slug']['name'] . '-' . $countData;
		}
		return $data['Slug']['name'];

	}

}
