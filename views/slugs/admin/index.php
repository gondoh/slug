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
<div id="DataList">
	<?php $bcBaser->element('pagination') ?>

	<table cellpadding="0" cellspacing="0" class="list-table sort-table" id="ListTable">
		<thead>
			<tr><th style="width: 90px;">操作</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' NO',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' NO'), 'id', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 記事ID',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 記事ID'), 'blog_post_id', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 記事NO',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 記事NO'), 'blog_post_no', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' スラッグ',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' スラッグ'), 'name', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 登録日',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 登録日'), 'created', array('escape' => false, 'class' => 'btn-direction')) ?>
					<br />
					<?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 更新日',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 更新日'), 'modified', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
			</tr>
		</thead>
	<tbody>
<?php if(!empty($datas)): ?>
	<?php foreach($datas as $key => $data): ?>
		<?php if (!$data['Slug']['status'] == '1'): ?>
			<?php $class=' class="unpublish disablerow sortable"'; ?>
		<?php else: ?>
			<?php $class=' class="publish sortable"'; ?>
		<?php endif; ?>
	<tr<?php echo $class; ?>>
		<td class="row-tools">
		<?php if($data['Slug']['status'] == '1'): ?>
			<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_unpublish.png', array('width' => 24, 'height' => 24, 'alt' => '無効', 'class' => 'btn')),
					array('action' => 'unpublish', $data['Slug']['id']), array('title' => '無効', 'class' => 'btn-unpublish')) ?>
		<?php elseif($data['Slug']['status'] == '0'): ?>
			<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_publish.png', array('width' => 24, 'height' => 24, 'alt' => '有効', 'class' => 'btn')),
					array('action' => 'publish', $data['Slug']['id']), array('title' => '有効', 'class' => 'btn-publish')) ?>
		<?php endif ?>
		<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
				array('action' => 'edit', $data['Slug']['id']), array('title' => '編集')) ?>
		<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_delete.png', 
				array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')),
				array('action' => 'delete', $data['Slug']['id'], $bcForm->value('Slug.id')), 
				array('title' => '削除', 'class' => 'btn-delete'),
				sprintf('ID：' . $data['Slug']['id'] . 'のデータを本当に削除してもいいですか？'), false) ?>
		</td>
		<td style="width: 45px;"><?php echo $data['Slug']['id']; ?></td>
		<td style="width: 45px;"><?php echo $data['Slug']['blog_post_id']; ?></td>
		<td style="width: 45px;"><?php echo $data['Slug']['blog_post_no']; ?></td>
		<td><?php echo $data['Slug']['name']; ?></td>
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