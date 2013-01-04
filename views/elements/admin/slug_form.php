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
<script type="text/javascript">
$(function () {
	$("#SlugName").change(function() {
		slugNameValueChengeHandler();
	});
});

function slugNameValueChengeHandler() {
	var options = {};
	options = {
		"data[Slug][id]": $("#SlugId").val(),
		"data[Slug][name]": $("#SlugName").val(),
		"data[Slug][blog_content_id]": $("#BlogPostBlogContentId").val()
	};
	$.ajax({
		type: "POST",
		data: options,
		url: $("#AjaxSlugCheckNameUrl").html(),
		dataType: "html",
		cache: false,
		success: function(result, status, xhr) {
			if(!result) {
				result = '<div class="error-message">同じスラッグがあります。変更してください。</div>';
				$("#SlugCheckNameResult").html(result);
			} else {
				$("#SlugCheckNameResult").html('');
			}			
		}
	});
}
</script>
<br />
<div id="AjaxSlugCheckNameUrl" class="display-none">
	<?php $bcBaser->url(array('plugin' => 'slug', 'controller' => 'slugs', 'action' => 'ajax_check_name')) ?>
</div>

<?php echo $bcForm->hidden('Slug.id') ?>
<?php echo $bcForm->label('Slug.name', 'スラッグ') ?>
<?php if($slug->jedgeAppearInputSlug($this->data['SlugConfig']['permalink_structure'])): ?>
	<?php echo $bcForm->input('Slug.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
<?php else: ?>
	<?php echo $bcForm->hidden('Slug.name') ?>
<?php endif ?>

<?php echo $html->image('admin/icn_help.png',array('id' => 'helpSlugName', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<div id="helptextSlugName" class="helptext">
	<ul>
		<li>ブログ記事URLを任意の文字列として設定します。</li>
		<li>表示形式は <small>http://〜/BLOG/
			<?php if(!$slug->slugConfigs['SlugConfig']['ignore_archives']): ?>archives/<?php endif ?>
			設定スラッグ</small> となります。</li>
	</ul>
</div>
<?php echo $bcForm->error('Slug.name') ?>
<div id="SlugCheckNameResult"></div>

<?php if($this->action == 'admin_edit'): ?>
<div class="box-tolink align-left">
	<?php if($bcForm->value('BlogPost.status')): ?>
		<?php if($bcForm->value('Slug.name')): ?>
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