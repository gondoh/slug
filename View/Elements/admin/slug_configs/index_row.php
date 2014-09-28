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
<tr>
	<td class="row-tools">
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
			array('action' => 'edit', $data['SlugConfig']['id']), array('title' => '編集')) ?>
	</td>
	<td style="width: 45px;"><?php echo $data['SlugConfig']['id']; ?></td>
	<td>
		<?php echo $this->BcBaser->link($blogContentDatas[$data['SlugConfig']['blog_content_id']], array('action' => 'edit', $data['SlugConfig']['id']), array('title' => '編集')) ?>
	</td>
	<td>
		<?php echo $permalink_structure[$data['SlugConfig']['permalink_structure']] ?>
	</td>
	<td>
		<?php echo $this->BcText->booleanDo($data['SlugConfig']['ignore_archives'], '省略') ?>
	</td>
	<td style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['SlugConfig']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['SlugConfig']['modified']) ?>
	</td>
</tr>
