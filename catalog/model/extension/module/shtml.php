<?php
class ModelExtensionModuleSHTML extends Model {
	public function getControllers($controller_id) {
		$query=$this->db->query('SELECT * FROM '.DB_PREFIX.'shtml_to_controller h2c LEFT JOIN '.DB_PREFIX.'module m ON(h2c.shtml_module_id=m.module_id) LEFT JOIN '.DB_PREFIX.'shtml_blocks sb ON (h2c.shtml_module_id=sb.shtml_module_id)  WHERE h2c.controller_id="'.$this->db->escape($controller_id).'" AND m.code="shtml"');
		$result=array();
		$this->load->model('localisation/language');
		$language=$this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		if($query->num_rows) {
			foreach($query->rows as $row) {
				$tmp=json_decode($row['setting'], true);
				if(@$tmp['status']==1) {
					$title=json_decode($row['shtml_block_name'], true);
					$description=json_decode($row['shtml_block_code'], true);
					
					$result[$row['shtml_block_id']]=array(
						'title'=>isset($title[$language['code']])?$title[$language['code']]:'',
						'description'=>isset($description[$language['code']])?$description[$language['code']]:'',
						'location'=>$row['location'],
						'position'=>$row['position']
					);
				}
			}
		}
		return $result;
	}
	
