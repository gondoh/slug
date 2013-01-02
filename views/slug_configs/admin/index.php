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
			<tr><th style="width: 50px;">操作</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' NO',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' NO'),
						'id', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th>
					<?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' ブログ名',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' ブログ名'),
						'blog_content_id', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th>
					<?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' スラッグ構造',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' スラッグ構造'),
						'permalink_structure', array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $paginator->sort(array(
						'asc' => $bcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' archivesの省略',
						'desc' => $bcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' archivesの省略'),
						'ignore_archives', array('escape' => false, 'class' => 'btn-direction')) ?>
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
	<?php foreach($datas as $data): ?>
	<tr>
		<td class="row-tools">
		<?php $bcBaser->link($bcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
				array('action' => 'edit', $data['SlugConfig']['id']), array('title' => '編集')) ?>
		</td>
		<td style="width: 45px;"><?php echo $data['SlugConfig']['id']; ?></td>
		<td>
			<?php echo $bcBaser->link($blogContentDatas[$data['SlugConfig']['blog_content_id']], array('action' => 'edit', $data['SlugConfig']['id']), array('title' => '編集')) ?>
		</td>
		<td>
			<?php echo $permalink_structure[$data['SlugConfig']['permalink_structure']] ?>
		</td>
		<td>
			<?php echo $ignore_archives[$data['SlugConfig']['ignore_archives']] ?>
		</td>
		<td style="white-space: nowrap">
			<?php echo $bcTime->format('Y-m-d', $data['SlugConfig']['created']) ?>
			<br />
			<?php echo $bcTime->format('Y-m-d', $data['SlugConfig']['modified']) ?>
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
