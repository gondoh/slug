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
<script type="text/javascript">
$(window).load(function() {
	$("#SlugName").focus();
});
</script>

<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('Slug', array('url' => array('action' => 'add'))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('Slug', array('url' => array('action' => 'edit'))) ?>
	<?php echo $this->BcForm->input('Slug.id', array('type' => 'hidden')) ?>
	<?php echo $this->BcForm->input('Slug.blog_post_id', array('type' => 'hidden')) ?>
	<?php echo $this->BcForm->input('Slug.blog_content_id', array('type' => 'hidden')) ?>
<?php endif ?>
<table cellpadding="0" cellspacing="0" class="form-table section" id="ListTable">
	<tr>
		<th class="col-head"><?php echo $this->BcForm->label('Slug.id', 'NO') ?></th>
		<td class="col-input">
			<?php echo $this->BcForm->value('Slug.id') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head"><?php echo $this->BcForm->label('Slug.name', 'スラッグ') ?></th>
		<td class="col-input">
			<?php echo $this->BcForm->input('Slug.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
			<?php echo $this->BcForm->error('Slug.name') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">ブログ名</th>
		<td class="col-input">
			<ul>
				<li><?php echo $blogContentDatas[$this->BcForm->value('Slug.blog_content_id')] ?></li>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->submit('登録', array('div' => false, 'class' => 'btn-red button')) ?>
<?php else: ?>
	<?php echo $this->BcForm->submit('更新', array('div' => false, 'class' => 'btn-red button')) ?>
	<?php $this->BcBaser->link('削除',
		array('action' => 'delete', $this->BcForm->value('Slug.id')),
		array('class' => 'btn-gray button'),
		sprintf('ID：%s のデータを削除して良いですか？', $this->BcForm->value('Slug.id')),
		false); ?>
<?php endif ?>
</div>
<?php echo $this->BcForm->end() ?>
