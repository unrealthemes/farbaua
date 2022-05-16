<?php
class ControllerExtensionModuleShtml extends Controller {
	private $error = array();

	public function install() {
		$this->load->model('extension/module/shtml');
		$this->model_extension_module_shtml->install();
	}

	public function uninstall() {
		$this->load->model('extension/module/shtml');
		$this->model_extension_module_shtml->uninstall();
	}
			

	public function index() {
		
		$this->load->language('extension/module/shtml');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('extension/module/shtml');
		/* Andrey Bondarenko 02/07/2021 */
		if(!$this->model_extension_module_shtml->tableExists('shtml_blocks')) {
			$this->model_extension_module_shtml->install();			
		}
		/* Andrey Bondarenko end 02/07/2021 */
		$blocks=array();
		$copy_from=0;
		$showed_default_modules = $this->config->get('shtml2_showed_default_modules') ? true : false;
		if(!empty($this->request->request['copy_from'])) {
			$copy_from=(int)$this->request->request['copy_from'];
			$module_info = $this->model_setting_module->getModule($copy_from);
			$module_info['name']=$module_info['name'].'_copy';
			unset($this->request->post);
		} else {
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
				$categories=is_array($this->request->post['category'])?$this->request->post['category']:array();
				$manufacturers=is_array($this->request->post['manufacturer'])?$this->request->post['manufacturer']:array();
				$products=is_array($this->request->post['product'])?$this->request->post['product']:array();
				$layouts=is_array($this->request->post['layout'])?$this->request->post['layout']:array();
				$product_categories=is_array($this->request->post['product_category'])?$this->request->post['product_category']:array();
				$product_manufacturers=is_array($this->request->post['product_manufacturer'])?$this->request->post['product_manufacturer']:array();			
				$blocks = (isset($this->request->post['block']) && is_array($this->request->post['block'])) ? $this->request->post['block'] : array();			
				$url=isset($this->request->post['url'])?$this->request->post['url']:'';
			//	$layouts=is_array($this->request->post['layout_list'])?$this->request->post['layout_list']:array();
				unset($this->request->post['category']);
				unset($this->request->post['manufacturer']);
				unset($this->request->post['product']);
				//unset($this->request->post['layout_list']);
				unset($this->request->post['layout']);
				unset($this->request->post['product_category']);
				unset($this->request->post['product_manufacturer']);
				unset($this->request->post['block']);
				unset($this->request->post['filter']);
				unset($this->request->post['sharedblock']);
				
				if (!isset($this->request->get['module_id'])) {				
					$module_id=$this->model_setting_module->addModule('shtml', $this->request->post);			
				} else {
					$module_id=$this->request->get['module_id'];
					$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);			
				}
				$this->model_extension_module_shtml->addControllers($module_id, $categories, $manufacturers, $products, $layouts, $product_categories, $product_manufacturers, $url);
				/*Andrey Bondarenko 28/04/2020 -> */
				$this->model_extension_module_shtml->saveBlocks($module_id, $blocks);
				/*Andrey Bondarenko 25/03/2021 */
				$this->load->model('setting/setting');
				$this->model_setting_setting->editSetting('shtml2', array('shtml2_showed_default_modules'=>'1'));				
				$showed_default_modules = true;
				/*Andrey Bondarenko 25/03/2021 end */
				$this->session->data['success'] = $this->language->get('text_success');

				$this->response->redirect($this->url->link('extension/module/shtml', 'user_token=' . $this->session->data['user_token'] . '&module_id='.$module_id, true));
			}			
		}


		if (isset($this->error['warning'])) {
			$data['error']['warning'] = $this->error['warning'];
		} else {
			$data['error']['warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/shtml', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/shtml', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/shtml', 'user_token=' . $this->session->data['user_token'], true);
			$data['new_copy'] = '';
		} else {
			$data['action'] = $this->url->link('extension/module/shtml', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
			$data['new_copy'] = $this->url->link('extension/module/shtml', 'user_token=' . $this->session->data['user_token'].'&copy_from='.$this->request->get['module_id'], true);
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

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

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['admin_language_code']=$this->config->get('config_admin_language');
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		if (isset($this->request->post['condition'])) {
			$data['condition'] = $this->request->post['condition'];
		} elseif (!empty($module_info['condition'])) {
			$data['condition'] = $module_info['condition'];
		} else {
			$data['condition'] = '';
		}
		if (isset($this->request->post['url'])) {
			$data['url'] = $this->request->post['url'];
		} elseif (!empty($module_info['url'])) {
			$data['url'] = $module_info['url'];
		} else {
			$data['url'] = '';
		}
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info['limit'])) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = '';
		}		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		} else {
			$data['success'] = '';
		}
		unset($this->session->data['success']);
		
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
		} else {
			$data['error'] = '';
		}
		/* Andrey Bondarenko 10/05/2021 */
		if (isset($this->request->post['admin'])) {
			$data['admin'] = $this->request->post['admin'];
		} elseif (!empty($module_info['admin'])) {
			$data['admin'] = $module_info['admin'];
		} else {
			$data['admin'] = '';
		}
		/* Andrey Bondarenko end 10/05/2021 */
		/* Andrey Bondarenko 29/06/2021 */
		if (isset($this->request->post['preloader'])) {
			$data['preloader'] = $this->request->post['preloader'];
		} elseif (!empty($module_info['preloader'])) {
			$data['preloader'] = $module_info['preloader'];
		} else {
			$data['preloader'] = array();
		}
		if (isset($this->request->post['preloader_enabled'])) {
			$data['preloader_enabled'] = $this->request->post['preloader_enabled'];
		} elseif (!empty($module_info['preloader_enabled'])) {
			$data['preloader_enabled'] = $module_info['preloader_enabled'];
		} else {
			$data['preloader_enabled'] = 0;
		}
		$sp_data = array();
		$data['standard_preloader'] = htmlentities($this->load->view('extension/module/shtml_standard_preloader', $sp_data)); 
		/* Andrey Bondarenko end 29/06/2021 */		
		unset($this->session->data['error']);
		
		$data['other_modules']=array();
		$modules=$this->model_setting_module->getModulesByCode('shtml');
		foreach($modules as $module) {
			if(!isset($this->request->get['module_id']) || $module['module_id']!=$this->request->get['module_id']) {
				$data['other_modules'][]=array(
					'module_id'=>$module['module_id'],
					'name'=>$module['name']
				);
			}
		}
		
		$data['constructor']='';
		if($data['condition']!='') {
			$searches=array(
				'(', ')', '&&', '||', '!', '[p]', '[pc]', '[pm]', '[l]', '[u]', '[c]', '[m]',
			);
			$replacements=array(
				'<span class="condition_sign" data-value="(">(</span> ',
				'<span class="condition_sign" data-value=")">)</span> ',
				'<span class="condition_sign" data-value="&&">'.$this->language->get('text_and').'</span> ',
				'<span class="condition_sign" data-value="||">'.$this->language->get('text_or').'</span> ',
				'<span class="condition_sign" data-value="!">'.$this->language->get('text_not').'</span> ',
				'<span class="condition_sign" data-value="[p]">'.$this->language->get('entry_product').'</span> ',
				'<span class="condition_sign" data-value="[pc]">'.$this->language->get('entry_product_categories').'</span> ',
				'<span class="condition_sign" data-value="[pm]">'.$this->language->get('entry_product_manufacturers').'</span> ',
				'<span class="condition_sign" data-value="[l]">'.$this->language->get('entry_layouts').'</span> ',
				'<span class="condition_sign" data-value="[u]">'.$this->language->get('entry_url').'</span> ',
				'<span class="condition_sign" data-value="[c]">'.$this->language->get('entry_category').'</span> ',
				'<span class="condition_sign" data-value="[m]">'.$this->language->get('entry_manufacturer').'</span> ',
			);
			$data['constructor']=str_replace($searches, $replacements, html_entity_decode($data['condition']));	
		}
		$data['controllers']=array('categories'=>array(), 'manufacturers'=>array(), 'products'=>array(), 'layouts'=>array(), 'product_categories'=>array(), 'product_manufacturers'=>array(),);

		$data['blocks']=array();
		$copy_module_id=0;
		if(!empty($copy_from)) {
			$copy_module_id=$copy_from;
		} else {
			if(isset($this->request->get['module_id'])) {
				$copy_module_id=(int)$this->request->get['module_id'];
			}
		}
		if($copy_module_id) {			
			$data['controllers']=$this->model_extension_module_shtml->getControllers($copy_module_id);
			/*Andrey Bondarenko 28/04/2020 */
			if(empty($blocks)) {
				$blocks=$this->model_extension_module_shtml->getBlocks($copy_module_id);
			}
			/*Andrey Bondarenko end 28/04/2020 */
		}
		/*Andrey Bondarenko 28/04/2020 */
		$data['server_url']='https://white-blue.com.ua/';
		$data['blocks']=$blocks;
		$data['SERVER_NAME']=$_SERVER['SERVER_NAME'];
		$data['upload_block_action'] = 'https://white-blue.com.ua/save_shtml_block.php';
		/*Andrey Bondarenko 28/04/2020 end */
		$data['user_token']=$this->session->data['user_token'];
		/*Andrey Bondarenko 29/11/2019 */
		$this->load->model('catalog/category');
		$data['categories']=$this->model_catalog_category->getCategories(array('sort'=>'name'));
		$this->load->model('catalog/manufacturer');
		$data['manufacturers']=$this->model_catalog_manufacturer->getManufacturers(array('sort'=>'name'));			
		/*Andrey Bondarenko end 29/11/2019 */
		/*Andrey Bondarenko 03/03/2020 */
		$data['is_ukrmap']=(bool)count($this->model_setting_module->getModulesByCode('ukrmap'));
		$data['entry_including_regional']=$this->language->get('entry_including_regional');
		/*Andrey Bondarenko end 03/03/2020 */		
		/*Andrey Bondarenko 17/12/2019 */
		$this->load->model('design/layout');
		$data['layout_list']=$this->model_design_layout->getLayouts(array('sort'=>'name'/*Andrey Bondarenko 03/03/2020 -> */, 'regional'=>true));		
		/*Andrey Bondarenko end 17/12/2019 */
		/*Andrey Bondarenko 25/03/2021 */
		//$data['default_modules'] = array();
		//if(!$showed_default_modules) {
			$data['default_modules'] = $this->model_extension_module_shtml->getDefaultSharedBlocks();
		//}
		/*Andrey Bondarenko end 25/03/2021 */
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if(!$this->licenseCheck($data)) {
			return true;
		}
		$this->response->setOutput($this->load->view('extension/module/shtml', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/shtml')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	private function licenseCheck($data) 
	{
		preg_match('/[A-Z][a-z0-9]+$/', get_class($this), $conf_code);
		$license=$this->config->get($conf_code[0].'_license');
		if($license) {
			if(md5(preg_replace('/www./', '', $_SERVER['SERVER_NAME']).$conf_code[0].'Bill Gates - con')==$license) {
				return true;
			}
		}
		return $this->getLicenseKey($data);
	}

	private function getLicenseKey($data) 
	{
		$ch=curl_init('https://white-blue.com.ua/licenses.php');
		preg_match('/[A-Z][a-z0-9]+$/', get_class($this), $conf_code);
		$request=array(
			'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
			'lang'=>$this->config->get('config_admin_language'),
			'module_name'=>$conf_code[0]
		);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		$response = curl_exec($ch);
		curl_close($ch);
		$resp=json_decode($response, true);
		if(isset($resp['key'])) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting($conf_code[0], array($conf_code[0].'_license'=>$resp['key']));
			return true;
		} else {
			$this->load->language('extension/module/shtml');
			
			$data['error']['license_error']=isset($resp['error'])?$resp['error']:$this->language->get('error_activation');
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('catalog/license_form', $data));
			return false;
		}
	}

	public function loadSharedBlocks() 
	{
		$server_url='https://white-blue.com.ua/';
		$ch=curl_init($server_url.'get_shtml_blocks.php');		
		$request=array(
			'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
			'lang'=>$this->config->get('config_admin_language'),			
		);
		if(isset($this->request->request['filter'])) $request['filter']=$this->request->request['filter'];
		/* Andrey Bondarenko 26/03/2021 -> */
		if(isset($this->request->request['selected'])) $request['selected']=$this->request->request['selected'];
		$sort_field='shtml_block_name';
		$sort_order='ASC';
		if(isset($this->request->request['sort_field'])) {
			$sort_field=$this->request->request['sort_field'];
			if(isset($this->request->request['sort_order']) && 
			mb_strtoupper($this->request->request['sort_order'])=='DESC') {
				$sort_order='DESC';
			}			
		}
		$request['sort'][$sort_field]=$sort_order;
		//debug
		//$page_limit=1;
		$page_limit = $this->config->get('config_limit_admin');
		$page=1;
		if(isset($this->request->request['page'])) {
			$page=(int)$this->request->request['page'];
		}
		$request['start']=$page_limit * ($page - 1);
		$request['limit']=$page_limit;
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$request_string=http_build_query($request);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
		$response = curl_exec($ch);
		curl_close($ch);

		$data=json_decode($response, true);
		$this->load->language('extension/module/shtml');
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$desc_length=$this->config->get('theme_default_product_description_length');
		/* Andrey Bondarenko 25/03/2021 */
		//$data['selected_ids'] = !empty($request['filter']['shtml_block_id']) ? $request['filter']['shtml_block_id'] : array();
		//if(!is_array($data['selected_ids'])) $data['selected_ids'] = array($data['selected_ids']);
		/* Andrey Bondarenko end 25/03/2021 */		
		if(isset($data['blocks'])) {
			foreach($data['blocks'] as $block_id=>$block) {
				$data['blocks'][$block_id]['block_json']=json_encode($block, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
				if(mb_strlen($block['comment'])>$desc_length) {
					$data['blocks'][$block_id]['comment_brief'] = mb_substr($block['comment'], 0, $desc_length).'...';
				} else {
					$data['blocks'][$block_id]['comment_brief'] = $block['comment'];
				}
				/* Andrey Bondarenko 25/03/2021 */
				//if(in_array($block['shtml_block_id'], $data['selected_ids'])) $data['blocks'][$block_id]['selected']=true;
				/* Andrey Bondarenko end 25/03/2021 */	
			}
		}
		if(isset($data['error'])) {			
			foreach($data['error'] as $error_id=>$error) {
				//$data['error'][$error_id]=$this->language->get($error);
				foreach(explode(' ', $data['error'][$error_id]) as $text) {
					$data['error'][$error_id]=str_replace($text, $this->language->get($text), $data['error'][$error_id]);
				}				
			}			
		}		
		$total=1;
		if(isset($data['block_count'])) {
			$total=(int)$data['block_count'];
		}

		$data['sort_field']=$sort_field;
		$data['sort_order']=$sort_order;
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;		
		$pagination->limit = $page_limit;		
		$pagination->url = 'javascript:shareBlocksPage({page});';
		$data['pagination'] = $pagination->render();
		$data['pagination'] = str_replace('javascript:shareBlocksPage({page});','javascript:shareBlocksPage(1);', $data['pagination']);
		$data['page'] = $page;
		$data['server_url'] = $server_url;
		$data['img_width'] = $this->config->get('theme_default_image_category_width');
		$data['img_height'] = $this->config->get('theme_default_image_category_height');
		$this->response->setOutput($this->load->view('extension/module/shtml_shared_blocks', $data));
	}

	public function uploadBlock() 
	{
		$ch=curl_init('https://white-blue.com.ua/save_shtml_block.php');	

		$request=$this->request->request;
		if(empty($request['SERVER_NAME'])) $request['SERVER_NAME']=$_SERVER['SERVER_NAME'];
		
		foreach($request as $key=>$value) {
			if(is_array($value)) {
				$request[$key]=json_encode($value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
			}
		}
		if(!empty($this->request->files['image'])) {			
			$upload_directory=DIR_IMAGE.'cache/';
			$uploadfile = $upload_directory . token(5) .'_'. basename($this->request->files['image']['name']);
			if(move_uploaded_file($this->request->files['image']['tmp_name'], $uploadfile)) {
				
				$request['image']= new \CurlFile($uploadfile, $this->request->files['image']['type'], basename($this->request->files['image']['name']));			
			} else {

			}
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);		
		$response = curl_exec($ch);		
		curl_close($ch);
		$data=json_decode($response, true);
		if(isset($data['error'])) {
			$this->load->language('extension/module/shtml');
			foreach($data['error'] as $error_id=>$error) {				
				foreach(explode(' ', $data['error'][$error_id]) as $text) {
					$data['error'][$error_id]=str_replace($text, $this->language->get($text), $data['error'][$error_id]);
				}				
			}			
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));
	}
	
	public function autocomplete() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('extension/module/shtml');
			$data=json_decode($_POST['data'], true);
			$products=$this->model_extension_module_shtml->getProductList($data);
			echo json_encode($products, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}
	}
	
	public function getBlockData($args=array()) {
		$controller_ids=array();
		$this->load->model('extension/module/shtml');
		if(isset($this->request->get['product_id'])) {
			$controller_ids[]='p_'.$this->request->get['product_id'];			
		} 
		if(isset($this->request->get['category_id'])) {			
			$category_id=(int)$this->request->get['category_id'];
			$controller_ids[]='c_'.$category_id;
		} 
		if(isset($this->request->get['manufacturer_id'])) {
			$controller_ids[]='m_'.$this->request->get['manufacturer_id'];
		}		
		if(isset($this->request->get['product_id'])) {
			if(empty($this->model_catalog_product)) $this->load->model('catalog/product');
			$product_categories=$this->model_catalog_product->getProductCategories($this->request->get['product_id']);
			$product_info=$this->model_catalog_product->getProduct($this->request->get['product_id']);
			$product_info['href'] = $this->url->link('catalog/product/edit', 'product_id='.$product_info['product_id'].'&user_token='.$this->session->data['user_token'], true);
			foreach($product_categories as $product_category) {
				$controller_ids[]='pc_'.$product_category;
			}
			
			$product_manufacturer_id=$this->model_extension_module_shtml->getProductManufacturerId($this->request->get['product_id']);
			
			if($product_manufacturer_id) $controller_ids[]='pm_'.$product_manufacturer_id;
		}
		$controller_ids[]='l_all';		
		$modules=$this->model_extension_module_shtml->getModules($controller_ids);
		$filtered_modules=array();
		foreach($modules as $module_id=>$module) {
			if(empty($module['setting']['condition'])) {
				$filtered_modules[$module_id]=$module;
			} else {
				$module_controller_types=array();
				foreach($module['controllers'] as $controller) {
					$module_controller_types[]='['.explode('_', $controller)[0].']';
				}
				$condition=str_replace($module_controller_types, '1', $module['setting']['condition']);
				$condition=preg_replace('/[\[pmlcu\]]+/', '0', $condition);
				//debug
				//echo 'condition='.'return (bool) ('.$condition.')<br>';				
				$condition=preg_replace('/[^0-9\(\)\!\|\&]+/', '', $condition);
				//debug
				//echo 'condition='.'return (bool) ('.$condition.')<br>';
				$result=eval('return (bool) ('.$condition.');');
				if($result) {
					$filtered_modules[$module_id]=$module;
				}
			}
		}
		$htmls=array();
		$shtml_modules=array();	
		//var_export($filtered_modules);
		
		foreach($filtered_modules as $module_id=>$filtered_module) {
			$htmls+=$filtered_module['blocks'];
				//debug
				//$filter=$this->model_extension_module_shtml->getModuleFilter($module_id);
				//echo $this->model_extension_module_shtml->getProducts($filter).'<br>';								
					
			
		}
		$data['htmls']=json_encode($htmls, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		$data['shtml_modules']=json_encode($filtered_modules, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		if(isset($product_info)) {
			$data['product_info']=json_encode($product_info, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}
		
		return $data;
	}	
}