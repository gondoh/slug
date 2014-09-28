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
<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('SlugConfig', array('url' => array('action' => 'add'))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('SlugConfig', array('url' => array('action' => 'edit'))) ?>
<?php endif ?>

<h2><?php echo $blogContentDatas[$this->request->data['SlugConfig']['blog_content_id']] ?></h2>

<?php $this->BcBaser->element('slug_config_form') ?>

<div class="submit">
	<?php echo $this->BcForm->submit('保　存', array('div' => false, 'class' => 'btn-red button')) ?>
</div>
<?php echo $this->BcForm->end() ?>
