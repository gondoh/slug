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
		
		// パラメータ取得
		$parameter = getPureUrl($request);
		$agent = Configure::read('BcRequest.agent');
		
		// DBに接続できない場合、CakePHPのエラーメッセージが表示されてしまう為、 try を利用
		try {
			$PluginContent = ClassRegistry::init('PluginContent');
		} catch (Exception $ex) {
			$PluginContent = null;
		}
		
		if ($PluginContent) {
			$pluginContent = $PluginContent->currentPluginContent($parameter);
			//$pluginContent = $PluginContent->currentPluginContent($request);
			if ($pluginContent) {
				$pluginContentName = $pluginContent['PluginContent']['name'];
				$pluginName = $pluginContent['PluginContent']['plugin'];
//				if (!$agent) {
//					Router::connect("/{$pluginContentName}/:action/*", array('plugin' => $pluginName, 'controller' => $pluginName));
//					Router::connect("/{$pluginContentName}", array('plugin' => $pluginName, 'controller' => $pluginName, 'action' => 'index'));
//				} else {
//					Router::connect("/{$agentAlias}/{$pluginContentName}/:action/*", array('prefix' => $agentPrefix, 'plugin' => $pluginName, 'controller' => $pluginName));
//					Router::connect("/{$agentAlias}/{$pluginContentName}", array('prefix' => $agentPrefix, 'plugin' => $pluginName, 'controller' => $pluginName, 'action' => 'index'));
//				}
			}
		}
		
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
					if($parseUrl['action'] != 'index') {
						$event->data['request']->params['action'] = 'archives';
						$event->data['request']->params['pass'][] = $parseUrl['action'];
						Router::connect("/{$pluginContentName}/*", array('plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
					} else {
						Router::connect("/{$pluginContentName}/index", array('plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'index'));
					}
				} else {
					// SP、FP用ルーティング
					if($parseUrl['action'] != 'index') {
						Router::connect("/{$agentAlias}/{$pluginContentName}/*", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
					} else {
						Router::connect("/{$agentAlias}/{$pluginContentName}/index", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'index'));
					}
					//Router::connect("/{$agentAlias}/{$pluginContentName}/:action/*", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName));
				}
				// ここでルーティングの優先順位を上げている
				Router::promote();
			}
		}
		
	}
	
}
