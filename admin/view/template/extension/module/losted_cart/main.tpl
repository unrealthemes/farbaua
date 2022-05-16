<?= $header; ?>
<?= $column_left; ?>
<?php $tabs = $license_status ? ['settings', 'carts', 'info'] : ['info']; ?>
<div id="content">
	<div class="page-header">	
		<div class="container-fluid top">
			<div class="row">
				<?php if (!empty($success)) { ?>
				<div class="col-sm-12">
					<div class="alert alert-success"><?= $success; ?></div>
				</div>
				<?php } ?>
				<div class="col-sm-10">
					<ul class="nav nav-tabs top" role="tablist">
						<?php foreach ($tabs as $name) { ?>
						<li> <a href="#<?= $name; ?>" data-toggle="tab" <?= $name == 'carts' ? "data-action='{$carts_action}'" : ''; ?>><?= ${'text_' . $name}; ?></a> </li>
						<?php } ?>
					</ul>
				</div>
				<div class="col-sm-2 text-right">				
					<button type="submit" form="form-losted" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-success btn-sm">
						<i class="fa fa-save"></i>
					</button>				
					<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></a>
				</div>	
				<div class="col-sm-12"> <hr class="top-line"> </div>			
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<?php if (!empty($errors) && $error) { ?>
					<div class="alert alert-danger" data-mt="20">
						<i class="fa fa-exclamation-circle"></i> <?= $error; ?>
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					</div>
					<?php } ?>						
				</div>
			</div>	
			<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-losted">				
				<div class="tab-content">	
					<?php foreach ($tabs as $name) { ?>
					<div class="tab-pane" id="<?= $name; ?>">
						<?php if ($name != 'carts') {
							require_once 'tab_' . $name . '.tpl'; 
						} ?>
					</div>		
					<?php } ?>			
				</div>		
			</form>	
		</div>
	</div>
</div>
<?php require_once 'modal.tpl'; ?>
<input type="hidden" id="var" value="<?= $var; ?>">
<?= $footer; ?>