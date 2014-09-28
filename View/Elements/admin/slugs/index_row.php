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
	<td class="row-tools">
	<?php // ブログ記事編集画面へ移動 ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_check.png', array('width' => 24, 'height' => 24, 'alt' => 'ブログ記事編集', 'class' => 'btn')),
			array('admin' => true, 'plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'edit', $data['BlogPost']['blog_content_id'], $data['BlogPost']['id']), array('title' => 'ブログ記事編集')) ?>

	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
			array('action' => 'edit', $data['Slug']['id']), array('title' => '編集')) ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', 
			array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')),
			array('action' => 'delete', $data['Slug']['id'], $this->BcForm->value('Slug.id')), 
			array('title' => '削除', 'class' => 'btn-delete'),
			sprintf('ID：%s のデータを削除して良いですか？', $data['Slug']['id']), false) ?>
	</td>
	<td style="width: 45px;"><?php echo $data['Slug']['id']; ?></td>
	<td>
		<?php echo $blogContentDatas[$data['Slug']['blog_content_id']] ?>
	</td>
	<td style="width: 45px;">
		<?php echo $data['Slug']['blog_post_no']; ?>
	</td>
	<td>
		<?php echo $this->BcBaser->link($data['Slug']['name'], array('action' => 'edit', $data['Slug']['id']), array('title' => '編集')) ?>
	</td>
	<td style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['Slug']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['Slug']['modified']) ?>
	</td>
</tr>
