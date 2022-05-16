// autocompleteSerach */
(function($) {
	$.fn.autocompleteSerach = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();
	
			$.extend(this, option);
	
			$(this).attr('autocompleteSerach', 'off');
			
			// Focus
			$(this).on('focus', function() {
				this.request();
			});
			
			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);				
			});
			
			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}				
			});
			
			// Click
			this.click = function(event) {
				event.preventDefault();
	
				value = $(event.target).parent().attr('data-value');
	
				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}
			
			// Show
			this.show = function() {
				var pos = $(this).position();
	
				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});
	
				$(this).siblings('ul.dropdown-menu').show();
			}
			
			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}		
			
			// Request
			this.request = function() {
				clearTimeout(this.timer);
		
				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}
			$(this).after('<ul class="dropdown-menu autosearch"></ul>');
			$(this).siblings('ul.dropdown-menu autosearch').delegate('a', 'click', $.proxy(this.click, this));	
		});
	}
})(window.jQuery);
$(document).ready(function(){
	var autoSearch = $('#searchtop input[name="search"]');
	var customAutocompleteSearchtop = null;
	autoSearch.autocompleteSerach({
	delay: 500,
	responsea : function (items){
		
		if (items.length) {
			for (i = 0; i < items.length; i++) {
				this.items[items[i]['value']] = items[i];
			}
		}
		var html='';
		if(items.length){
			$.each(items,function(key,item){
				if(item.product_id!=0){
					html += '<li><a href="'+ item.href +'" class="autosearch_link">';
					html += '<div class="ajaxadvance">';
					html += '<div class="image">';
					if(item.image){
					html += '<img title="'+item.name+'" src="'+item.image+'"/>';
					}
					html += '</div>';
					html += '<div class="content">';
					html += 	'<h3 class="name">'+item.label+'</h3>';
					if(item.model){
					html += 	'<div class="model">';
					html +=		item.model;
					html +=		'</div>';
					}
					if(item.manufacturer){
					html += 	'<div class="manufacturer">';
					html +=		item.manufacturer;			
					html +=		'</div>';		
					}
					if(item.stock_status){
					html += 	'<div class="stock_status">';
					html +=		item.stock_status;			
					html +=		'</div>';
					}	
					if(item.price){
					html += 	'<div class="price"> ';
					if (!item.special) { 
					html +=			 item.price;
					} else {	
					html +=			'<span class="price-old">'+ item.price +'</span> <span class="price-new">'+ item.special +'</span>';
					}	
					html +=		'</div>';
					}	
									
					if (item.rating) {
					html +=		'<div class="ratings"> ';
					for (var i = 1; i <= 5; i++) {
					if (item.rating < i) { 
					html +=		'<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>';
					} else {	
					html +=		'<span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>';
					} 
					}
					html +=		'</div>';
					}
					html +='</div>';
					html += '</div></a></li>'
				}
			});
					html +=	'<li><a class="search-view-all-result" href="index.php?route=product/search&search=' + autoSearch.val() + '">'+ text_autosearch_view_all +'</a></li>';
		}	
		if (html) {
			autoSearch.siblings('ul.dropdown-menu').show();
		} else {
			autoSearch.siblings('ul.dropdown-menu').hide();
		}

		$(autoSearch).siblings('ul.dropdown-menu').html(html);
	},
		source: function(request, response) {
		customAutocompleteSearchtop = this;
			$.ajax({
				url: 'index.php?route=extension/module/autosearch/ajaxLiveSearch&filter_name=' +  encodeURIComponent(request),
				dataType : 'json',
				success : function(json) {
				customAutocompleteSearchtop.responsea($.map(json, function(item) {
					return {
						label: item.name,
						name: item.name1,
						value: item.product_id,
						model: item.model,
						stock_status: item.stock_status,
						image: item.image,
						manufacturer: item.manufacturer,
						price: item.price,
						special: item.special,
						
						rating: item.rating,
						href:item.href,
						}
				}));
				}
			});
		},
		select : function (ui){	
			return false;
		},
		selecta: function(ui) {
		if(ui.href){
			location = ui.href;
		}
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
		});							
	});

	$(document).ready(function(){
	var autoSearchFixed = $('#search-fixed-top input[name="search"]');
	var customAutocomplete = null;
	$('#search-fixed-top ul.dropdown-menu.autosearch').remove();
	autoSearchFixed.autocompleteSerach({
	delay: 500,
	responsea : function (items){
		if (items.length) {
			for (i = 0; i < items.length; i++) {
				this.items[items[i]['value']] = items[i];
			}
		}
		var html='';
		if(items.length){
			$.each(items,function(key,item){
				if(item.product_id!=0){
					html += '<li><a href="'+ item.href +'" class="autosearch_link">';
					html += '<div class="ajaxadvance">';
					html += '<div class="image">';
					if(item.image){
					html += '<img title="'+item.name+'" src="'+item.image+'"/>';
					}
					html += '</div>';
					html += '<div class="content">';
					html += 	'<h3 class="name">'+item.label+'</h3>';
					if(item.model){
					html += 	'<div class="model">';
					html +=		item.model;
					html +=		'</div>';
					}
					if(item.manufacturer){
					html += 	'<div class="manufacturer">';
					html +=		item.manufacturer;			
					html +=		'</div>';		
					}
					if(item.stock_status){
					html += 	'<div class="stock_status">';
					html +=		item.stock_status;			
					html +=		'</div>';
					}	
					if(item.price){
					html += 	'<div class="price"> ';
					if (!item.special) { 
					html +=			 item.price;
					} else {	
					html +=			'<span class="price-old">'+ item.price +'</span> <span class="price-new">'+ item.special +'</span>';
					}	
					html +=		'</div>';
					}
					if (item.rating) {
					html +=		'<div class="ratings"> ';
					for (var i = 1; i <= 5; i++) {
					if (item.rating < i) { 
					html +=		'<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>';
					} else {	
					html +=		'<span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>';
					} 
					}
					html +=		'</div>';
					}
					html +='</div>';
					html += '</div></a></li>'
				}
			});
					html +=	'<li><a class="search-view-all-result" href="index.php?route=product/search&search=' + autoSearchFixed.val() + '">'+ text_autosearch_view_all +'</a></div>';
		}	
		if (html) {
			autoSearchFixed.siblings('ul.dropdown-menu').show();
		} else {
			autoSearchFixed.siblings('ul.dropdown-menu').hide();
		}

		$(autoSearchFixed).siblings('ul.dropdown-menu').html(html);
	},
		source: function(request, response) {
		customAutocomplete = this;
			$.ajax({
				url: 'index.php?route=extension/module/autosearch/ajaxLiveSearch&filter_name=' +  encodeURIComponent(request),
				dataType : 'json',
				success : function(json) {
				customAutocomplete.responsea($.map(json, function(item) {
					return {
						label: item.name,
						name: item.name1,
						value: item.product_id,
						model: item.model,
						stock_status: item.stock_status,
						image: item.image,
						manufacturer: item.manufacturer,
						price: item.price,
						special: item.special,
						rating: item.rating,
						href:item.href
						}
				}));
				}
			});
		},
		select : function (ui){	
			return false;
		},
		selecta: function(ui) {
		if(ui.href){
			location = +ui.href;
		} 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
		});			
	});