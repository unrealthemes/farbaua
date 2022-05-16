<?php
class ControllerExtensionModuleProductTabs extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('extension/module/product_tabs');
		$this->model_extension_module_product_tabs->installDB();	
		$this->load->language('extension/module/product_tabs');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');
		
		$data['token'] = $this->session->data['user_token'];

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_product_tabs', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['tab_setting'] = $this->language->get('tab_setting');

		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
	
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/product_tabs', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/module/product_tabs', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_product_tabs_status'])) {
			$data['module_product_tabs_status'] = $this->request->post['module_product_tabs_status'];
		} else {
			$data['module_product_tabs_status'] = $this->config->get('module_product_tabs_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/product_tabs', $data));
	}

	

  public function uninstall() {
	$this->load->model('extension/module/product_tabs');
	$this->model_extension_module_product_tabs->deleteDB();
  }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/product_tabs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
