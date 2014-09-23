<?php
/**
 * [ModelEventListener] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
class SlugModelEventListener extends BcModelEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'Blog.BlogPost.beforeValidate',
		'Blog.BlogPost.afterSave',
		'Blog.BlogPost.afterDelete',
		'Blog.BlogPost.beforeFind',
		'Blog.BlogContent.afterSave',
		'Blog.BlogContent.afterDelete',
		'Blog.BlogContent.beforeFind'
	);
	
/**
 * ブログ記事多重保存の判定
 * 
 * @var boolean
 */
	public $throwBlogPost = false;
	
/**
 * Construct
 * 
 */
	function __construct() {
		parent::__construct();
		if (ClassRegistry::isKeySet('Slug.Slug')) {
			$this->Slug = ClassRegistry::getObject('Slug.Slug');
		} else {
			$this->Slug = ClassRegistry::init('Slug.Slug');
		}
		if (ClassRegistry::isKeySet('Slug.SlugConfig')) {
			$this->SlugConfig = ClassRegistry::getObject('Slug.SlugConfig');
		} else {
			$this->SlugConfig = ClassRegistry::init('Slug.SlugConfig');
		}
	}
	
/**
 * blogBlogPostBeforeFind
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostBeforeFind(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ記事取得の際にスラッグ情報も併せて取得する
		$association = array(
			'Slug' => array(
				'className' => 'Slug.Slug',
				'foreignKey' => 'blog_post_id'
			)
		);
		$Model->bindModel(array('hasOne' => $association));
		
		// 最近の投稿、ブログ記事前後移動を find する際に実行
		// TODO get_recent_entries に呼ばれる find 判定に、より良い方法があったら改修する
		if(count($event->data['query']['fields']) === 2) {
			if(($event->data['query']['fields']['0'] == 'no') && ($event->data['query']['fields']['1'] == 'name')) {
				$event->data['query']['fields'][] = 'id';
				$event->data['query']['fields'][] = 'posts_date';
				$event->data['query']['fields'][] = 'blog_category_id';
				$event->data['query']['recursive'] = 2;
			}
		}
		return $event->data['query'];
	}
	
/**
 * blogBlogContentBeforeFind
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentBeforeFind(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ設定取得の際にスラッグ設定情報も併せて取得する
		$association = array(
			'SlugConfig' => array(
				'className' => 'Slug.SlugConfig',
				'foreignKey' => 'blog_content_id'
			)
		);
		$Model->bindModel(array('hasOne' => $association));
	}
	
/**
 * blogBlogPostBeforeValidate
 * 
 * @param CakeEvent $event
 * @return boolean
 */
	public function blogBlogPostBeforeValidate(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ記事保存の手前で Slug モデルのデータに対して validation を行う
		// TODO saveAll() ではbeforeValidateが効かない？
		$this->Slug->set($Model->data);
		return $this->Slug->validates();
	}
	
