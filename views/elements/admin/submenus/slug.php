<?php
/**
 * [ADMIN] slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug
 * @license			MIT
 */
?>
<tr>
	<th>スラッグ管理メニュー</th>
	<td>
		<ul>
			<li><?php $bcBaser->link('スラッグ一覧', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slugs', 'action'=>'index')) ?></li>
			<li><?php $bcBaser->link('スラッグ一括設定', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slugs', 'action'=>'batch')) ?></li>
		</ul>
	</td>
</tr>
<tr>
	<th>スラッグ設定管理メニュー</th>
	<td>
		<ul><?php if($judgeSlugConfigUse): ?>
			<li><?php $bcBaser->link('スラッグ設定一覧', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slug_configs', 'action'=>'index')) ?></li>
			<?php endif ?>
			<li><?php $bcBaser->link('スラッグ設定データ作成', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slug_configs', 'action'=>'first')) ?></li>
		</ul>
	</td>
</tr>