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
<div id="DataList">
	<?php $this->BcBaser->element('pagination') ?>

	<table cellpadding="0" cellspacing="0" class="list-table sort-table" id="ListTable">
		<thead>
			<tr><th style="width: 50px;">操作</th>
				<th><?php echo $this->Paginator->sort('id', array(
						'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' NO',
						'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' NO'),
						array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th>
					<?php echo $this->Paginator->sort('blog_content_id', array(
						'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' ブログ名',
						'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' ブログ名'),
						array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th>
					<?php echo $this->Paginator->sort('permalink_structure', array(
						'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' スラッグ構造',
						'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' スラッグ構造'),
						array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $this->Paginator->sort('ignore_archives', array(
						'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' archivesの省略',
						'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' archivesの省略'),
						array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
				<th><?php echo $this->Paginator->sort('created', array(
						'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 登録日',
						'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 登録日'),
						array('escape' => false, 'class' => 'btn-direction')) ?>
					<br />
					<?php echo $this->Paginator->sort('modified', array(
						'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 更新日',
						'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 更新日'),
						array('escape' => false, 'class' => 'btn-direction')) ?>
				</th>
			</tr>
		</thead>
	<tbody>
<?php if(!empty($datas)): ?>
	<?php foreach($datas as $data): ?>
	<tr>
		<td class="row-tools">
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
				array('action' => 'edit', $data['SlugConfig']['id']), array('title' => '編集')) ?>
		</td>
		<td style="width: 45px;"><?php echo $data['SlugConfig']['id']; ?></td>
		<td>
			<?php echo $this->BcBaser->link($blogContentDatas[$data['SlugConfig']['blog_content_id']], array('action' => 'edit', $data['SlugConfig']['id']), array('title' => '編集')) ?>
		</td>
		<td>
			<?php echo $permalink_structure[$data['SlugConfig']['permalink_structure']] ?>
		</td>
		<td>
			<?php echo $this->BcText->booleanDo($data['SlugConfig']['ignore_archives'], '省略') ?>
		</td>
		<td style="white-space: nowrap">
			<?php echo $this->BcTime->format('Y-m-d', $data['SlugConfig']['created']) ?>
			<br />
			<?php echo $this->BcTime->format('Y-m-d', $data['SlugConfig']['modified']) ?>
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
<?php $this->BcBaser->element('list_num') ?>
</div>
