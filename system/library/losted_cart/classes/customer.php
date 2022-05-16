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
namespace losted_cart\classes;
// use losted_cart\classes\Encryption as Encryption;

class Customer extends \Controller 
{
	private $settings;
	private $configs;
	private $data;
	private $now;

	public function __construct($registry) 
	{
		parent::__construct($registry);

		$this->now      = date('Y-m-d H:i') . ':00';
		$this->settings = $this->config->get('losted_cart_settings');
	}

	/* label */
	public function createLabel() 
	{
		$life = !empty($this->settings['cookie_life']) ? ((int)$this->settings['cookie_life']) : 360;
		
		setcookie(LC_LABEL_KEY, trim(($this->session->getId() . '.c' 
			. (int)$this->customer->getId())), time() + 3600 * 24 * $life, '/', ini_get('session.cookie_domain'));

	}

	public function getLabel() 
	{
		return !empty($this->request->cookie[LC_LABEL_KEY]) ? 
			trim(strip_tags(html_entity_decode($this->request->cookie[LC_LABEL_KEY]))) : false;		
	}

	public function removeLabel() 
	{
		setcookie(LC_LABEL_KEY, null, time() - 3600, '/', ini_get('session.cookie_domain'));
	}

	public function createCoupon() 
	{	
		$this->load->language('extension/module/losted_cart');
		
		$settings = $this->settings['coupon'];
		$code     = (!empty($settings['prefix']) ? $settings['prefix'] : 'LcAP') . token(6);

		$this->db->query("DELETE FROM `". DB_PREFIX ."coupon` WHERE code = '". $this->db->escape($code) ."'");

		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "coupon` 
				SET 
					name 	   	  = '" . $this->db->escape($settings['name']) . "', 
					code 	   	  = '" . $this->db->escape($code) . "', 
					discount   	  = '" . (float) $settings['discount'] . "', 
					type 	   	  = '" . $this->db->escape($settings['type']) . "', 
					total      	  = '" . (float) $settings['total'] . "', 
					logged     	  = '0', 
					shipping   	  = '" . (isset($settings['shipping']) ? (int) $settings['shipping'] : 0) . "', 
					date_start 	  = NOW(), 
					date_end   	  = ADDDATE(NOW(), INTERVAL '". (int) $settings['life_days'] ."' DAY),
					uses_total 	  = '1', 
					uses_customer = '1', 
					status        = '1', 
					date_added    = NOW()
		");

		$cupon_end = date_add(
			date_create('now'), date_interval_create_from_date_string((int) $settings['life_days'] . ' days')
		);

		$cupon_end = date_format($cupon_end, 'j.n.Y');

		return 
		[
			'code'     => $code,
			'date_end' => $cupon_end,
			'discount' => $settings['discount'],
			'type'	   => $settings['type']
		];
	}	

