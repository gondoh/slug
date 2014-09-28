<?php
/**
 * [ADMIN] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
?>
<tr>
	<th>スラッグ管理メニュー</th>
	<td>
		<ul>
			<li><?php $this->BcBaser->link('スラッグ一覧', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slugs', 'action'=>'index')) ?></li>
			<?php if(!$judgeSlugUse): ?>
			<li><?php $this->BcBaser->link('スラッグ一括設定', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slugs', 'action'=>'batch')) ?></li>
			<?php endif ?>
		</ul>
	</td>
</tr>
<tr>
	<th>スラッグ設定管理メニュー</th>
	<td>
		<ul>
			<li><?php $this->BcBaser->link('スラッグ設定一覧', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slug_configs', 'action'=>'index')) ?></li>
			<?php if(!$judgeSlugConfigUse): ?>
			<li><?php $this->BcBaser->link('スラッグ設定データ作成', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slug_configs', 'action'=>'first')) ?></li>
			<?php endif ?>
		</ul>
	</td>
</tr>