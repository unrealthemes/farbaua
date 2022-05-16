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

class Cart extends \Controller 
{
	private $products;

	public function updateSession($session_id) 
	{
		if ($this->session->getId() == $session_id) {
			return;
		}

		$this->db->query("  
			UPDATE `". DB_PREFIX ."cart` 
				SET session_id = '". $this->db->escape($this->session->getId()) ."'
				WHERE session_id = '". $this->db->escape($session_id) ."'
		");
	}

	public function getAbandonedCart(array $abandoned) 
	{		 
		$cart_query = $this->db->query("  
			SELECT cart_id, product_id, quantity, `option` 
			FROM `". DB_PREFIX ."cart`
				WHERE session_id = '". $this->db->escape($abandoned['session_id']) ."'
					AND customer_id = '". (int) $abandoned['customer_id'] ."'
		");

		$now = date('Y-m-d') . ' 00:00:00';

		$cart_total    = 0;
		$cart_quantity = 0;
		$product_data  = [];

		foreach ($cart_query->rows as $cart) {
			
			$option_price  = 0;
			$option_points = 0;
			$option_weight = 0;
			$option_data   = [];

			if (!$cart['quantity']) {
				continue;
			}

			$product_query = 
			"  
				SELECT p.price, p.product_id, pd.name, p.model, p.image, p.tax_class_id 
				FROM " . DB_PREFIX . "product_to_store p2s 
				LEFT JOIN " . DB_PREFIX . "product p 
					ON (p2s.product_id = p.product_id) 
				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id) 
				WHERE p2s.store_id = '" . (int) $abandoned['store_id'] . "' 
					AND p2s.product_id = '" . (int) $cart['product_id'] . "' 
					AND pd.language_id = '" . (int) $abandoned['language_id'] . "' 
					AND p.date_available <= '". $this->db->escape($now) ."'
					AND p.status = '1'
				LIMIT 1
			";

			$hash = md5($product_query);

			if (!isset($this->products[$hash])) {
				$product_query = $this->db->query($product_query);
				
				if (!$product_query->num_rows) { 
					continue;
				}

				$product_query = $this->products[$hash] = $product_query->row;
			} else {
				$product_query = $this->products[$hash];
			}

			foreach (json_decode($cart['option']) as $product_option_id => $value) {
				
				$option_query = 
					$this->db->query("
						SELECT po.product_option_id, po.option_id, od.name, o.type 
						FROM " . DB_PREFIX . "product_option po 
						LEFT JOIN `" . DB_PREFIX . "option` o 
							ON (po.option_id = o.option_id) 
						LEFT JOIN " . DB_PREFIX . "option_description od 
							ON (o.option_id = od.option_id) 
						WHERE po.product_option_id = '" . (int) $product_option_id . "' 
							AND po.product_id  = '" . (int) $cart['product_id'] . "' 
							AND od.language_id = '" . (int) $abandoned['language_id'] . "' 
						LIMIT 1
					");

					if ($option_query->num_rows) {

						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio') {
							
							$option_value_query = 
								$this->db->query("
									SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, 
										pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix 
									FROM " . DB_PREFIX . "product_option_value pov 
									LEFT JOIN " . DB_PREFIX . "option_value ov 
										ON (pov.option_value_id = ov.option_value_id) 
									LEFT JOIN " . DB_PREFIX . "option_value_description ovd 
										ON (ov.option_value_id = ovd.option_value_id) 
									WHERE pov.product_option_value_id = '" . (int) $value . "' 
										AND pov.product_option_id = '" . (int) $product_option_id . "'
										AND ovd.language_id = '" . (int) $abandoned['language_id'] . "' 
									LIMIT 1 
								");

							if ($option_value_query->num_rows) {

								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								$option_data[] = 
								[
									'name'         => $option_query->row['name'],
									'value'        => $option_value_query->row['name'],
									'quantity'     => $option_value_query->row['quantity'],
									'price'        => $option_value_query->row['price'],
									'price_prefix' => $option_value_query->row['price_prefix'],
								];
							}
						
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
							
							foreach ($value as $product_option_value_id) {
								
								$option_value_query = 
									$this->db->query("
										SELECT pov.option_value_id, pov.quantity, pov.subtract, pov.price, 
											pov.price_prefix, pov.points, pov.points_prefix, pov.weight, 
											pov.weight_prefix, ovd.name 
										FROM " . DB_PREFIX . "product_option_value pov 
											LEFT JOIN " . DB_PREFIX . "option_value_description ovd 
												ON (pov.option_value_id = ovd.option_value_id) 
											WHERE pov.product_option_value_id = '" . (int) $product_option_value_id . "' 
												AND pov.product_option_id = '" . (int) $product_option_id . "' 
												AND ovd.language_id = '" . (int) $abandoned['language_id'] . "'  
											LIMIT 1
									");

								if ($option_value_query->num_rows) {

									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									$option_data[] = 
									[
										'name'         => $option_query->row['name'],
										'value'        => $option_value_query->row['name'],
										'quantity'     => $option_value_query->row['quantity'],
										'price'        => $option_value_query->row['price'],
										'price_prefix' => $option_value_query->row['price_prefix']
									];
								}
							}

						}
					}
			}

			$price = $product_query['price'];

			/* Product Discounts */
			$discount_quantity = 0;

			foreach ($cart_query->rows as $cart_2) {
				if ($cart_2['product_id'] == $cart['product_id']) {
					$discount_quantity += $cart_2['quantity'];
				}
			}

			$product_discount_query = 
				$this->db->query("
					SELECT price FROM " . DB_PREFIX . "product_discount 
					WHERE product_id = '" . (int)$cart['product_id'] . "' 
						AND customer_group_id = '" . (int) $abandoned['customer_group_id'] . "' 
						AND quantity <= '" . (int)$discount_quantity . "' 
						AND ((date_start = '0000-00-00' OR date_start < '". $this->db->escape($now) ."') 
							AND (date_end = '0000-00-00' OR date_end > '". $this->db->escape($now) ."')) 
					ORDER BY quantity DESC, priority ASC, price ASC 
					LIMIT 1
				");

			if ($product_discount_query->num_rows) {
				$price = $product_discount_query->row['price'];
			}

			/* Product Specials */
			$product_special_query = 
				$this->db->query("
					SELECT price FROM " . DB_PREFIX . "product_special 
					WHERE product_id = '" . (int)$cart['product_id'] . "' 
						AND customer_group_id = '" . (int) $abandoned['customer_group_id'] . "' 
						AND ((date_start = '0000-00-00' OR date_start < '". $this->db->escape($now) ."') 
							AND (date_end = '0000-00-00' OR date_end > '". $this->db->escape($now) ."')) 
					ORDER BY priority ASC, price ASC 
					LIMIT 1
				");

			if ($product_special_query->num_rows) {
				$price = $product_special_query->row['price'];
			}

			$product_data['products'][] = 
			[
				'cart_id'		  => $cart['cart_id'], 
				'product_id'      => $product_query['product_id'],
				'name'            => $product_query['name'],
				'model'           => $product_query['model'],
				'image'           => $product_query['image'],
				'option'          => $option_data,
				'quantity'        => $cart['quantity'],
				'tax_class_id'    => $product_query['tax_class_id'],
				'price'           => ($price + $option_price),
				'total'           => ($price + $option_price) * $cart['quantity'],
			];

			$cart_total    += ($price + $option_price) * $cart['quantity'];
			$cart_quantity += $cart['quantity'];
		}

		if (!empty($product_data)) {
			$product_data['quantity'] = $cart_quantity;
			/* $product_data['total'] = $cart_total; bad idea, different tax_class_id */
		}

		return $product_data;
	}			
}