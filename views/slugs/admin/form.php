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
<script type="text/javascript">
$(window).load(function() {
	$("#SlugName").focus();
});
</script>

<?php if($this->action == 'admin_add'): ?>
	<?php echo $bcForm->create('Slug', array('url' => array('action' => 'add'))) ?>
<?php else: ?>
	<?php echo $bcForm->create('Slug', array('url' => array('action' => 'edit'))) ?>
	<?php echo $bcForm->input('Slug.id', array('type' => 'hidden')) ?>
	<?php echo $bcForm->input('Slug.blog_post_id', array('type' => 'hidden')) ?>
	<?php echo $bcForm->input('Slug.blog_content_id', array('type' => 'hidden')) ?>
<?php endif ?>
<table cellpadding="0" cellspacing="0" class="form-table section" id="ListTable">
	<tr>
		<th class="col-head"><?php echo $bcForm->label('Slug.id', 'NO') ?></th>
		<td class="col-input">
			<?php echo $bcForm->value('Slug.id') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head"><?php echo $bcForm->label('Slug.name', 'スラッグ') ?></th>
		<td class="col-input">
			<?php echo $bcForm->input('Slug.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
			<?php echo $bcForm->error('Slug.name') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">ブログ名</th>
		<td class="col-input">
			<ul>
				<li><?php echo $blogContentDatas[$bcForm->value('Slug.blog_content_id')] ?></li>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
<?php if($this->action == 'admin_add'): ?>
	<?php echo $bcForm->submit('登録', array('div' => false, 'class' => 'btn-red button')) ?>
<?php else: ?>
	<?php echo $bcForm->submit('更新', array('div' => false, 'class' => 'btn-red button')) ?>
	<?php $bcBaser->link('削除',
		array('action' => 'delete', $bcForm->value('Slug.id')),
		array('class' => 'btn-gray button'),
		sprintf('ID：%s のデータを削除して良いですか？', $bcForm->value('Slug.id')),
		false); ?>
<?php endif ?>
</div>
<?php echo $bcForm->end() ?>