	public function getModules($controller_ids=array()) {
		if(!$this->tableExists('shtml_to_controller')) return array();
		foreach($controller_ids as $i=>$controller_id) {
			$controller_ids[$i]=$this->db->escape($controller_id);
		}
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
			if(@$tmp['status']==1 && empty(@$tmp['admin'])) {
				$title=json_decode($row['shtml_block_name'], true);
				$description=json_decode($row['shtml_block_code'], true);
				$modules[$row['module_id']]['setting']=json_decode($row['setting'], true);
				$modules[$row['module_id']]['setting']['condition'] = isset($modules[$row['module_id']]['setting']['condition']) ? html_entity_decode($modules[$row['module_id']]['setting']['condition']) : '';
				$modules[$row['module_id']]['setting']['preloader'] = isset($modules[$row['module_id']]['setting']['preloader'][$language['code']]) ? $modules[$row['module_id']]['setting']['preloader'][$language['code']] : '';
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
	
	public function getModuleFilter($module_id) {
		$query=$this->db->query('SELECT * FROM '.DB_PREFIX.'shtml_to_controller WHERE shtml_module_id="'.(int)$module_id.'"');
		$filter_data=array();
		foreach($query->rows as $row) {
			$controller_id=$row['controller_id'];
			$controller_id_array=explode('_', $controller_id);
			$controller_id_array=array(array_shift($controller_id_array), implode('_', $controller_id_array));
			$controller_type=$controller_id_array[0];
			if(isset($controller_id_array[1])) {
				$filter_data[$controller_type][]=$controller_id_array[1];
			}			
		}
		//var_export($filter_data);
		//echo 'SELECT * FROM '.DB_PREFIX.'shtml_to_controller WHERE shtml_module_id="'.(int)$module_id.'"<br>';
		$query2=$this->db->query('SELECT * FROM '.DB_PREFIX.'module WHERE module_id="'.(int)$module_id.'"  AND code="shtml"');
		$setting=array();
		if($query2->row) {
			$setting=json_decode($query2->row['setting'], true);			
		}		
		if(!empty($setting['condition'])) {
			$condition=html_entity_decode($setting['condition']);
			//echo $condition.'<br>';
		} else {
			$condition='';
			$conditions=array();
			foreach($filter_data as $type=>$filter_type) {
				if($type!='c' && $type!='m') {
					$conditions[]='['.$type.']';
				}
			}
			$condition='('.implode('||', $conditions).')';
		}
		
		$fields=array(
			'p'=>'p.product_id',
			'pc'=>'cp.path_id',
			'pm'=>'p.manufacturer_id',
			'l'=>'l.layout_id',			
		);
		$data=array();
		
		foreach($filter_data as $type=>$filter_type) {
			if(strpos($condition, '['.$type.']')!==false) {
				$filter_type_text='';
				if(isset($fields[$type])) {
					$filter_type_escaped=array();
					foreach($filter_type as $i=>$filter_type_value) {
						$filter_type_escaped[]=$this->db->escape($filter_type_value);
					}					
					if($type=='l' && in_array('all', $filter_type_escaped)) {
						$filter_type_text='1';	
					} else {					
						$filter_type_text=$fields[$type].' IN ("';
						$filter_type_text.=implode('", "', $filter_type_escaped);
						$filter_type_text.='")';
					}
				} else if($type=='u') {
					//$data[$type]=$filter_type;
					$filter_type_text='s.keyword LIKE "%'.$this->db->escape($filter_type[0]).'%"';
				}
				$condition=str_replace('['.$type.']', $filter_type_text, $condition);
			}
		}
		$condition = str_replace(array('||', '&&', '!'), array(' OR ', ' AND ', ' NOT '), $condition);
		$filter['condition']=$condition;
		$filter['limit']=isset($setting['limit'])?$setting['limit']:'';
		$filter['data']=$data;
		$filter['escaped']=true;
		return $filter;
	}
	
	public function getProducts($filter=array('condition'=>'1', 'limit'=>'', 'data'=>array(), 'sort'=>'p.name', 'order'=>'ASC', 'escaped'=>false)) {
		//$sql="SELECT p.*, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category c ON (cp.category_id = c.category_id) LEFT JOIN ". DB_PREFIX . "product_to_layout l ON (l.product_id=p.product_id)";
		
		$sql="SELECT p.*, pd.name, pd.description, pd.meta_title, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id=p.manufacturer_id) AS manufacturer  FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "category_path cp ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_layout l ON (l.product_id=p.product_id) LEFT JOIN " . DB_PREFIX . "seo_url s ON (s.query=CONCAT('product_id=', p.product_id)) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		
		if(!isset($filter['condition'])) {
			$filter['condition']='1';
		}
		if(strpos($filter['condition'], '{%')!==false) {
			$after=explode('{%', $filter['condition'])[1];
			$code=explode($after, '%}')[0];
			
		}
		if(empty($filter['escaped'])) {
			/*Andrey Bondarenko 25/03/20201 -> */ $filter['condition']=str_replace(array('"', "'"), '{{quote}}', $filter['condition']);
			$filter['condition'] = $this->db->escape($filter['condition']);
			/*Andrey Bondarenko 25/03/20201 -> */ $filter['condition']=str_replace('{{quote}}', "'", $filter['condition']);
		}
		$sql.=' AND ('.$filter['condition'].')';
		$sql .= " GROUP BY p.product_id";
		
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added',
			'RAND()',
		);

		if (isset($filter['sort']) && in_array($filter['sort'], $sort_data)) {
			if ($filter['sort'] == 'pd.name' || $filter['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $filter['sort'] . ")";
			} elseif ($filter['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $filter['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}		
		if (isset($filter['order']) && ($filter['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
			
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}		
		if(isset($filter['limit']) && $filter['limit']!=='') {
			$sql.=' LIMIT '.(int)$filter['limit'];
		}
		//debug
		//echo $sql."<br>\n";
		$query=$this->db->query($sql);
		return $query->rows;
	}
	
	public function getLayoutId($route='') {
		$this->load->model('design/layout');
		if($route=='') {
			if (isset($this->request->get['route'])) {
				$route = (string)$this->request->get['route'];
			} else {
				$route = 'common/home';
			}
		}
		$layout_id = 0;

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');

			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');

			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');

			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}
		return $layout_id;
	}
	
	public function getProductManufacturerId($product_id) {
		$res=$this->db->query('SELECT manufacturer_id FROM '.DB_PREFIX.'product WHERE product_id="'.(int)$product_id.'"');
		if($res->row) {
			return $res->row['manufacturer_id'];
		} else {
			return 0;
		}
	}
	
	public function tableExists($table_name) {
		$query = $this->db->query('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = "'.DB_PREFIX.$this->db->escape($table_name).'"');
		return $query->row ? true : false;
	}	
}
?>