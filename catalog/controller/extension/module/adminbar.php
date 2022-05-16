<?php
class ControllerExtensionModuleAdminBar extends Controller {
	private $version = "3.0.2";

	public function template(&$route, &$data, &$output) {
		$adminbar_config = $this->config->get('module_admin_bar');
		//print_r($adminbar_config);
		if (isset($adminbar_config['status']) && $adminbar_config['status']) {
			if ($this->registry->get('templates')) {
				$templates = $this->registry->get('templates');
			} else {
				$templates = array();
			}
			$templates[] = $route;
			$this->registry->set('templates',$templates);
		}
	}
	
	public function index(&$route, &$data, &$output) {
		$adminbar_config = $this->config->get('module_admin_bar');
	
		if ($adminbar_config) {
			$adminbar_status = $adminbar_config['status'];
		} else {
			$adminbar_status = false;
		}
		if ($adminbar_status) {
			$this->user = new Cart\User($this->registry);
			$adminLogged = $this->user->isLogged();
		} else {
			return '';
		}
		if (!$adminLogged) {
			return '';
		}
		$data_ab =	$this->load->language('extension/module/adminbar');
		$data_ab['adminbar'] = array();
		$data_ab['version'] = $adminbar_config['version'];

//		$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
//		$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		$this->document->addScript('catalog/view/javascript/adminbar/adminbar.js');
		$stylesheet = 'catalog/view/theme/default/stylesheet/admin-bar.css';
		$this->document->addStyle($stylesheet);

		$data_ab['adminbar']['user_token'] = $this->session->data['user_token'];

		$data_ab['adminbar']['admin_path'] = $adminbar_config['path'];
		$data_ab['adminbar']['custom_links'] = $adminbar_config['custom_link'];
		$data_ab['adminbar']['popup_width'] = $adminbar_config['popup_width']? (int)$adminbar_config['popup_width']:'600';
		$language_id = $this->config->get('config_language_id');
		$layout_id = 0;
		$data_ab['adminbar_info']	= array();
		$data_ab['adminbar']['prod_id'] = 0;
		if (isset($this->request->get['product_id']) && $this->user->hasPermission('modify', 'catalog/product')) {
			$data_ab['adminbar']['prod_id'] = $this->request->get['product_id'];
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
			
			$data_ab['adminbar']['product_field'] = $adminbar_config['product'];
			$data_ab['adminbar']['product_info']  = $product_info;			
			$data_ab['adminbar']['edit_href'] =  $data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] .
								'&amp;table=product';
			
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);	
			$sql = "SELECT keyword 
			FROM " . DB_PREFIX . "seo_url 
			WHERE query = 'product_id=" . (int)$this->request->get['product_id'] . "' 
			AND language_id = '" . $this->config->get('config_language_id') . "'
			AND store_id = '" . $this->config->get('config_store_id') . "'
			LIMIT 1";
			$result_url = $this->db->query($sql);
			$keyword = isset($result_url->row['keyword'])?$result_url->row['keyword']:'';
			$data_ab['adminbar_info'] = array(
				'meta_title' => array(
					'value' => 	$product_info['meta_title'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=meta_title' . 
								'&amp;language_id=' . $language_id
				),
				'meta_description' => array(
					'value' => 	$product_info['meta_description'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=meta_description' .
								'&amp;language_id=' . $language_id
				),
				'meta_h1' => isset($product_info['meta_h1'])? array(
					'value' =>	$product_info['meta_h1'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=meta_h1' .
								'&amp;language_id=' . $language_id
				): false,
				'name' => array(
					'value' => 	$product_info['name'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=name' .
								'&amp;language_id=' . $language_id
				), 
				'tag' => array(
					'value' => 	$product_info['tag'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=tag' .
								'&amp;language_id=' . $language_id
				), 
				'price' =>  array(
					'value' => 	$product_info['price'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=price'
				),
				'quantity' =>   array(
					'value' => 	$product_info['quantity'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=quantity'
				),
				'seo_url' => array(
					'value' =>	$keyword,
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editproduct&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;product_id=' . $data_ab['adminbar']['prod_id'] . 
								'&amp;field=keyword' .
								'&amp;language_id=' . $this->config->get('config_language_id') . 
								'&amp;store_id=' . $this->config->get('config_store_id_id') . 
								'&amp;table=seo_url'
				),
			);
		}

		$data_ab['adminbar']['cleaer_system']['cache'] = array(
			'href' => $data_ab['adminbar']['admin_path'] . 'index.php?route=extension/module/adminbar/clearcache&amp;user_token=' .$data_ab['adminbar']['user_token'] . '&amp;type=system',
			'value' => $this->language->get('text_clear_system_cache'),
			'icon' => 'fa fa-files-o'
		);
		$data_ab['adminbar']['cleaer_system']['image'] = array(
			'href' => $data_ab['adminbar']['admin_path'] . 'index.php?route=extension/module/adminbar/clearcache&amp;user_token=' .$data_ab['adminbar']['user_token'] . '&amp;type=image',
			'value' => $this->language->get('text_clear_image_cache'),
			'icon' => 'fa fa-picture-o'
		);
	
		$data_ab['adminbar']['cat_id'] = 0;
		if (isset($this->request->get['path']) && !isset($this->request->get['product_id']) && $this->user->hasPermission('modify', 'catalog/category')) {
			$parts = explode('_', (string)$this->request->get['path']);
			$data_ab['adminbar']['cat_id'] = (int)array_pop($parts);
			$this->load->model('catalog/category');
			$category_info = $this->model_catalog_category->getCategory($data_ab['adminbar']['cat_id']);
			$layout_id = $this->model_catalog_category->getCategoryLayoutId($data_ab['adminbar']['cat_id']);	
			$data_ab['adminbar_info'] = array(
				'meta_title' => array(
					'value' => 	$category_info['meta_title'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editcategory&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;category_id=' . $data_ab['adminbar']['cat_id'] . 
								'&amp;field=meta_title' . 
								'&amp;language_id=' . $language_id
				),
				'meta_description' => array(
					'value' => 	$category_info['meta_description'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editcategory&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;category_id=' . $data_ab['adminbar']['cat_id'] . 
								'&amp;field=meta_description' .
								'&amp;language_id=' . $language_id
				),
				'meta_h1' => isset($category_info['meta_h1'])? array(
					'value' =>	$category_info['meta_h1'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editcategory&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;category_id=' . $data_ab['adminbar']['cat_id'] . 
								'&amp;field=meta_h1' .
								'&amp;language_id=' . $language_id
				):false,
				'name' => array(
					'value' => 	$category_info['name'],
					'href'  =>	$data_ab['adminbar']['admin_path'] . 
								'index.php?route=extension/module/adminbar/editcategory&amp;user_token=' .$data_ab['adminbar']['user_token'] . 
								'&amp;category_id=' . $data_ab['adminbar']['cat_id'] . 
								'&amp;field=name' .
								'&amp;language_id=' . $language_id
				),
			);
		}
		$data_ab['adminbar']['information_id'] = 0;
		if (isset($this->request->get['information_id']) && $this->user->hasPermission('modify', 'catalog/information') ) {
			$data_ab['adminbar']['information_id'] = $this->request->get['information_id'];
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}
		$data_ab['adminbar']['manufacturer_id'] = 0;
		if (isset($this->request->get['manufacturer_id']) && $this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$data_ab['adminbar']['manufacturer_id'] = $this->request->get['manufacturer_id'];
		}
		$data_ab['adminbar']['new_review_result'] = 0;
		if ($this->user->hasPermission('access', 'catalog/review')) {
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "review WHERE status = 0";
			$result = $this->db->query($sql);
			$data_ab['adminbar']['new_review_result'] = $result->row['total'];
		}
		$data_ab['adminbar']['new_order'] = 0;
		$data_ab['adminbar']['new_order_result'] = false;
		if ($adminbar_config['order'] && $adminbar_config['neworder']) {
			if (is_array($adminbar_config['neworder'])) {
				$sql = "SELECT COUNT(*) as total, o.order_status_id as status_id, os.name 
					FROM `" . DB_PREFIX . "order` o
					JOIN " . DB_PREFIX . "order_status os ON o.order_status_id = os.order_status_id AND os.language_id = '" . (int)$language_id . "'
					WHERE o.order_status_id IN (" . $this->db->escape(implode(',',$adminbar_config['neworder'])) . ")
					GROUP BY o.order_status_id";
				$result = $this->db->query($sql);
	
				$data_ab['adminbar']['new_order_result'] = $result->rows;
				$data_ab['adminbar']['new_order'] = $adminbar_config['neworder'];
			} 
		}
			if (!$layout_id) {
				$this->load->model('design/layout');
				if (isset($this->request->get['route'])) {
					$route = (string)$this->request->get['route'];
				} else {
					$route = 'common/home';
				}
				$layout_id = $this->model_design_layout->getLayout($route);
			}
			if (!$layout_id) {
				$layout_id = $this->config->get('config_layout_id');
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "' ORDER BY sort_order";
			$modules = $this->db->query($sql);
			$data_ab['menu'] = array();
			$data_ab['menu']['modules'] = array();
			foreach ($modules->rows as $module) {
				if ($module['code']) {
					$url_module = explode('.',$module['code']);
					$module_id = '';
					if (isset($url_module[1])) {
						$module_id = '&amp;module_id=' . $url_module[1];
						$this->load->model('setting/module');		
						$setting_info = $this->model_setting_module->getModule($url_module[1]);
						if (isset($setting_info['name']) && $setting_info['name']) {
							$name_module = $url_module[0] . '&gt;&gt;' .  $setting_info['name'];
						} else {
							$name_module = $url_module[0] . '&gt;&gt;' .  $url_module[1];
						}
					} else {
							$name_module = $url_module[0];
					}
					if ($this->user->hasPermission('access', 'extension/module/' . $url_module[0])) {
						$data_ab['menu']['modules'][] = array(
							'href'=> $data_ab['adminbar']['admin_path'] . 'index.php?route=extension/module/' . $url_module[0] . '&amp;user_token=' .$data_ab['adminbar']['user_token'] . $module_id,
							'name' => $name_module,
							'icon' =>false
						); 
					}
				}
			}
			
			$data_ab['menu']['catalog'] = array();
			$data_ab['menu']['catalog'][] = array(
				'name' => $this->language->get('dashboard'),
				'href' => $data_ab['adminbar']['admin_path'] . 'index.php?route=common/dashboard&amp;user_token=' . $data_ab['adminbar']['user_token'],
				'icon' => 'fa fa-dashboard fa-fw'
			);
			if ($this->user->hasPermission('access', 'catalog/category')) {
				$data_ab['menu']['catalog'][] = array(
					'name' => $this->language->get('category'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/category&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-folder-o'
				);
			}
			if ($this->user->hasPermission('access', 'catalog/product')) {
				$data_ab['menu']['catalog'][] = array(
					'name' => $this->language->get('products'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/product&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-list'
				);
			}
			if ($this->user->hasPermission('access', 'catalog/information')) {
				$data_ab['menu']['catalog'][] = array(
					'name' => $this->language->get('information'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/information&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-align-justify'
				);
			}
			if ($this->user->hasPermission('access', 'catalog/review')) {
				$data_ab['menu']['catalog'][] = array(
					'name' => $this->language->get('reviews'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/review&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-comment-o'
				);
			}
			if ($this->user->hasPermission('access', 'marketplace/extension')) {
				$data_ab['menu']['catalog'][] = array(
					'name' => $this->language->get('modules'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=marketplace/extension&amp;type=module&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-puzzle-piece'
				);
			}
			$data_ab['menu']['add'] = array();
			if ($this->user->hasPermission('modify', 'catalog/category')) {
				$data_ab['menu']['add'][] = array(
					'name' => $this->language->get('category'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/category/add&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-folder-o'
				);
			}
			if ($this->user->hasPermission('modify', 'catalog/product')) {
				$data_ab['menu']['add'][] = array(
					'name' => $this->language->get('products'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/product/add&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-list'
				);
			}
			if ($this->user->hasPermission('modify', 'catalog/manufacturer')) {
				$data_ab['menu']['add'][] = array(
					'name' => $this->language->get('manufacturer'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/manufacturer/add&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-bars'
				);
			}
			if ($this->user->hasPermission('modify', 'catalog/information')) {
				$data_ab['menu']['add'][] = array(
					'name' => $this->language->get('information'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=catalog/information/add&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-align-justify'
				);
			}
			if ($this->user->hasPermission('access', 'tool/log')) {
				$data_ab['menu']['log'] = array(
					'name' => $this->language->get('view_err'),
					'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=tool/log&amp;user_token=' . $data_ab['adminbar']['user_token'],
					'icon' => 'fa fa-align-justify'
				);
			}
			$data_ab['menu']['logout'] = array(
				'name' => $this->language->get('logout'),
				'href' =>$data_ab['adminbar']['admin_path'] . 'index.php?route=common/logout&amp;user_token=' . $data_ab['adminbar']['user_token'],
				'icon' => 'fa fa-sign-out'
			);
		
			$result = $this->load->view('extension/module/adminbar', $data_ab);
			$output = str_replace('</body>', $result . "\n" . '</body>',$output);
	}
}