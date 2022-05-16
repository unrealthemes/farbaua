<?php
class ControllerExtensionModuleProductCategorytabs extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/product_categorytabs');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('product_categorytabs', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_width_banner'] = $this->language->get('entry_width_banner');
		$data['entry_height_banner'] = $this->language->get('entry_height_banner');
		$data['entry_status_banner'] = $this->language->get('entry_status_banner');
		$data['entry_position_banner'] = $this->language->get('entry_position_banner');
		$data['text_left'] = $this->language->get('text_left');
		$data['text_right'] = $this->language->get('text_right');
		$data['entry_banner'] = $this->language->get('entry_banner');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_link_image'] = $this->language->get('entry_link_image');
		$data['entry_image_open_popup'] = $this->language->get('entry_image_open_popup');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['entry_featured_product'] = $this->language->get('entry_featured_product');
		$data['entry_show_only_featured_product'] = $this->language->get('entry_show_only_featured_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/product_categorytabs', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/product_categorytabs', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/product_categorytabs', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/product_categorytabs', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('catalog/category');

		$filter_data_categories = array(
			'sort'  => 'name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => 999999
		);	
		$data['categories'] = array();

		foreach ($this->model_catalog_category->getCategories($filter_data_categories) as $category) {
			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name']
			);
		}	
		
		if (isset($this->request->post['category_id'])) {
			$data['category_id'] = $this->request->post['category_id'];
		} elseif (!empty($module_info)) {
			$data['category_id'] = $module_info['category_id'];
		} else {
			$data['category_id'] = '';
		}
		
		$this->load->model('catalog/product');

		$data['products'] = array();

		if (isset($this->request->post['product'])) {
			$products = $this->request->post['product'];
		} elseif (!empty($module_info['product'])) {
			$products = $module_info['product'];
		} else {
			$products = array();
		}
		
			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$data['products'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
		
		
		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
		);

		if ($this->config->get('config_review_status')) {
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
			);
		}

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
		);
				
		if (isset($this->request->post['sorts_product'])) {
			$data['sorts_product'] = $this->request->post['sorts_product'];
		} elseif (!empty($module_info)) {
			$data['sorts_product'] = $module_info['sorts_product'];
		} else {
			$data['sorts_product'] = '';
		}
		
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		$this->load->model('tool/image');
		
		
		
		if (isset($this->request->post['banner_to_category'])) {			
			$data['banner_to_category'] = $this->request->post['banner_to_category'];
		} elseif (!empty($module_info)) {
			$data['banner_to_category'] = $module_info['banner_to_category'];
		} else {
			$data['banner_to_category'] = $this->config->get('banner_to_category');
		}
		
		if (isset($this->request->post['banner_to_category']) && is_file(DIR_IMAGE . $this->request->post['banner_to_category'])) {
			$data['banner_product_to_category'] = $this->model_tool_image->resize($this->request->post['banner_to_category'], 100, 100);
		} elseif ($data['banner_to_category'] && is_file(DIR_IMAGE . $data['banner_to_category'])) {
			$data['banner_product_to_category'] = $this->model_tool_image->resize($data['banner_to_category'], 100, 100);
		} else {
			$data['banner_product_to_category'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (isset($this->request->post['width_banner'])) {
			$data['width_banner'] = $this->request->post['width_banner'];
		} elseif (!empty($module_info)) {
			$data['width_banner'] = $module_info['width_banner'];
		} else {
			$data['width_banner'] = 200;
		}

		if (isset($this->request->post['height_banner'])) {
			$data['height_banner'] = $this->request->post['height_banner'];
		} elseif (!empty($module_info)) {
			$data['height_banner'] = $module_info['height_banner'];
		} else {
			$data['height_banner'] = 200;
		}
		if (isset($this->request->post['link_image'])) {
			$data['link_image'] = $this->request->post['link_image'];
		} elseif (!empty($module_info)) {
			$data['link_image'] = $module_info['link_image'];
		} else {
			$data['link_image'] = '';
		}
		if (isset($this->request->post['link_image_open_popup'])) {
			$data['link_image_open_popup'] = $this->request->post['link_image_open_popup'];
		} elseif (!empty($module_info)) {
			$data['link_image_open_popup'] = $module_info['link_image_open_popup'];
		} else {
			$data['link_image_open_popup'] = '';
		}
		if (isset($this->request->post['show_only_featured_product'])) {
			$data['show_only_featured_product'] = $this->request->post['show_only_featured_product'];
		} elseif (!empty($module_info)) {
			$data['show_only_featured_product'] = $module_info['show_only_featured_product'];
		} else {
			$data['show_only_featured_product'] = '';
		}
		if (isset($this->request->post['status_banner'])) {
			$data['status_banner'] = $this->request->post['height_banner'];
		} elseif (!empty($module_info)) {
			$data['status_banner'] = $module_info['status_banner'];
		} else {
			$data['status_banner'] = '';
		}
		if (isset($this->request->post['position_banner'])) {
			$data['position_banner'] = $this->request->post['position_banner'];
		} elseif (!empty($module_info)) {
			$data['position_banner'] = $module_info['position_banner'];
		} else {
			$data['position_banner'] = '';
		}
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/product_categorytabs', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/product_categorytabs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('extension/module/productcategorytabs');
		

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			if (isset($this->request->get['filter_category'])) {
				$filter_category = $this->request->get['filter_category'];
				$filter_sub_category = $this->request->get['filter_category'];
			} else {
				$filter_category = '';
				$filter_sub_category = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}
			$limit = 10;

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_category'  => $filter_category,
				'filter_sub_category'  => $filter_sub_category,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_extension_module_productcategorytabs->getProducts($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],										
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}