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
<?php echo $bcForm->create('SlugConfig', array('action' => 'first')) ?>
<?php echo $bcForm->input('SlugConfig.active', array('type' => 'hidden', 'value' => '1')) ?>
<table cellpadding="0" cellspacing="0" class="form-table section" id="ListTable">
	<tr>
		<th class="col-head">
			はじめに<br />お読み下さい。
		</th>
		<td class="col-input">
			<strong>スラッグ設定データ作成では、各ブログ用のスラッグ設定データを作成します。</strong>
			<ul>
				<li>スラッグ設定データがないブログ用のデータのみ作成します。</li>
			</ul>
		</td>
	</tr>
	<tr>
		<th class="col-head">スラッグ設定の未登録状況</th>
		<td class="col-input">
			<ul>
			<?php foreach ($registerd as $value): ?>
				<li><?php echo $value['name'] ?>：
					<span class="large"><strong><?php echo $bcText->booleanExists($value['config']) ?></strong></span></li>
			<?php endforeach ?>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
	<?php echo $bcForm->submit('作成する', array(
		'div' => false,
		'class' => 'btn-red button',
		'id' => 'BtnSubmit',
		'onClick'=>"return confirm('スラッグ設定データの作成を行いますが良いですか？')")) ?>
</div>
<?php echo $bcForm->end() ?>
