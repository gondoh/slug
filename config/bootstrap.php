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
		} else {
			// SP、FP用ルーティング
			//Router::connect("/{$agentAlias}/{$pluginContentName}/*", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName, 'action' => 'archives'));
			//Router::connect("/{$agentAlias}/{$pluginContentName}/:action/*", array('prefix'	=> $agentPrefix, 'plugin' => $pluginName, 'controller'=> $pluginName));
		}
		// ここでルーティングの優先順位を上げている
		Router::promote();
	}

}
