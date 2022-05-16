<?php
class ControllerExtensionModuleUt5setting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/ut5setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {	
			$store_id = 0;			
			$code = 'ut5setting';					
			$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '". $store_id ."' AND `code` = '". $code ."'");
		
			foreach ($this->request->post as $key => $value) {
					if (!is_array($value)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
					}	
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['entry_on_off_model_product'] = $this->language->get('entry_on_off_model_product');
		$data['entry_on_off_sku_product'] = $this->language->get('entry_on_off_sku_product');
		$data['entry_on_off_upc_product'] = $this->language->get('entry_on_off_upc_product');
		$data['entry_on_off_payment_method'] = $this->language->get('entry_on_off_payment_method');
		$data['entry_on_off_shipping_method'] = $this->language->get('entry_on_off_shipping_method');
		$data['entry_on_off_column_product'] = $this->language->get('entry_on_off_column_product');
		$data['entry_on_off_column_comment_manager'] = $this->language->get('entry_on_off_column_comment_manager');
		$data['entry_on_off_column_send_ttn_email'] = $this->language->get('entry_on_off_column_send_ttn_email');
		$data['entry_on_off_column_price_purchase'] = $this->language->get('entry_on_off_column_price_purchase');
		$data['entry_on_off_column_total_profit'] = $this->language->get('entry_on_off_column_total_profit');
		$data['entry_on_off_column_manager_process_orders'] = $this->language->get('entry_on_off_column_manager_process_orders');
		$data['entry_on_off_column_delivery_price'] = $this->language->get('entry_on_off_column_delivery_price');
		$data['entry_on_off_column_build'] = $this->language->get('entry_on_off_column_build');
		$data['entry_on_off_column_rise_product'] = $this->language->get('entry_on_off_column_rise_product');
		
		if (isset($this->request->post['config_on_off_model_product'])) {
			$data['config_on_off_model_product'] = $this->request->post['config_on_off_model_product'];
		} else {
			$data['config_on_off_model_product'] = $this->config->get('config_on_off_model_product');
		}
		if (isset($this->request->post['config_on_off_sku_product'])) {
			$data['config_on_off_sku_product'] = $this->request->post['config_on_off_sku_product'];
		} else {
			$data['config_on_off_sku_product'] = $this->config->get('config_on_off_sku_product');
		}
		if (isset($this->request->post['config_on_off_upc_product'])) {
			$data['config_on_off_upc_product'] = $this->request->post['config_on_off_upc_product'];
		} else {
			$data['config_on_off_upc_product'] = $this->config->get('config_on_off_upc_product');
		}
		if (isset($this->request->post['config_on_off_order_payment_method'])) {
			$data['config_on_off_order_payment_method'] = $this->request->post['config_on_off_order_payment_method'];
		} else {
			$data['config_on_off_order_payment_method'] = $this->config->get('config_on_off_order_payment_method');
		}
		if (isset($this->request->post['config_on_off_order_shipping_method'])) {
			$data['config_on_off_order_shipping_method'] = $this->request->post['config_on_off_order_shipping_method'];
		} else {
			$data['config_on_off_order_shipping_method'] = $this->config->get('config_on_off_order_shipping_method');
		}
		if (isset($this->request->post['config_on_off_column_product'])) {
			$data['config_on_off_column_product'] = $this->request->post['config_on_off_column_product'];
		} else {
			$data['config_on_off_column_product'] = $this->config->get('config_on_off_column_product');
		}
		if (isset($this->request->post['config_on_off_column_comment_manager'])) {
			$data['config_on_off_column_comment_manager'] = $this->request->post['config_on_off_column_comment_manager'];
		} else {
			$data['config_on_off_column_comment_manager'] = $this->config->get('config_on_off_column_comment_manager');
		}
		if (isset($this->request->post['config_on_off_column_send_ttn_email'])) {
			$data['config_on_off_column_send_ttn_email'] = $this->request->post['config_on_off_column_send_ttn_email'];
		} else {
			$data['config_on_off_column_send_ttn_email'] = $this->config->get('config_on_off_column_send_ttn_email');
		}
		if (isset($this->request->post['config_on_off_column_price_purchase'])) {
			$data['config_on_off_column_price_purchase'] = $this->request->post['config_on_off_column_price_purchase'];
		} else {
			$data['config_on_off_column_price_purchase'] = $this->config->get('config_on_off_column_price_purchase');
		}
		if (isset($this->request->post['config_on_off_column_total_profit'])) {
			$data['config_on_off_column_total_profit'] = $this->request->post['config_on_off_column_total_profit'];
		} else {
			$data['config_on_off_column_total_profit'] = $this->config->get('config_on_off_column_total_profit');
		}
		if (isset($this->request->post['config_on_off_column_manager_process_orders'])) {
			$data['config_on_off_column_manager_process_orders'] = $this->request->post['config_on_off_column_manager_process_orders'];
		} else {
			$data['config_on_off_column_manager_process_orders'] = $this->config->get('config_on_off_column_manager_process_orders');
		}
		
		if (isset($this->request->post['config_on_off_column_delivery_price'])) {
			$data['config_on_off_column_delivery_price'] = $this->request->post['config_on_off_column_delivery_price'];
		} else {
			$data['config_on_off_column_delivery_price'] = $this->config->get('config_on_off_column_delivery_price');
		}
		if (isset($this->request->post['config_on_off_column_build'])) {
			$data['config_on_off_column_build'] = $this->request->post['config_on_off_column_build'];
		} else {
			$data['config_on_off_column_build'] = $this->config->get('config_on_off_column_build');
		}
		if (isset($this->request->post['config_on_off_column_rise_product'])) {
			$data['config_on_off_column_rise_product'] = $this->request->post['config_on_off_column_rise_product'];
		} else {
			$data['config_on_off_column_rise_product'] = $this->config->get('config_on_off_column_rise_product');
		}
		
		if (isset($this->request->post['ttn_subject_ut'])) {
			$data['ttn_subject_ut'] = $this->request->post['ttn_subject_ut'];
		} else {
			$data['ttn_subject_ut'] = $this->config->get('ttn_subject_ut');
		}
		if (isset($this->request->post['ttn_description_ut'])) {
			$data['ttn_description_ut'] = $this->request->post['ttn_description_ut'];
		} else {
			$data['ttn_description_ut'] = $this->config->get('ttn_description_ut');
		}
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['ttn_subject_text'] = $this->language->get('ttn_subject_text');
		$data['ttn_subject_text_variables'] = $this->language->get('ttn_subject_text_variables');
		$data['entry_description_send_email_ttn'] = $this->language->get('entry_description_send_email_ttn');
		$data['it_supports_variable_or_html_tags'] = $this->language->get('it_supports_variable_or_html_tags');
		$data['ttn_subject_text_variables'] = $this->language->get('ttn_subject_text_variables');
		$data['list_of_variables_text'] = $this->language->get('list_of_variables_text');
		$data['list_of_variables_entry'] = $this->language->get('list_of_variables_entry');
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ut5setting', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ut5setting', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/ut5setting', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/ut5setting', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/ut5setting', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ut5setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}