<?php
class ControllerMarketingNewsletter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/newsletter');

		$this->getList();
	}

	public function delete() {
		$this->load->language('marketing/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/newsletter');
		$url = '';
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $newsletter_id) {
				$this->model_marketing_newsletter->deleteNewsletter($newsletter_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['delete'] = $this->url->link('marketing/newsletter/delete', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$data['newsletters'] = array();

		$filter_data = array(
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$newsletter_total = $this->model_marketing_newsletter->getTotalNewsletters($filter_data);

		$results = $this->model_marketing_newsletter->getNewsletters($filter_data);

		foreach ($results as $result) {
			$data['newsletters'][] = array(
				'id' 			=> $result['id'],
				'email'        => $result['email'],
				'date_added'   => date($this->language->get('date_format_short'), strtotime($result['date_added'])),				
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_none'] = $this->language->get('text_none');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['token'] = $this->session->data['user_token'];

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

	
		$url = '';

		$pagination = new Pagination();
		$pagination->total = $newsletter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($newsletter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($newsletter_total - $this->config->get('config_limit_admin'))) ? $newsletter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $newsletter_total, ceil($newsletter_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/newsletter', $data));
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'marketing/newsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}