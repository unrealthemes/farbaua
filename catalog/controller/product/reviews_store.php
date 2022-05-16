<?php
class ControllerProductReviewsStore extends Controller {
	public function index() {
		$this->document->addStyle('catalog/view/theme/newstore/stylesheet/popup-reviews-store/stylers.css');
		$this->load->language('product/reviews_store');
		$this->load->model('catalog/reviews_store');
		$data['reviews_theme_rating'] = $this->model_catalog_reviews_store->getReviewsThemeRating();
		$data['text_review_guest'] = sprintf($this->language->get('text_review_guest'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
		$reviews_store_setting = $this->config->get('reviews_store_setting');
		if ((isset($reviews_store_setting['review_guest']) && ($reviews_store_setting['review_guest'] == 1)) || $this->customer->isLogged()) {
			$data['review_guest'] = true;
		} else {
			$data['review_guest'] = false;
		}

		if(isset($reviews_store_setting['status']) && ($reviews_store_setting['status'] == 1)){

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added_desc';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = 15;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/reviews_store', $url)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_sum_reviews'] = $this->language->get('text_sum_reviews');
		$data['btn_write_review'] = $this->language->get('btn_write_review');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['reviews_store_setting'] = $this->config->get('reviews_store_setting');
		$data['rating_store'] = $this->model_catalog_reviews_store->getSumAvgReviewsStore();
		$data['total_rs'] = $this->model_catalog_reviews_store->getTotalReviewsStore();
		$data['percent_rating'] = $this->model_catalog_reviews_store->getPercentReviewsStore();

		$filter_data = array(
			'sort'  => $sort,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		$data['reviews_store'] = array();
		$reviews_total = $this->model_catalog_reviews_store->getTotalReviewsStore();
		$results = $this->model_catalog_reviews_store->getAllReviews($filter_data);
		foreach ($results as $result) {
			$data['reviews_store'][] = array(
				'reviews_store_id'  	=> $result['reviews_store_id'],
				'admin_response'       	=> $result['admin_response'],
				'author'        		=> $result['author'],
				'like'   				=> $result['like'],
				'dislike'   			=> $result['dislike'],
				'avg_customer_rating'   => $this->model_catalog_reviews_store->getAvgRatingCustomer($result['reviews_store_id']),
				'description' 			=> html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'date_added' 			=> $result['date_added']

			);
		}





		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'date_added_desc',
			'href'  => $this->url->link('product/reviews_store', 'sort=date_added_desc' . $url)
		);
		$data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_asc'),
				'value' => 'date_added_asc',
				'href'  => $this->url->link('product/reviews_store', 'sort=date_added_asc' . $url)
		);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		$data['limits'] = array();

		$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('product/reviews_store', $url . '&limit=' . $value)
			);
		}
		$pagination = new Pagination();
		$pagination->total = $reviews_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('product/reviews_store', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($reviews_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($reviews_total - $limit)) ? $reviews_total : ((($page - 1) * $limit) + $limit), $reviews_total, ceil($reviews_total / $limit));


		if ($page == 1) {
		    $this->document->addLink($this->url->link('product/reviews_store', '', 'SSL'), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('product/reviews_store', '', 'SSL'), 'prev');
		} else {
		    $this->document->addLink($this->url->link('product/reviews_store', 'page='. ($page - 1), 'SSL'), 'prev');
		}

		if ($limit && ceil($reviews_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('product/reviews_store', 'page='. ($page + 1), 'SSL'), 'next');
		}

		$data['sort'] = $sort;
		$data['limit'] = $limit;
		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('product/reviews_store', $data));
		} else {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/reviews_store', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function write() {
		$this->load->language('product/reviews_store');
		$this->load->model('catalog/reviews_store');
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 25)) {
				$json['error'] = $this->language->get('error_author');
			}

			if ((utf8_strlen($this->request->post['description']) < 15) || (utf8_strlen($this->request->post['description']) > 1000)) {
				$json['error'] = $this->language->get('error_description');
			}

			if (isset($this->request->post['rating'])) {
				$rating = array_filter($this->request->post['rating']);
			} else {
				$rating = array();
			}

			$all_review_theme = $this->model_catalog_reviews_store->getAllReviewTheme();

			foreach ($all_review_theme as $result) {
				if(!isset($rating[$result['reviews_store_theme_id']])) {
					$json['error'] = $this->language->get('error_rating');
				}
			}

			$reviews_store_setting = $this->config->get('reviews_store_setting');
			if(isset($reviews_store_setting['captcha']) && ($reviews_store_setting['captcha'] == 1)){
			if(isset($this->request->post['captcha']) && $this->config->get($this->config->get('config_captcha') . '_status')) {
				$captcha = $this->load->controller((VERSION >= 2.2) ? 'extension/captcha/' . $this->config->get('config_captcha') . '/validate' : 'captcha/' . $this->config->get('config_captcha') . '/validate');
				if ($captcha) {
					$json['error'] = $captcha;
				}
			}
			}

			if (!isset($json['error'])) {
				$this->model_catalog_reviews_store->addReviewStore($this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function popupFormReviewStore() {
		$this->load->language('product/reviews_store');
		$this->load->model('catalog/reviews_store');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['text_store_rating'] = $this->language->get('text_store_rating');
		$data['entry_review'] = $this->language->get('entry_review');
		$data['text_note'] = $this->language->get('text_note');
		$data['btn_add_new_review_store'] = $this->language->get('btn_add_new_review_store');
		$data['reviews_theme'] = $this->model_catalog_reviews_store->getReviewsTheme();
		$data['reviews_store_setting'] = $this->config->get('reviews_store_setting');
		if(isset($data['reviews_store_setting']['captcha']) && ($data['reviews_store_setting']['captcha'] == 1)){
		if ($this->config->get($this->config->get('config_captcha') . '_status')) {
			$data['captcha_rs'] = $this->load->controller((VERSION >= 2.2) ? 'extension/captcha/' . $this->config->get('config_captcha') : 'captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha_rs'] = '';
		}
		}
		$this->response->setOutput($this->load->view('product/reviews_store_form', $data));
	}

	public function likeDislike() {
		$this->load->language('product/reviews_store');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('catalog/reviews_store');
			$reviews_store_id = $this->model_catalog_reviews_store->addLikeDislike($this->request->post);
			$json = $this->model_catalog_reviews_store->getLikeDislike($reviews_store_id);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
