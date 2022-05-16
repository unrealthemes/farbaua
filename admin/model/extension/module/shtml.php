<?php
class ModelExtensionModuleSHTML extends Model {
	public function install() {
		$old_version = false;
		/* Andrey Bondarenko 02/07/2021 */
		if($this->tableExists('html_to_controller')) {
			$this->db->query('RENAME TABLE '.DB_PREFIX.'html_to_controller TO '.DB_PREFIX.'shtml_to_controller');
			$this->db->query('ALTER TABLE '.DB_PREFIX.'shtml_to_controller CHANGE `html_module_id` `shtml_module_id` INT(11) NOT NULL');
			$old_version = true;
		}
		if($this->tableExists('shtml_to_controller')) {			
			$this->db->query('ALTER TABLE '.DB_PREFIX.'shtml_to_controller CHANGE `controller_id` `controller_id` VARCHAR(500) NOT NULL');
		} else {		
		/* Andrey Bondarenko end 02/07/2021 */
			$this->db->query('CREATE TABLE `'.DB_PREFIX.'shtml_to_controller` ( `id` INT NOT NULL AUTO_INCREMENT , `shtml_module_id` INT NOT NULL , `controller_id` VARCHAR(500) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB');
		}
		/* Andrey Bondarenko 02/07/2021 */
		if(!$this->tableExists('shtml_blocks')) {
			$this->db->query('CREATE TABLE `'.DB_PREFIX.'shtml_blocks` (
				`shtml_block_id` INT(11) NOT NULL AUTO_INCREMENT,
				`shtml_module_id` INT(11) NOT NULL,
				`shtml_block_name` TEXT NOT NULL DEFAULT "",
				`shtml_block_code` TEXT NOT NULL DEFAULT "",
				`location` VARCHAR(250) NOT NULL DEFAULT "",
				`position` TINYINT(4) NOT NULL DEFAULT "0",
				PRIMARY KEY (`shtml_block_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8');			
		}		  
		$this->db->query('ALTER TABLE '.DB_PREFIX.'module CHANGE `setting` `setting` MEDIUMTEXT');
		if($old_version) {
			if(version_compare(VERSION, '3.0.0', '>=')) {
				$this->load->model('setting/module');
				$model = $this->model_setting_module;
			} else {
				$this->load->model('extension/module');
				$model = $this->model_extension_module;
			}
			$modules = $model->getModulesByCode('shtml');
			$this->load->model('localisation/language');			
			$languages = $this->model_localisation_language->getLanguages();
			foreach($modules as $module) {				
				$setting = json_decode($module['setting'], true);
				if($setting) {
					$block = array(
						'shtml_module_id' => $module['module_id'],
						'shtml_block_name' => array(),
						'shtml_block_code' => array(),
						'location' => isset($setting['location']) ? $setting['location'] : '',
						'position' => isset($setting['position']) ? $setting['position'] : 1,
					);
					foreach($languages as $language) {
						$block['shtml_block_name'][$language['code']] = isset($setting['module_description'][$language['language_id']]['title']) ? $setting['module_description'][$language['language_id']]['title'] : 'block '.$module['module_id'];
						$block['shtml_block_code'][$language['code']] = isset($setting['module_description'][$language['language_id']]['description']) ? $setting['module_description'][$language['language_id']]['description'] : '';
						if(!isset($setting['preloader'][$language['code']])) $setting['preloader'][$language['code']] = '';
					}
					$this->saveBlocks($module['module_id'], array($block));
					if(!isset($setting['condition'])) $setting['condition'] = '';
					if(!isset($setting['preloader_enabled'])) $setting['preloader_enabled'] = 0;
					if(!isset($setting['url'])) $setting['url'] = '';
					if(!isset($setting['admin'])) $setting['admin'] = 0;
					$model->editModule($module['module_id'], $setting);
				}
			}
		}		
		
	}

	public function uninstall() {
		$this->db->query('DROP TABLE '.DB_PREFIX.'shtml_to_controller');
		$this->db->query('DROP TABLE '.DB_PREFIX.'shtml_blocks');
	}

	public function getControllers($module_id) {
		$query=$this->db->query('SELECT * FROM '.DB_PREFIX.'shtml_to_controller WHERE shtml_module_id="'.(int)$module_id.'"');
		$result=array('categories'=>array(), 'manufacturers'=>array(), 'products'=>array(),'layouts'=>array(), 'product_categories'=>array(), 'product_manufacturers'=>array());
		if($query->num_rows) {
			foreach($query->rows as $row) {
				$controller_id=explode('_', $row['controller_id']);
				switch ($controller_id[0]) {
					case 'c':
						$name=$this->db->query('SELECT name FROM '.DB_PREFIX.'category_description WHERE category_id="'.(int)$controller_id[1].'" AND language_id="'.(int)$this->config->get('config_language_id').'"');
						if(isset($name->row['name'])) $result['categories'][]=array('category_id'=>$controller_id[1], 'name'=>$name->row['name']);
						break;
					case 'm':
						$name=$this->db->query('SELECT name FROM '.DB_PREFIX.'manufacturer WHERE manufacturer_id="'.(int)$controller_id[1].'"');
						if(isset($name->row['name'])) $result['manufacturers'][]=array('manufacturer_id'=>$controller_id[1], 'name'=>$name->row['name']);
						break;
					case 'p':
						 $name=$this->db->query('SELECT name FROM '.DB_PREFIX.'product_description WHERE product_id="'.(int)$controller_id[1].'" AND language_id="'.(int)$this->config->get('config_language_id').'"');
						 if(isset($name->row['name'])) $result['products'][]=array('product_id'=>$controller_id[1], 'name'=>$name->row['name']);
						break;
					case 'l':
						$this->load->language('extension/module/shtml');
						$name=$this->db->query('SELECT name FROM '.DB_PREFIX.'layout WHERE layout_id="'.$this->db->escape($controller_id[1]).'"');
						if(isset($name->row['name']) || $controller_id[1]=='all') $result['layouts'][]=array('layout_id'=>$controller_id[1], 'name'=>($controller_id[1]=='all')?$this->language->get('ms_entry_all'):$name->row['name']);
						break;
					case 'pc':
						$name=$this->db->query('SELECT name FROM '.DB_PREFIX.'category_description WHERE category_id="'.(int)$controller_id[1].'" AND language_id="'.(int)$this->config->get('config_language_id').'"');
						if(isset($name->row['name'])) $result['product_categories'][]=array('category_id'=>$controller_id[1], 'name'=>$name->row['name']);
						break;
					case 'pm':
						$name=$this->db->query('SELECT name FROM '.DB_PREFIX.'manufacturer WHERE manufacturer_id="'.(int)$controller_id[1].'"');
						if(isset($name->row['name'])) $result['product_manufacturers'][]=array('manufacturer_id'=>$controller_id[1], 'name'=>$name->row['name']);
						break;												
				}
			}
		}
		return $result;
	}

	public function addControllers($module_id, $categories=array(), $manufacturers=array(), $products=array(), $layouts=array(), $product_categories=array(), $product_manufacturers=array(), $url='') {
		$this->db->query('DELETE FROM '.DB_PREFIX.'shtml_to_controller WHERE shtml_module_id="'.(int)$module_id.'"');
		foreach($categories as $category) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_to_controller SET shtml_module_id="'.(int)$module_id.'", controller_id="'.('c_'.(int)$category).'"');
		}
		foreach($manufacturers as $manufacturer) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_to_controller SET shtml_module_id="'.(int)$module_id.'", controller_id="'.('m_'.(int)$manufacturer).'"');
		}
		foreach($products as $product) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_to_controller SET shtml_module_id="'.(int)$module_id.'", controller_id="'.('p_'.(int)$product).'"');
		}
		foreach($layouts as $layout) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_to_controller SET shtml_module_id="'.(int)$module_id.'", controller_id="'.('l_'.$this->db->escape($layout).'"'));
		}
		foreach($product_categories as $product_category) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_to_controller SET shtml_module_id="'.(int)$module_id.'", controller_id="'.('pc_'.$this->db->escape($product_category).'"'));
		}
		foreach($product_manufacturers as $product_manufacturer) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_to_controller SET shtml_module_id="'.(int)$module_id.'", controller_id="'.('pm_'.$this->db->escape($product_manufacturer).'"'));
		}
		if($url!=='') {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_to_controller SET shtml_module_id="'.(int)$module_id.'", controller_id="'.('u_'.$this->db->escape($url).'"'));
		}
	}
	
	/*Andrey Bondarenko 29/11/2019*/
	public function getProductList($data) {
		if($data['except']=='') {
			$data['except']='0';
		}
		$sql='SELECT DISTINCT p.product_id, pd.name FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id=pd.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id=p2c.product_id)';
		if(isset($data['category'])) {
			if($data['category']=='all') {
				$sql.=' WHERE p.product_id NOT IN ('.$data['except'].')';
			} else {
				$sql.=' LEFT JOIN '.DB_PREFIX.'category_path cp ON (cp.path_id=p2c.category_id) WHERE pd.language_id='.(int)$this->config->get('config_language_id').' AND p.product_id NOT IN ('.$data['except'].') AND cp.path_id='.$data['category'];
			}
					
		} else {
			$manuf_sql='';
			if(isset($data['manufacturer'])) {
				if($data['manufacturer']=='all') {
					$manuf_sql.='';
				} else {
					$manuf_sql.=' AND p.manufacturer_id='.(int)$data['manufacturer'];
				}
			}
			$sql.=' WHERE pd.name LIKE "'.$data['name'].'%" '.$manuf_sql.' AND pd.language_id='.(int)$this->config->get('config_language_id').' AND p.product_id NOT IN ('.$data['except'].')';
		}
		//return $sql;
		$query=$this->db->query($sql);
		if($query->num_rows) {
			return $query->rows;
		} else {
			return false;
		}
	}
	/*Andrey Bondarenko end 29/11/2019*/
	/*Andrey Bondarenko 28/04/2020 */
	public function getBlocks($module_id) {
		$query=$this->db->query('SELECT * FROM '.DB_PREFIX.'shtml_blocks WHERE shtml_module_id="'.(int)$module_id.'" ORDER BY shtml_block_name');
		foreach($query->rows as $i=>$row) {
			$query->rows[$i]['shtml_block_name']=json_decode($row['shtml_block_name'], true);
			$query->rows[$i]['shtml_block_code']=json_decode($row['shtml_block_code'], true);			
		}
		return $query->rows;
	}
	
	public function saveBlocks($module_id, $blocks) {
		$this->db->query('DELETE FROM '.DB_PREFIX.'shtml_blocks WHERE shtml_module_id="'.(int)$module_id.'"');
		foreach($blocks as $block) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'shtml_blocks SET shtml_module_id="'.(int)$module_id.'", shtml_block_name="'.$this->db->escape(json_encode($block['shtml_block_name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)).'", shtml_block_code="'.$this->db->escape(json_encode($block['shtml_block_code'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)).'", location="'.$this->db->escape($block['location']).'", position="'.$this->db->escape($block['position']).'"');
		}
	}
	/*Andrey Bondarenko end 28/04/2020 */
	/* Andrey Bondarenko 25/03/2021 */
	public function getDefaultSharedBlocks() {
		return array(/*1, 2*/);
	}
	/* Andrey Bondarenko 25/03/2021 end */
	/*Andrey Bondarenko 10/05/2021 */
	public function getModules($controller_ids=array()) {
		foreach($controller_ids as $i=>$controller_id) {
			$controller_ids[$i]=$this->db->escape($controller_id);
		}
		if(!$this->tableExists('shtml_to_controller')) return array();
		$controller_ids_string='"'.implode('", "', $controller_ids).'"';
		$this->load->model('localisation/language');
		$language=$this->model_localisation_language->getLanguage($this->config->get('config_language_id'));		
		$query=$this->db->query('SELECT * FROM '.DB_PREFIX.'shtml_to_controller h2c LEFT JOIN '.DB_PREFIX.'module m ON(h2c.shtml_module_id=m.module_id) LEFT JOIN '.DB_PREFIX.'shtml_blocks sb ON (h2c.shtml_module_id=sb.shtml_module_id)  WHERE h2c.controller_id IN ('.$controller_ids_string.') AND m.code="shtml"');
		$modules=array();
		$results=$query->rows;
		$query2=$this->db->query('SELECT * FROM '.DB_PREFIX.'shtml_to_controller h2c LEFT JOIN '.DB_PREFIX.'module m ON(h2c.shtml_module_id=m.module_id) LEFT JOIN '.DB_PREFIX.'shtml_blocks sb ON (h2c.shtml_module_id=sb.shtml_module_id)  WHERE h2c.controller_id LIKE "u_%" AND m.code="shtml"');
		$url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
		foreach($query2->rows as $row) {
			$search_arr=explode('_', $row['controller_id']);
			array_shift($search_arr);
			$search=implode('_', $search_arr);
			if(mb_strpos($url, $search)!==false) {
				$results[]=$row;
			}
		}
		foreach($results as $row) {
			$tmp=json_decode($row['setting'], true);
			if(@$tmp['status']==1 && !empty(@$tmp['admin'])) {
				$title=json_decode($row['shtml_block_name'], true);
				$description=json_decode($row['shtml_block_code'], true);
				$modules[$row['module_id']]['setting']=json_decode($row['setting'], true);
				$modules[$row['module_id']]['setting']['condition']= isset($modules[$row['module_id']]['setting']['condition']) ? html_entity_decode($modules[$row['module_id']]['setting']['condition']) : '';
				$modules[$row['module_id']]['controllers'][]=$row['controller_id'];
				$modules[$row['module_id']]['blocks'][$row['shtml_block_id']]=array(
					'title'=>isset($title[$language['code']])?$title[$language['code']]:'',
					'description'=>isset($description[$language['code']])?$description[$language['code']]:'',
					'location'=>$row['location'],
					'position'=>$row['position'],
					'module_id'=>$row['module_id'],
				);
			}
		}
		return $modules;		
	}

	public function getProductManufacturerId($product_id) {
		$res=$this->db->query('SELECT manufacturer_id FROM '.DB_PREFIX.'product WHERE product_id="'.(int)$product_id.'"');
		if($res->row) {
			return $res->row['manufacturer_id'];
		} else {
			return 0;
		}
	}	
	/*Andrey Bondarenko 10/05/2021 end */
	/*Andrey Bondarenko 30/06/2021 */
	public function tableExists($table_name) {
		$query = $this->db->query('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = "'.DB_PREFIX.$this->db->escape($table_name).'"');
		return $query->row ? true : false;
	}	
	
	public function getTableFields($table, $all_data = false) {
		$query = $this->db->query('SHOW COLUMNS FROM `'.DB_PREFIX.$this->db->escape($table).'`');
		$field_rows = $query->rows;

		if($all_data) {
			$fields = $field_rows;
		} else {
			$fields = array();
			foreach($field_rows as $row) {
				$fields[] = $row['Field'];
			}			
		}
		return $fields;
	}
	
	
	/*Andrey Bondarenko 30/06/2021 end */
}


?>