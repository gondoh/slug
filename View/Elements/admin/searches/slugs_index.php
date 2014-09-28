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
<?php echo $this->BcForm->create('Slug', array('url' => array('action' => 'index'))) ?>
<p>
	<span>
		<?php echo $this->BcForm->label('Slug.name', 'スラッグ') ?>
		&nbsp;<?php echo $this->BcForm->input('Slug.name', array('type' => 'text', 'size' => '30')) ?></span>
	<span>
		<?php echo $this->BcForm->label('Slug.blog_content_id', 'ブログ') ?>
		&nbsp;<?php echo $this->BcForm->input('Slug.blog_content_id', array('type' => 'select', 'options' => $blogContentDatas)) ?>
	</span>
</p>
<div class="button">
	<?php echo $this->BcForm->submit('admin/btn_search.png', array('alt' => '検索', 'class' => 'btn'), array('div' => false, 'id' => 'BtnSearchSubmit')) ?>
</div>
<?php echo $this->BcForm->end() ?>