/**
 * blogBlogPostAfterSave
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostAfterSave(CakeEvent $event) {
		$Model = $event->subject();
		$created = $event->data[0];
		if ($created) {
			$contentId = $Model->getLastInsertId();
		} else {
			$contentId = $Model->data[$Model->alias]['id'];
		}
		$saveData = $this->generateSaveData($Model, $contentId);
		// 2周目では保存処理に渡らないようにしている
		if (!$this->throwBlogPost) {
			if (isset($saveData['Slug']['id'])) {
				// ブログ記事編集保存時に設定情報を保存する
				$this->Slug->set($saveData);
			} else {
				// ブログ記事追加時に設定情報を保存する
				$this->Slug->create($saveData);
			}
			if (!$this->Slug->save()) {
				$this->log(sprintf('ID：%s のスラッグの保存に失敗しました。', $Model->data['Slug']['id']));
			}
		}
		
		// ブログ記事コピー保存時、アイキャッチが入っていると処理が2重に行われるため、1周目で処理通過を判定し、
		// 2周目では保存処理に渡らないようにしている
		$this->throwBlogPost = true;
	}
	
/**
 * blogBlogPostAfterDelete
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ記事削除時、そのブログ記事が持つスラッグを削除する
		$data = $this->Slug->find('first', array(
			'conditions' => array('Slug.blog_post_id' => $Model->id),
			'recursive' => -1
		));
		if ($data) {
			if (!$this->Slug->delete($data['Slug']['id'])) {
				$this->log('ID:' . $data['Slug']['id'] . 'のSlugの削除に失敗しました。');
			}
		}
	}
	
/**
 * blogBlogContentAfterSave
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentAfterSave(CakeEvent $event) {
		$Model = $event->subject();
		$created = $event->data[0];
		if ($created) {
			$contentId = $Model->getLastInsertId();
		} else {
			$contentId = $Model->data[$Model->alias]['id'];
		}
		$saveData = $this->generateContentSaveData($Model, $contentId);
		if (isset($saveData['SlugConfig']['id'])) {
			// ブログ設定編集保存時に設定情報を保存する
			$this->SlugConfig->set($saveData);
		} else {
			// ブログ追加時に設定情報を保存する
			$this->SlugConfig->create($saveData);
		}
		if (!$this->SlugConfig->save()) {
			$this->log(sprintf('ID：%s のスラッグ設定の保存に失敗しました。', $Model->data['SlugConfig']['id']));
		}
		
	}
	
/**
 * blogBlogContentAfterDelete
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ削除時、そのブログが持つスラッグ設定を削除する
		$data = $this->SlugConfig->find('first', array(
			'conditions' => array('SlugConfig.blog_content_id' => $Model->id),
			'recursive' => -1
		));
		if ($data) {
			if (!$this->SlugConfig->delete($data['SlugConfig']['id'])) {
				$this->log('ID:' . $data['SlugConfig']['id'] . 'のスラッグ設定の削除に失敗しました。');
			}
		}
	}
	
/**
 * 保存するデータの生成
 * 
 * @param Object $Model
 * @param int $contentId
 * @return array
 */
	public function generateSaveData($Model, $contentId = '') {
		$params = Router::getParams();
		$data = array();
		$modelId = $oldModelId = null;
		if ($Model->alias == 'BlogPost') {
			$modelId = $contentId;
			if(!empty($params['pass'][1])) {
				$oldModelId = $params['pass'][1];
			}
		}
		
		if ($contentId) {
			$data = $this->Slug->find('first', array(
				'conditions' => array('Slug.blog_post_id' => $contentId),
				'recursive' => -1
			));
		}
		
		switch ($params['action']) {
			case 'admin_add':
				// 追加時
				$data['Slug'] = $Model->data['Slug'];
				$data['Slug']['blog_post_id'] = $contentId;
				$data['Slug']['blog_content_id'] = $Model->BlogContent->id;
				break;
				
			case 'admin_edit':
				// 編集時
				$data['Slug'] = array_merge($data['Slug'], $Model->data['Slug']);
				break;
				
			case 'admin_ajax_copy':
				// Ajaxコピー処理時に実行
				// ブログコピー保存時にエラーがなければ保存処理を実行
				if (empty($Model->validationErrors)) {
					$_data = array();
					if ($oldModelId) {
						$_data = $this->Slug->find('first', array(
							'conditions' => array('Slug.blog_post_id' => $oldModelId),
							'recursive' => -1
						));
					}
					// もしオプショナルリンク設定の初期データ作成を行ってない事を考慮して判定している
					if ($_data) {
						// コピー元データがある時
						$data['Slug'] = $_data['Slug'];
						$data['Slug']['blog_post_id'] = $contentId;
						unset($data['Slug']['id']);
					} else {
						// コピー元データがない時
						$data['Slug']['blog_post_id'] = $modelId;
						$data['Slug']['blog_content_id'] = $params['pass'][0];
					}
					
					// TODO
					// 重複スラッグを探索して、重複していれば重複個数＋１をつける
					$duplicateDatas = $this->SlugModel->searchDuplicateSlug($slugDate);
					if ($duplicateDatas) {
						$slugDate['Slug']['name'] = $this->SlugModel->makeSlugName($duplicateDatas, $slugDate);
					}
					
				}
				break;
				
			default:
				break;
		}
		
		return $data;
	}
	
