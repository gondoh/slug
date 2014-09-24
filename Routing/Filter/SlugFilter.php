<?php
/**
 * [DispatcherFilter] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
App::uses('DispatcherFilter', 'Routing');
class SlugFilter extends DispatcherFilter {
/**
 * beforeDispatch
 * 
 * @param CakeEvent $event
 * @return type
 */
	public function beforeDispatch(CakeEvent $event) {
		// $Dispatch = $event->subject();
		// $response = $event->data['response'];
		$request = $event->data['request'];
		
		// パラメータ取得（文字列のURL）
		$parameter = getPureUrl($request);
		$agent = Configure::read('BcRequest.agent');
		$agentAlias = Configure::read('BcRequest.agentAlias');
		$agentPrefix = Configure::read('BcRequest.agentPrefix');
		
		// DBに接続できない場合、CakePHPのエラーメッセージが表示されてしまうので try を利用
		try {
			$PluginContent = ClassRegistry::init('PluginContent');
		} catch (Exception $ex) {
			$PluginContent = null;
		}
		
		if ($PluginContent) {
			// 現在のリクエストからプラグインへのアクセスかどうか判定し、プラグイン側へのアクセスであれば、プラグイン情報を返す
			$pluginContent = $PluginContent->currentPluginContent($parameter);
			if ($pluginContent) {
				// プラグイン名を設定する
				$pluginContentName = $pluginContent['PluginContent']['name'];
				$pluginName = $pluginContent['PluginContent']['plugin'];
			}
		}
		
		// archives 除外指定の対応
		// ブログへのアクセス時のみ実行する
		if(!empty($pluginName) && $pluginName == 'blog') {
			$pluginData = $PluginContent->find('first', array(
				'conditions' => array(
					'PluginContent.name' => $pluginContentName,
					'PluginContent.plugin' => $pluginName
				)
			));

			$SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
			$SlugConfigModel->setIgnoreArchives($pluginData['PluginContent']['content_id']);
			if($SlugConfigModel->ignore_archives) {
				$parseUrl = Router::parse('/' . Configure::read('BcRequest.pureUrl'));
				if(!$agent) {
					// PC用ルーティング
					// indexアクション以外の場合、本来ならparams['pass']に入るものが、request内のactionに入っているため置き換えている
					if($parseUrl['action'] != 'index') {
						$event->data['request']->params['action'] = 'archives';
						$event->data['request']->params['pass'][] = $parseUrl['action'];
					}
					Router::connect("/{$pluginContentName}/:action/*",
								array('plugin' => $pluginName, 'controller' => $pluginName));
					Router::connect("/{$pluginContentName}",
								array('plugin' => $pluginName, 'controller' => $pluginName, 'action' => 'index'));
				} else {
					// SP、FP用ルーティング
					// indexアクション以外の場合、本来ならparams['pass']に入るものが、request内のactionに入っているため置き換えている
					if($parseUrl['action'] != 'index') {
						$event->data['request']->params['action'] = 'archives';
						$event->data['request']->params['pass'][] = $parseUrl['action'];
					}
					Router::connect("/{$agentAlias}/{$pluginContentName}/:action/*",
								array('prefix' => $agentPrefix, 'plugin' => $pluginName, 'controller' => $pluginName));
					Router::connect("/{$agentAlias}/{$pluginContentName}",
								array('prefix' => $agentPrefix, 'plugin' => $pluginName, 'controller' => $pluginName, 'action' => 'index'));
				}
				// ここでルーティングの優先順位を上げている（通常のルーティング処理の前に処理されるため、
				// 優先順位の入替え指定は不要になった From baserCMS2系
				// Router::promote();
			}
		}
		
	}
	
}
