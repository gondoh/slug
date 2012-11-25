<?php
/**
 * [ADMIN] slug
 *
 * @copyright		Copyright 2012, materializing.
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug.views
 * @version			1.0.0
 * @license			MIT
 */
?>
<br />
<?php echo $bcForm->hidden('Slug.id') ?>
<?php echo $bcForm->label('Slug.name', 'スラッグ') ?>
<?php echo $bcForm->input('Slug.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
<?php echo $bcForm->error('Slug.name') ?>

<?php echo $bcForm->input('Slug.status', array('type' => 'checkbox', 'label' => '有効')) ?>
<?php echo $bcForm->error('Slug.status') ?>

<?php echo $html->image('admin/icn_help.png',array('id' => 'helpSlugName', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<div id="helptextSlugName" class="helptext">
	<ul>
		<li>ブログ記事URLを任意の文字列として表示します。</li>
		<li>「有効」のチェックボックスにチェックを入れることで、この記事に対してのスラッグが有効化されます。</li>
		<li>表示形式は <small>http://〜/BLOG/archives/設定文字列</small> となります。</li>
	</ul>
</div>

<?php if($this->action == 'admin_edit'): ?>
<div class="box-tolink align-left">
	<?php if($bcForm->value('BlogPost.status')): ?>
		<?php if($bcForm->value('Slug.name') && $bcForm->value('Slug.status')): ?>
	URL：<?php $bcBaser->link(
			$bcBaser->getUri('/' . $blogContent['BlogContent']['name'] . '/archives/' . $bcForm->value('Slug.name')),
			'/' . $blogContent['BlogContent']['name'] . '/archives/' . $bcForm->value('Slug.name')) ?>
		<?php elseif($bcForm->value('Slug.name')): ?>
	URL：<?php echo $bcBaser->getUri('/' . $blogContent['BlogContent']['name'] . '/archives/' . $bcForm->value('Slug.name')) ?>
		<?php endif ?>
	<?php else: ?>
		<?php if($bcForm->value('Slug.name')): ?>
	URL：<?php echo $bcBaser->getUri('/' . $blogContent['BlogContent']['name'] . '/archives/' . $bcForm->value('Slug.name')) ?>
		<?php endif ?>
	<?php endif ?>
</div>
<?php endif ?>