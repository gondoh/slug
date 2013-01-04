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
<?php if($this->action == 'admin_add'): ?>
	<?php echo $bcForm->create('SlugConfig', array('url' => array('action' => 'add'))) ?>
<?php else: ?>
	<?php echo $bcForm->create('SlugConfig', array('url' => array('action' => 'edit'))) ?>
	<?php echo $bcForm->input('SlugConfig.id', array('type' => 'hidden')) ?>
<?php endif ?>

<h2><?php echo $blogContentDatas[$this->data['SlugConfig']['blog_content_id']] ?></h2>
<?php $bcBaser->element('slug_config_form') ?>


<div class="submit">
	<?php echo $bcForm->submit('保　存', array('div' => false, 'class' => 'btn-red button')) ?>
</div>
<?php echo $bcForm->end() ?>
