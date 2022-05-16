<?php
class ControllerExtensionModuleReviewscustomer extends Controller {
    public function index($setting) {
		$setting = $setting['reviewscustomer'];
	
        $this->language->load('extension/module/reviewscustomer');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $this->load->model('catalog/reviewscustomer');
        
        $data['module'] = 'reviews';

        $data['module_header'] = $setting['module_header'][$this->config->get('config_language_id')];

        $data['reviews'] = array();

        if (!$setting['limit']) {
			$setting['limit'] = 4;
		}
		
        if (isset($setting['category_sensitive']) && !empty($this->request->get['path'])){
            $categories = explode('_', $this->request->get['path']);
            $category_id = (int) array_pop($categories);
        } else {
            $category_id = 0;
        }

        if ($setting['order_type'] == 'last') {
            $results = $this->model_catalog_reviewscustomer->getLatestCustomerReviews($setting['limit'], $category_id);
        } else {
            $results = $this->model_catalog_reviewscustomer->getRandomCustomerReviews($setting['limit'], $category_id);
        }

        foreach ($results as $result) {
            if ($this->config->get('config_review_status')) {
                $rating = $result['rating'];
            } else {
                $rating = false;
            }

            $product_id = false;
            $product = false;
            $prod_thumb = false;
            $prod_name = false;
            $prod_model = false;
            $prod_href = false;

            $product = $this->model_catalog_product->getProduct($result['product_id']);
             
			if ($product['image']) {
				if (VERSION >= 2.2) {
					$thumb = $this->model_tool_image->resize($product['image'], 150, 150);
				} else {
					$thumb = $this->model_tool_image->resize($product['image'], 150, 150);
				}
            }

            $data['reviews'][] = array(
				'product_id'  => $product['product_id'],
                'prod_thumb'  => $thumb,
                'prod_name'   => $product['name'],
                'review_id'   => $result['review_id'],
                'rating'      => $rating,
				'description' => utf8_substr(strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8')), 0, 150) . '..',
                'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'author'      => $result['author'],
                'prod_href'   => $this->url->link('product/product', 'product_id=' . $product['product_id'])
            );

			$data['link_all_reviews'] = $this->url->link('product/reviewscustomer');
			
        }
		
        $data['text_all_reviews'] = $this->language->get('text_all_reviews');
		
		$data['show_all_button'] = '';
		if(isset($setting['show_all_button'])) {
			$data['show_all_button'] = $setting['show_all_button'];
		}
		if($data['reviews']){
			if (VERSION >= 2.2) {
				return $this->load->view('extension/module/reviewscustomer', $data);
			} else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/reviewscustomer.tpl')) {
					return $this->load->view($this->config->get('config_template') . '/template/extension/module/reviewscustomer.tpl', $data);
				} else {
					return $this->load->view('default/template/extension/module/reviewscustomer.tpl', $data);
				}
			}
		}
    }
}