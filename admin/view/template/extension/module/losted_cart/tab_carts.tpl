<div id="carts-content">
	<?php if (empty($carts)) { ?> <div class="alert alert-success" data-mt="15"><b><?= $text_cart_good; ?></b></div> <?php } ?>

	<?php foreach ($carts as $cart) { ?>
	<div class="cart-item">
		<header>
			<table>
				<tr>
					<td class="cart-id text-center"><span>#</span><?= $cart['losted_id']; ?></td>
					<td class="date">
						<span><b><?= $text_create; ?></b><?= $cart['date_added']; ?></span>
					</td>
					<td class="info">						
						<?php if (!empty($cart['firstname']) || !empty($cart['lastname'])) { ?>
							<span><i class="fa fa-user"></i> <?= $cart['customer_link'] ? 
								"<a target=\"_blank\" href='{$cart['customer_link']}'>{$cart['firstname']} {$cart['lastname']}</a>" : $cart['firstname'] .' '. $cart['lastname']; ?></span>
						<?php } else { ?>
							<span class="none-data" title="<?= $text_not_found; ?>" data-toggle="tooltip" data-placement="top">
								<i class="fa fa-user"></i></span>						
						<?php } ?>
						
						<?php if ($cart['email']) { ?>
							<span><i class="fa fa-envelope"></i> <?= $cart['email']; ?></span> 
						<?php } else { ?>
							<span class="none-data" title="<?= $text_not_found; ?>" data-toggle="tooltip" data-placement="top">
								<i class="fa fa-envelope"></i></span>
						<?php } ?>

						<?php if ($cart['telephone']) { ?>
							<span><i class="fa fa-phone-square"></i> <?= $cart['telephone']; ?></span>
						<?php } else { ?>
							<span class="none-data" title="<?= $text_not_found; ?>" data-toggle="tooltip" data-placement="top">
								<i class="fa fa-phone-square"></i></span>
						<?php } ?>
					</td>
					<td class="total">
						<span><b><?= $text_in_cart; ?></b><?= $cart['cart']['quantity']; ?></span>
						<span><b><?= $text_total; ?></b> <?= $cart['cart']['total']; ?></span>
					</td>	
					<td class="msg">
						<?php $email_total = count($cart['logs']); ?>
						<?php if ($email_total) { ?>
							<div class="alert alert-success"> <?= $text_send_email; ?> (<?= $email_total; ?>)</div>
						<?php } else { ?>
							<div class="alert alert-danger"> <?= $text_send_none_email; ?> </div>
						<?php } ?>
					</td>
					<td class="text-right">
						<button type="button" class="btn btn-sm btn-default cart-full-info"> <i class="fa fa-ellipsis-h"></i> </button>											
					</td>																							
				</tr>
			</table>			
		</header>
		<div class="full-info">
			<div class="row">
				<div class="col-sm-5">
					<h5 class="title-listing"><?= $text_cart; ?></h5>
					<table class="table product-info">
						<tr>
							<th style="width:40%" colspan="2"><?= $text_product; ?></th>
							<th><?= $text_model; ?></th>
							<th class="text-center" style="width: 80px;"><?= $text_quantity; ?></th>
							<th><?= $text_price; ?></th>
							<th><?= str_replace(':', '', $text_total); ?></th>
							<th style="width:150px"></th>
						</tr>
						<?php foreach ($cart['cart']['products'] as $product) { ?>
						<tr>
							<td><img src="<?= $product['image']; ?>"></td>
							<td class="name">
								<?= $product['name']; ?>
								<?php if (!empty($product['option'])) { ?>
								<ul>
								<?php foreach ($product['option'] as $option) { ?>
									<li><?= $option['name'] . ': ' . $option['value'] .' ('. $option['price_prefix'] . $option['price'] . ')'; ?></li>
								<?php } ?>
								</ul>
								<?php } ?>
							</td>
							<td><?= $product['model']; ?></td>
							<td class="text-center">
								<?= $product['quantity']; ?>
								<input type="hidden" name="quantity" value="<?= $product['quantity']; ?>" class="form-control">
							</td>
							<td><?= $product['price']; ?></td>
							<td><?= $product['total']; ?></td>
							<td class="text-right">
								<div class="btn-group">
									<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="<?= $text_in_store; ?>" target="_blank" href="<?= $product['view']; ?>"><i class="fa fa-globe"></i></a>
									<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="<?= $text_in_admin; ?>" target="_blank" href="<?= $product['edit']; ?>"><i class="fa fa-shopping-cart"></i></a>
									<?php if ($validation) { ?>
									<button type="button" class="btn btn-sm btn-success edit-product" disabled data-toggle="tooltip" data-placement="top" title="<?= $text_edit_product; ?>"><i class="fa fa-pencil"></i></button>
									<button type="button" class="btn btn-sm btn-danger remove-product" title="<?= $text_remove; ?>" data-action="<?= $remove_action; ?>&cart_id=<?= $product['cart_id']; ?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-close"></i></button>
									<?php } ?>
								</div>
							</td>
						</tr>
						<?php } ?>
						<?php if ($validation) { ?>
						<tr class="tfooter">
							<td colspan="7">
								<button class="btn btn-default btn-sm" title="<?= $text_add_product; ?>" disabled data-toggle="tooltip" data-placement="top" type="button" onclick="addProduct(this);"><i class="fa fa-plus"></i></button>
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
				<div class="col-sm-4">
					<h5 class="title-listing"><?= $text_other_info; ?></h5>
					<div class="sending-log">
						<table class="table table-striped table-hover tab-other-info">
							<tr>
								<td style="width: 30%"><b><?= $text_store; ?></b></td>
								<td><a target="_blank" href="<?= $cart['store']['url']; ?>"><?= $cart['store']['name']; ?></a></td>
							</tr>													
							<tr>
								<td><b><?= $text_language; ?></b></td>
								<td>
									<?php foreach ($languages as $language) {
										if ($language['language_id'] == $cart['language_id']) {
											echo $language['name'];
											break;
										}
									} ?>
								</td>
							</tr>
							<tr>
								<td><b><?= $text_currency; ?></b></td>
								<td><?= $cart['currency_code']; ?></td>
							</tr>
							<tr>
								<td><b><?= $text_customer_group; ?></b></td>
								<td><?= $cart['customer_group']; ?></td>
							</tr>
							<?php if ($validation) { ?>
							<tr>
								<td colspan="2">
									<button data-action="<?= $remove_action; ?>&losted_id=<?= $cart['losted_id']; ?>" type="button" class="btn btn-sm btn-danger cart-remove"> <i class="fa fa-trash"></i> <?= $text_remove_cart; ?> </button>
									<?php if ($cart['notification']) { ?>
										<button data-action="<?= $email_action; ?>&losted_id=<?= $cart['losted_id']; ?>" type="button" class="btn btn-sm btn-success notification-btn"> <i class="fa fa-paper-plane"></i> <?= $text_send_notification; ?> </button>		
									<?php } ?>								
									<button type="button" data-action="<?= $template_action; ?>&losted_id=<?= $cart['losted_id']; ?>" class="btn btn-sm btn-info template-btn"> <i class="fa fa-file-code-o"></i> <?= $text_show_template; ?> </button>
									<button type="button" disabled data-toggle="tooltip" data-placement="top" title="будет в версии 2.1.0" data-action="" class="btn btn-sm btn-default write-to-customer"> <i class="fa fa-pencil"></i> <?= $text_write_to_customer; ?> </button>
								</td>
							</tr>																					
							<?php } ?>
						</table>
					</div>
				</div>				
				<div class="col-sm-3">
					<h5 class="title-listing"><?= $text_log; ?></h5>
					<div class="sending-log">
						<table class="table table-striped table-hover">
							<?php $i = 0; foreach ($cart['logs'] as $log) { ?>
							<tr>
								<td style="width: 15px"><i class="fa fa-paper-plane"></i></td>
								<td><?= $log; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<?php } ?>

	<?php if (!empty($pagination)) { ?>
	<div id="paginagtion" class="row">
		<div class="col-sm-6"> <?= str_replace('pagination', 'pagination pagination-sm', $pagination); ?>	</div>
		<div class="col-sm-6 text-right"> <?= $results; ?> </div>
	</div>	
	<?php } ?>	
</div>