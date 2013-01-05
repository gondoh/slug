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
				Router::connect("/{$pluginContentName}/*", array('plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
			} else {
				Router::connect("/{$pluginContentName}/index", array('plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'index'));
			}
		} else {
			// SP、FP用ルーティング
			//Router::connect("/{$agentAlias}/{$pluginContentName}/:action/*", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName));
		}
		// ここでルーティングの優先順位を上げている
		Router::promote();
	}

}
