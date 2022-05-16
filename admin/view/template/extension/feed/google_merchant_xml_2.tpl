<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-auto_seo_faq" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-google_merchant_xml_2" class="form-horizontal">
          <div class="help-link"><a href="https://support.google.com/merchants/answer/7052112?hl=ru" target="_blank"><?php echo $text_help_link; ?> Google</a></div>
		   <div class="help-link"><a href="https://www.facebook.com/business/help/125074381480892?id=725943027795860" target="_blank"><?php echo $text_help_link; ?> Facebook</a></div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="google_merchant_xml_2_status" id="input-google_merchant_xml_2_status" class="form-control">
                <?php if ($google_merchant_xml_2_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div> 
          </div>
		  <div class="form-group">
		  <label class="control-label col-sm-2" for="input-google_merchant_xml_2_currency"><?php echo $entry_google_merchant_xml_2_currency; ?></label>
		  <div class="col-sm-10">
			<select name="google_merchant_xml_2_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $google_merchant_xml_2_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_identifier"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="google_merchant_xml_2_identifier" id="input-google_merchant_xml_2_identifier" class="form-control">
                <?php if ($google_merchant_xml_2_identifier == 'product_id') { ?>
                <option value="product_id" selected="selected"><?php echo $text_id; ?></option>
                <option value="model"><?php echo $text_model; ?></option>
                <?php } elseif ($google_merchant_xml_2_identifier == 'model')  { ?>
                <option value="product_id"><?php echo $text_id; ?></option>
                <option value="model" selected="selected"><?php echo $text_model; ?></option>
                <?php } else { ?>
				<option value="product_id" selected="selected"><?php echo $text_id; ?></option>
                <option value="model"><?php echo $text_model; ?></option>
				<?php } ?>
              </select>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_key"><?php echo $entry_google_merchant_xml_2_key; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_key" value="<?php echo $google_merchant_xml_2_key; ?>" id="input-google_merchant_xml_2_key" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_google_merchant_xml_2_links; ?></label>
               <div class="col-sm-10">
               		<b><?php echo $text_feed_merchant; ?></b> <a href="<?php echo $link_merchant; ?><?php echo $google_merchant_xml_2_key ? '&key=' . $google_merchant_xml_2_key : ''; ?>" target="_blank"><b><?php echo $link_merchant; ?><?php echo $google_merchant_xml_2_key ? '&key=' . $google_merchant_xml_2_key : ''; ?></b></a><br>
					<b><?php echo $text_feed_facebook; ?></b> <a href="<?php echo $link_facebook; ?><?php echo $google_merchant_xml_2_key ? '&key=' . $google_merchant_xml_2_key : ''; ?>" target="_blank"><b><?php echo $link_facebook; ?><?php echo $google_merchant_xml_2_key ? '&key=' . $google_merchant_xml_2_key : ''; ?></b></a><br><br>
					<b>Cron Command:</b><br><b>php -f <?php echo $link_cron; ?></b><br><br> 
					<b><?php echo $text_feed_cron; ?></b><br>
					<a href="<?php echo $link_cron_merchant; ?>" target="_blank"><b><?php echo $link_cron_merchant; ?></b></a><br>
					<a href="<?php echo $link_cron_facebook; ?>" target="_blank"><b><?php echo $link_cron_facebook; ?></b></a><br>
					<br> 
					<b><?php echo $text_feed_help; ?></b>
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_condition"><?php echo $entry_google_merchant_xml_2_condition; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_condition" value="<?php echo $google_merchant_xml_2_condition; ?>" id="input-google_merchant_xml_2_condition" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_gtin"><?php echo $entry_google_merchant_xml_2_gtin; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_gtin" value="<?php echo $google_merchant_xml_2_gtin; ?>" id="input-google_merchant_xml_2_gtin" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_mpn"><?php echo $entry_google_merchant_xml_2_mpn; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_mpn" value="<?php echo $google_merchant_xml_2_mpn; ?>" id="input-google_merchant_xml_2_mpn" class="form-control" />
               </div>
			</div>
		  	<div class="form-group">
             <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?>
			 <br>
			 <a href="http://www.google.com/basepages/producttype/taxonomy-with-ids.ru-RU.xls" target="_blank"><?php echo $text_category_google; ?></a><br><br>
			 <a href="https://support.google.com/merchants/answer/6324436?hl=ru" target="_blank">google_product_category HELP</a><br>
			 <a href="https://support.google.com/merchants/answer/6324406?hl=ru" target="_blank">product_type HELP</a><br>
			 <a href="https://support.google.com/merchants/answer/6324469?hl=ru" target="_blank">condition HELP</a><br>
			 <a href="https://support.google.com/google-ads/answer/6275295?hl=ru" target="_blank"> custom_label HELP</a>
			 </label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="max-height: 400px; overflow: auto;">
                    <table class="table table-striped">
                    <?php foreach ($categories as $category) { ?>
                    <tr>
                      <td class="checkbox">
                        <label>
                          <?php if (in_array($category['category_id'], $google_merchant_xml_2_category)) { ?>
                          <input type="checkbox" name="google_merchant_xml_2_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                          <b><?php echo $category['name']; ?></b>
                          <?php } else { ?>
                          <input type="checkbox" name="google_merchant_xml_2_category[]" value="<?php echo $category['category_id']; ?>" />
                          <b><?php echo $category['name']; ?></b>
                          <?php } ?>
                        </label>
						<table class="table table-striped table-bordered">
							<tr>
								<td class="text-left">google_product_category <input type="text" name="google_merchant_xml_2_category_google_category[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_google_category[$category['category_id']]) ? $google_merchant_xml_2_category_google_category[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">product_type <input type="text" name="google_merchant_xml_2_category_product_type[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_product_type[$category['category_id']]) ? $google_merchant_xml_2_category_product_type[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">condition <input type="text" name="google_merchant_xml_2_category_condition[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_condition[$category['category_id']]) ? $google_merchant_xml_2_category_condition[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_0 <input type="text" name="google_merchant_xml_2_category_custom_label_0[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_custom_label_0[$category['category_id']]) ? $google_merchant_xml_2_category_custom_label_0[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_1 <input type="text" name="google_merchant_xml_2_category_custom_label_1[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_custom_label_1[$category['category_id']]) ? $google_merchant_xml_2_category_custom_label_1[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_2 <input type="text" name="google_merchant_xml_2_category_custom_label_2[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_custom_label_2[$category['category_id']]) ? $google_merchant_xml_2_category_custom_label_2[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_3 <input type="text" name="google_merchant_xml_2_category_custom_label_3[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_custom_label_3[$category['category_id']]) ? $google_merchant_xml_2_category_custom_label_3[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_4 <input type="text" name="google_merchant_xml_2_category_custom_label_4[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($google_merchant_xml_2_category_custom_label_4[$category['category_id']]) ? $google_merchant_xml_2_category_custom_label_4[$category['category_id']] : ''); ?>" class="form-control"/></td>
							</tr>
						</table>
                      </td>
                    </tr>
                    <?php } ?>
                    </table>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
		  <div class="form-group">
             <label class="col-sm-2 control-label" for="input-manufacturer"><?php echo $entry_manufacturer; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="max-height: 400px; overflow: auto;">
                    <table class="table table-striped">
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                    <tr>
                      <td class="checkbox">
                        <label>
                          <?php if (in_array($manufacturer['manufacturer_id'], $google_merchant_xml_2_manufacturer)) { ?>
                          <input type="checkbox" name="google_merchant_xml_2_manufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                          <?php echo $manufacturer['name']; ?>
                          <?php } else { ?>
                          <input type="checkbox" name="google_merchant_xml_2_manufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                          <?php echo $manufacturer['name']; ?>
                          <?php } ?>
                        </label>
                      </td>
                    </tr>
                    <?php } ?>
                    </table>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
		  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-google_merchant_xml_2_customer_group"><?php echo $entry_customer_group; ?></label>
				<div class="col-sm-10">
				<select name="google_merchant_xml_2_customer_group" id="input-google_merchant_xml_2_customer_group" class="form-control">
				<?php foreach ($customer_groups as $customer_group) { ?>
					<?php if ($customer_group['customer_group_id'] == $google_merchant_xml_2_customer_group) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
				<?php } ?>
				<?php } ?>
				</select>
			</div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_custom_sql"><?php echo $entry_google_merchant_xml_2_custom_sql; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_custom_sql" value="<?php echo $google_merchant_xml_2_custom_sql; ?>" id="input-google_merchant_xml_2_custom_sql" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_special"><?php echo $entry_google_merchant_xml_2_special; ?></label>
            <div class="col-sm-10">
              <select name="google_merchant_xml_2_special" id="input-google_merchant_xml_2_special" class="form-control">
                <?php if ($google_merchant_xml_2_special) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_min_price"><?php echo $entry_google_merchant_xml_2_min_price; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_min_price" value="<?php echo $google_merchant_xml_2_min_price; ?>" id="input-google_merchant_xml_2_min_price" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_max_price"><?php echo $entry_google_merchant_xml_2_max_price; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_max_price" value="<?php echo $google_merchant_xml_2_max_price; ?>" id="input-google_merchant_xml_2_max_price" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_zero_quantity"><?php echo $entry_google_merchant_xml_2_zero_quantity; ?></label>
            <div class="col-sm-10">
              <select name="google_merchant_xml_2_zero_quantity" id="input-google_merchant_xml_2_zero_quantity" class="form-control">
                <?php if ($google_merchant_xml_2_zero_quantity) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_original_description"><?php echo $entry_google_merchant_xml_2_original_description; ?></label>
            <div class="col-sm-10">
              <select name="google_merchant_xml_2_original_description" id="input-google_merchant_xml_2_original_description" class="form-control">
                <?php if ($google_merchant_xml_2_original_description) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_multiplier"><?php echo $entry_google_merchant_xml_2_multiplier; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_multiplier" value="<?php echo $google_merchant_xml_2_multiplier; ?>" id="input-google_merchant_xml_2_multiplier" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_original_image_status"><?php echo $entry_google_merchant_xml_2_original_image_status; ?></label>
            <div class="col-sm-10">
              <select name="google_merchant_xml_2_original_image_status" id="input-google_merchant_xml_2_original_image_status" class="form-control">
                <?php if ($google_merchant_xml_2_original_image_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_additional_images"><?php echo $entry_google_merchant_xml_2_additional_images; ?></label>
            <div class="col-sm-10">
              <select name="google_merchant_xml_2_additional_images" id="input-google_merchant_xml_2_additional_images" class="form-control">
                <?php if ($google_merchant_xml_2_additional_images) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-google_merchant_xml_2_utm"><?php echo $entry_google_merchant_xml_2_utm; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="google_merchant_xml_2_utm" value="<?php echo $google_merchant_xml_2_utm; ?>" id="input-google_merchant_xml_2_multiplier" class="form-control" />
               </div>
			</div>
		</form>
    </div>
  </div>
   <div class="credits"><?php echo $text_credits; ?></div>
</div>
</div>
<?php echo $footer; ?>