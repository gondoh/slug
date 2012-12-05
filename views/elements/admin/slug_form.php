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
<?php if($slug->jedgeAppearInputSlug($this->data['SlugConfig']['permalink_structure'])): ?>
	<?php echo $bcForm->input('Slug.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
<?php else: ?>
	<?php echo $bcForm->hidden('Slug.name') ?>
<?php endif ?>

<?php echo $bcForm->input('Slug.status', array('type' => 'checkbox', 'label' => '有効')) ?>
<?php echo $bcForm->error('Slug.status') ?>

<?php echo $html->image('admin/icn_help.png',array('id' => 'helpSlugName', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<div id="helptextSlugName" class="helptext">
	<ul>
		<li>ブログ記事URLを任意の文字列として表示します。</li>
		<li>「有効」のチェックボックスにチェックを入れることで、この記事に対してのスラッグが有効化されます。</li>
		<li>表示形式は <small>http://〜/BLOG/archives/設定スラッグ</small> となります。</li>
	</ul>
</div>
<?php echo $bcForm->error('Slug.name') ?>

<?php if($this->action == 'admin_edit'): ?>
<div class="box-tolink align-left">
	<?php if($bcForm->value('BlogPost.status')): ?>
		<?php if($bcForm->value('Slug.name') && $bcForm->value('Slug.status')): ?>
	URL：<?php $bcBaser->link(
			$bcBaser->getUri('/' . $blogContent['BlogContent']['name'] . $slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])),
			'/' . $blogContent['BlogContent']['name'] . $slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])) ?>
		<?php elseif($bcForm->value('Slug.name')): ?>
	URL：<?php echo $bcBaser->getUri('/' . $blogContent['BlogContent']['name'] . $slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])) ?>
		<?php endif ?>
	<?php else: ?>
		<?php if($bcForm->value('Slug.name')): ?>
	URL：<?php echo $bcBaser->getUri('/' . $blogContent['BlogContent']['name'] . $slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])) ?>
		<?php endif ?>
	<?php endif ?>
</div>
<?php endif ?>