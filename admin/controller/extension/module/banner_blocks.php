<?php
class ControllerExtensionModuleBannerBlocks extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/banner_blocks');
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('banner_blocks', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			
			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_open_banner_popup'] = $this->language->get('text_open_banner_popup');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		
		

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
				'href' => $this->url->link('extension/module/banner_blocks', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/banner_blocks', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/banner_blocks', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/banner_blocks', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
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
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		$data['entry_banner_column'] = $this->language->get('entry_banner_column');
		if (isset($this->request->post['banner_column'])) {
			$data['banner_column'] = $this->request->post['banner_column'];
		} elseif (!empty($module_info['banner_column'])) {
			$data['banner_column'] = $module_info['banner_column'];
		} else {
			$data['banner_column'] = 3;
		}
		$this->load->model('tool/image');
		
		if (isset($this->request->post['banner_item'])) {
			$results = $this->request->post['banner_item'];
		} elseif (!empty($module_info) & isset($module_info['banner_item'])) {
			$results = $module_info['banner_item'];
		} else {
			$results = array();
		}

		$data['banner_items'] = array();
		
		foreach ($results as $result) {
		
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $result['image'];
				$thumb = $result['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}
			if(isset($result['popup'])){
				$result_popup = $result['popup'];
			} else {
				$result_popup = '0';
			}
			$data['banner_items'][] = array(
				'image' => $image,
				'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
				'title' => $result['title'],
				'description' => $result['description'],
				'link'  => $result['link'],
				'popup'  => $result_popup,
				'sort'  => $result['sort']
			);	
		} 
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/banner_blocks', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/banner_blocks')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		return !$this->error;
	}
}