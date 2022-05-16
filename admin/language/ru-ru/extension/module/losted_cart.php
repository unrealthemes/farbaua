<?php
/**
 * Losted Cart by Artem Pitov (c) 2018
 *
 * @author Artem Pitov <artempitov@gmail.com>
 * @link https://www.pitov.pro
 * @link https://opencartforum.com/user/674600
 * @see  https://opencartforum.com/files/file/5564
 *
 * @license Сommercial license
 */

$_['heading_title']       = 'Система управления забытыми товарами';
$_['heading_title_main']  = 'Система управления забытыми заказами';

$_['text_use']				 = 'Статус модуля';
$_['text_label']			 = 'Метка';
$_['text_label_life']		 = 'Время жизни корзины в днях';
$_['text_crypt']			 = 'Ключ шифрования';
$_['text_crypt_help']		 = 'Время жизни метки брошенной корзины в браузере, а также, время очистки старых корзин с базы данных в днях. <b>Не рекомендуется ставить слишком большой период жизни, достаточно месяц-два</b>';

$_['text_send']		 		 = 'Уведомления';
$_['text_collector']		 = 'Каждый роут с новой строки!';
$_['text_collector_help']	 = '<b>Страницы оформления заказа на которых будут собираться данные о покупателе</b> <hr> checkout/simplecheckout <br> checkout/checkout <br> simplecheckout <br> checkout';

$_['text_cli']		       	 = 'Для автоматической рассылки сообщений покупателям добавите в cron следующую wget команду: <b>wget -O - \'%sindex.php?route=tool/losted_cart_api/mailer&wget=%s\' >/dev/null 2>&1 </b> <br> Интервал запуска задания рекомендуется устанавливать отталкиваясь от параметра "Отправка повторных уведомлений". <b>К примеру, раз в час */59 * * * *</b>';
$_['text_not_found']      	 = 'нет данных :(';
$_['text_settings']       	 = 'Настройки'; 
$_['text_carts']	      	 = 'Список корзин'; 
$_['text_info']		      	 = 'Информация';
$_['text_send_title']     	 = 'Статус отправки';
$_['text_send_success']   	 = 'Ваше сообщение успешно отправлено!';
$_['text_send_error']	  	 = 'Сообщение не отправлено!';
$_['text_show_template']  	 = 'Показать email';
$_['text_cart_good']	  	 = 'Поздравляем, брошенных товаров в магазине нет :)'; 
$_['text_send_email']		 = 'Покупатель уведомлен';
$_['text_send_none_email']	 = 'Покупатель еще не уведомлен';
$_['text_in_cart']			 = 'Кол-во: ';
$_['text_total']			 = 'Итого: ';
$_['text_create']			 = 'Создано: ';
$_['text_updated']			 = 'Обновлено: ';
$_['text_cart']				 = 'Корзина';
$_['text_product']			 = 'Товар';
$_['text_model']			 = 'Модель';
$_['text_price']			 = 'Цена';
$_['text_quantity']			 = 'Кол-во';
$_['text_log']				 = 'Email лог ';
$_['text_in_store']			 = 'Товар на витрине';
$_['text_in_admin']			 = 'Товар в магазине';
$_['text_token']			 = 'Создания токена автоматической авторизации и восстановления корзины';
$_['text_twig_help'] 		 = 'При рендеринге используется шаблонизатор <b>twig</b> с документацией которого вы можете ознакомится по адресу: <a href="https://twig.symfony.com/doc/2.x"> https://twig.symfony.com</a>. Если вам по какой-либо причине не подходит стандартный шаблон, вы всегда можете заказать индивидуальный шаблон у <a href="https://pitov.pro">автора &#8594;</a>';
$_['text_twig_full']		 = 'Нажмите <b>F11</b>, когда курсор находится в редакторе, чтобы включить полноэкранное редактирование. <b>Esc</b> - выход из полноэкранного режима.';
$_['text_backup_email']   	 = '<i class="fa fa-life-ring"></i> Востановить шаблоны';
$_['text_var']				 = '<i class="fa fa-puzzle-piece"></i> Доступные переменные';
$_['text_subject']			 = 'Тема письма';
$_['text_on']				 = 'Вкл';
$_['text_off']				 = 'Выкл';
$_['text_coupon']			 = 'Генератор купонов';
$_['text_coupon_name']		 = 'Название (только для админки)';
$_['text_coupon_life']	     = 'Время жизни купона в днях';
$_['text_coupon_prefix']	 = 'Префикс';
$_['text_coupon_minimum']	 = 'Минимальная сумма заказа для генерации или использования купона';
$_['text_coupon_sale']		 = 'Размер скидки';
$_['text_coupon_sale_type']  = 'Тип скидки';
$_['text_P']				 = 'Процент';
$_['text_F']				 = 'Сумма';
$_['text_email_first']		 = 'Отправка первого уведомления (в часах)';
$_['text_email_second']		 = 'Отправка повторных уведомлений (в днях)';
$_['text_email_second_help'] = 'Если установить значение 0, повторные уведомления не будут отправляться ';
$_['text_sending_type']		 = 'Каналы отправки';
$_['text_size']				 = 'Размеры изображений';
$_['text_product_w']		 = 'Ширина товара';
$_['text_product_h']		 = 'Выстора товара';
$_['text_logo_w']			 = 'Ширина логотипа';
$_['text_logo_h']			 = 'Выстора логотипа';
$_['text_coupon_delivery']	 = 'Бесплатная доставка';
$_['text_mail_type']		 = 'Библиотека для отправки';
$_['text_other_info']		 = 'Прочая информация';
$_['text_store']			 = 'Магазин';
$_['text_language']			 = 'Язык';
$_['text_currency']			 = 'Валюта';
$_['text_customer_group']    = 'Группа покупателей';
$_['text_remove']			 = 'Удалить товар с корзины';
$_['text_confirm']		     = 'Внимание!';
$_['text_confirm_remove']    = 'Товар будет удален у покупателя в корзине!';
$_['text_remove']		     = 'Удалить';
$_['text_cancel']		     = 'Отмена';
$_['text_remove_cart']       = 'Удалить корзину';
$_['text_write_to_customer'] = 'Написать email';
$_['text_add_product']		 = 'Добавить товар (будет в версии 2.1.0)';
$_['text_edit_product']      = 'Изменить (будет в версии 2.1.0)';  
$_['text_groups_disabled']	 = 'Не отправлять уведомления для';

