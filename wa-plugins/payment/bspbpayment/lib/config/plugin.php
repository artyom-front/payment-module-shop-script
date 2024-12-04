<?php
require_once 'include.php';
return array(
    'name' => 'БСПБ оплата картой банка',
    'description' => 'Oплата банковскими картами через эквайринг '. BSPB_PAYMENT_BANK_NAME,
//    'icon' => 'img/logo16.png',
    'logo' => 'img/logo.png',
    'version' => '1.0.0',
    'type' => waPayment::TYPE_ONLINE,
);
