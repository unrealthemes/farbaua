/**
 * AP | Losted Cart (c) 2018
 *
 * @author Artem Pitov <artempitov@gmail.com>
 * @link https://opencartforum.com/user/674600-artempitov/
 * @link https://www.pitov.pro
 * @see https://opencartforum.com/files/file/5564
 *
 * @license Сommercial license
 */

/* document.addEventListener('contextmenu', event => event.preventDefault());
if ((window.outerHeight - window.innerHeight) > 100) { } */

$(() => {
	/* proxy */
	let data   = JSON.parse(window.atob($('#var').val()));
	let proxy  = new Proxy({}, { 
		get(target, name) {
  			return typeof data[name] !== undefined ? data[name] : false;
		}
	});
	/* proxy */

	/* utils */
	let utils = () => {
		$('[data-mt]').each(function(idx, el) {
			let top = $(this).data('mt');

			$(this).css({'margin-top': top});
		});

		$('[data-mb]').each(function(idx, el) {
			let bottom = $(this).data('mb');

			$(this).css({'margin-bottom' : bottom});
		});
	}
	/* utils */

	/* tab carts */
	$('a[href="#carts"]').on('click', function () {
		let $self = $(this), url = $self.data('action');

		$self.data('loading-text', '<i class="fa fa-spinner fa-spin"></i>');
		$self.button('loading');

		if (url) {
			$.get(url, function (html) {
			
				$(html).appendTo($('#carts'));
				$('[data-toggle="tooltip"]').tooltip();
			
			}, 'html').done(() => {
				
				$self.off();
				setTimeout(() => { $self.button('reset'); }, 500);
				utils();
		
			}); 
		} else {
			$self.button('reset');
		}
	});
	/* tab carts */

	/* tab carts full info */
	$('body').on('click', '.cart-full-info', function () {
		let $this     = $(this), 
			i 	      = $this.children('.fa'), 
			container = $this.closest('.cart-item');

		if (container) {
			if (i) {
				i.toggleClass('fa-close')
				 .toggleClass('fa-ellipsis-h');
			}

			container.toggleClass('open');
		}
	});
	/* tab carts full info */

	/* tab carts pagination */
	$('body').on('click', '#carts #paginagtion a', function (e) {
		e.preventDefault();
		$('#carts').addClass('loading');

		$.get($(this).attr('href'), (html) => {
			$('#carts').html(html);
		}, 'html').done(() => {
			$('[data-toggle="tooltip"]').tooltip();

			setTimeout(() => { $('#carts').removeClass('loading') }, 500);
			utils();
		});
	});		
	/* tab carts pagination */

	/* tab carts remove item */
	$('body').on('click', '.cart-remove', function () {
		let $this = $(this), 
			url   = $this.data('action'), 
			$item = $this.closest('.cart-item');

		$this.data('loading-text', '<i class="fa fa-spinner fa-spin"></i>').button('loading');	

		if (!url) {
			$this.button('reset');
			return;	
		}

		$.confirm({
			title: proxy.text_confirm,
			content: proxy.text_confirm_remove_cart,	
			buttons: {
				confirm: {
					text: proxy.text_remove,
					action: function () 
					{
						$.get(url, function(json) {

							if (json.error) {
								$.alert({
									title: proxy.text_send_title,
									content: json.error,
									action: function () 
									{

									}
								});			
							}						
								
							if (json.success) {
								$this.closest('.cart-item').remove();
							}

							$this.button('reset');
						}, 'json');							
					}
				},
				cancel: {
					text: proxy.text_cancel,
					action: function () 
					{
						$this.button('reset');
					} 
				}
			}
		});			
	});
	/* tab carts remove item */

	/* tab carts remove product */
	$('body').on('click', '.remove-product', function () {
		let $this = $(this), 
			url   = $this.data('action');

		if (!url) {
			return;
		}

		$.confirm({
			title: proxy.text_confirm,
			content: proxy.text_confirm_remove,	
			buttons: {
				confirm: {
					text: proxy.text_remove,
					action: function () 
					{
						$.get(url, function(json) {

							if (json.error) {
								$.alert({
									title: proxy.text_send_title,
									content: json.error
								});			
							}

							if (json.success) {
								
								let items = $($this.closest('.product-info'));
								
								$this.closest('tr').remove();
								
								items = items.find('tr');

								if (items.length == 2) {
									$this.closest('.cart-item').remove();
								}
							}
						}, 'json');							
					}
				},
				cancel: {
					text: proxy.text_cancel
				}
			}
		});	
	});
	/* tab carts remove product */	

	/* codemirror */
	let cm_array = [];
	let el = document.getElementsByClassName('code-mirror');

	for (let i = 0; el.length > i; i++) {
		cm_array[i] = code_mirror = CodeMirror.fromTextArea(el[i], {
			mode: 'text/html',
			lineNumbers: true,
			autofocus: false,
			htmlMode: true,
			theme: 'monokai',
			lineWrapping: true,
	       	indentUnit: 4,
			matchBrackets: true,
			extraKeys: 
			{
	       		F11: function(cm) 
	       		{
	         		cm_array[i].setOption("fullScreen", !cm_array[i].getOption("fullScreen"));
	       		},
	       		Esc: function(cm) 
	       		{
	         		if (cm_array[i].getOption("fullScreen")) { 
	         			cm_array[i].setOption("fullScreen", false);
	         		}
	       		}
	     	}			
		});
			
		cm_array[i].refresh();
	}

	$('#template-backup').on('click', function() {
		let url = $(this).data('action'); 

		$.get(url, function (html) {
			for (let i = 0; cm_array.length > i; i++) {
				cm_array[i].setValue(html);	
			}
		}, 'html');
	});
	/* codemirror */

	/* notifications */
	$('body').on('click', '.notification-btn', function () {
		let self = $(this), url = $(this).data('action');
		
		self.tooltip('hide');
		
		self.data('loading-text', '<i class="fa fa-spinner fa-spin"></i>');
		self.button('loading');

		if (url) {
			$.get(url, (json) => {
				$.alert({
					title: proxy.text_send_title,
					content: json.status ? proxy.text_send_success : proxy.text_send_error,
					buttons: {
						confirm: {
							text: 'ok',
						}
					}
				});
			}, 'json').done(() => {
				setTimeout(() => { 
					self.button('reset'); 
				}, 500);
			});
		} else {
			self.button('reset');
		}
	});
	/* notifications */

	/* show template */
	$('body').on('click', '.template-btn', function () {
		let self = $(this), url = $(this).data('action');
		
		self.tooltip('hide');
		
		self.data('loading-text', '<i class="fa fa-spinner fa-spin"></i>');
		self.button('loading');

		if (url) {
			$.get(url, (html) => {
				$.confirm({
					title: false,
					content: html,
					columnClass: 'col-md-12',
					buttons: {
						confirm: {
							text: 'ok',
						}
					}
				});				
			}, 'html').done(() => {
				setTimeout(() => { self.button('reset'); }, 500);
			});
		} else {
			self.button('reset');
		}
	});
	/* show template */

	$('.activation-form button').on('click', () => {
		let license = $('input[name="lckey"]').val();

		if (license.length) {
			$.get(proxy.license_url + '&key=' + license, (json) => {
				if (json.status) {
					window.location = window.location.href;
				} else {

				}
			}, 'json');
		}
	});

	/* run */
	$('a[data-toggle="tab"]:first').tab('show');
	
	utils();
	/* run */

	//$('.summernote').summernote({
	//});
});

$(() => {
	let resize = () => $('.template-info .table-wrap').css({'max-height' : $('.template-tab').height() - $('.template-info .alert.alert-info').height() + 15 });
	setTimeout(() => resize(), 1000);
	$(window).on('resize', () => { resize(); });
});

(($) => {
	/* inouter */
	$(() => {
		$('.inputer input, .inputer textarea').each(function (idx, el) {
			let $el = $(el);
			
			$el.attr('placeholder', $el.siblings('label').text());
			$el.parent().toggleClass('active', $el.val().length > 0);

		}).on('input', function () {
			let $el = $(this);

			$el.parent().toggleClass('active', $el.val().length > 0);		
		});
	});
	/* inouter */
})(jQuery);