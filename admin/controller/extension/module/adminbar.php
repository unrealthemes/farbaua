<?php
class ControllerExtensionModuleAdminBar extends Controller {
	private $version = '3.1.0';
	private $error = array();

	public function index() {
		$this->load->language('extension/module/adminbar');
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_form()) {
			$module_admin_bar = array('module_admin_bar'=> $this->request->post['adminbar']);
			$this->model_setting_setting->editSetting('module_admin_bar', $module_admin_bar);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		$this->document->setTitle($this->language->get('heading_title'));
		$data['heading_title'] = $this->language->get('heading_title') . ' ' . $this->version;

		$data['path_to_admin']                   = HTTPS_SERVER;
		$data['version']                         = $this->version;

		$data['product_field'] = array ('sku','upc','ean','jan','isbn','mpn','weight','length','width','height');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/adminbar', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/adminbar', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['adminbar'])) {
			$data['adminbar'] = $this->request->post['adminbar'];
		} elseif ($this->config->has('module_admin_bar')) {
			$data['adminbar'] = $this->config->get('module_admin_bar');
		} else {
			$data['adminbar']['status'] = 0;
			$data['adminbar']['order'] = array();
			$data['adminbar']['neworder'] = array();
			$data['adminbar']['popup_width'] = 600;
			$data['adminbar']['custom_link'] = array();
			if ($this->request->server['HTTPS']) {
				$server = HTTPS_SERVER;
			} else {
				$server = HTTP_SERVER;
			}
			$data['adminbar']['path'] = $server;
		}

		if (!isset($data['adminbar']['version'])) $data['adminbar']['version'] = $this->version;

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/adminbar', $data));
	}

	public function editproduct() {
		if ($this->validate() && $this->validate_product()) {
			if (	isset($this->request->get['product_id']) 
				&& 	isset($this->request->get['field'])) {
				$allow_field = array('meta_description','meta_h1', 'name', 'meta_title', 'price','quantity','keyword','tag');
				if (in_array($this->request->get['field'], $allow_field)) {
					$value = trim($this->request->post['value']);
					if (in_array($this->request->get['field'], array('price','quantity'))) {
						$value = str_replace(',','.',$value);
						$sql = " UPDATE " . DB_PREFIX . "product SET `" . $this->db->escape($this->request->get['field']) ."` = '" . (float) $value . "' WHERE product_id = " . (int) $this->request->get['product_id'];
						$this->db->query($sql);
						echo 'ok';
					} elseif (in_array($this->request->get['field'], array('meta_title','meta_h1','meta_description','name','tag')))  {
						if (isset($this->request->get['language_id'])) {
							$value = trim($this->request->post['value']);
							if ( $this->request->get['field'] == 'name' && !$value) {
								echo "error - empty name"; return;
							}
							$sql = " UPDATE " . DB_PREFIX . "product_description SET `" . $this->db->escape($this->request->get['field']) ."` = '" . $this->db->escape($value) . "'
							WHERE product_id = " . (int)$this->request->get['product_id'] . " AND language_id = '" . (int) $this->request->get['language_id'] . "'";
//						echo $sql;
							$this->db->query($sql);
							echo 'ok';
						} else {
							echo 'error - language_id empty';
						}
					} elseif ($this->request->get['field'] == 'keyword') {
						$value = trim($this->request->post['value']);
						$sql = "SELECT * FROM " . DB_PREFIX . "seo_url WHERE 
						keyword  = '" . $this->db->escape($value) . "' 
						AND  query != 'product_id=" . (int)$this->request->get['product_id'] . "'";
						$result = $this->db->query($sql);
						if ($result->num_rows) {
							echo "error - double keyword";
						} else {
							$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url 
							WHERE query = 'product_id=" . (int)$this->request->get['product_id'] . "'
							AND language_id = '" . (int)$this->request->get['language_id'] . "'
							AND store_id = '" . (int)$this->request->get['store_id'] . "'");
							if ($value) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET 
								query = 'product_id=" . (int)$this->request->get['product_id'] . "', 
								keyword = '" . $this->db->escape($value) . "',
								language_id = '" . (int)$this->request->get['language_id'] . "',
								store_id = '" . (int)$this->request->get['store_id'] . "'");
							}
							echo "ok";
						}
					}
				} else {
					echo 'error - not allowed field';
				}
			} else {
				echo 'error - product_id or field is absent';
			}
		} else {
			echo 'error - not access ';
		}
	}
	
	public function editcategory() {
		if ($this->validate() && $this->validate_product()) {
			if (	isset($this->request->get['category_id']) 
				&& 	isset($this->request->get['field']) ) {
				$allow_field = array('meta_description','meta_h1', 'name', 'meta_title');
				if (in_array($this->request->get['field'], $allow_field)) {
					if (isset($this->request->get['language_id'])) {
						$sql = " UPDATE " . DB_PREFIX . "category_description 
							SET `" . $this->request->get['field'] ."` = '" . $this->db->escape($this->request->post['value']) . "'
							WHERE category_id = " . (int) $this->request->get['category_id'] . " 
							AND language_id = '" . $this->request->get['language_id'] . "'";
							$this->db->query($sql);
							echo 'ok';
					} else {
							echo 'error';
					}
				} else {
					echo 'error';
				}
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	}

	public function editinformation() {
		if ($this->validate() && $this->validate_information()) {
			if (	isset($this->request->get['information_id']) 
				&& 	isset($this->request->get['field']) ) {
				$allow_field = array('meta_description','meta_h1', 'name', 'meta_title');
				if (in_array($this->request->get['field'], $allow_field)) {
					if (isset($this->request->get['language_id'])) {
						$sql = " UPDATE " . DB_PREFIX . "information_description 
							SET `" . $this->request->get['field'] ."` = '" . $this->db->escape($this->request->post['value']) . "'
							WHERE information_id = " . (int) $this->request->get['information_id'] . " 
							AND language_id = '" . $this->request->get['language_id'] . "'";
							$this->db->query($sql);
							echo 'ok';
					} else {
							echo 'error';
					}
				} else {
					echo 'error';
				}
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	}

	public function editmanufacturer() {
		if ($this->validate() && $this->validate_manufacturer()) {
			if (	isset($this->request->get['manufacturer_id']) 
				&& 	isset($this->request->get['field']) ) {
				$allow_field = array('meta_description','meta_h1', 'name', 'meta_title');
				if (in_array($this->request->get['field'], $allow_field)) {
					if (isset($this->request->get['language_id'])) {
						$sql = " UPDATE " . DB_PREFIX . "manufacturer_description 
							SET `" . $this->request->get['field'] ."` = '" . $this->db->escape($this->request->post['value']) . "'
							WHERE manufacturer_id = " . (int) $this->request->get['manufacturer_id'] . " 
							AND language_id = '" . $this->request->get['language_id'] . "'";
							$this->db->query($sql);
							echo 'ok';
					} else {
							echo 'error';
					}
				} else {
					echo 'error';
				}
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	}

    public function clearcache() {
		$result ='';
		if ($this->validate()) {
			if (isset($this->request->get['type'])) {
				if ($this->request->get['type'] == 'image') {
					$result .= $this->cleanDirectory(DIR_IMAGE . 'cache/');
				} elseif ($this->request->get['type'] == 'system') {
					$result .= $this->cleanDirectory(DIR_CACHE);
				} else {
					$result .= 'error cache clear';
				}
			}
		} else {
			$result .= 'error cache permission';
		}
		$this->response->setOutput($result);
    }
	
    protected function cleanDirectory($directory){
        if (file_exists($directory)) {
            $result = '';
            $it = new RecursiveDirectoryIterator($directory);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

            foreach($files as $file) {
                if (($file->getFilename() === '.') 
					|| ($file->getFilename() === '..')
					|| ($file->getFilename() === 'index.html') 
					|| ($file->getFilename() === 'index.htm')) {
                    continue;
                }

                if ($file->isDir()){
                    @rmdir($file->getRealPath());
                    $result .= 'Remove folder: ' . $file . PHP_EOL;
                } else {
                    @unlink($file->getRealPath());
                    $result .= 'Remove file: ' . $file . PHP_EOL;
                }
            }

        } else {
            $result = $directory . ' not found';
        }

        return $result;
    }
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/adminbar')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate_product() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate_category() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate_information() {
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate_manufacturer() {
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	protected function validate_form() {
		if (!$this->user->hasPermission('modify', 'extension/module/adminbar')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (isset($this->request->post['adminbar']['custom_link'])) {
			$adminbar_custom_link = $this->request->post['adminbar']['custom_link'];
			if (is_array($adminbar_custom_link)) {
				$customs = array();
				foreach ($adminbar_custom_link as $custom_link) {
					if ($custom_link['name'] && $custom_link['href']) {
						if (isset($custom_link['new_window'])) {
							$href = urldecode($custom_link['href']);
							$href = htmlspecialchars_decode ($href);
							$custom_link['href'] = urldecode($href);
						} else {
							$href = str_replace($this->request->post['adminbar']['path'],'',urldecode($custom_link['href']));
							$href = str_replace('index.php', '',$href);
							$href = str_replace('?', '',$href);
							$href = htmlspecialchars_decode ($href);
							parse_str($href, $query_array);
							unset($query_array['user_token']);
							$custom_link['href'] = urldecode(http_build_query($query_array));
						}
						$customs[] = $custom_link;
					}
				}
				$this->request->post['adminbar']['custom_link'] = $customs;
			}
		} else {
			$this->request->post['adminbar']['custom_link'] =array();
		}
		if (!isset($this->request->post['adminbar']['neworder'])) {
			$this->request->post['adminbar']['neworder'] =array();
		}
		

		return !$this->error;
	}

	private function getEvents() {
		$events = array(
			'ab_Panel' => array(
				'trigger' => 'catalog/controller/common/footer/after',
				'action'  => 'extension/module/adminbar',
			),
			'ab_Template' => array(
				'trigger' => 'catalog/view/*/after',
				'action'  => 'extension/module/adminbar/template',
			),
		);
		return $events;
	}
	public function install() {
		$events = $this->getEvents();
		$this->load->model('setting/event');
		foreach ($events as $code=>$value) {
			$this->model_setting_event->deleteEventByCode($code);
			$this->model_setting_event->addEvent($code, $value['trigger'], $value['action'], 1,9999);
		}
	}

	public function uninstall() {
		$events = $this->getEvents();
		$this->load->model('setting/event');
		foreach ($events as $code=>$value) {
			$this->model_setting_event->deleteEventByCode($code);
		}				
	}
	
}