$_['error_count_affected']	 = 'У вас недостаточно прав!';
$_['error_permission']	     = 'У вас недостаточно прав!';
$_['error_remove_cart']	     = 'У вас недостаточно прав для удаления корзины!';
$_['error_email']            = 'Введите корректный email!';
$_['error_subject']	         = 'Тема письма должна быть больше 5 символов!';
$_['error_mesage']	         = 'Сообщение письма должна быть больше 5 символов!';
$_['text_sale']				 = 'Индивидуальная скидка';

$_['text_var_title']		 = 'Доступные переменные';
$_['text_var_email']		 = 'Email покупателя';
$_['text_var_telephone']     = 'Телефон покупателя';
$_['text_var_subject']		 = 'Тема письма';
$_['text_var_store_name']	 = 'Название магазина';
$_['text_var_store_url']	 = 'URL магазина';
$_['text_var_checkout_url']	 = 'URL страницы оформления заказа';
$_['text_var_logo']			 = 'Логотип, пример: &#60;img src="{{ logo }}" /&#62;';
$_['text_var_lastname']		 = 'Имя покупателя';
$_['text_var_firstname']	 = 'Фамилия покупателя';

$_['text_var_coupon_title']	        = 'Купон';
$_['text_var_coupon']		        = 'Одномерный массив'; 
$_['text_var_coupon_code']	        = 'Код купона'; 
$_['text_var_coupon_date_end']      = 'Срок действия купона';
$_['text_var_coupon_free_shipping'] = 'Бесплатная доставка';

$_['text_var_cart_title']	                     = 'Корзина';
$_['text_var_cart']                              = 'Многомерный массив';
$_['text_var_cart_quantity']                     = 'Кол-во товаров в корзине'; 
$_['text_var_cart_total']                        = 'Стоимость товаров в корзине';
$_['text_var_cart_products']                     = 'Многомерный массив товаров';
$_['text_var_cart_products_name']                = 'URL товара';
$_['text_var_cart_products_url']                 = 'Название товара';
$_['text_var_cart_products_model']               = 'Модуль товара';
$_['text_var_cart_products_image']               = 'Фото товара';
$_['text_var_cart_products_price']               = 'Цена товара'; 
$_['text_var_cart_products_total']               = 'Итого товара';
$_['text_var_cart_products_option']              = 'Многомерный массив опций';
$_['text_var_cart_products_option_name']         = 'Имя опции';
$_['text_var_cart_products_option_value']        = 'Значение опции';
$_['text_var_cart_products_option_price']        = 'Цена опции';
$_['text_var_cart_products_option_price_prefix'] = 'Префикс опции (+/- etc)';


$_['text_version_install'] = 'Установлена версия';
$_['text_version']		   = 'Текущая версия модуля';

/* TAB CARTS */
$_['text_send_notification']   = 'Отправить email уведомление';
$_['text_confirm_remove_cart'] = 'У покупателя будут удалены все товары!';

$_['text_product_w']		   = 'Ширина фото товара';
$_['text_product_h']		   = 'Высота фото товара';
$_['text_logo_w']		       = 'Ширина логотипа';
$_['text_logo_h']		       = 'Выстота логотипа';
$_['text_api_info']			   = 'Вы можете использовать API модуля чтобы создания клиента брошенной корзины. Для отправки данных используйте <b>API http://losted.loc/index.php?route=tool/losted_cart_api/collector</b>, метод принимает POST данные с <b>обязательными</b> параметрами email или telephone, а также <b>не обязательными</b> firstname, lastname. В случае если покупатель зарегистрирован, данные будут подтянуты с личного кабинета. <b>Важно: если у покупателя не будет товаров в корзине, брошенная корзина не будет создана!</b>';

$_['text_success']	= 'Настройки модуля успешно сохранены!';

$_['text_checkout_route']		= 'Страница оформления заказа';
$_['text_label_checkout_route']	= 'Route';
$_['text_checkout_route_help']	= 'Укажите на какую страницу заказа отправлять покупателя после перехода с письма. <br>
<b>checkout/checkout</b> – стандартная страница оформления заказа OpenCart <br>
<b>checkout/simplecheckout</b> – страница оформления заказа модуля Silmle';

$_['text_email_title']			= 'Отправитель (если оставить пустым, будет использоватся названия магазина)';
$_['text_resending_max']		= 'Максимальное ко-во уведомлений';