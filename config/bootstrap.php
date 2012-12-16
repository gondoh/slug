<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.config
 * @license			MIT
 */
loadPluginConfig('slug.slug');

// TODO アクション名「archives」を省略する処理
// HINT /baser/config/routes.php
$SlugConfigModel = ClassRegistry::init('Slug.SlugConfig');
$SlugConfigs = $SlugConfigModel->findExpanded();
if($SlugConfigs['ignore_archives'] === '1') {

	$PluginContent = ClassRegistry::init('PluginContent');
	if($PluginContent) {
		$agent = Configure::read('BcRequest.agent');
		$agentAlias = Configure::read('BcRequest.agentAlias');
		$parameter = getUrlParamFromEnv();
		$pluginContent = $PluginContent->currentPluginContent($parameter);
		if($pluginContent) {
			$pluginContentName = $pluginContent['PluginContent']['name'];
			$pluginName = $pluginContent['PluginContent']['plugin'];
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
