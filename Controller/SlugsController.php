<?php
/**
 * [Controller] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
App::uses('SlugApp', 'Slug.Controller');
class SlugsController extends SlugAppController {
/**
 * コントローラー名
 * 
 * @var string
 */
	public $name = 'Slugs';
	
/**
 * モデル
 * 
 * @var array
 */
	public $uses = array('Slug.Slug', 'Slug.SlugConfig');
	
/**
 * ぱんくずナビ
 *
 * @var array
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'スラッグ管理', 'url' => array('plugin' => 'slug', 'controller' => 'slugs', 'action' => 'index'))
	);
	
/**
 * beforeFilter
 *
 * @return	void
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
/**
 * [ADMIN] スラッグ一覧
 * 
 * @return void
 */
	public function admin_index() {
		$this->pageTitle = 'スラッグ一覧';
		$this->search = 'slugs_index';
		$this->help = 'slugs_index';
		
		parent::admin_index();
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->pageTitle = 'スラッグ編集';
		
		parent::admin_edit($id);
	}
	
/**
 * [ADMIN] 削除
 *
 * @param int $id
 * @return void
 */
	public function admin_delete($id = null) {
		parent::admin_delete($id);
	}
	
/**
 * ブログ記事のスラッグを、ブログ別に一括で登録する
 *   ・スラッグの登録がないブログ記事に登録する
 *   ・登録するスラッグはブログ記事タイトルを元に行う
 *   ・登録するスラッグが重複する場合、スラッグには「-重複個数＋１」をつける
 * 
 * @return void
 */
	public function admin_batch() {
		if ($this->request->data) {
			// 既にスラッグ登録のあるブログ記事は除外する
			// 登録済のスラッグを取得する
			$slugs = $this->Slug->find('list', array(
				'conditions' => array('Slug.blog_content_id' => $this->request->data['Slug']['blog_content_id']),
				'fields' => 'blog_post_id',
				'recursive' => -1));
			// スラッグの登録がないブログ記事を取得する
			$BlogPostModel = ClassRegistry::init('Blog.BlogPost');
			if ($slugs) {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'NOT' => array('BlogPost.id' => $slugs),
						'BlogPost.blog_content_id' => $this->request->data['Slug']['blog_content_id']),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			} else {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'BlogPost.blog_content_id' => $this->request->data['Slug']['blog_content_id']),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			}
			
			// スラッグを保存した数を初期化
			$count = 0;
			if ($datas) {
				foreach ($datas as $data) {
					$this->request->data['Slug']['blog_post_id'] = $data['BlogPost']['id'];
					$this->request->data['Slug']['blog_post_no'] = $data['BlogPost']['no'];
					$this->request->data['Slug']['name'] = $data['BlogPost']['name'];
					$this->Slug->create($this->request->data);
					if ($this->Slug->save($this->request->data, false)) {
						$count++;
					} else {
						$this->log('ID:' . $data['BlogPost']['id'] . 'のブログ記事のスラッグ登録に失敗');
					}

					// 重複スラッグを探索して、重複していれば重複個数＋１をつける
					$duplicateDatas = $this->Slug->searchDuplicateSlug($this->request->data, $this->Slug->getLastInsertId());
					if ($duplicateDatas) {
						$saveData = $this->Slug->read(null, $this->Slug->getLastInsertId());
						$saveData['Slug']['name'] = $this->Slug->makeSlugName($duplicateDatas, $saveData);
						$this->Slug->set($saveData);
						$this->Slug->save($saveData, false);
					}
				}
			}
			
			if ($count) {
				$message = sprintf('%s 件のスラッグを登録しました。', $count);
				$this->setMessage($message, false, true);
			} else {
				$this->setMessage('登録されたスラッグはありません。', true);
			}
		}
		unset($slugs);
		unset($datas);
		unset($data);

		$registerd = array();
		foreach ($this->blogContentDatas as $key => $blog) {
			// $key : blog_content_id
			// 登録済のスラッグを取得する
			$slugs = $this->Slug->find('list', array(
				'conditions' => array('Slug.blog_content_id' => $key),
				'fields' => 'blog_post_id',
				'recursive' => -1));
			// スラッグの登録がないブログ記事を取得する
			$BlogPostModel = ClassRegistry::init('Blog.BlogPost');
			if ($slugs) {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'NOT' => array('BlogPost.id' => $slugs),
						'BlogPost.blog_content_id' => $key),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			} else {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'BlogPost.blog_content_id' => $key),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			}

			$registerd[] = array(
				'name' => $blog,
				'slug' => count($datas)
			);
		}
		
		$this->set('registerd', $registerd);
		$this->set('blogContentDatas', $this->blogContentDatas);
		
		$this->pageTitle = 'スラッグ一括設定';
	}
	
