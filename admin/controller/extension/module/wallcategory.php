<?php
class ControllerExtensionModuleWallcategory extends Controller {
	private $error = array();

	public function index() {
		
		$this->load->language('extension/module/wallcategory');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('wallcategory', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title_name'] = $this->language->get('entry_title_name');
		$data['entry_banner'] = $this->language->get('entry_banner');
		$data['setting_sub_cat_limit'] = $this->language->get('setting_sub_cat_limit');
		$data['setting_column_limit'] = $this->language->get('setting_column_limit');
		$data['setting_column_limit_child'] = $this->language->get('setting_column_limit_child');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['text_category'] = $this->language->get('text_category');
		$data['entry_manufactures'] = $this->language->get('entry_manufactures');
		$data['text_manufactures'] = $this->language->get('text_manufactures');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['button_banner_add'] = $this->language->get('button_banner_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['title_wallcategory_btn'] = $this->language->get('title_wallcategory_btn');
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
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
				'href' => $this->url->link('extension/module/wallcategory', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/wallcategory', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/wallcategory', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/wallcategory', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$data['href_wallcategory'] = $this->url->link('design/wallcategory', 'user_token=' . $this->session->data['user_token'], 'SSL');

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
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = '10';
		}
		
		if (isset($this->request->post['title_name'])) {
			$data['title_name'] = $this->request->post['title_name'];
		} elseif (!empty($module_info['title_name'])) {
			$data['title_name'] = $module_info['title_name'];
		} else {
			$data['title_name'] = '';
		}
		
		
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		
		$data['categories'] = array();		
		$categories_results = $this->model_catalog_category->getCategories(array('start'=>0,'limit'=>99999,'sort'=>'name'));
		foreach ($categories_results as $result) {
			$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => addslashes($result['name'])
			);
		}	
		
		$data['manufacturers_list'] = array();		
		$results = $this->model_catalog_manufacturer->getManufacturers(array('start'=>0,'limit'=>99999,'sort'=>'name'));
		foreach ($results as $result) {
				$data['manufacturers_list'][] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name'        => addslashes($result['name'])
				);
			}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		
		if (isset($this->request->post['wall_category'])) {
			$data['wall_category'] = $this->request->post['wall_category'];
		} elseif (!empty($module_info['wall_category'])) {
			$data['wall_category'] = $module_info['wall_category'];
		} else {
			$data['wall_category'] = array();
		}
		
		if (!empty($data['wall_category'])){
			foreach ($data['wall_category'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			} 
			array_multisort($sort_order, SORT_ASC, $data['wall_category']);
		}
		$data['wall_categorys'] = array();

		foreach ($data['wall_category'] as $value) {
			if (is_file(DIR_IMAGE . $value['image'])) {
				$image = $value['image'];
				$thumb = $value['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['wall_categorys'][] = array(
				'category'    => $value['category'],
				'image'       => $image,
				'sort_order'  => $value['sort_order'],
				'thumb'       => $this->model_tool_image->resize($thumb, 100, 100),
			);
		}

		if (isset($this->request->post['wall_manufactures'])) {
			$data['wall_manufactures'] = $this->request->post['wall_manufactures'];
		} elseif (!empty($module_info['wall_manufactures'])) {
			$data['wall_manufactures'] = $module_info['wall_manufactures'];
		} else {
			$data['wall_manufactures'] = array();
		}
		
		if (!empty($data['wall_manufactures'])){
			foreach ($data['wall_manufactures'] as $key => $value) {
				$sort_order_manufacturer[$key] = $value['sort_order'];
			} 
			array_multisort($sort_order_manufacturer, SORT_ASC, $data['wall_manufactures']);
		}
		$data['wall_manufactures_list'] = array();
		
		foreach ($data['wall_manufactures'] as $value) {
			if (is_file(DIR_IMAGE . $value['image'])) {
				$image = $value['image'];
				$thumb = $value['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['wall_manufactures_list'][] = array(
				'manufacturer_id'      => $value['manufacturer_id'],
				'image'         => $image,
				'sort_order'    => $value['sort_order'],
				'thumb'         => $this->model_tool_image->resize($thumb, 100, 100),
			);
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info['width'])) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 150;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info['height'])) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 150;
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/wallcategory', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/wallcategory')) {
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
}