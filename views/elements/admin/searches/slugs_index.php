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
<?php echo $bcForm->create('Slug', array('url' => array('action' => 'index'))) ?>
<p>
	<span><?php echo $bcForm->label('Slug.name', 'スラッグ') ?>
		&nbsp;<?php echo $bcForm->input('Slug.name', array('type' => 'text', 'size' => '30')) ?></span>
	<span><?php echo $bcForm->label('Slug.status', '状態') ?>
		&nbsp;<?php echo $bcForm->input('Slug.status', array('type' => 'select', 'options' => $bcText->booleanMarkList(), 'empty' => '指定なし')) ?></span>
</p>
<div class="button">
	<?php echo $bcForm->submit('admin/btn_search.png', array('alt' => '検索', 'class' => 'btn'), array('div' => false, 'id' => 'BtnSearchSubmit')) ?>
</div>
<?php echo $bcForm->end() ?>
