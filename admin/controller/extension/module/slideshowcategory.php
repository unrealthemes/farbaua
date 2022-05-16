<?php
class ControllerExtensionModuleSlideshowCategory extends Controller {
	private $error = array();

	public function index() {
	
		$this->load->language('extension/module/slideshowcategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('slideshowcategory', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title_name'] = $this->language->get('entry_title_name');
		$data['entry_banner'] = $this->language->get('entry_banner');
		$data['setting_sub_cat_limit'] = $this->language->get('setting_sub_cat_limit');
		$data['setting_column_limit'] = $this->language->get('setting_column_limit');
		$data['setting_column_limit_child'] = $this->language->get('setting_column_limit_child');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sub_category'] = $this->language->get('entry_sub_category');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['text_category'] = $this->language->get('text_category');
		$data['entry_manufactures'] = $this->language->get('entry_manufactures');
		$data['text_manufactures'] = $this->language->get('text_manufactures');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['button_banner_add'] = $this->language->get('button_banner_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['title_wallcategory_btn'] = $this->language->get('title_wallcategory_btn');
		
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
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
		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
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
				'href' => $this->url->link('extension/module/slideshowcategory', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/slideshowcategory', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/slideshowcategory', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/slideshowcategory', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

			$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
			$data['href_wallcategory'] = $this->url->link('design/slideshowcategory', 'user_token=' . $this->session->data['user_token'], 'SSL');

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
		if (isset($this->request->post['slideshow_category_id'])) {
			$data['slideshow_category_id'] = $this->request->post['slideshow_category_id'];
		} elseif (!empty($module_info)) {
			$data['slideshow_category_id'] = $module_info['slideshow_category_id'];
		} else {
			$data['slideshow_category_id'] = '';
		}
		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = '';
		}
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		if (isset($this->request->post['show_slider_sub_category'])) {
			$data['show_slider_sub_category'] = $this->request->post['show_slider_sub_category'];
		} elseif (!empty($module_info)) {
			$data['show_slider_sub_category'] = $module_info['show_slider_sub_category'];
		} else {
			$data['show_slider_sub_category'] = '';
		}
	
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		
		
		$data['categories'] = array();		
		$categories_results = $this->model_catalog_category->getCategories(array('start'=>0,'limit'=>9999,'sort'=>'name'));
		foreach ($categories_results as $result) {
			$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => addslashes($result['name'])
			);
		}	
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		
		if (isset($this->request->post['category_banner'])) {
			$data['category_banner'] = $this->request->post['category_banner'];
		} elseif (!empty($module_info)) {
			$data['category_banner'] = $module_info['category_banner'];
		} else {
			$data['category_banner'] = array();
		}
		
		if (!empty($data['category_banner'])){
			foreach ($data['category_banner'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			} 
			array_multisort($sort_order, SORT_ASC, $data['category_banner']);
		}
		$data['category_banners'] = array();

		foreach ($data['category_banner'] as $value) {
			foreach($data['languages'] as $language){
				if (is_file(DIR_IMAGE . $value['image'][$language['language_id']])) {
					$image = $value['image'][$language['language_id']];
					$thumb = $this->model_tool_image->resize($value['image'][$language['language_id']],100,100);
				} else {
					$image = '';
					$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
				}
				$image_catalog[$language['language_id']] = $image;
				$thumb_resize[$language['language_id']] = $thumb;
			}

			$data['category_banners'][] = array(
				'image' 				=> $image_catalog,	
				'thumb' 				=> $thumb_resize,
				'description'          	=> $value['description'],
				'link'          		=> $value['link'],
				'sort_order'          	=> $value['sort_order'],
				
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/slideshowcategory', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/slideshowcategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}