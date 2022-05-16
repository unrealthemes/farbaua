$(() => {
	let collector  = 'input[type="text"], input[type="email"], input[type="tel"]',
		$collector = $(collector),
		route 	   = '/index.php?route=tool/losted_cart_api/collector';
	
	if ($collector.length) { $.post(route, $collector); }

	$('body').on('change', collector, () => $.post(route, $(collector)));
});