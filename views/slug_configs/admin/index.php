<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @version			1.1.0
 * @license			MIT
 */
?>
<?php echo $bcForm->create('SlugConfig', array('action' => 'index')) ?>
<table cellpadding="0" cellspacing="0" class="list-table" id="ListTable">
	<tr>
		<th><?php echo $bcForm->label('SlugConfig.permalink_structure', 'スラッグ構造') ?></th>
		<td>
			<?php echo $bcForm->input('SlugConfig.permalink_structure', array('type' => 'radio', 'options' => $permalink_structure, 'separator' => '<br />')) ?>
				<?php echo $html->image('admin/icn_help.png', array('id' => 'helpPermalinkStructure', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
					<?php echo $bcForm->error('SlugConfig.permalink_structure') ?>
					<div id="helptextPermalinkStructure" class="helptext">
						<ul>
							<li>スラッグとして用いるURLを指定します。</li>
							<li><?php // TODO URL例を記述する ?></li>
						</ul>
					</div>
		</td>
	</tr>
	<tr>
		<th><?php echo $bcForm->label('SlugConfig.ignore_archives', 'archivesの省略') ?></th>
		<td>
			<?php echo $bcForm->input('SlugConfig.ignore_archives', array('type' => 'radio', 'options' => $ignore_archives)) ?>
				<?php echo $html->image('admin/icn_help.png', array('id' => 'helpIgnoreArchives', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
					<?php echo $bcForm->error('SlugConfig.ignore_archives') ?>
					<div id="helptextIgnoreArchives" class="helptext">
						<ul>
							<li>ブログ記事URLに入る「archives」の省略を指定します。</li>
						</ul>
					</div>
		</td>
	</tr>
</table>

<div class="submit">
	<?php echo $bcForm->submit('保　存', array('div' => false, 'class' => 'button')) ?>
</div>
<?php echo $bcForm->end() ?>
