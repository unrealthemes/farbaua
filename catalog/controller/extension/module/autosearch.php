<?php 
class ControllerExtensionModuleAutosearch extends Controller { 
	private $error = array();
	public function ajaxLiveSearch() {
		$json = array();
		if(!empty($this->request->get['filter_name'])){
			$this->load->model('catalog/product');
			$this->load->model('extension/module/autosearch');
			$this->load->model('tool/image');
			$this->load->language('product/product');
			$filter_manufacturer = ($this->config->get('search_manufacturer_on_off')=='1') ? true : false;
			$filter_upc = ($this->config->get('search_upc_on_off')=='1') ? true : false;
			$filter_sku = ($this->config->get('search_sku_on_off')=='1') ? true : false;
			$filter_model = ($this->config->get('search_model_on_off')=='1') ? true : false;
			$filter_tag = ($this->config->get('search_tag_on_off')=='1') ? true : false;
			$filterdata=array(
				'filter_name' => $this->request->get['filter_name'],
				'filter_manufacturer' => $filter_manufacturer,
				'filter_upc' => $filter_upc,
				'filter_sku' => $filter_sku,
				'filter_model' => $filter_model,
				'filter_tag' => $filter_tag,
				'start' => 0,
				'limit' => 5,
			);
			$results = (array) $this->model_extension_module_autosearch->ajaxLiveSearch($filterdata);
			$this->load->language('ns/newstorelang');
			$text_autosearch_model = $this->language->get('text_autosearch_model');
			$text_autosearch_manufacturer = $this->language->get('text_autosearch_manufacturer');
			$text_autosearch_stock_status = $this->language->get('text_autosearch_stock_status');
								
			foreach($results as $result){
				$width = 100;
				$height = 100;
					if($this->config->get('image_search_width')!='' && $this->config->get('image_search_height')!=''){
						$width = $this->config->get('image_search_width');
						$height = $this->config->get('image_search_height');
					}
					if(!empty($result['image'])&&file_exists(DIR_IMAGE .$result['image'])){
						$image = $this->model_tool_image->resize($result['image'],$width,$height);
					}else if(file_exists(DIR_IMAGE .'data/logo.png')){
						$image = $this->model_tool_image->resize('data/logo.png',$width,$height);
					}else{	
						$image = $this->model_tool_image->resize('no_image.jpg',$width,$height);
					}
					if ($result['quantity'] <= 0) {
						$stock_result = $result['stock_status'];
					} else {
						$stock_result = $this->language->get('text_instock');
					}
					$name='';
					$model='';
					$manufacturer='';							
					$breakchars = array();
					$result['name'] = html_entity_decode ($result['name'], ENT_QUOTES, 'UTF-8');
					$this->request->get['filter_name'] = html_entity_decode ($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8');
					$name=str_ireplace($this->request->get['filter_name'],'<span class="highlight">'. htmlspecialchars(substr($result['name'],stripos($result['name'],$this->request->get['filter_name']),strlen($this->request->get['filter_name']))) .'</span>',$result['name']);
					
					$result['model'] = html_entity_decode ($result['model'], ENT_QUOTES, 'UTF-8');
					$model=str_ireplace($this->request->get['filter_name'],'<span class="highlight">'. htmlspecialchars(substr($result['model'],stripos($result['model'],$this->request->get['filter_name']),strlen($this->request->get['filter_name']))) .'</span>',$result['model']);
					
					$result['manufacturer'] = html_entity_decode($result['manufacturer'], ENT_QUOTES, 'UTF-8');
					$manufacturer=str_ireplace($this->request->get['filter_name'],'<span class="highlight">'. htmlspecialchars(substr($result['manufacturer'],stripos($result['manufacturer'],$this->request->get['filter_name']),strlen($this->request->get['filter_name']))) .'</span>',$result['manufacturer']);
				
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}
					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'];
					} else {
						$rating = false;
					}		
					$json[] = array(
						'product_id' 	=> $result['product_id'],
						'name' 			=> $name,								
						'name1' 		=> $result['name'],
						'model' 		=> ($this->config->get('display_model_on_off') =='1') ? $text_autosearch_model . $model: false ,
						'stock_status' 	=> ($this->config->get('display_stock_on_off') =='1') ? $text_autosearch_stock_status . $stock_result: false ,
						'image' 		=> ($this->config->get('display_image_on_off') =='1') ? $image: false ,
						'manufacturer' 	=> ($this->config->get('display_manufacturer_on_off') =='1') ? $text_autosearch_manufacturer . $manufacturer: false ,
						'price' 		=> ($this->config->get('display_price_on_off') =='1') ? $price: false ,
						'special' 		=> ($this->config->get('display_price_on_off') =='1') ? $special: false ,
						'rating' 		=> ($this->config->get('display_rating_on_off') =='1') ? $rating: false ,
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
						
					);
			}
		}
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>
