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
<?php echo $bcForm->create('SlugConfig', array('action' => 'index')) ?>
<table cellpadding="0" cellspacing="0" class="form-table section" id="ListTable">
	<tr>
		<th class="col-head">
			<?php echo $bcForm->label('SlugConfig.permalink_structure', 'スラッグ構造') ?>
			<?php echo $html->image('admin/icn_help.png', array('id' => 'helpPermalinkStructure', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
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
			<?php echo $html->image('admin/icn_help.png', array('id' => 'helpIgnoreArchives', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextIgnoreArchives" class="helptext">
				<ul>
					<li>ブログ記事URLに入る「archives」の省略を指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php echo $bcForm->input('SlugConfig.ignore_archives', array('type' => 'radio', 'options' => $ignore_archives)) ?>
			<?php echo $bcForm->error('SlugConfig.ignore_archives') ?>
		</td>
	</tr>
</table>

<div class="submit">
	<?php echo $bcForm->submit('保　存', array('div' => false, 'class' => 'button')) ?>
</div>
<?php echo $bcForm->end() ?>