/**
 * [ADMIN][AJAX] 重複スラッグをチェックする
 *   ・blog_content_id が異なるものは重複とみなさない
 * 
 * @return void
 */
	public function admin_ajax_check_name() {
		Configure::write('debug', 0);
		$this->layout = null;
		$result = true;
		
		if ($this->request->data) {
			$datas = $this->Slug->find('all', array(
				'conditions' => array(
					'Slug.name' => $this->request->data['Slug']['name'],
					'Slug.blog_content_id' => $this->request->data['Slug']['blog_content_id']
				),
				'fields' => array('id', 'name'),
				'recursive' => -1
			));
			if ($datas) {
				$result = false;
				// 編集対応のため、重複スラッグが存在する場合でも、同じ id のものはOKとみなす
				foreach ($datas as $data) {
					if ($this->request->data['Slug']['id'] == $data['Slug']['id']) {
						$result = true;
						break;
					}
				}
			}
		}
		
		$this->set('result', $result);
		$this->render('ajax_result');
	}
	
/**
 * [ADMIN] 無効状態にする
 * 
 * @param int $id
 * @return void
 */
	public function admin_unpublish($id) {
		if (!$id) {
			$this->setMessage('この処理は無効です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->_changeStatus($id, false)) {
			$this->setMessage('「無効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->setMessage('処理に失敗しました。', true);
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * [ADMIN] 有効状態にする
 * 
 * @param int $id
 * @return void
 */
	public function admin_publish($id) {
		if (!$id) {
			$this->setMessage('この処理は無効です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->_changeStatus($id, true)) {
			$this->setMessage('「有効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->setMessage('処理に失敗しました。', true);
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * ステータスを変更する
 * 
 * @param int $id
 * @param boolean $status
 * @return boolean 
 */
	protected function _changeStatus($id, $status) {
		$data = $this->Slug->find('first', array('conditions' => array('Slug.id' => $id), 'recursive' => -1));
		$data['Slug']['status'] = $status;
		if ($status) {
			$data['Slug']['status'] = true;
		} else {
			$data['Slug']['status'] = false;
		}
		$this->Slug->set($data);
		if ($this->Slug->save()) {
			return true;
		} else {
			return false;
		}
	}
	
/**
 * 一覧用の検索条件を生成する
 *
 * @param array $data
 * @return array $conditions
 */
	protected function _createAdminIndexConditions($data) {
		$conditions = array();
		$name = '';
		$blogContentId = '';
		
		if (isset($data['Slug']['name'])) {
			$name = $data['Slug']['name'];
		}
		if (isset($data['Slug']['blog_content_id'])) {
			$blogContentId = $data['Slug']['blog_content_id'];
		}
		
		unset($data['_Token']);
		unset($data['Slug']['name']);
		unset($data['Slug']['blog_content_id']);
		
		// 条件指定のないフィールドを解除
		foreach ($data['Slug'] as $key => $value) {
			if ($value === '') {
				unset($data['Slug'][$key]);
			}
		}
		
		if ($data['Slug']) {
			$conditions = $this->postConditions($data);
		}
		
		if ($name) {
			$conditions[] = array(
				'Slug.name LIKE' => '%'.$name.'%'
			);
		}
		if ($blogContentId) {
			$conditions['and'] = array(
				'Slug.blog_content_id' => $blogContentId
			);
		}
		
		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}
	
}
