<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012 - 2013, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.config
 * @license			MIT
 */
loadPluginConfig('slug.slug');

// TODO アクション名「archives」を省略する処理
// HINT /baser/config/routes.php
$PluginContent = ClassRegistry::init('PluginContent');
if($PluginContent) {

	$agent = Configure::read('BcRequest.agent');
	$agentAlias = Configure::read('BcRequest.agentAlias');
	$parameter = getUrlParamFromEnv();
	$pluginContent = $PluginContent->currentPluginContent($parameter);
	if($pluginContent) {

		$pluginContentName = $pluginContent['PluginContent']['name'];
		$pluginName = $pluginContent['PluginContent']['plugin'];
		if($pluginName == 'blog') {

			$pluginData = $PluginContent->find('first', array(
				'conditions' => array(
					'PluginContent.name' => $pluginContentName,
					'PluginContent.plugin' => $pluginName
				)
			));

			$SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
			$slugConfigData = $SlugConfigModel->findByBlogContentId($pluginData['PluginContent']['content_id']);
			// TODO slug 内のヘルパーやフックなど、以降の処理で使えるようにオブジェクトにIDを入れている
			//   ・こうする事で以下で呼び出せる
			//		$SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
			//		$this->slugConfigs = $SlugConfigModel->read();
			$SlugConfigModel->id = $slugConfigData['SlugConfig']['id'];
			if($slugConfigData['SlugConfig']['ignore_archives']) {
				if(!$agent) {
					$parseUrl = Router::parse($url);
					if($parseUrl['action'] != 'index') {
						Router::connect("/{$pluginContentName}/*", array('plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
					} else {
						Router::connect("/{$pluginContentName}/index", array('plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'index'));
					}
					//Router::connect("/{$pluginContentName}/:action/*", array('plugin' => $pluginName, 'controller'=> $pluginName));
				} else {
					Router::connect("/{$agentAlias}/{$pluginContentName}/*", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
				}
				// ここでルーティングの優先順位を上げている
				Router::promote();
			}

		}

	}
}
