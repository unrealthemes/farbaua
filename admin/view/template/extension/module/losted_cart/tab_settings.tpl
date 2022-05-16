<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label class="block-title"><?= $text_use; ?></label>
			<label class="switch" data-mt="0" data-mb="-15">
				<input class="switch-input" type="checkbox" name="losted_cart_status" value="1" <?= $status ? 'checked' : ''; ?> />
				<span class="switch-label" data-on="<?= $text_on; ?>" data-off="<?= $text_off; ?>"></span> 
				<span class="switch-handle"></span> 
			</label>						    
		</div>					
		<div class="form-group">
			<label class="block-title"><?= $text_label; ?></label>
			<div class="inputer" data-mb="15">
				<label><?= $text_label_life; ?></label>
				<input type="text" name="losted_cart_settings[cookie_life]" value="<?= $settings['cookie_life']; ?>">	
			</div>	
			<div class="alert alert-info" data-mb="15"><?= $text_crypt_help; ?></div>												   	
			<?php if (false) { ?>
			<div class="inputer">
				<label><?= $text_crypt; ?></label>
				<textarea name="losted_cart_settings[crypt_key]"><?= $settings['crypt_key']; ?></textarea>
			</div>
			<?php } ?>		
		</div>
		<div class="form-group">
			<label class="block-title"><?= $text_checkout_route; ?></label>
			<div class="inputer" data-mb="15">
				<label><?= $text_label_checkout_route; ?></label>
				<input type="text" name="losted_cart_settings[checkout_route]" value="<?= $settings['checkout_route']; ?>">	
			</div>	
			<div class="alert alert-info" data-mb="15"><?= $text_checkout_route_help; ?></div>
		</div>		
	</div>
	<div class="col-sm-4">		
		<div class="form-group">
			<label class="block-title"><?= $text_send; ?></label>
			<div class="inputer" data-mb="15">
				<label><?= $text_email_first; ?></label>
				<input type="text" name="losted_cart_settings[sending_interval]" value="<?= $settings['sending_interval']; ?>">	
			</div>	
			<div class="inputer" data-mb="0">
				<label><?= $text_email_second; ?></label>
				<input type="text" name="losted_cart_settings[resending_day]" value="<?= $settings['resending_day']; ?>">	
			</div>																										   	
			<div class="alert alert-info" data-mt="15" data-mb="0"><?= $text_email_second_help; ?></div>
			<div class="inputer" data-mb="0" data-mt="15">
				<label><?= $text_resending_max; ?></label>
				<input type="text" name="losted_cart_settings[resending_max]" value="<?= $settings['resending_max']; ?>">	
			</div>
		</div>
		<div class="form-group">
			<label><?= $text_groups_disabled; ?></label>
			<div class="well well-sm" style="height: 150px; overflow: auto;">
				<div class="checkbox">
					<?php foreach ($customer_groups as $customer_group) { ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="losted_cart_settings[groups_disabled][]" value="<?= $customer_group['customer_group_id'] ?>"
								<?= in_array($customer_group['customer_group_id'], $settings['groups_disabled']) ? 'checked="checked"' : '' ?> />
								<?= $customer_group['name'] ?>
						</label>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="block-title"><?= $text_size; ?></label>
			<div class="row">
				<div class="col-sm-6">
					<div class="inputer" data-mb="15">
						<label><?= $text_product_w; ?></label>
						<input type="text" name="losted_cart_settings[product_w]" value="<?= $settings['product_w']; ?>">	
					</div>			
				</div>
				<div class="col-sm-6">
					<div class="inputer" data-mb="15">
						<label><?= $text_product_h; ?></label>
						<input type="text" name="losted_cart_settings[product_h]" value="<?= $settings['product_h']; ?>">	
					</div>			
				</div>
			</div>	
			<div class="row">
				<div class="col-sm-6">
					<div class="inputer" data-mb="15">
						<label><?= $text_logo_w; ?></label>
						<input type="text" name="losted_cart_settings[logo_w]" value="<?= $settings['logo_w']; ?>">	
					</div>			
				</div>
				<div class="col-sm-6">
					<div class="inputer" data-mb="15">
						<label><?= $text_logo_h; ?></label>
						<input type="text" name="losted_cart_settings[logo_h]" value="<?= $settings['logo_h']; ?>">	
					</div>			
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="block-title">Collector</label>
			<div class="alert alert-info" data-mb="15"> <?= $text_collector_help; ?> </div>
			<div class="inputer">
				<label><?= $text_collector; ?></label>
				<textarea name="losted_cart_settings[collector][routers]"><?= $settings['collector']['routers']; ?></textarea>
			</div>	
		</div>																   	
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="block-title"><?= $text_coupon; ?></label>
			<input class="switch-input" name="losted_cart_settings[coupon][status]" type="hidden" value="0" />
			<label class="switch" data-mb="-15">
				<input class="switch-input" name="losted_cart_settings[coupon][status]" type="checkbox" value="1" <?= $settings['coupon']['status'] ? 'checked' : ''; ?> />
				<span class="switch-label" data-on="<?= $text_on; ?>" data-off="<?= $text_off; ?>"></span> 
				<span class="switch-handle"></span> 
			</label>
		</div>
		<div class="form-group">
			<div class="inputer">
				<label><?= $text_coupon_name; ?></label>
				<input type="text" name="losted_cart_settings[coupon][name]" value="<?= $settings['coupon']['name']; ?>">	
			</div>
		</div>
		<div class="form-group">
			<div class="inputer">
				<label><?= $text_coupon_life; ?></label>
				<input type="text" name="losted_cart_settings[coupon][life_days]" value="<?= $settings['coupon']['life_days']; ?>">	
			</div>
		</div>
		<div class="form-group">
			<div class="inputer">
				<label><?= $text_coupon_prefix; ?></label>
				<input type="text" name="losted_cart_settings[coupon][prefix]" value="<?= $settings['coupon']['prefix']; ?>">
			</div>
		</div>	
		<div class="form-group">
			<div class="inputer">
				<label><?= $text_coupon_minimum; ?></label>
				<input type="text" name="losted_cart_settings[coupon][total]" value="<?= $settings['coupon']['total']; ?>">	
			</div>
		</div>	
		<div class="form-group">
			<div class="inputer">
				<label><?= $text_coupon_sale; ?></label>
				<input type="text" name="losted_cart_settings[coupon][discount]" value="<?= $settings['coupon']['discount']; ?>">
			</div>
		</div>	
		<div class="form-group">
			<label><?= $text_coupon_sale_type; ?></label>
			<select class="form-control" name="losted_cart_settings[coupon][type]">
				<option <?= $settings['coupon']['type'] == 'P' ? 'selected' : ''; ?> value="P"><?= $text_P; ?></option>
				<option <?= $settings['coupon']['type'] == 'F' ? 'selected' : ''; ?> value="F"><?= $text_F; ?></option>
			</select>	
		</div>
		<div class="form-group">
			<label><?= $text_coupon_delivery; ?></label>
			<select class="form-control" name="losted_cart_settings[coupon][shipping]">
				<option <?= $settings['coupon']['shipping'] == 0 ? 'selected' : ''; ?> value="0"><?= $text_disabled; ?></option>
				<option <?= $settings['coupon']['shipping'] == 1 ? 'selected' : ''; ?> value="1"><?= $text_enabled; ?></option>
			</select>	
		</div>	
	</div>
</div>

<div class="form-group row">
	<div class="col-sm-4 template-info">
		<div class="block-title" data-mb="15"><?= $text_var_title; ?></div>	
		<div class="table-wrap">
			<div class="alert alert-info"><?= $text_twig_help; ?></div>
			<div class="table-responsive">
	    		<table class="table table-striped table-hover">
	    			<?php $vars = [ 'lastname', 'firstname', 'email', 'telephone', 'subject', 'store_name', 'store_url', 'checkout_url', 'logo' ];
	    			foreach ($vars as $valu) { ?>
	    			<tr>
	    				<td><?= $valu; ?></td>
	    				<td><?= ${'text_var_' . $valu}; ?></td>
	    			</tr>
	    			<?php } ?> 
	    		</table>

	    		<table class="table table-striped table-hover" data-mt="25">
	    			<tr>
	    				<th colspan="2"><?= $text_var_coupon_title; ?></th>
	    			</tr>
	    			<?php $vars = [ 'coupon', 'coupon.code', 'coupon.date_end', 'coupon.free_shipping'];
	    			foreach ($vars as $valu) { ?>
	    			<tr>
	    				<td><?= $valu; ?></td>
	    				<td><?= ${'text_var_' . str_replace('.', '_',$valu)}; ?></td>
	    			</tr>
	    			<?php } ?> 	    			  
	    		</table>

	    		<table class="table table-striped table-hover" data-mt="25"> 
	    			<tr>
	    				<th colspan="2"><?= $text_var_cart_title; ?></th>
	    			</tr>
	    			<?php $vars = [ 'cart', 'cart.quantity', 'cart.total', 'cart.products', 'cart.products.name', 
	    			'cart.products.url', 'cart.products.model', 'cart.products.image', 'cart.products.price', 'cart.products.total', 'cart.products.option', 'cart.products.option.name', 'cart.products.option.value', 'cart.products.option.price', 'cart.products.option.price_prefix' ];
	    			foreach ($vars as $valu) { ?>
	    			<tr>
	    				<td><?= $valu; ?></td>
	    				<td><?= ${'text_var_' . str_replace('.', '_',$valu)}; ?></td>
	    			</tr>
	    			<?php } ?>		 	    				    								     					    				    				    				
	    		</table>
			</div>
		</div>
	</div>	
	<div class="col-sm-8 template-tab">
		<label class="block-title">Email</label>
		<input type="hidden" name="losted_cart_settings[email][status]" value="0">
		<label class="switch" data-mb="10">
			<input class="switch-input" type="checkbox" value="1" name="losted_cart_settings[email][status]" <?= $settings['email']['status'] ? 'checked' : ''; ?> />
			<span class="switch-label" data-on="<?= $text_on; ?>" data-off="<?= $text_off; ?>"></span> 
			<span class="switch-handle"></span> 
		</label>
		<div class="form-group hide">
			<label><?= $text_mail_type; ?></label>
			<select class="form-control" name="losted_cart_settings[email][mail_library]">
				<option selected value="default">Default</option>
				<option disabled value="phpmailer">PHPmailer (не доступно)</option>
			</select>	
		</div>	
		<ul class="nav nav-tabs">
			<?php $i = 0; foreach ($languages as $language) { ?>
			<li role="presentation" <?= !$i ? 'class="active"' : ''; ?>>
				<a href="#template-<?= $i; ?>" aria-controls="template-<?= $i; ?>" role="tab" data-toggle="tab" title="<?= $language['name']; ?>">
					<img src="<?= $version2 ? "view/image/flags/{$language['image']}" : 
						"language/{$language['code']}/{$language['code']}.png"; ?>" title="<?= $language['name']; ?>" />
				</a>
			</li>
			<?php $i++; } ?>
		</ul>
		<div class="tab-content">
			<div class="alert alert-info" data-mt="15" data-mb="0"><?= $text_twig_full; ?></div>
			<button data-mt="15" class="btn btn-sm btn-danger" type="button" id="template-backup" data-action="<?= $template_action; ?>"><?= $text_backup_email; ?></button>
			<?php $i = 0; foreach ($languages as $language) { ?>
			<div role="tabpanel" class="tab-pane<?= !$i ? ' active' : ''; ?>" id="template-<?= $i; ?>">
				<div class="form-group" data-mb="-15">
					<div class="inputer">
						<label><?= $text_subject; ?></label>
						<input type="text" name="losted_cart_settings[email][subject][<?= $language['language_id']; ?>]" value="<?= $settings['email']['subject'][$language['language_id']]; ?>">	
					</div>
				</div>
				<div class="form-group" data-mb="-15">
					<div class="inputer">
						<label><?= $text_email_title; ?></label>
						<input type="text" name="losted_cart_settings[email][title][<?= $language['language_id']; ?>]" value="<?= $settings['email']['title'][$language['language_id']]; ?>">	
					</div>
				</div>										
				<div class="code-mirror-wrap">
					<textarea class="<?php /* summernote */ ?>code-mirror" name="losted_cart_settings[email][template][<?= $language['language_id']; ?>]"><?= $settings['email']['template'][$language['language_id']]; ?></textarea>
				</div>
			</div>
			<?php $i++; } ?> 
		</div>	
	</div>
</div>

