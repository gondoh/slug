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
<?php echo $bcForm->create('Slug', array('url' => array('action' => 'batch'))) ?>
<table cellpadding="0" cellspacing="0" class="list-table" id="ListTable">
	<tr>
		<th class="col-head" style="width:20%;">はじめに<br />お読み下さい。</th>
		<td class="col-input">
			<strong>スラッグ一括設定では、ブログ別にスラッグを一括で登録できます。</strong>
			<ul>
				<li>スラッグの登録がないブログ記事用のスラッグを登録します。</li>
				<li>登録するスラッグはブログ記事タイトルを元に行います。</li>
				<li>登録するスラッグが重複する場合、スラッグには「-重複個数＋１」を付与します。</li>
			</ul>
		</td>
	</tr>
	<tr>
		<th class="col-head">ブログの指定</th>
		<td class="col-input">
			<?php if($blogContentDatas): ?>
				<?php echo $bcForm->input('Slug.blog_content_id', array('type' => 'select', 'options' => $blogContentDatas)) ?>
			<?php else: ?>
				ブログがないために設定できません。
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">スラッグの未登録状況</th>
		<td class="col-input">
			<ul>
			<?php foreach ($registerd as $value): ?>
				<li><?php echo $value['name'] ?>：
					<span class="large"><strong><?php echo $value['slug'] ?> 件</strong></span></li>
			<?php endforeach ?>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
	<?php if($blogContentDatas): ?>
		<?php echo $bcForm->submit('一括設定する', array(
			'div' => false,
			'class' => 'btn-red button',
			'id' => 'BtnSubmit',
			'onClick'=>"return confirm('スラッグの一括設定を行いますが良いですか？')")) ?>
	<?php else: ?>
		ブログがないために設定できません。
	<?php endif ?>
</div>
<?php echo $bcForm->end() ?>