	/* token */
	public function useToken() 
	{	
		if (empty($this->request->get[LC_TOKEN])) { 
			return false;
		}

		$token = trim(strip_tags(html_entity_decode($this->request->get[LC_TOKEN], ENT_QUOTES, 'UTF-8')));
	
		/* get cart */
		$losted_cart = $this->db->query("SELECT losted_id, customer_id, session_id, email FROM `". DB_PREFIX ."losted_cart` 
			WHERE token = '". $this->db->escape($token) ."' LIMIT 1
		");

		if (!$losted_cart->num_rows) {
			return false;
		}

		$old_session_id = $this->getLabel();

		/* clear old cart if customer get link */
		if ($old_session_id && $losted_cart->row['session_id'] != $old_session_id) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cart` 
				WHERE customer_id = '" . (int)$this->customer->getId() . "' 
					AND session_id  = '" . $this->db->escape($old_session_id) . "' 
			");
		}

		/* authorization */
		$this->customer->logout();

		if ($this->customer->login($losted_cart->row['email'], '', true)) {
			unset(
				$this->session->data['order_id'],
				$this->session->data['payment_address'],
				$this->session->data['payment_method'],
				$this->session->data['payment_methods'],
				$this->session->data['shipping_address'],
				$this->session->data['shipping_method'],
				$this->session->data['shipping_methods'],
				$this->session->data['comment'],
				$this->session->data['coupon'],
				$this->session->data['reward'],
				$this->session->data['voucher'],
				$this->session->data['vouchers']
			);

			// default data
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress(
					$this->customer->getAddressId()
				);
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress(
					$this->customer->getAddressId()
				);
			}
		}

		// Update Losted Cart 
		$this->db->query("UPDATE `". DB_PREFIX ."losted_cart` 
			SET token = '', session_id = '". $this->db->escape($this->session->getId()) ."' 
			WHERE losted_id = '". (int) $losted_cart->row['losted_id'] ."'
		");	

		// Update cart
		$this->db->query("
			UPDATE `". DB_PREFIX ."cart` 
				SET 
					session_id  = '". $this->db->escape($this->session->getId()) ."',
					customer_id = '". (int) $this->customer->getId() ."',
					date_added  = NOW()  
				WHERE session_id = '". $this->db->escape($losted_cart->row['session_id']) ."' 
		");		

		// if cart update
		return $this->db->countAffected();
	}

	public function createToken($losted_id) 
	{
		$token = token(32);

		$this->db->query("  
			UPDATE `". DB_PREFIX ."losted_cart` SET token = '". $this->db->escape($token) ."'
				WHERE losted_id = '". (int) $losted_id ."'
		");

		if ($this->db->countAffected()) {
			return $token;
		}
	}	

	/* customer */
	public function createCustomer() 
	{
		// if (!$this->cart->hasProducts()) {
		// 	return;
		// }
		
		$customer_info = $this->preparationCustomerData();

		if (empty($customer_info['email']) && empty($customer_info['telephone'])) {
			return;
		}

		$sql = '';

		if (!empty($customer_info['email'])) {
			$sql .= " OR email = '" . $this->db->escape($customer_info['email']) . "'";
		}

		if (!empty($customer_info['telephone'])) {
			$sql .= " OR telephone = '" . $this->db->escape($customer_info['telephone']) . "'"; 
		}

		$customer = $this->db->query("  
			SELECT losted_id, session_id FROM `". DB_PREFIX ."losted_cart` 
			WHERE (session_id = '". $this->db->escape($customer_info['session_id']) ."'
				AND customer_id = '". (int) $customer_info['customer_id'] ."') {$sql}
			LIMIT 1
		"); 

		if ($customer->num_rows) {

			$sql = 
			"  
				UPDATE `". DB_PREFIX ."losted_cart`
					SET 
						[FIRSTNAME][LASTNAME][TELEPHONE][EMAIL]
						session_id 		  = '". $this->db->escape($customer_info['session_id'])    ."',
						language_id       = '". (int) $customer_info['language_id']                ."',
						currency_code     = '". $this->db->escape($customer_info['currency_code']) ."',
						customer_id       = '". (int) $customer_info['customer_id']                ."',
						customer_group_id = '". (int) $customer_info['customer_group_id']          ."',
						token 			  = ''
					WHERE losted_id = '". (int) $customer->row['losted_id'] ."'
			";

			if ($customer->row['session_id'] != $customer_info['session_id']) {
				$this->db->query("DELETE FROM `". DB_PREFIX ."cart` 
					WHERE session_id = '". $this->db->escape($customer->row['session_id']) ."'");
			}
			
			$this->db->query("DELETE FROM `". DB_PREFIX ."losted_cart` 
				WHERE session_id = '". $this->db->escape($customer->row['session_id']) ."' 
				AND losted_id != '". (int) $customer->row['losted_id'] ."'");

		} else {
			$sql = 
			"  
				INSERT INTO `". DB_PREFIX ."losted_cart`
					SET 
						[FIRSTNAME][LASTNAME][TELEPHONE][EMAIL]
						store_id          = '". (int) $customer_info['store_id']                   ."',
						session_id 		  = '". $this->db->escape($customer_info['session_id'])    ."',
						language_id       = '". (int) $customer_info['language_id']                ."',
						currency_code     = '". $this->db->escape($customer_info['currency_code']) ."',
						customer_id       = '". (int) $customer_info['customer_id']                ."',
						customer_group_id = '". (int) $customer_info['customer_group_id']          ."',
						token 			  = '',
						date_added 		  = NOW(),
						notified 		  = '0'
			";
		}

		/*$this->db->query("
			DELETE lc, lcl FROM `". DB_PREFIX ."losted_cart` lc
				LEFT JOIN `". DB_PREFIX ."losted_cart_log` lcl
					ON (lcl.losted_id = lc.losted_id)
				WHERE lc.session_id = '". $this->db->escape($customer_info['session_id']) ."' 
					OR (lc.customer_id = '". (int) $customer_info['customer_id'] ."' AND lc.customer_id != 0)  
		");*/

		$replace[] = !empty($customer_info['firstname']) ? 
			"firstname = '" . $this->db->escape($customer_info['firstname']) . "'," : ''; 
			
		$replace[] = !empty($customer_info['lastname'])  ? 
			"lastname = '" . $this->db->escape($customer_info['lastname']) . "'," : ''; 
			
		$replace[] = !empty($customer_info['telephone']) ? 
			"telephone = '" . $this->db->escape($customer_info['telephone']) . "'," : ''; 		
			
		$replace[] = !empty($customer_info['email']) ? 
			"email = '" . $this->db->escape($customer_info['email']) . "'," : ''; 

		$this->db->query(str_replace(['[FIRSTNAME]','[LASTNAME]','[TELEPHONE]','[EMAIL]'], $replace, $sql));
	}

	public function removeCustomer() 
	{
		if (!($session = $this->getLabel())) {
			return;
		}

		/* перестрахуемся, чтобы в корзине не было ошибки */
		$session_data = explode('.c', $session);

		if (empty($session_data) || !isset($session_data[1])) {
			return;
		}

		$session_id = $session_data[0];
		$customer_id = $session_data[1];

		if ($customer_id > 0) {
			$is_customer = $this->db->query("  
				SELECT cart_id FROM `". DB_PREFIX ."cart` 
					WHERE customer_id = '".  (int) $customer_id ."'
					LIMIT 1
			");
		}

		if (empty($is_customer) || !$is_customer->num_rows) { 
			$this->db->query("DELETE lc, lcl
				FROM `". DB_PREFIX ."losted_cart` lc
				LEFT JOIN `". DB_PREFIX ."losted_cart_log` lcl
					ON (lcl.losted_id = lc.losted_id)
				WHERE lc.session_id = '". $this->db->escape($session_id) ."'
					AND lc.customer_id = '".  (int) $customer_id ."'
			");
		}
	} 

	public function getCustomerInfo($losted_id) 
	{
		$customer_info = $this->db->query("  
			SELECT losted_id, firstname, lastname, email, telephone, store_id, language_id, currency_code, customer_id, 
			customer_group_id, session_id, token, coupon
			FROM `". DB_PREFIX ."losted_cart`
				WHERE losted_id = '". (int) $losted_id ."'
				LIMIT 1
		");

		return $customer_info->row;
	}

	public function updateSession($session_id) 
	{
		if ($this->session->getId() == $session_id) {
			return;
		}
		
		$this->db->query("  
			UPDATE `". DB_PREFIX ."losted_cart`
				SET session_id = '". $this->db->escape($this->session->getId()) ."'
				WHERE session_id = '". $this->db->escape($session_id) ."'
				LIMIT 1
		");
	}

	public function getCorrectConfig($store_id) 
	{
		if (!isset($this->configs[$store_id])) {
			$config = [];

			$settings = $this->db->query("
				SELECT * FROM " . DB_PREFIX . "setting 
				WHERE store_id = '" . (int)$store_id . "' 
					AND `code` = 'config'  
			");

			foreach ($settings->rows as $result) {
				if (!$result['serialized']) {
					$config[$result['key']] = $result['value'];
				} else {
					$config[$result['key']] = json_decode($result['value'], true);
				}
			}

			if (!isset($config['config_url'])) {
				$config['config_url'] = HTTP_SERVER;
			}

			if (!isset($config['config_ssl'])) {
				$config['config_ssl'] = HTTPS_SERVER;
			}

			$config['url'] = new \Url($config['config_url'], $config['config_ssl']);

			$this->configs[$store_id] = $config;
		}

		return $this->configs[$store_id];
	}

	public function getListPendingNotifications() 
	{
		// Sending hour 
		$first = isset($this->settings['sending_interval']) ? (int) $this->settings['sending_interval'] : 1;
		$first = ($first >= 1 && $first < 10) ? 0 . $first . ':00:00' : $first . ':00:00'; 
		
		/* if (isset($this->settings['resending_day']) && $this->settings['resending_day'] > 0) {
			$next = ((int) $this->settings['resending_day'] * 24) . ':00:00';	

			$sql = "  
				SELECT losted_id FROM `". DB_PREFIX ."losted_cart`
				WHERE email != '' 
					AND ((TIMEDIFF('". $this->db->escape($this->now) ."', date_added) >= '". $this->db->escape($first) ."' AND notified = '0') 
						OR (TIMEDIFF('". $this->db->escape($this->now) ."', date_notified) >= '". $this->db->escape($next) ."' AND notified != '0')) 
			";
		} else {
			$sql = "  
				SELECT losted_id FROM `". DB_PREFIX ."losted_cart`
				WHERE email != '' 
					AND (TIMEDIFF('". $this->db->escape($this->now) ."', date_added) >= '". $this->db->escape($first) ."' AND notified = '0')
			";			
		} */

		if (isset($this->settings['resending_day']) && $this->settings['resending_day'] > 0) {
			// Resending hour, convert to days
			$next = ((int) $this->settings['resending_day'] * 24) . ':00:00';	

			$sql = "  
				SELECT lc.losted_id FROM `". DB_PREFIX ."losted_cart` lc
				WHERE lc.email != '' 
					AND ((TIMEDIFF('". $this->db->escape($this->now) ."', lc.date_added) >= '". $this->db->escape($first) ."' AND lc.notified = '0') 
						OR (TIMEDIFF('". $this->db->escape($this->now) ."', lc.date_notified) >= '". $this->db->escape($next) ."' AND lc.notified != '0'))
					AND (SELECT COUNT(*) FROM `" . DB_PREFIX . "losted_cart_log` ll WHERE ll.losted_id = lc.losted_id ) < '". (int) $this->settings['resending_max']  ."'
			";
		} else {
			$sql = "  
				SELECT lc.losted_id FROM `". DB_PREFIX ."losted_cart` lc
				WHERE lc.email != '' 
					AND (TIMEDIFF('". $this->db->escape($this->now) ."', lc.date_added) >= '". $this->db->escape($first) ."' AND lc.notified = '0')
					AND (SELECT COUNT(*) FROM `" . DB_PREFIX . "losted_cart_log` ll WHERE ll.losted_id = lc.losted_id ) < '". (int) $this->settings['resending_max']  ."'
			";			
		}

		if (isset($this->settings['groups_disabled'])) {
			$sql .= ' AND lc.customer_group_id NOT IN('. (implode(',', array_map('intval', $this->settings['groups_disabled']))) .')';
		}

		$list = $this->db->query($sql);

		return $list->rows;
	}

	public function logCustomer($losted_id) 
	{
		$this->db->query("
			UPDATE `". DB_PREFIX ."losted_cart` 
				SET 
					notified = '1', 
					date_notified = NOW()
				WHERE losted_id = '". (int) $losted_id ."'  
		");
		
		$this->db->query("
			INSERT INTO `". DB_PREFIX ."losted_cart_log` 
				SET 
					losted_id = '". (int) $losted_id ."',
					date_send = NOW() 
		");
	}

	private function preparationCustomerData() 
	{
		/* logged customer */
		if (empty($this->data) && (int) $this->customer->getId()) {
			$result = $this->db->query("
				SELECT firstname, lastname, email, telephone FROM `" . DB_PREFIX . "customer` 
					WHERE customer_id = '" . (int) $this->customer->getId() . "'  
					LIMIT 1
			");

			$this->data = $result->row;
		}

		/* run collector */
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post)) {

			$form = $this->request->post;

			/* email */
			if (isset($form['customer']) && !empty($form['customer']['email']) && 
				filter_var($form['customer']['email'], FILTER_VALIDATE_EMAIL)
			) {
				$email = $form['customer']['email'];
			} elseif (!empty($form['email']) && filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
				$email = $form['email'];
			}

			/* phone */
			if (isset($form['customer']) && !empty($form['customer']['telephone'])) {
				$phone = $form['customer']['telephone'];
			} elseif (!empty($form['telephone'])) {
				$phone = $form['telephone'];
			}

			/* customer */
	        foreach (['payment_address', 'shipping_address', 'customer'] as $block) {
	            if (!empty($form[$block])) {
	                if (!empty($form[$block]['firstname'])) {
	                    $firstname = $form[$block]['firstname'];
	                }

	                if (!empty($form[$block]['lastname'])) {
	                    $lastname = $form[$block]['lastname'];
	                }
	            }
	        }

	        if (empty($firstname) && !empty($form['firstname'])) {
	        	$firstname = $form['firstname'];
	        }

	        if (empty($lastname) && !empty($form['lastname'])) {
	        	$lastname = $form['lastname'];
	        }	
		}        

		/* marge date */
		if (!empty($this->data)) {
			if (!empty($this->data['email']) && filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
				$email = $this->data['email'];
			}

			if (!empty($this->data['telephone'])) {
				$phone = $this->data['telephone'];
			}

			if (!empty($this->data['firstname'])) {
				$firstname = $this->data['firstname'];
			}

			if (!empty($this->data['lastname'])) {
				$lastname = $this->data['lastname'];
			}			
		}

		$regex_user = '~[^A-Za-zа-яА-ЯЁёҐґЇїіІЄє\s]~u';
		
		/* sanitize */
		if (!empty($firstname)) {
			$firstname = trim(strip_tags(html_entity_decode($firstname, ENT_QUOTES, 'UTF-8')));
			$firstname = preg_replace($regex_user, '', $firstname);
			$firstname = preg_replace('/\s{2,}/', '', $firstname);
		} else {
			$firstname = '';
		}

		if (!empty($lastname)) {
			$lastname = trim(strip_tags(html_entity_decode($lastname, ENT_QUOTES, 'UTF-8')));
			$lastname = preg_replace($regex_user, '', $lastname);
			$lastname = preg_replace('/\s{2,}/', '', $lastname);
		} else {
			$lastname = '';
		}	
		
		if (!empty($phone)) {
			$phone = trim(strip_tags(html_entity_decode($phone, ENT_QUOTES, 'UTF-8')));
			$phone = preg_replace('~[^0-9]~', '', $phone);
		} else {
			$phone = '';
		}	

		if (!empty($email)) {
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		} else {
			$email = '';
		}						

		return 
		[
			'firstname'		    => $firstname,
			'lastname'		    => $lastname,
			'email'			    => $email,
			'telephone'		    => $phone, 
			'store_id'		    => $this->config->get('config_store_id'),
			'language_id'		=> $this->config->get('config_language_id'),
			'currency_code'		=> $this->session->data['currency'],
			'customer_id'		=> (int) $this->customer->getId(),
			'customer_group_id' => (int) $this->config->get('config_customer_group_id'),
			'session_id'		=> $this->session->getId()
		];
	}

	// collector 
	public function initCollector() 
	{
		/* customer created on startup cart/cart.php */
		if ((int)$this->customer->getId()) {
			return;
		}

		$route = !empty($this->request->get['_route_']) ? $this->request->get['_route_'] : 
			(!empty($this->request->get['route']) ? $this->request->get['route'] : false); 

		if ($route && $this->settings && !empty($this->settings['collector']['routers'])) { 
			$route = preg_replace('~/+$~', '', $route);

			$collector_routers = $this->settings['collector']['routers'];
			$collector_routers = array_map('trim', explode(PHP_EOL, $collector_routers));
			
			$script = '/view/javascript/losted-collector.js';

			if (in_array($route, $collector_routers) && file_exists(DIR_APPLICATION . $script)) {
				$this->document->addScript('catalog' . $script);
			}
		}
	}

}