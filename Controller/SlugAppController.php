<?php
/**
 * Slug 基底コントローラ
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
class SlugAppController extends BcPluginAppController {
/**
 * ヘルパー
 *
 * @var array
 */
	public $helpers = array('Blog.Blog');
	
/**
 * コンポーネント
 * 
 * @var     array
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');
	
/**
 * サブメニューエレメント
 *
 * @var array
 */
	public $subMenuElements = array('slug');
	
/**
 * ぱんくずナビ
 *
 * @var string
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index'))
	);
	
/**
 * ブログコンテンツデータ
 * 
 * @var array
 */
	public $blogContentDatas = array();
	
/**
 * メッセージ用機能名
 * 
 * @var string
 */
	public $controlName = 'スラッグ';
	
/**
 * beforeFilter
 *
 * @return	void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$judgeSlugConfigUse = false;
		$judgeSlugUse = false;
		$message = '';
		
		// ブログ情報を取得
		$BlogContentModel = ClassRegistry::init('Blog.BlogContent');
		$this->blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
		// スラッグ設定データを取得
		$datas = $this->SlugConfig->find('all', array('recursive' => -1));
		
		// スラッグ設定データ数よりブログ設定データの方が多ければ、メニューを表示する
		if (count($this->blogContentDatas) > count($datas)) {
			$message .= '「スラッグ設定データ作成」にてスラッグ設定用のデータを作成して下さい。';
		} else {
			$judgeSlugConfigUse = true;
		}
		
		// ブログ記事データを取得
		$BlogPostModel = ClassRegistry::init('Blog.BlogPost');
		$blogPostDatas = $BlogPostModel->find('list', array('recursive' => -1));
		// スラッグデータを取得
		$dataList = $this->Slug->find('all', array('recursive' => -1));
		// スラッグデータ数よりブログ記事データの方が多ければ、メニューを表示する
		if (count($blogPostDatas) > count($dataList)) {
			$message .= '「スラッグ一括設定」にてスラッグ用のデータを作成して下さい。';
		} else {
			$judgeSlugUse = true;
		}
		
		$this->set('judgeSlugConfigUse', $judgeSlugConfigUse);
		$this->set('judgeSlugUse', $judgeSlugUse);
		
		if ($message) {
			$this->setMessage($message, true);
		}
		
		App::import('Helper', 'Slug.Slug');
		$this->SlugHelper = new SlugHelper(new View());
	}
	
/**
 * [ADMIN] 一覧表示
 * 
 * @return void
 */
	public function admin_index() {
		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions($this->modelClass, array('default' => $default));
		
		$conditions = $this->_createAdminIndexConditions($this->request->data);
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num']
		);
		$datas = $this->paginate();
		if ($datas) {
			$this->set('datas',$datas);
		}
		
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));			
		}
		if (empty($this->request->data)) {
			$this->{$this->modelClass}->id = $id;
			$this->request->data = $this->{$this->modelClass}->read();
		} else {
			$this->{$this->modelClass}->set($this->request->data);
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$message = $this->controlName . ' NO.' . $id . ' を更新しました。';
				$this->setMessage($message, false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		
		$this->render('form');
	}
	
/**
 * [ADMIN] 削除
 *
 * @param int $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->{$this->modelClass}->delete($id)) {
			$message = $this->controlName . ' NO.' . $id . ' を削除しました。';
			$this->setMessage($message, false, true);
			$this->redirect(array('action' => 'index'));
		} else {
			$message = 'データベース処理中にエラーが発生しました。';
			$this->setMessage($message, true);
		}
		$this->redirect(array('action' => 'index'));
	}
	
}
