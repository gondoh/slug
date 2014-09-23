<?php
/**
 * [ADMIN] slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			slug
 * @license			MIT
 */
?>
<?php echo $bcForm->create('Slug', array('url' => array('action' => 'index'))) ?>
<p>
	<span>
		<?php echo $bcForm->label('Slug.name', 'スラッグ') ?>
		&nbsp;<?php echo $bcForm->input('Slug.name', array('type' => 'text', 'size' => '30')) ?></span>
	<span>
		<?php echo $bcForm->label('Slug.blog_content_id', 'ブログ') ?>
		&nbsp;<?php echo $bcForm->input('Slug.blog_content_id', array('type' => 'select', 'options' => $blogContentDatas)) ?>
	</span>
</p>
<div class="button">
	<?php echo $bcForm->submit('admin/btn_search.png', array('alt' => '検索', 'class' => 'btn'), array('div' => false, 'id' => 'BtnSearchSubmit')) ?>
</div>
<?php echo $bcForm->end() ?>
