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
<?php echo $this->BcForm->create('SlugConfig', array('action' => 'first')) ?>
<?php echo $this->BcForm->input('SlugConfig.active', array('type' => 'hidden', 'value' => '1')) ?>
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
					<span class="large"><strong><?php echo $this->BcText->booleanExists($value['config']) ?></strong></span></li>
			<?php endforeach ?>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
	<?php echo $this->BcForm->submit('作成する', array(
		'div' => false,
		'class' => 'btn-red button',
		'id' => 'BtnSubmit',
		'onClick'=>"return confirm('スラッグ設定データの作成を行いますが良いですか？')")) ?>
</div>
<?php echo $this->BcForm->end() ?>
