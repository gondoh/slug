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
<?php if($this->request->params['controller'] == 'blog_contents'): ?>
<script type="text/javascript">
$(function () {
	$("#textSlugConfigTable").toggle(
		function() {
			$('#SlugConfigTable').slideDown('slow');
		},
		function() {
			$('#SlugConfigTable').slideUp('slow');
		}
	);
});
</script>
<style type="text/css">
	#textSlugConfigTable {
		cursor: pointer;
	}
</style>
<h3 id="textSlugConfigTable">スラッグ設定</h3>
<?php endif ?>

<?php if($this->action == 'admin_add'): ?>
<?php else: ?>
	<?php echo $this->BcForm->input('SlugConfig.id', array('type' => 'hidden')) ?>
<?php endif ?>

<?php if($this->request->params['controller'] == 'blog_contents'): ?>
<div id="SlugConfigTable" style="display: none;">
<?php else: ?>
<div id="SlugConfigTable">
<?php endif ?>

<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('SlugConfig.permalink_structure', 'スラッグ構造') ?>
			<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpPermalinkStructure', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextPermalinkStructure" class="helptext">
				<ul>
					<li>スラッグとして用いるURLを指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('SlugConfig.permalink_structure', array('type' => 'radio', 'options' => $permalink_structure, 'separator' => '<br />')) ?>
			<?php echo $this->BcForm->error('SlugConfig.permalink_structure') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('SlugConfig.ignore_archives', 'archivesの省略') ?>
			<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpIgnoreArchives', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextIgnoreArchives" class="helptext">
				<ul>
					<li>ブログのアーカイブURLに入る「archives」の省略を指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('SlugConfig.ignore_archives', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('省略'))) ?>
			<?php echo $this->BcForm->error('SlugConfig.ignore_archives') ?>
		</td>
	</tr>
</table>
</div>
