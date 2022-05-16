<?php
/**
 * Losted Cart by Artem Pitov (c) 2018
 *
 * @author Artem Pitov <artempitov@gmail.com>
 * @link https://www.pitov.pro
 * @link https://opencartforum.com/user/674600
 * @see  https://opencartforum.com/files/file/5564
 *
 * @license Сommercial license
 */

namespace losted_cart;

class Controller extends \Controller
{
	const EMAIL_BACKUP_PATH = DIR_TEMPLATE .'extension'. DIRECTORY_SEPARATOR 
 		.'module'. DIRECTORY_SEPARATOR .'losted_cart'. DIRECTORY_SEPARATOR .'email.twig';

 	private $errors	= [];

 	public function __construct($registry) 
 	{
 		parent::__construct($registry);

 		$this->load->language('extension/module/losted_cart');

        $this->losted_cart = Core::getInstance($registry);
 		$this->wget        = md5(Core::getPrivateKey() . ($this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG));
 		$this->suffix      = version_compare(VERSION, '2.3', '>=') ? '' : '.tpl';

 		if (version_compare(VERSION, '3.0', '>=')) {
 			$this->token = 'user_token=' . $this->session->data['user_token'];  
 		} else {
 			$this->token = 'token=' . $this->session->data['token'];
 		}
 	}

