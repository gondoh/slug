<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @version			1.0.0
 * @license			MIT
 */
?>
<tr>
	<th>スラッグ管理メニュー</th>
	<td>
		<ul>
			<li><?php $bcBaser->link('スラッグ一覧', array('plugin' => 'slug', 'admin' => true, 'controller' => 'slugs', 'action'=>'index')) ?></li>
		</ul>
	</td>
</tr>
