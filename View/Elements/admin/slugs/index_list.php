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
<?php $this->BcBaser->element('pagination') ?>

<table cellpadding="0" cellspacing="0" class="list-table sort-table" id="ListTable">
	<thead>
		<tr><th style="width: 90px;">操作</th>
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
				<?php echo $this->Paginator->sort('blog_post_no', array(
					'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' 記事NO',
					'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' 記事NO'),
					array('escape' => false, 'class' => 'btn-direction')) ?>
			</th>
			<th><?php echo $this->Paginator->sort('name', array(
					'asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => '昇順', 'title' => '昇順')).' スラッグ',
					'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => '降順', 'title' => '降順')).' スラッグ'),
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
		<?php $this->BcBaser->element('slugs/index_row', array('data' => $data)) ?>
	<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan="7"><p class="no-data">データがありません。</p></td>
	</tr>
<?php endif; ?>
	</tbody>
</table>

<!-- list-num -->
<?php $this->BcBaser->element('list_num') ?>
