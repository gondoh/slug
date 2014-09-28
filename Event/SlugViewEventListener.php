<?php
/**
 * [ViewEventListener] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
class SlugViewEventListener extends BcViewEventListener {
/**
 * 登録イベント
 * 
 * @var array
 *
 */
	public $events = array(
		'Blog.Blog.afterElement'
	);
	
/**
 * afterElement
 * ignore_archivesが有効の場合に、paginationのリンクを書換える
 * 
 * @param CakeEvent $event
 * @return string 
 */
	public function blogBlogAfterElement(CakeEvent $event) {
		$View = $event->subject();
		// プレビュー時に Undefined index が出るため判定
		if (preg_match('/^paginations\/.*/', $event->data['name'])) {
			if ($View->Slug->SlugConfigModel->ignore_archives) {
				if ($View->request->params['action'] == 'archives') {
					$pattern = '/href\=\"(.+?)\/archives\/(.+?)\"/';
					$event->data['out'] = preg_replace($pattern, 'href="$1' . '/$2' . '"', $event->data['out']);
				}
			}
		}
		return $event->data['out'];
	}
	
/**
 * beforeElement：未使用：コード内コメント参照
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function beforeElement(CakeEvent $event) {
		$View = $event->subject();
		if (empty($View->request->params['prefix']) || ($View->request->params['prefix'] != 'admin')) {
			// if($name == 'paginations/simple' || $name == 'paginations/default') {
			if (preg_match('/^paginations\/.*/', $event->data['name'])) {
				if ($View->request->params['action'] == 'archives') {
					// ここで action を省略しても、最終的に Router:LINE:800 で index が付けられてしまう
					// unset($this->View->passedArgs['action']);
					// $this->View->passedArgs['action'] = '';
				}
			}
		}
		return $event->data;
	}
	
}
