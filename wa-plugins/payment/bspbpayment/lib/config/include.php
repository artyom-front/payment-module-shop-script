<?php

define('BSPB_PAYMENT_BANK_NAME', 'BSPB');

define('BSPB_PROD_URL' , 'https://pgtest.bspb.ru:5443/order');
define('BSPB_TEST_URL' , 'https://pgtest.bspb.ru:5443/order');

define('BSPB_ENABLE_FISCALE_OPTIONS', true);
define('BSPB_MEASUREMENT_NAME', 'шт');
define('BSPB_MEASUREMENT_CODE', 0);

define('BSPB_ENABLE_CALLBACK', true);

define('BSPB_PAYMENT_CURRENCY', serialize(array(
    '840' => 'USD',
    '980' => 'UAH',
    '643' => 'RUB',
    '810' => 'RUR',
    '946' => 'RON',
    '398' => 'KZT',
    '417' => 'KGS',
    '392' => 'JPY',
    '826' => 'GBR',
    '978' => 'EUR',
    '156' => 'CNY',
    '974' => 'BYR',
    '933' => 'BYN'
)));

// -------------------------------------------------

define('BSPB_PAYMENT_TEXT_LOGIN_API', 'Логин-API');
define('BSPB_PAYMENT_TEXT_PASSWORD', 'Пароль');
define('BSPB_PAYMENT_TEXT_TEST_MODE', 'Тестовый режим');
define('BSPB_PAYMENT_TEXT_TWO_PHASE_PAYMENTS', 'Двухстадийные платежи');

define('BSPB_PAYMENT_TEXT_SEND_CART_DATA', "Отправлять данные корзины покупателя");
define('BSPB_PAYMENT_TEXT_SEND_CART_DATA_DESCRIPTION', "");
define('BSPB_PAYMENT_TEXT_TAX_SYSTEM', 'Система налогооблажения');
define('BSPB_PAYMENT_TEXT_VAT_DEFAULT', 'Налоговая ставка по умолчаниюю');

define('BSPB_PAYMENT_ENTRY_TAX_SYSTEM_1', 'Общая');
define('BSPB_PAYMENT_ENTRY_TAX_SYSTEM_2', 'Упрощённая, доход');
define('BSPB_PAYMENT_ENTRY_TAX_SYSTEM_3', 'Упрощённая, доход минус расход');
define('BSPB_PAYMENT_ENTRY_TAX_SYSTEM_4', 'Eдиный налог на вменённый доход');
define('BSPB_PAYMENT_ENTRY_TAX_SYSTEM_5', 'Eдиный сельскохозяйственный налог');
define('BSPB_PAYMENT_ENTRY_TAX_SYSTEM_6', 'Патентная система налогообложения');

define('BSPB_PAYMENT_TEXT_PAYMENT_METHOD', 'Тип оплаты');
define('BSPB_PAYMENT_TEXT_PAYMENT_OBJECT', 'Тип оплачиваемой позиции');
define('BSPB_PAYMENT_TEXT_PAYMENT_METHOD_DELIVERY', 'Тип оплаты для доставки');

define('BSPB_PAYMENT_ENTRY_NO_VAT', 'Без НДС');
define('BSPB_PAYMENT_ENTRY_VAT0', 'НДС 0%');
define('BSPB_PAYMENT_ENTRY_VAT10', 'НДС 10%');
define('BSPB_PAYMENT_ENTRY_VAT18', 'НДС 18%');
define('BSPB_PAYMENT_ENTRY_VAT20', 'НДС 20%');
define('BSPB_PAYMENT_ENTRY_VAT10_110', 'НДС чека по расчетной ставке 10/110');
define('BSPB_PAYMENT_ENTRY_VAT18_118', 'НДС чека по расчетной ставке 18/118');
define('BSPB_PAYMENT_ENTRY_VAT20_120', 'НДС чека по расчетной ставке 20/120');

define('BSPB_PAYMENT_TEXT_FFD_VERSION', 'Версия ФФД');

define('BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_1', 'Полная предварительная оплата до момента передачи предмета расчёта');
define('BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_2', 'Частичная предварительная оплата до момента передачи предмета расчёта');
define('BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_3', 'Аванс');
define('BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_4', 'Полная оплата в момент передачи предмета расчёта');
define('BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_5', 'астичная оплата предмета расчёта в момент его передачи с последующей оплатой в кредит');
define('BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_6', 'Передача предмета расчёта без его оплаты в момент его передачи с последующей оплатой в кредит');
define('BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_7', 'Оплата предмета расчёта после его передачи с оплатой в кредит');

define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_1', 'Товар');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_2', 'Подакцизный товар');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_3', 'Работа');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_4', 'Услуга');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_5', 'Ставка азартной игры');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_6', 'Выигрыш в азартных играх');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_7', 'Лотерейный билет');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_8', 'Выигрыш в лотерею');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_9', 'Предоставление РИД');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_10', 'Платеж');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_11', 'Агентское вознаграждение');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_12', 'Составной предмет расчёта');
define('BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_13', 'Другое');

define('BSPB_PAYMENT_MSG_PAYMENT_ERROR', 'Ошибка оплаты. Обратитесть в магазин.');
define('BSPB_PAYMENT_MSG_EMAIL_ERROR', 'System Email is not set.');
define('BSPB_PAYMENT_MSG_CURRENCY_ERROR', 'Ошибка оплаты. Неизвестная валюта.');
define('BSPB_PAYMENT_MSG_ERROR_MESSAGE', 'Ошибка оплаты: ');
define('BSPB_PAYMENT_TEXT_REDIRECT', 'Перенаправление к форме оплаты...');
define('BSPB_PAYMENT_TEXT_REDIRECT_BUTTON', 'перейти к оплате');
define('BSPB_PAYMENT_TEXT_DELIVERY', 'Доставка');
