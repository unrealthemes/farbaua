<div class="row" data-mt="20">
	<div class="col-sm-4">
		<div class="alert alert-info">
			<b><?= $heading_title; ?></b> by <a href="https://pitov.pro">Artem Pitov</a> <br> 
			<b><?= $text_version_install; ?>:</b> <?= $version; ?> <br>
		</div>
		<div data-mb="15" class="activation-form">
			<?php if (!$license_status) { ?>
			<p>Если Вы на смогли активировать модуль, свяжитесь с автором artempitov@gmail.com</p>
			<div class="form-group">
				<div class="input-group">
					<label class="input-group-addon">Ключ активации</label>
					<input type="password" class="form-control" name="lckey" value="<?= $license_key; ?>">
					<div class="input-group-btn"><button class="btn btn-primary" type="button">Активировать</button></div>
				</div>
			</div>				
			<button class="btn btn-primary hide" type="button">Активировать</button>
			<?php } else { ?>
			<span class="license-active">Модуль успешно активирован для <?= $site; ?></span>
			<?php } ?>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="alert alert-info"><?= $text_cli; ?></div>
		<div class="alert alert-info"><?= $text_api_info; ?></div>
	</div>
</div>
<div class="row" data-mt="-20">
	<div class="col-sm-12">
		<h4><b>Лицензионное соглашение</b></h4>
		<div class="license-text"><?= $license_text; ?></div>
	</div>
</div>