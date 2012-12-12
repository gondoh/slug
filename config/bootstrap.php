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
		if($pluginContent) {
			$pluginContentName = $pluginContent['PluginContent']['name'];
			$pluginName = $pluginContent['PluginContent']['plugin'];
			if(!$agent) {
				Router::connect("/{$pluginContentName}/*", array('plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
				//Router::connect("/{$pluginContentName}/:action/*", array('plugin' => $pluginName, 'controller'=> $pluginName));
			} else {
				Router::connect("/{$agentAlias}/{$pluginContentName}/*", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
			}
			// ここでルーティングの優先順位を上げている
			Router::promote();
		}
	}

}
