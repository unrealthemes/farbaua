<?php
class ControllerExtensionModuleSHTML extends Controller {
	public function index($setting) {
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');

			return $this->load->view('extension/module/html', $data);
		}
	}
	
	public function getBlockData($args=array()) {
		$controller_ids=array();
		$this->load->model('extension/module/shtml');
		if(isset($this->request->get['product_id'])) {
			$controller_ids[]='p_'.$this->request->get['product_id'];			
		} 
		if(isset($this->request->get['path'])) {
			$path=explode('_', $this->request->get['path']);
			$category_id=array_pop($path);
			$controller_ids[]='c_'.$category_id;
		} 
		if(isset($this->request->get['manufacturer_id'])) {
			$controller_ids[]='m_'.$this->request->get['manufacturer_id'];
		}		
		if(isset($this->request->get['product_id'])) {
			if(empty($this->model_catalog_product)) $this->load->model('catalog/product');
			$product_categories=$this->model_catalog_product->getCategories($this->request->get['product_id']);
			$product_info=$this->model_catalog_product->getProduct($this->request->get['product_id']);
			$product_info['href']=$this->url->link('product/product', 'product_id='.$product_info['product_id'], true);
			foreach($product_categories as $product_category) {
				$controller_ids[]='pc_'.$product_category['category_id'];
				$product_info['product_categories'][] = $product_category['category_id'];
			}
			
			$product_manufacturer_id=$this->model_extension_module_shtml->getProductManufacturerId($this->request->get['product_id']);
			
			if($product_manufacturer_id) $controller_ids[]='pm_'.$product_manufacturer_id;
		}
		
		$layout_id=$this->model_extension_module_shtml->getLayoutId();
		$controller_ids[]='l_'.$layout_id;
		$controller_ids[]='l_all';		
		$modules=$this->model_extension_module_shtml->getModules($controller_ids);
		$filtered_modules=array();
		foreach($modules as $module_id=>$module) {
			if(empty($module['setting']['condition'])) {
				$filtered_modules[$module_id]=$module;
			} else {
				$module_controller_types=array();
				foreach($module['controllers'] as $controller) {
					$module_controller_types[]='['.explode('_', $controller)[0].']';
				}
				$condition=str_replace($module_controller_types, '1', $module['setting']['condition']);
				$condition=preg_replace('/[\[pmlcu\]]+/', '0', $condition);
				//debug
				//echo 'condition='.'return (bool) ('.$condition.')<br>';				
				$condition=preg_replace('/[^0-9\(\)\!\|\&]+/', '', $condition);
				//debug
				//echo 'condition='.'return (bool) ('.$condition.')<br>';
				$result=eval('return (bool) ('.$condition.');');
				if($result) {
					$filtered_modules[$module_id]=$module;
				}
			}
		}
		$htmls=array();
		$shtml_modules=array();	
		//var_export($filtered_modules);
		$data['preloader'] = '';
		foreach($filtered_modules as $module_id=>$filtered_module) {
			$htmls+=$filtered_module['blocks'];			
			if(!empty($filtered_module['setting']['preloader']) && !empty($filtered_module['setting']['preloader_enabled'])) {
				$data['preloader'] = html_entity_decode($filtered_module['setting']['preloader']);
				
			}
				//debug
				//$filter=$this->model_extension_module_shtml->getModuleFilter($module_id);
				//echo $this->model_extension_module_shtml->getProducts($filter).'<br>';								
					
			
		}
		$data['htmls']=json_encode($htmls, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		$data['shtml_modules']=json_encode($filtered_modules, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		if(isset($product_info)) {
			$data['product_info']=json_encode($product_info, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}
		
		return $data;
		
		/*
/ SHTML module
		$controller_ids=array();
		$this->load->model('extension/module/shtml');
		if(isset($this->request->get['product_id'])) {
			$controller_ids[]='p_'.$this->request->get['product_id'];			
		} else
		if(isset($this->request->get['path'])) {
			$path=explode('_', $this->request->get['path']);
			$category_id=array_pop($path);
			$controller_ids[]='c_'.$category_id;
		} else
		if(isset($this->request->get['manufacturer_id'])) {
			$controller_ids[]='m_'.$this->request->get['manufacturer_id'];
		}
		if(isset($this->request->get['product_id'])) {
			if(empty($this->model_catalog_product)) $this->load->model('catalog/product');
			$product_categories=$this->model_catalog_product->getCategories($this->request->get['product_id']);
			foreach($product_categories as $product_category) {
				$controller_ids[]='pc_'.$product_category['category_id'];
			}
			
			$product_manufacturer_id=$this->model_extension_module_shtml->getProductManufacturerId($this->request->get['product_id']);
			
			if($product_manufacturer_id) $controller_ids[]='pm_'.$product_manufacturer_id;
		}
		
		$layout_id=$this->model_extension_module_shtml->getLayoutId();
		$controller_ids[]='l_'.$layout_id;
		$controller_ids[]='l_all';
		$htmls=array();
		if(!empty($controller_ids)) {
			foreach($controller_ids as $controller_id) {
				$tmp=$this->model_extension_module_shtml->getControllers($controller_id);
				if(!empty($tmp)) {
					$htmls=$htmls + $tmp;
				}
			}
		}
		if(!empty($htmls)) {
			$data['htmls']=json_encode($htmls, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}
// End SHTML module		
		*/
	}
	
	public function getShtmlProducts($args=array()) {
		$this->load->language('extension/module/shtml');
		$json=array();
		$json['products']=array();
		$module_id=0;
		if(isset($args['module_id'])) {
			$module_id=$args['module_id'];
		} else {
			if(isset($this->request->request['module_id'])) {
				$module_id=$this->request->request['module_id'];
			}
		}
		$add_condition='';
		if(isset($args['add_condition'])) {
			$add_condition=$args['add_condition'];
		} else {
			if(isset($this->request->request['add_condition'])) {
				$add_condition=$this->request->request['add_condition'];
			}			
		}
		if(!$module_id && !$add_condition) $json['error']=$this->language->get('error_module_id');
		if(!isset($json['error'])) {			
			$this->load->model('extension/module/shtml');
			$this->load->model('tool/image');
			if($module_id) {
				$filter=$this->model_extension_module_shtml->getModuleFilter($module_id);
			}
			if($add_condition) {
				if(isset($filter['condition'])) {
					$filter['condition'].=' AND '.$add_condition;
				} else {
					$filter['condition']=$add_condition;
				}
				if(isset($this->request->request['limit'])) $filter['limit'] = $this->request->request['limit'];
				if(isset($this->request->request['sort'])) $filter['sort'] = $this->request->request['sort'];
				if(isset($this->request->request['order'])) $filter['order'] = $this->request->request['order'];
			}
			$json['products']=$this->model_extension_module_shtml->getProducts($filter);
			foreach($json['products'] as $i=>$product) {
				$json['products'][$i]['href']=$this->url->link('product/product', 'product_id='.$product['product_id'], true);
				$json['products'][$i]['price_tax']=$this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				$json['products'][$i]['price_formatted'] = $this->currency->format($product['price'], $this->session->data['currency']);
				$json['products'][$i]['price_tax_formatted'] = $this->currency->format($json['products'][$i]['price_tax'], $this->session->data['currency']);
				$json['products'][$i]['thumb'] = $this->model_tool_image->resize($product['image'], $this->config->get('theme_'.$this->config->get('config_theme').'_image_thumb_width'), $this->config->get('theme_'.$this->config->get('config_theme').'_image_thumb_height'));
				$json['products'][$i]['image_category'] = $this->model_tool_image->resize($product['image'], $this->config->get('theme_'.$this->config->get('config_theme').'_image_category_width'), $this->config->get('theme_'.$this->config->get('config_theme').'_image_category_height'));				
			}
		}
		if(!count($args)) {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));				
		} else {
			return $json;
		}
	
	}
}