/**
 * 保存するデータの生成
 * 
 * @param Object $Model
 * @param int $contentId
 * @return array
 */
	public function generateContentSaveData($Model, $contentId = '') {
		$params = Router::getParams();
		$data = array();
		if ($Model->alias == 'BlogContent') {
			$modelId = $contentId;
			if (isset($params['pass'][0])) {
				$oldModelId = $params['pass'][0];
			}
		}
		
		if ($contentId) {
			$data = $this->SlugConfig->find('first', array(
				'conditions' => array('SlugConfig.blog_content_id' => $contentId)
			));
		}
		
		switch ($params['action']) {
			case 'admin_add':
				// 追加時
				if (!empty($Model->data['SlugConfig'])) {
					$data['SlugConfig'] = $Model->data['SlugConfig'];
				}
				$data['SlugConfig']['blog_content_id'] = $contentId;
				break;
				
			case 'admin_edit':
				// 編集時
				$data['SlugConfig'] = array_merge($data['SlugConfig'], $Model->data['SlugConfig']);
				break;
				
			case 'admin_ajax_copy':
				// Ajaxコピー処理時に実行
				// ブログコピー保存時にエラーがなければ保存処理を実行
				if (empty($Model->validationErrors)) {
					$_data = $this->SlugConfig->find('first', array(
						'conditions' => array('SlugConfig.blog_content_id' => $oldModelId),
						'recursive' => -1
					));
					// もしスラッグ設定の初期データ作成を行ってない事を考慮して判定している
					if ($_data) {
						// コピー元データがある時
						$data = Hash::merge($data, $_data);
						$data['SlugConfig']['blog_content_id'] = $contentId;
						unset($data['SlugConfig']['id']);
					} else {
						// コピー元データがない時
						$data['SlugConfig']['blog_content_id'] = $modelId;
						$data['SlugConfig']['status'] = true;
					}
				}
				break;
				
			default:
				break;
		}
		
		return $data;
	}
	
/**
 * スラッグ情報を保存する
 * 
 * @param Controller $controller 
 * @return void
 */
	public function slugSaving($Controller) {
		$Controller->data['Slug']['blog_content_id'] = $Controller->data['BlogPost']['blog_content_id'];
		$Controller->data['Slug']['blog_post_no'] = $Controller->data['BlogPost']['no'];
		
		// スラッグが未入力の場合は、ブログ記事タイトルを設定する
		if (!$Controller->data['Slug']['name']) {
			$Controller->data['Slug']['name'] = $Controller->data['BlogPost']['name'];
		}
		
		if ($Controller->action == 'admin_add') {
			$Controller->data['Slug']['blog_post_id'] = $Controller->BlogPost->getLastInsertId();
			// 重複スラッグを探索して、重複していれば重複個数＋１をつける
			$duplicateDatas = $this->SlugModel->searchDuplicateSlug($Controller->data);
			if ($duplicateDatas) {
				$Controller->data['Slug']['name'] = $this->SlugModel->makeSlugName($duplicateDatas, $Controller->data);
			}
		} else {
			$Controller->data['Slug']['blog_post_id'] = $Controller->BlogPost->id;
			// 重複スラッグを探索して、重複していれば重複個数＋１をつける
			$duplicateDatas = $this->SlugModel->searchDuplicateSlug($Controller->data, $Controller->data['Slug']['id']);
			if ($duplicateDatas) {
				$Controller->data['Slug']['name'] = $this->SlugModel->makeSlugName($duplicateDatas, $Controller->data);
			}
		}
		
		if (empty($Controller->data['Slug']['id'])) {
			$this->SlugModel->create($Controller->data['Slug']);
		} else {
			$this->SlugModel->set($Controller->data['Slug']);
		}
		
		if (!$this->SlugModel->save($Controller->data['Slug'], false)) {
			$this->log('ブログ記事ID：' . $Controller->data['Slug']['blog_post_id'] . 'のスラッグ情報保存に失敗しました。');
		}
		
	}
	
}
