<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012 - 2013, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @license			MIT
 */
?>
<?php if($this->params['controller'] == 'blog_contents'): ?>
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
	<?php echo $bcForm->input('SlugConfig.id', array('type' => 'hidden')) ?>
<?php endif ?>

<?php if($this->params['controller'] == 'blog_contents'): ?>
<div id="SlugConfigTable" style="display: none;">
<?php else: ?>
<div id="SlugConfigTable">
<?php endif ?>

<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr>
		<th class="col-head">
			<?php echo $bcForm->label('SlugConfig.permalink_structure', 'スラッグ構造') ?>
			<?php echo $bcBaser->img('admin/icn_help.png', array('id' => 'helpPermalinkStructure', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextPermalinkStructure" class="helptext">
				<ul>
					<li>スラッグとして用いるURLを指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php echo $bcForm->input('SlugConfig.permalink_structure', array('type' => 'radio', 'options' => $permalink_structure, 'separator' => '<br />')) ?>
			<?php echo $bcForm->error('SlugConfig.permalink_structure') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $bcForm->label('SlugConfig.ignore_archives', 'archivesの省略') ?>
			<?php echo $bcBaser->img('admin/icn_help.png', array('id' => 'helpIgnoreArchives', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextIgnoreArchives" class="helptext">
				<ul>
					<li>ブログのアーカイブURLに入る「archives」の省略を指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php echo $bcForm->input('SlugConfig.ignore_archives', array('type' => 'radio', 'options' => $bcText->booleanDoList('省略'))) ?>
			<?php echo $bcForm->error('SlugConfig.ignore_archives') ?>
		</td>
	</tr>
</table>
</div>
