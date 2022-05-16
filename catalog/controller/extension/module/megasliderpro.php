<?php
class ControllerExtensionModuleMegasliderpro extends Controller {
	public function index($setting) {
	
		static $module = 0;	
		$this->language->load('module/ocslideshow'); 
		$this->load->model('megasliderpro/megaslider');
		$this->load->model('tool/image');		
		$this->document->addStyle('catalog/view/javascript/megasliderpro/style.css');		
		$this->document->addScript('catalog/view/javascript/megasliderpro/jquery.nivo.slider.js');	
		$data['slide_setting'] = $this->model_megasliderpro_megaslider->getSettingSlide($setting['banner']);
		$data['megasliders'] = array();
		$results = $this->model_megasliderpro_megaslider->getocslideshow($setting['banner']);
		if($results) {		
			foreach ($results as $result) {
				if($result['small_image']){
					$small_image = $this->config->get('config_url'). 'image/' . $result['small_image'];
				} else {
					$small_image = '';
				}
				$data['megasliders'][] = array(
					'title' => $result['title'],
					'bg_title' => ($result['bg_title'] ? $this->hex2rgba($result['bg_title'],$result['opacity_bg_title']) : ''),
					'color_title' => ($result['color_title'] ? $result['color_title'] : ''),
					'sub_title' => $result['sub_title'],
					'bg_sub_title' => ($result['bg_sub_title'] ? $this->hex2rgba($result['bg_sub_title'],$result['opacity_bg_sub_title']) : ''),
					'color_sub_title' => ($result['color_sub_title'] ? $result['color_sub_title'] : ''),
					'bg_btn_readmore' => ($result['bg_btn_readmore'] ? $this->hex2rgba($result['bg_btn_readmore'],$result['opacity_bg_btn_readmore']) : ''),
					'bg_btn_readmore_hover' => ($result['bg_btn_readmore_hover'] ? $this->hex2rgba($result['bg_btn_readmore_hover'],$result['opacity_bg_btn_readmore_hover']) : ''),
					'border_btn_readmore' => ($result['border_btn_readmore'] ? $this->hex2rgba($result['border_btn_readmore'],$result['opacity_bg_btn_readmore']) : ''),
					'border_btn_readmore_hover' => ($result['border_btn_readmore_hover'] ? $this->hex2rgba($result['border_btn_readmore_hover'],$result['opacity_bg_btn_readmore_hover']) : ''),
					'text_btn_readmore' => ($result['text_btn_readmore'] ? $result['text_btn_readmore'] : ''),
					'color_text_btn_readmore' => ($result['color_text_btn_readmore'] ? $result['color_text_btn_readmore'] : ''),
					'color_text_btn_readmore_hover' => ($result['color_text_btn_readmore_hover'] ? $result['color_text_btn_readmore_hover'] : ''),
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
					'effect_title'  => $result['effect_title'],
					'effect_sub_title'  => $result['effect_sub_title'],
					'effect_btn_title'  => $result['effect_btn_title'],
					'effect_description_title'  => $result['effect_description_title'],
					'link'  => $result['link'],
					'type'  => $result['type'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),
					'small_image' => $small_image
				);
			}
			
			return $this->load->view('extension/module/megasliderpro', $data);		
			
		}
	}
	function hex2rgba($color, $opacity = false) {

	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if(empty($color))
          return $default; 

	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity >=0){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
}
}