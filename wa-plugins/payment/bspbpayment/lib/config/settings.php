<?php
$form_fields = array (
    'userName' => array(
        'value' => '',
        'title' => BSPB_PAYMENT_TEXT_LOGIN_API,
//        'description' => '',
        'control_type' => 'input',
    ),
    'password' => array(
        'value' => '',
        'title' => BSPB_PAYMENT_TEXT_PASSWORD,
        'description' => '',
        'control_type' => 'password',
    ),
    'test_mode' => array(
        'value' => '1',
        'title' => BSPB_PAYMENT_TEXT_TEST_MODE,
        'description' => '',
        'control_type' => 'checkbox',
    ),
    'stage_mode' => array(
        'value' => 'one-stage',
        'title' => BSPB_PAYMENT_TEXT_TWO_PHASE_PAYMENTS,
        'description' => '',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            'one-stage' => 'Disabled',
            'two-stage' => 'Enabled',
        )
    ),
);

$form_fields_ext = array(
    'userName' => array(
        'value' => '',
        'title' => BSPB_PAYMENT_TEXT_LOGIN_API,
//        'description' => '',
        'control_type' => 'input',
    ),
    'password' => array(
        'value' => '',
        'title' => BSPB_PAYMENT_TEXT_PASSWORD,
        'description' => '',
        'control_type' => 'password',
    ),
    'test_mode' => array(
        'value' => '1',
        'title' => BSPB_PAYMENT_TEXT_TEST_MODE,
        'description' => '',
        'control_type' => 'checkbox',
    ),
    'stage_mode' => array(
        'value' => 'one-stage',
        'title' => BSPB_PAYMENT_TEXT_TWO_PHASE_PAYMENTS,
        'description' => '',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            'one-stage' => 'Disabled',
            'two-stage' => 'Enabled',
        )
    ),
    'send_order' => array(
        'value' => '',
        'title' => BSPB_PAYMENT_TEXT_SEND_CART_DATA,
        'control_type' => 'checkbox',
        'class' => '',
        'description' => BSPB_PAYMENT_TEXT_SEND_CART_DATA_DESCRIPTION . BSPB_PAYMENT_ENTRY_TAX_SYSTEM_1
    ),

    'tax_system' => array(
        'value' => '',
        'title' => BSPB_PAYMENT_TEXT_TAX_SYSTEM ,
//        'description' => '',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            '0' => BSPB_PAYMENT_ENTRY_TAX_SYSTEM_1,
            '1' => BSPB_PAYMENT_ENTRY_TAX_SYSTEM_2,
            '2' => BSPB_PAYMENT_ENTRY_TAX_SYSTEM_3,
            '3' => BSPB_PAYMENT_ENTRY_TAX_SYSTEM_4,
            '4' => BSPB_PAYMENT_ENTRY_TAX_SYSTEM_5,
            '5' => BSPB_PAYMENT_ENTRY_TAX_SYSTEM_6,
        ),
    ),

    'FFDVersion' => array(
        'value' => 'v1_05',
        'title' => BSPB_PAYMENT_TEXT_FFD_VERSION,
//        'description' => '',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            'v1_05' => 'v1.05',
            'v1_2' => 'v1.2',
        ),
    ),

    'ffd_paymentMethodType' => array(
        'title' => BSPB_PAYMENT_TEXT_PAYMENT_METHOD,
        'control_type' => waHtmlControl::SELECT,
        'value' => '1',
        'options' => array(
            '1' => BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_1,
            '2' => BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_2,
            '3' => BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_3,
            '4' => BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_4,
            '5' => BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_5,
            '6' => BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_6,
            '7' => BSPB_PAYMENT_ENTRY_PAYMENT_METHOD_7,
        ),
        'description' => '',
    ),
    'ffd_paymentObjectType' => array(
        'title' => BSPB_PAYMENT_TEXT_PAYMENT_OBJECT,
        'control_type' => waHtmlControl::SELECT,
        'value' => '1',
        'options' => array(
            '1' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_1,
            '2' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_2,
            '3' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_3,
            '4' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_4,
            '5' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_5,
//            '6' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_6,
            '7' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_7,
//            '8' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_8,
            '9' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_9,
            '10' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_10,
            '11' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_11,
            '12' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_12,
            '13' => BSPB_PAYMENT_ENTRY_PAYMENT_OBJECT_13,
        ),
        'description' => '',
    ),
);

if (BSPB_ENABLE_FISCALE_OPTIONS === true) {
    $form_fields = array_merge($form_fields, $form_fields_ext);
}
return $form_fields;
