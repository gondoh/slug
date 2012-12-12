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
<table cellpadding="0" cellspacing="0" class="list-table" id="ListTable">
	<tr>
		<th><?php echo $bcForm->label('SlugConfig.permalink_structure', 'スラッグ構造') ?></th>
		<td>
			<?php echo $bcForm->input('SlugConfig.permalink_structure', array('type' => 'radio', 'options' => $permalink_structure, 'separator' => '<br />')) ?>
			<?php echo $bcForm->error('SlugConfig.permalink_structure') ?>
			<?php echo $html->image('admin/icn_help.png', array('id' => 'helpPermalinkStructure', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextPermalinkStructure" class="helptext">
				<ul>
					<li>スラッグとして用いるURLを指定します。</li>
					<li><?php // TODO URL例を記述する ?></li>
				</ul>
			</div>
		</td>
	</tr>
	<tr>
		<th><?php echo $bcForm->label('SlugConfig.active_all_slug', '全てのスラッグを有効化') ?></th>
		<td>
			<?php echo $bcForm->input('SlugConfig.active_all_slug', array('type' => 'radio', 'options' => $active_all_slugs)) ?>
			<?php echo $bcForm->error('SlugConfig.active_all_slug') ?>
			<?php echo $html->image('admin/icn_help.png', array('id' => 'helpActiveAllSlug', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextActiveAllSlug" class="helptext">
				<ul>
					<li>全てのブログ記事のスラッグを有効化します。</li>
					<li>ブログ記事個別で「無効」を選択した場合はそちらが優先されます。</li>
				</ul>
			</div>
		</td>
	</tr>
	<tr>
		<th><?php echo $bcForm->label('SlugConfig.ignore_archives', 'archivesの省略') ?></th>
		<td>
			<?php echo $bcForm->input('SlugConfig.ignore_archives', array('type' => 'radio', 'options' => $ignore_archives)) ?>
			<?php echo $bcForm->error('SlugConfig.ignore_archives') ?>
			<?php echo $html->image('admin/icn_help.png', array('id' => 'helpIgnoreArchives', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
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
