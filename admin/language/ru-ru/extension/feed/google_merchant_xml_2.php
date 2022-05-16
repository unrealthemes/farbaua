<?php
// Heading
$_['heading_title']    			= 'Товарный фид для Google Merchant и Facebook - 2';
 
// Text
$_['text_feed']          		= 'Каналы продвижения';
$_['text_success']          	= 'Настройки модуля обновлены!';
$_['text_edit']          		= 'Редактировать Google Merchant XML';
$_['text_extension']         = 'Каналы продвижения';
$_['text_feed_merchant']    = 'Google Merchant:'; 
$_['text_feed_facebook']    = 'Facebook:'; 
$_['text_feed_help']    = 'К ссылкам можно добавлять GET-параметры языка и валюты<br><br>Как пример - language=ru-ru - фид принудительно будет на русском языке<br>currency=USD - фид будет в USD<br>Язык и валюта должны существовать в магазине и быть включенными.'; 
$_['text_feed_cron']    = 'После запуска cron-задания (как его запустить - надо спросить у хостера!) файлы будут доступны по ссылкам:'; 
$_['text_category_google']    = 'Скачать список категорий Google'; 
$_['text_help_link']    = 'Ссылка на документацию'; 
$_['text_edit']         = 'Редактирование модуля';
$_['text_id']   	   	= 'ID товара';
$_['text_model']        = 'Модель';
$_['text_identifier']   = 'Идентификатор товара';

$_['entry_status']     = 'Статус';
$_['entry_currency']     = 'Валюта';

$_['entry_category'] = 'Категории';
$_['entry_manufacturer'] = 'Производители';
$_['entry_customer_group'] = 'Цена для группы покупателей';

$_['entry_google_merchant_xml_2_original_image_status']   = 'Выгружать оригиналы картинок (быстрее)';
$_['entry_google_merchant_xml_2_key']   = 'Ключ защиты ссылок (рекомендуется)';
$_['entry_google_merchant_xml_2_additional_images']   = 'Дополнительные картинки (медленнее)';
$_['entry_google_merchant_xml_2_multiplier']   = 'Коэффициент наценки (1 - 100%, 0.7 - -30%, 1.2 - +20%)';
$_['entry_google_merchant_xml_2_utm']   = 'UTM-метки. Переменные: {product_id}, {product_model}';
$_['entry_google_merchant_xml_2_original_description']   = 'HTML-описание';
$_['entry_google_merchant_xml_2_currency']   = 'Основная валюта фида';
$_['entry_google_merchant_xml_2_custom_sql']   = 'Дополнительное условие SQL для выгрузки (пример AND p.export = 1 AND p.stock_status_id = 3). Использовать если знаете что делаете.';
$_['entry_google_merchant_xml_2_special']   = 'Выгружать акционные цены';
$_['entry_google_merchant_xml_2_min_price']   = 'Минимальная цена для выгрузки';
$_['entry_google_merchant_xml_2_max_price']   = 'Максимальная цена для выгрузки';
$_['entry_google_merchant_xml_2_gtin']   = 'Откуда брать GTIN (если используется). Пример: sku, upc, mpn и тп.';
$_['entry_google_merchant_xml_2_mpn']   = 'Откуда брать MPN (если используется). Пример: sku, upc, mpn и тп.';
$_['entry_google_merchant_xml_2_zero_quantity']   = 'Выгружать товары с количеством только больше 0';
$_['entry_google_merchant_xml_2_condition']   = 'Condition (new - новый,refurbished - восстановленный, used - б/у)';
$_['entry_google_merchant_xml_2_links']   = 'Ссылки на выгрузку';
$_['text_credits']        = '
<b>Спасибо за покупку моего модуля!</b><br>
<div class="text-credits">
Если вам необходима поддержка, доработка этого модуля либо какого другого - обращайтесь, буду рад помочь.<br><br>
Связаться со мной можно следующими способами:<br>
1. Личное сообщение на opencartforum - <a href="https://opencartforum.com/profile/678128-spectre/" target="_blank" style="display: inline-block;border-radius: 2px;padding: 1px 5px;font-size: 90%;color: #fff;text-decoration: none !important;background: #3d6594;">@spectre</a><br>
2. Электронная почта - <a href="mailto:job@freelancer.od.ua">job@freelancer.od.ua</a><br><br>

Если хотите поблагодарить или угостить пивом когда читаете эту страницу:<br>
1. Яндекс-Деньги - 41001189848733<br>
2. QIWI - SPECTREAV<br>
3. Приватбанк - 5168742228748467<br><br>

<b style="font-size:18px;color:red;">Удачных продаж! <i class="fa fa-hand-peace-o"></i></b>
</div>
';

// Error
$_['error_permission']          = 'У вас нет прав для управления этим модулем!';
