<?php 
/**
 * Losted Cart (c) 2018
 *
 * @author Artem Pitov <artempitov@gmail.com>
 * @link https://www.pitov.pro
 * @link https://opencartforum.com/user/674600
 * @see https://opencartforum.com/files/file/5564
 *
 * @license Сommercial license
 */

if (!class_exists('Twig_Autoloader')) {
	include_once(DIR_SYSTEM . 'library/losted_cart/libs/twig/lib/Twig/Autoloader.php');
	Twig_Autoloader::register();
}

class ControllerToolLostedCartApi extends Controller 
{  
	private $languages;

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->settings = $this->config->get('losted_cart_settings');
	}

	public function collector() 
	{
		if ($this->losted_cart && $this->config->get('losted_cart_status') 
			&& ($this->request->server['REQUEST_METHOD'] == 'POST')
		) {
			$this->losted_cart->customer->createCustomer();
		}
	}

	public function mailer() 
	{
		$json = [];

		if ($this->validation()) {
			$list = $this->losted_cart->customer->getListPendingNotifications();

			if (empty($list)) {
				$json['status'] = true;
				$json['text']   = 'Congratulations! Not found lost cart :)';
			}

			foreach ($list as $losted) {
				$abandoned_info = $this->createAbandoned($losted['losted_id']);

				if (!empty($abandoned_info)) {
					if (!empty($this->settings['email']['template'][$abandoned_info['language_id']])) { 
						
						if (!$this->sendEmail($abandoned_info)) {
							continue;
						}
						
						$this->losted_cart->customer->logCustomer($losted['losted_id']);	
						
						$json[] = 
						[
							'text'   => 'Send #' . $losted['losted_id'],
							'status' => true
						];
					} else {
						$json[] = 
						[
							'text'   => 'Email template is empty',
							'status' => true
						];
					}
				} else {
					$this->losted_cart->customer->removeCustomer($losted['losted_id']);

					$json['status'] = false;
					$json['text']   = 'Not found or remove losted cart #' . $losted['losted_id'];
				}
			}
		} else {
			$json['status'] = false;
			$json['text']   = 'Validation error';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function sendMailToClient()
	{
		$json = [];

		$losted_id = (isset($this->request->get['losted_id']) && (int) $this->request->get['losted_id']) ? $this->request->get['losted_id'] : false;

		if ($this->validation() && $losted_id) {
			$abandoned_info = $this->createAbandoned($losted_id);

			if (!empty($abandoned_info)) {
				if (!empty($this->settings['email']['template'][$abandoned_info['language_id']])) { 
					$this->sendEmail($abandoned_info);
					$this->losted_cart->customer->logCustomer($losted_id);	

					$json['status'] = true;
					$json['text']   = 'Send #' . $losted_id;
				} else {
					$json['status'] = false;
					$json['text']   = 'Email template is empty';
				}
			} else {
				$this->losted_cart->customer->removeCustomer($losted_id);

				$json['status'] = false;
				$json['text']   = 'Not found or remove losted cart #' . $losted_id;
			}
		} else {
			$json['status'] = false;
			$json['text']   = !$losted_id ? 'Wrong format losted_id' : 'Validation error';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getTemplate() 
	{
		if ($this->validation() && isset($this->request->get['losted_id'])) {
			$this->response->setOutput($this->renderTemplate($this->createAbandoned((int)$this->request->get['losted_id']))); 
		} else {
			$this->response->setOutput('<div style="text-align:center;"><h4 style="color:red;">Validation error!</h4></div>');
		}
	}

	private function createAbandoned($losted_id)
	{	
		$abandoned_info = $this->losted_cart->customer->getCustomerInfo($losted_id);

		if (empty($abandoned_info)) {
			return [];
		}
		
		unset($abandoned_info['token']);

		// cart
		$abandoned_info['cart'] = $this->losted_cart->cart->getAbandonedCart($abandoned_info);

		if (empty($abandoned_info['cart'])) {
			return [];
		}

		$config   = $this->losted_cart->customer->getCorrectConfig($abandoned_info['store_id']); 
		$settings = $this->settings;

		$w = !empty($settings['product_w']) ? $settings['product_w'] : 250;
		$h = !empty($settings['product_h']) ? $settings['product_h'] : 250;

		// Если мы отправляем запрос с админки, нам нужно обнулить свои данные которые могут тянутся с сессии, и при этом налог будет не верно расчитыватся  
		// If we send a request from the admin area, we need to zero out your data that can be dragged from the session, and the tax will not be calculated correctly
				
		$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		$this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

		$this->load->model('tool/image');
		
		$cart_total = 0;
		
		// token 
		$customer_token = $this->losted_cart->customer->createToken($abandoned_info['losted_id']);
		$customer_token_key = losted_cart\Core::getTokenKey();
		
		$token =  '&' . $customer_token_key .'='. $customer_token;

	 	foreach ($abandoned_info['cart']['products'] as &$product) {
	 		$price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

	 		$product['price'] = $this->currency->format($price, $abandoned_info['currency_code']);
	 		$product['total'] = $this->currency->format($price * $product['quantity'], $abandoned_info['currency_code']);
	 		
	 		$product['url'] = $config['url']->link('product/product', 'product_id=' . $product['product_id'] . $token, true);
			$product['image'] = $this->model_tool_image->resize($product['image'], $w, $h);

	 		if (!empty($product['option'])) {
	 			foreach ($product['option'] as &$option) {
	 				$option['price'] = (float)$option['price'] ? 
	 					$this->currency->format($option['price'], $abandoned_info['currency_code']) : false;
	 			}
	 		}

	 		$cart_total += $price * $product['quantity'];
	 	}	 			

	 	$abandoned_info['cart']['total'] = $this->currency->format($cart_total, $abandoned_info['currency_code']);		

		// coupon 
		if (!empty($abandoned_info['coupon'])) {
			$this->db->query("DELETE FROM `". DB_PREFIX ."coupon` 
				WHERE code = '". $this->db->escape($abandoned_info['coupon']) ."' 
			");
		}

		unset($abandoned_info['coupon']);

		if ($settings['coupon']['status'] && ($cart_total >= $settings['coupon']['total'])) {
			
			$abandoned_info['coupon'] = $this->losted_cart->customer->createCoupon();
			$language                 = $this->getLanguage($abandoned_info['language_id']);
					
			$end    = explode('.', $abandoned_info['coupon']['date_end']); 
			$mounts = $language['text_mounts'];

			if (isset($end[1]) && isset($mounts[$end[1]])) {
				$abandoned_info['coupon']['date_end'] = $end[0] . ' ' . $mounts[$end[1]] . ' ' . $end[2];
			}		

			if ((int)$abandoned_info['coupon']['discount']) {
				$abandoned_info['coupon']['sale'] = ($abandoned_info['coupon']['type'] == 'P') ? 
					$abandoned_info['coupon']['discount'] . '%' : $this->currency->format($abandoned_info['coupon']['discount'], $abandoned_info['currency_code']); 
			} else {
				$abandoned_info['coupon']['sale'] = false;
			}		

			$abandoned_info['coupon']['free_shipping'] = $settings['coupon']['shipping'];
		}			

		// update token and coupon
		$this->db->query("
			UPDATE `". DB_PREFIX ."losted_cart` 
				SET 
					token  = '". $this->db->escape( (!empty($customer_token) ? 
						$customer_token : '') ) ."',
					coupon = '". $this->db->escape( (!empty($abandoned_info['coupon']) && 
						isset($abandoned_info['coupon']['code']) ? $abandoned_info['coupon']['code'] : '') ) ."' 
				WHERE losted_id = '". (int) $abandoned_info['losted_id'] ."' 
		");

		// subject		
		$search  = [];
		$replace = [];

		$abandoned_info['subject'] = str_replace(
			$search, 
			$replace, 
			$settings['email']['subject'][$abandoned_info['language_id']]
		);		

		$abandoned_info['title'] = $settings['email']['title'][$abandoned_info['language_id']];
		
		// store name 
		if (isset($config['config_langdata'])) { // ocStore
			$abandoned_info['store_name'] = $config['config_langdata'][$abandoned_info['language_id']]['meta_title'];
		} else{
			$abandoned_info['store_name'] = $config['config_meta_title'];
		}

		// logo 
		$logo_w = !empty($settings['logo_w']) ? $settings['logo_w'] : 200;
		$logo_h = !empty($settings['logo_h']) ? $settings['logo_h'] : 200; 
		
		$abandoned_info['logo']      = $this->model_tool_image->resize($config['config_logo'], $logo_w, $logo_h);
		$abandoned_info['store_url'] = $config['config_secure'] ? $config['config_ssl'] . $token : $config['config_url'] . $token;		

		// checkout
		$checkout = !empty($settings['checkout_route']) ? $settings['checkout_route'] : 'checkout/checkout';
		$abandoned_info['checkout_url'] = $config['url']->link($checkout . $token, false, true);

		return $abandoned_info;
	}

	private function sendEmail($abandoned_info) 
	{

		$settings = $this->settings;
		$config   = $this->losted_cart->customer->getCorrectConfig(0);
		$template = $this->renderTemplate($abandoned_info); 

		if (empty($settings['email']['mail_library']) || $settings['email']['mail_library'] == 'default') {

			try {
				$version = explode('.', VERSION);

				if ($version[0] < 3) {
					$mail = new \Mail();
					$mail->protocol = $config['config_mail_protocol'];
				} else {
					$mail = new Mail($this->config->get('config_mail_engine'));
				}
				
				$mail->parameter     = $config['config_mail_parameter'];
				$mail->smtp_hostname = $config['config_mail_smtp_hostname'];
				$mail->smtp_username = $config['config_mail_smtp_username'];
				$mail->smtp_port     = $config['config_mail_smtp_port'];
				$mail->smtp_timeout  = $config['config_mail_smtp_timeout'];
				$mail->smtp_password = html_entity_decode($config['config_mail_smtp_password'], ENT_QUOTES, 'UTF-8');

				$mail->setTo($abandoned_info['email']);
				$mail->setFrom($config['config_email']);
				$mail->setReplyTo($config['config_email']);

				$title = !empty($abandoned_info['title']) ? $abandoned_info['title'] : $abandoned_info['store_name'];

				$mail->setSender(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode(!empty($abandoned_info['subject']) ? $abandoned_info['subject'] : $abandoned_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($template);
				$mail->send();

				return true;	
			} catch (\Exception $e) {
				return false;
			}			
		} else {
			// phpMailer ....
		}
	}

	private function validation() 
	{
		if ($this->losted_cart !== null && $this->losted_cart && (int) $this->settings['email']['status'] && !empty($this->request->get['wget']) &&
			$this->request->get['wget'] == md5(losted_cart\Core::getPrivateKey() . ($this->config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER))
		) {
			$validation = true;
		}

		return !empty($validation);
	}	

	private function renderTemplate(array $abandoned_info) 
	{	 
		$template = $this->settings['email']['template'][$abandoned_info['language_id']];

		$twig   = !$this->twig ? new Twig_Environment(new Twig_Loader_String()) : $this->twig;
		$output = $twig->render(html_entity_decode($template, ENT_QUOTES, 'UTF-8'), $abandoned_info);

		return $output;
	}

	public function getLanguage($language_id) 
	{
		if (isset($this->languages[$language_id])) {
			return $this->languages[$language_id];
		}

		$path = version_compare(VERSION, '2.3', '<') ? 'directory as code' : 'code';
		$sql  = "
			SELECT {PATH} FROM `". DB_PREFIX ."language`
				WHERE language_id = '". (int) $language_id ."'
			LIMIT 1
		";

		$code = $this->db->query(str_replace('{PATH}', $path, $sql));		

		if ($code->num_rows) {
			$language = new \Language($code->row['code']);
			$language->load('extension/module/losted_cart');

			$language = $language->all();
		} else {
			$language = $this->language->load('extension/module/losted_cart');
		}

		$this->languages[$language_id] = $language;

		return $this->languages[$language_id];
	}		
}
