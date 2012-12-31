<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @license			MIT
 */
?>
<div id="DataList">
	<?php $bcBaser->element('pagination') ?>

	<table cellpadding="0" cellspacing="0" class="list-table sort-table" id="ListTable">
		<thead>
			<tr><th style="width: 90px;">操作</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' NO',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' NO'),
						'id', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th>
					ブログ名
				</th>
				<th>
					<?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 記事NO',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 記事NO'),
						'blog_post_no', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' スラッグ',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' スラッグ'),
						'name', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 登録日',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 登録日'),
						'created', array('escape' => false, 'class' => 'btn-direction')) ?>
					<br />
					<?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 更新日',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 更新日'),
						'modified', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
			</tr>
		</thead>
	<tbody>
<?php if(!empty($datas)): ?>
	<?php foreach($datas as $key => $data): ?>
	<tr>
		<td class="row-tools">
		<?php // ブログ記事編集画面へ移動 ?>
		<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_check.png', array('width' => 24, 'height' => 24, 'alt' => 'ブログ記事編集', 'class' => 'btn')),
				array('admin' => true, 'plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'edit', $data['BlogPost']['blog_content_id'], $data['BlogPost']['id']), array('title' => 'ブログ記事編集')) ?>

		<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
				array('action' => 'edit', $data['Slug']['id']), array('title' => '編集')) ?>
		<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_delete.png', 
				array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')),
				array('action' => 'delete', $data['Slug']['id'], $bcForm->value('Slug.id')), 
				array('title' => '削除', 'class' => 'btn-delete'),
				sprintf('ID：%s のデータを削除して良いですか？', $data['Slug']['id']), false) ?>
		</td>
		<td style="width: 45px;"><?php echo $data['Slug']['id']; ?></td>
		<td>
			<?php $blogContentData = $slug->getBlogContentData($data['BlogPost']['blog_content_id']) ?>
			<?php echo $blogContentData['BlogContent']['title'] ?>
		</td>
		<td style="width: 45px;">
			<?php echo $data['Slug']['blog_post_no']; ?>
		</td>
		<td>
			<?php echo $bcBaser->link($data['Slug']['name'], array('action' => 'edit', $data['Slug']['id']), array('title' => '編集')) ?>
		</td>
		<td style="white-space: nowrap">
			<?php echo $bcTime->format('Y-m-d', $data['Slug']['created']) ?>
			<br />
			<?php echo $bcTime->format('Y-m-d', $data['Slug']['modified']) ?>
		</td>
	</tr>
	<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan="6"><p class="no-data">データがありません。</p></td>
	</tr>
<?php endif; ?>
	</tbody>
</table>
<!-- list-num -->
<?php $bcBaser->element('list_num') ?>
</div>
