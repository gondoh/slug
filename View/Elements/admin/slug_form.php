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
	<?php $this->BcBaser->url(array('plugin' => 'slug', 'controller' => 'slugs', 'action' => 'ajax_check_name')) ?>
</div>

<?php echo $this->BcForm->hidden('Slug.id') ?>
<?php echo $this->BcForm->label('Slug.name', 'スラッグ') ?>
<?php if($this->Slug->judgeAppearInputSlug($this->data['SlugConfig']['permalink_structure'])): ?>
	<?php echo $this->BcForm->input('Slug.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
<?php else: ?>
	<?php echo $this->BcForm->hidden('Slug.name') ?>
<?php endif ?>

<?php echo $this->BcBaser->img('admin/icn_help.png',array('id' => 'helpSlugName', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<div id="helptextSlugName" class="helptext">
	<ul>
		<li>ブログ記事URLを任意の文字列として設定します。</li>
		<li>表示形式は <small>http://〜/BLOG/
			<?php if(!$this->Slug->slugConfigs['SlugConfig']['ignore_archives']): ?>archives/<?php endif ?>
			設定スラッグ</small> となります。</li>
	</ul>
</div>
<?php echo $this->BcForm->error('Slug.name') ?>
<div id="SlugCheckNameResult"></div>

<?php if($this->request->action == 'admin_edit'): ?>
<div id="SlugPublishUrl" class="display-none"><?php echo $this->BcBaser->getUri('/' . $blogContent['BlogContent']['name'] . $this->Slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])) ?></div>
<div class="box-tolink align-left">
	<?php if($this->BcForm->value('BlogPost.status')): ?>
		<?php if($this->BcForm->value('Slug.name')): ?>
	URL：<?php $this->BcBaser->link(
			$this->BcBaser->getUri('/' . $blogContent['BlogContent']['name'] . $this->Slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])),
			'/' . $blogContent['BlogContent']['name'] . $this->Slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])) ?>
		<?php elseif($this->BcForm->value('Slug.name')): ?>
	URL：<?php echo $this->BcBaser->getUri('/' . $blogContent['BlogContent']['name'] . $this->Slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])) ?>
		<?php endif ?>
	<?php else: ?>
		<?php if($this->BcForm->value('Slug.name')): ?>
	URL：<?php echo $this->BcBaser->getUri('/' . $blogContent['BlogContent']['name'] . $this->Slug->getSlugUrl($this->data['Slug'], $this->data['BlogPost'])) ?>
		<?php endif ?>
	<?php endif ?>
</div>
<?php endif ?>
