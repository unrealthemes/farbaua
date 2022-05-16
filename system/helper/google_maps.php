<?php
if ( !function_exists('gmaps_width_height') ) {
	function gmaps_width_height($value, $ret = 'auto')
	{
		if ( is_numeric($value) ) $ret = $value . 'px';
		else if ( strpos(strtolower($value), 'px') !== false ) {
			$tmp = explode('px', $value);
			if ( is_numeric($tmp[0])  ) $ret = $tmp[0] . 'px';
		}
		else if ( strpos($value, '%') !== false ) {
			$tmp = explode('%', $value);
			if ( is_numeric($tmp[0])  ) $ret = $tmp[0] . '%';
		}
		else if ( strtolower($value) == 'auto' or strlen(trim($value)) == 0 ) $ret = 'auto';

		return $ret;
	}
}

if ( !function_exists('gmaps_info') ) {
	function gmaps_info()
	{
		$gmaps_info = array(
			'gmaps_info_name'		=> 'Google Maps Markers',
			'gmaps_info_author'		=> 'Nick Baris (AkisC)',
			'gmaps_info_version'	=> '1.2.0',
			'gmaps_info_copyright'	=> '2012-2015 comvos.net',
			'gmaps_info_license'	=> 'Free',
			'gmaps_info_link'		=> 'Download at http://www.opencart.com/index.php?route=extension/extension/info&extension_id=5561',
			'gmaps_info_opencart'	=> 'Apply to OpenCart versions: [1.5.2.1], [1.5.4.1], [1.5.5.1], [1.5.6.4]'

		);

		return $gmaps_info;
	}
}

if ( !function_exists('gmaps_make_doc') ) {
	function gmaps_make_doc($bl_start = '<!--', $bl_stop = '  -->', $bl = '  - ', $b = PHP_EOL, $t = '	')
	{
		$doc = array('@api','@author','@category','@copyright','@deprecated','@example','@filesource','@global','@ignore','@internal','@license','@link','@method','@package','@param','@property','@property-read','@property-write','@return','@see','@since','@source','@subpackage','@throws','@todo','@uses','@var','@version');
		$gi = gmaps_info(); ;
		$ret = $b . $bl_start . $b;
		foreach ($gi as $k => $v) {
			$at = str_replace('gmaps_info_', '@', $k);
			$ret .= $bl . (!in_array($at, $doc) ? $b . $bl : $at . $t . $t) . $v . $b . (!in_array($at, $doc) ? $bl . $b : '');
		}
		$ret .= $bl_stop . $b;
		return $ret;
	}
}

if ( !function_exists('gmaps_donate_button') ) {
	function gmaps_donate_button()
	{
return <<<EOD
<form style="float: right;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="VE664VGF65Q3A">
	<input type="image" height="15" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
EOD;
	}
}



