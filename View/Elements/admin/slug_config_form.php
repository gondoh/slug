<?php
/**
 * [ADMIN] Slug
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			Slug
 * @license			MIT
 */
$this->BcBaser->css('Slug.admin/slug', array('inline' => false));
?>
<?php if($this->request->action != 'admin_add'): ?>
	<?php echo $this->BcForm->input('SlugConfig.id', array('type' => 'hidden')) ?>
<?php endif ?>

<div id="WrapperSlugConfig">
	<div id="WrapperSlugConfigPermalinkStructure">
		<strong><?php echo $this->BcForm->label('SlugConfig.permalink_structure', 'スラッグとして用いるURLの指定') ?></strong>
	<br />
	<?php echo $this->BcForm->input('SlugConfig.permalink_structure', array('type' => 'radio', 'options' => $permalink_structure, 'separator' => '<br />')) ?>
	<?php echo $this->BcForm->error('SlugConfig.permalink_structure') ?>
	</div>
	<div id="WrapperSlugConfigIgnoreArchives">
	<?php echo $this->BcForm->input('SlugConfig.ignore_archives', array('type' => 'checkbox', 'label' => 'アーカイブURLの「archives」を省略する')) ?>
	<?php echo $this->BcForm->error('SlugConfig.ignore_archives') ?>
	</div>
</div>