 	public function index() 
 	{
		$this->load->model('setting/setting');

		$data = $this->language->all();
		
 		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validation() && $this->losted_cart) {
 			$this->model_setting_setting->editSetting('losted_cart', $this->request->post);

 			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->link('extension/module/losted_cart'));
 		}

 		$this->document->setTitle($this->language->get('heading_title_main'));

		foreach (['codemirror/codemirror', 'codemirror/xml', 'codemirror/fullscreen',
		 	'codemirror/formatting', 'main', 'confirm/jquery-confirm.min', 'summernote/summernote'] as $path
		) {
			$this->document->addScript("view/template/extension/module/losted_cart/js/{$path}.js");
		}

		foreach (['main', 'codemirror/codemirror', 'codemirror/theme/monokai', 
			'confirm/jquery-confirm.min', 'summernote/summernote'] as $path
		) {
			$this->document->addStyle("view/template/extension/module/losted_cart/style/{$path}.css");
		}

 		foreach(['setting/setting', 'localisation/language', 'customer/customer_group'] as $model) {
 			$this->load->model($model);
 		}

 		foreach ($this->errors as $key => $error) {
 			$data['error_' . $key] = $error;
 		} 		

 		if (isset($this->session->data['success'])) {
 			$data['success'] = $this->session->data['success'];
 		}

 		$data['text_cli'] = sprintf($this->language->get('text_cli'), 
 			$this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG, $this->wget);
 		
 		$data['action']     	 = $this->link('extension/module/losted_cart');
 		$data['cancel']     	 = $this->link('extension/extension', 'type=module');
 		$data['carts_action']    = $this->link('extension/module/losted_cart/getList');
 		$data['mesage_action']	 = $this->link('extension/module/losted_cart/sendMesage');
 		$data['template_action'] = $this->link('extension/module/losted_cart/getEmailTemplate');
 		$data['collector_api']   = str_replace('admin/', '', $this->url->link('tool/losted_cart_api/collector'));
 		
 		$data['email_template']  = file_get_contents(self::EMAIL_BACKUP_PATH);
 		$data['license_text']	 = nl2br(file_get_contents(str_replace('email.twig', 'license.txt', self::EMAIL_BACKUP_PATH)));
 		
 		$data['version']		 = Core::getVersion();
 		$data['site']		     = $this->request->server['SERVER_NAME'];

 		$data['license_key']	 = $this->config->get('losted_cart_license');
 		$data['license_status']  = $this->losted_cart && $this->losted_cart !== NULL && $this->losted_cart != false;

 		$data['languages']       = $this->model_localisation_language->getLanguages();
 		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

 		$data['version2']		 = version_compare(VERSION, '2.3', '<');

 		$data['var'] = base64_encode(json_encode([
 			'text_send_title'          => $this->language->get('text_send_title'),
			'text_send_success'        => $this->language->get('text_send_success'),
			'text_send_error'          => $this->language->get('text_send_error'),
			'text_confirm'		       => $this->language->get('text_confirm'),
			'text_confirm_remove'      => $this->language->get('text_confirm_remove'),
			'text_remove'		       => $this->language->get('text_remove'),
			'text_cancel'		       => $this->language->get('text_cancel'),
			'error_count_affected'     => $this->language->get('error_count_affected'),
			'error_permission'         => $this->language->get('error_permission'),
			'text_confirm_remove_cart' => $this->language->get('text_confirm_remove_cart'),
			'license_url'	           => $this->link('extension/module/losted_cart/license')
  		]));

 		$data['status'] = isset($this->request->post['losted_cart_status']) ? 
 			$this->request->post['losted_cart_status'] : $this->config->get('losted_cart_status');

 		/* settings value */
		$settings = isset($this->request->post['losted_cart_settings']) ? 
			$this->request->post['losted_cart_settings'] : ($this->config->get('losted_cart_settings') 
				? $this->config->get('losted_cart_settings') : []);

 		$data['settings'] = 
 		[
 			'checkout_route'   => 'checkout/checkout',
 			'cookie_life' 	   => 360,
			// 'crypt_key'   	   => Core::getCryptKey(),
			'sending_interval' => 1,
			'resending_day'    => 0,
			'create_token'	   => 0,
			'collector' 	   => 
			[
				'routers' => 'checkout/simplecheckout'. PHP_EOL .'checkout/checkout' . PHP_EOL . 'simplecheckout' . PHP_EOL . 'checkout'
			],
			'coupon'		   => 
			[
				'status'     => 0,
				'name' 		 => '',
				'life_days'  => 360,
				'prefix'	 => 'LcAP',
				'total'		 => 0,
				'discount'	 => 0,
				'type'		 => 'P',
				'shipping'	 => 0,
			],
			'email' 		  => 
			[ 
				'status'   => 0,
				'subject'  => '',
				'template' => '',
				'title'	   => ''
			],
			'product_w' 	=> 200,
			'product_h' 	=> 200,
			'logo_w'    	=> 200,
			'logo_h'    	=> 200,
			'resending_max'	=> 1,
			'groups_disabled' => []
		];
		
		foreach ($data['languages'] as $language) {
			if (is_array($data['settings']['email']['template'])) {
				$data['settings']['email']['template'][$language['language_id']] = file_get_contents(self::EMAIL_BACKUP_PATH);
			} else {
				$data['settings']['email']['template'] = 
				[
					$language['language_id'] => file_get_contents(self::EMAIL_BACKUP_PATH)
				];
			}

			if (is_array($data['settings']['email']['subject'])) {
				$data['settings']['email']['subject'][$language['language_id']] = '';
			} else {
				$data['settings']['email']['subject'] = 
				[
					$language['language_id'] => ''
				];
			}

			if (is_array($data['settings']['email']['title'])) {
				$data['settings']['email']['title'][$language['language_id']] = '';
			} else {
				$data['settings']['email']['title'] = 
				[
					$language['language_id'] => ''
				];
			}
		}

		foreach ($data['settings'] as $key => &$value) {
			if (!empty($settings[$key]) && !is_array($value)) {
				$value = $settings[$key];
			} elseif(is_array($value)) {
				
				if (empty($value) && isset($settings[$key])) {
					$value = $settings[$key];
					continue;
				}

				foreach ($value as $key2 => &$value2) {
					if (!empty($settings[$key][$key2])) {
						$value2 = $settings[$key][$key2];
					}
				}
			}
		}

 		$data['footer']      = $this->load->controller('common/footer');
 		$data['header']      = $this->load->controller('common/header');
 		$data['column_left'] = $this->load->controller('common/column_left');

		if (version_compare(VERSION, '3.0', '>=')) {
 			$this->config->set('template_engine', 'template');
 		}
 		
 		$this->response->setOutput($this->load->view('extension/module/losted_cart/main' . $this->suffix, $data));
 	} 

 	public function getList() 
 	{
 		if (!$this->losted_cart) {
 			return [];
 		}

 		$data = $this->language->all();

 		/* list */
 		$limit = 25;
 		$start = isset($this->request->get['page']) ? ($this->request->get['page'] - 1) * 25 : 0;

 		$list  = $this->db->query("  
 			SELECT losted_id, session_id, firstname, lastname, email, telephone, date_notified, 
 			date_added, store_id, currency_code, customer_id, customer_group_id, language_id, token 
 				FROM  `". DB_PREFIX ."losted_cart`
 				ORDER BY losted_id DESC
 				LIMIT {$limit} OFFSET {$start}
 		");

 		$data['carts'] = [];

 		if ($list->num_rows) {
	 		/* models */
		 	foreach(['localisation/language', 'tool/image', 'setting/store', 
		 		'customer/customer_group'] as $model
		 	) {
		 		$this->load->model($model);
		 	}

		 	$data['carts'] = [];

	 		foreach ($list->rows as &$item) {
	 			/*echo "<pre>";
	 			print_r($item); die; */
	 			$item['cart'] = $this->losted_cart->cart->getAbandonedCart($item);

	 			/* clearing */
	 			if (empty($item['cart'])) {
	 				$this->db->query("DELETE lc, lcl FROM `". DB_PREFIX ."losted_cart` lc 
	 					LEFT JOIN `". DB_PREFIX ."losted_cart_log` lcl
	 						ON (lcl.losted_id = lc.losted_id)
	 					WHERE lc.session_id = '". $this->db->escape($item['session_id']) ."'
	 						AND lc.customer_id = '". (int) $item['customer_id'] ."'
	 				");

	 				continue;
	 			}

			 	/* stores */
				$stores[0] = 
				[
					'name' => $this->config->get('config_name') . $this->language->get('text_default'),
					'url'  => HTTP_CATALOG,
				];

				foreach ($this->model_setting_store->getStores() as $result) {
					$stores[$result['store_id']] = 
					[
						'name' => $result['name'],
						'url'  => $result['url']
					];
				}

	 			$total = 0;

	 			foreach ($item['cart']['products'] as &$product) {
	 				$price = $this->tax->calculate($product['price'], 
	 					$product['tax_class_id'], $this->config->get('config_tax'));

	 				
	 				if (version_compare(VERSION, '2.3', '>=')) {
	 					$product['view']  = str_replace('admin/', '', $this->url->link('catalog/product', 
	 						'product_id=' . $product['product_id'], true));
	 				} else {
	 					$product['view']  = str_replace('admin/', '', $this->url->link('product/product', 
	 						'product_id=' . $product['product_id'], true));	 					
	 				}
	 				
	 				$product['edit']  = $this->url->link('catalog/product/edit', 'product_id=' . $product['product_id'] . 
	 					'&' . $this->token, true);
	 				
	 				$product['price'] = $this->currency->format($price, $item['currency_code']);
	 				$product['total'] = $this->currency->format($price * $product['quantity'], $item['currency_code']);
	 				$product['image'] = $this->model_tool_image->resize($product['image'], 50, 50);
	 				
	 				if (!empty($product['option'])) {
	 					foreach ($product['option'] as &$option) {
	 						$option['price'] = $this->currency->format($option['price'], $item['currency_code']);
	 					}
	 				}

	 				$total += $price * $product['quantity'];
	 			}	 			

	 			$item['cart']['total'] = $this->currency->format($total, $item['currency_code']);

	 			if ($this->validation()) {
	 				$item['notification'] = !empty($item['email']) && filter_var($item['email'], FILTER_VALIDATE_EMAIL);
	 			} else {
	 				$item['notification'] = false;
	 			}

	 			$item['customer_link']  = $item['customer_id'] ? $this->url->link('customer/customer/edit', 
	 				$this->token . '&customer_id=' . $item['customer_id']) : false;

	 			$item['store'] = $stores[$item['store_id']]; isset($stores[$item['store_id']]) ? $stores[$item['store_id']] : false;
	 			
	 			$item['customer_group'] = '-';
	 			$group = $this->model_customer_customer_group->getCustomerGroup($item['customer_group_id']);
	 			
	 			if (isset($group['name'])) {
	 				$item['customer_group'] = $group['name'];
	 			}	 			

	 			$logs = $this->db->query("SELECT date_send FROM `". DB_PREFIX ."losted_cart_log` WHERE losted_id = '". (int) $item['losted_id'] ."'");
	 			
	 			$item['logs'] = [];

	 			foreach ($logs->rows as $log) {
	 				$item['logs'][] = $log['date_send'];
	 			}

	 			$data['carts'][] = $item;
	 		}

	 		$data['email_action']    = str_replace('admin/', '', 
	 			$this->url->link("tool/losted_cart_api/sendMailToClient&{$this->token}&wget={$this->wget}", false, true));

	 		$data['remove_action']   = $this->link("extension/module/losted_cart/removeCart&{$this->token}}", false, true);
		 	$data['languages']       = $this->model_localisation_language->getLanguages();
	 		$data['template_action'] = str_replace('admin/', '', $this->url->link("tool/losted_cart_api/getTemplate&wget={$this->wget}", false, true));
	 		

	 		$total = $this->db->query("SELECT COUNT(losted_id) as total FROM `". DB_PREFIX ."losted_cart`")->row['total'];
			$page  = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

			$pagination 	    = new \Pagination();
			$pagination->total  = $total;
			$pagination->page   = $page;
			$pagination->limit  = $limit;
			$pagination->url    = $this->link('extension/module/losted_cart/getList', 'page={page}');

			$data['pagination'] = $pagination->render();
			$data['results']    = sprintf(
				$this->language->get('text_pagination'), 
				($total) ? (($page - 1) * $limit) + 1 : 0, 
				((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit)
			);
		}

		$data['validation'] = $this->validation();

		if (version_compare(VERSION, '3.0', '>=')) {
 			$this->config->set('template_engine', 'template');
 		}

 		$this->response->setOutput($this->load->view('extension/module/losted_cart/tab_carts' . $this->suffix, $data));
 	}

 	public function removeCart() 
 	{
 		$json = [];

 		if ($this->request->server['REQUEST_METHOD'] == 'GET' && (!empty($this->request->get['cart_id']) 
 			|| !empty($this->request->get['losted_id'])) && $this->validation() && $this->losted_cart
 		) {
 			if (empty($this->request->get['losted_id'])) {
 				$this->db->query("DELETE FROM `". DB_PREFIX ."cart` WHERE cart_id = '". (int) $this->request->get['cart_id'] ."'");
 			} else { 
 				$this->db->query("  
					DELETE lc, lcl, c
						FROM `". DB_PREFIX ."losted_cart` lc
						LEFT JOIN `". DB_PREFIX ."losted_cart_log` lcl
							ON (lcl.losted_id = lc.losted_id)
						LEFT JOIN `". DB_PREFIX ."cart` c
							ON (c.session_id = lc.session_id)
						WHERE lc.losted_id = '". (int) $this->request->get['losted_id'] ."'
							AND c.customer_id = lc.customer_id
				");
 			}

 			if ($this->db->countAffected()) {
 				$json['success'] = true;
 			} else {
 				$json['error'] = $this->language->get('error_count_affected');
 			}
 		} else {
 			$json['error'] = $this->language->get('error_permission');
 		}

 		$this->response->addHeader('Content-Type: application/json');
 		$this->response->setOutput(json_encode($json));
 	}
 	
	private function validation() 
	{
 		$path = version_compare(VERSION, '2.3', '>=') ? 'extension/' : '';

		if (!$this->user->hasPermission('modify', $path . 'module/losted_cart')) {
			$this->errors['warning'] = $this->language->get('error_permission');
		}

		return !$this->errors;
	}

	public function getEmailTemplate() 
	{
 		$this->response->setOutput(file_get_contents(self::EMAIL_BACKUP_PATH));
	}
  
	public function install() 
	{
		$this->uninstall();

		$this->db->query("   
			CREATE TABLE `". DB_PREFIX ."losted_cart` 
			(
			  `losted_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			  `firstname` varchar(255) NOT NULL,
			  `lastname` varchar(255) NOT NULL,
			  `email` varchar(255) NOT NULL,
			  `telephone` varchar(255) NOT NULL,
			  `store_id` int(2) NOT NULL,
			  `language_id` int(2) NOT NULL,
			  `currency_code` varchar(5) NOT NULL,
			  `customer_id` int(11) NOT NULL,
			  `customer_group_id` int(11) NOT NULL,
			  `session_id` varchar(32) NOT NULL,
			  `token` varchar(64) NOT NULL,
			  `coupon` varchar(50) NOT NULL,
			  `notified` tinyint(1) NOT NULL,
			  `date_added` datetime NOT NULL,
			  `date_notified` datetime NOT NULL
			) 
			ENGINE=MyISAM DEFAULT CHARSET=utf8
		");

		$this->db->query("CREATE INDEX session_id ON `". DB_PREFIX ."losted_cart` (session_id)");

		$this->db->query("
			CREATE TABLE `". DB_PREFIX ."losted_cart_log` (
			  `losted_id` int(11) NOT NULL,
			  `date_send` datetime NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");		

		$this->db->query("CREATE INDEX losted_id ON `". DB_PREFIX ."losted_cart_log` (losted_id)");		
	} 

	public function uninstall() 
	{
		$tabs = 
		[
			'losted_cart', 
			'losted_cart_log', 
			'losted_cart_products'
		];

		foreach ($tabs as $tab) {
			$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $tab . "`");
		}
	}	

	public function license() 
	{
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('losted_cart_license');

		if ($this->request->server['REQUEST_METHOD'] == 'GET' && !empty($this->request->get['key']) 
			&& Core::checkLicense($this->request->get['key'])
		) {
			$status = true; 
			$this->model_setting_setting->editSetting('losted_cart_license', ['losted_cart_license' => $this->request->get['key']]);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(['status' => !empty($status)]));
	}

 	public function link($url, $get = false, $ssl = true) {
 		
 		if (version_compare(VERSION, '2.3', '<')) {
 			if ($url == 'extension/extension') {
 				$url = 'extension/module';
 			} else {
 				$url = str_replace('extension/', '', $url);  
 			}
 		}

 		return $this->url->link($url . '&' . $this->token, $get, $ssl);
 	}	
}