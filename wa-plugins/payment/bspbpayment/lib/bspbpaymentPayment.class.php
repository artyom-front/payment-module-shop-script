<?php

require_once 'config/include.php';

/**
 *  Class bspbpaymentPayment
 * @property-read string $stage_mode
 * @property-read string $userName
 * @property-read string $password
 * @property-read $tax_system
 * @property-read $send_order
 * @property-read $FFDVersion
 */
class bspbpaymentPayment extends waPayment implements waIPayment
{

    private $currency = BSPB_PAYMENT_CURRENCY;
    private $version = '1.0.0';

    private $order_id;
    private $allowCallbacks = BSPB_ENABLE_CALLBACK;

    private $certificate_path = '';
    private $private_key_path = '';

    public function payment($payment_form_data, $order_data, $auto_submit = false)
    {
        if (empty($order_data['description'])) {
            $order_data['description'] = 'Заказ ' . $order_data['order_id'];
        }

        $order = waOrder::factory($order_data);
        if (!in_array($order->currency_id, $this->allowedCurrency())) {
            throw new waException(BSPB_PAYMENT_MSG_CURRENCY_ERROR);
        }

        $action_url = $this->getUrl();

        $this->certificate_path = wa()->getDataPath('crt', false, 'bspbpayment') . '/pgtest_cer.pem';
        $this->private_key_path = wa()->getDataPath('crt', false, 'bspbpayment') . '/pgtest_key.key';

        //        $currency_id = $order->currency_id;
        //        $currency = array_search($currency_id, unserialize($this->currency));
        $currency = $order->currency_id;
        $pattern = "@[^\\w\\d" . preg_quote("~@#$%^-_(){}'`+=[]:;/\\", '@') . "]+@u";
        $description = trim(preg_replace('@\\s{2,}@', ' ', preg_replace($pattern, ' ', $order_data['description'])));

        $data = [
            'typeRid' => 'Purchase',
            'amount' => number_format($order->total, 2, '.', ''),
            'currency' => $currency,
            'title' => $order->order_id,
            'description' => $description,
            'hppRedirectUrl' => 'https://pgtest.bspb.ru/result',
        ];

        if (BSPB_ENABLE_FISCALE_OPTIONS == true && $this->send_order) {
            $data['srcEmail'] = 'pg@bspb.ru';
            $data['receipt'] = $this->getOrderBundle($order_data);
        }


        $headers = [
            'Content-Type:application/json',
            'Authorization: Basic ' . base64_encode($this->userName . ":" . $this->password)
        ];

        $response = $this->sendData($action_url, ['order' => $data], $headers);
        $responseData = json_decode($response, true);

        if ($responseData) {
            if (isset($responseData['order'])) {
                $order = $responseData['order'];

                if (isset($order['hppUrl'], $order['id'], $order['password'])) {
                    $hppUrl = $responseData['order']['hppUrl'];
                    $order_id = $responseData['order']['id'];
                    $order_password = $responseData['order']['password'];

                    $formUrl = "$hppUrl?id=$order_id&password=$order_password";
                } else {
                    throw new waPaymentException(BSPB_PAYMENT_MSG_ERROR_MESSAGE . 'Объект order не содержит необходимых данных.');
                }
            } else {
                throw new waPaymentException(BSPB_PAYMENT_MSG_ERROR_MESSAGE . 'Ответ не содержит объекта order.');
            }
        } else {
            throw new waPaymentException(BSPB_PAYMENT_MSG_ERROR_MESSAGE . 'Ошибка разбора JSON-ответа.');
        }

        $view = wa()->getView();
        $view->assign('form_url', $formUrl);
        $view->assign('auto_submit', $auto_submit);

        $view->assign('loading_message', BSPB_PAYMENT_TEXT_REDIRECT);
        $view->assign('payment_button_text', BSPB_PAYMENT_TEXT_REDIRECT_BUTTON);

        return $view->fetch($this->path . '/templates/payment.html');
    }

    public function allowedCurrency()
    {
        return unserialize($this->currency);
    }

    private function getUrl()
    {
        if ($this->test_mode) {
            return BSPB_TEST_URL;
        } else {
            return BSPB_PROD_URL;
        }
    }

    /**
     * @param $order_data
     * @return string
     * @throws waPaymentException
     * @throws waException
     */
    protected function getOrderBundle($order_data)

    {
        $contact = new waContact($order_data['contact_id']);
        $email = $contact->get('email', 'default');
        $phone = $contact->get('phone', 'default');

        if (!$email && !$phone) {
            $mail = new waMail();
            $email = $mail->getDefaultFrom();
            $email = key($email);
            if (!$email) {
                self::log($this->id, BSPB_PAYMENT_MSG_EMAIL_ERROR);
                throw new waPaymentException(BSPB_PAYMENT_MSG_PAYMENT_ERROR);
            }
        }

        $payments[] = array(
            'type' => 2,
            'amt' => number_format($order_data['total'], 2, '.', ''),
        );

        $order_bundle = array(
            'consumer' => array(
                'email' => $email,
                'phone' => $phone,
            ),
            'items' => $this->getOrderItems($order_data),
            'payments' => $payments,
        );

        return ($order_bundle);
    }

    /**
     * @param $order_data
     * @return array
     * @throws waException
     * @throws waPaymentException
     */
    protected function getOrderItems($order_data)
    {
        $items = array();
        if (is_array($order_data['items'])) {
            foreach ($order_data['items'] as $key => $data) {
                //                if (!empty($data['tax_included']) && (int)$data['tax_rate'] > 0) {
                //                    self::log($this->id, sprintf('НДС не включен в цену товара: %s.', var_export($data, true)));
                //                    throw new waPaymentException('Ошибка платежа. Обратитесь в службу поддержки.');
                //                }
                $items[] = $this->formalizeItemData($data, $order_data, $key);
            }
        };

        // DELIVERY
        if (!empty($order_data['shipping'])) {
            //            if (!$order_data->shipping_tax_included && (int)$order_data->shipping_tax_rate > 0) {
            //                self::log($this->id, sprintf('НДС не включен в стоимость доставки (%s).', $order_data->shipping_name));
            //                throw new waPaymentException('Ошибка платежа. Обратитесь в службу поддержки.');
            //            }
            $data = array(
                'desc' => (!empty($order_data->shipping_name)) ? $order_data->shipping_name : BSPB_PAYMENT_TEXT_DELIVERY,
                'quantity' => 1,
                'price' => $order_data->shipping,
                'measure' => 0,
                'tax_rate' => $order_data->shipping_tax_rate,
                'type' => 4,
                'mode' => 1,
            );
            $position = count($items);
            $items[] = $this->formalizeItemData($data, $order_data, $position, true);
        }
        return $items;
    }

    /**
     * @param $data
     * @param $order_data
     * @param $number
     * @param bool $isDelivery
     * @return array
     */
    public function formalizeItemData($data, $order_data, $number, $isDelivery = false)
    {
        if (!empty($data['total_discount'])) {
            $data['total'] = $data['total'] - $data['total_discount'];
            $data['price'] = round($data['price'], 2) - round(ifset($data['discount'], 0.0), 2); //calculate flexible discounts
        }

        $item_data = array(
            'desc' => $data['name'],
            'quantity' => $data['quantity'],
            'price' => number_format($data['price'], 2, '.', ''),
            'measure' => BSPB_MEASUREMENT_CODE,
            'taxRate' => $this->getTaxType($data['tax_rate']),
            'type' => 1,
            'mode' => 1,
        );

        return $item_data;
    }

    /**
     * Calculation of VAT
     * @param $amount
     * @param $tax
     * @return float|int
     */
    protected function getTaxSum($amount, $tax)
    {
        if (!isset($tax)) {
            return 0;
        }
        $vat = ($amount * $tax) / (100 + $tax);
        return $vat;
    }

    /**
     * @param null $tax
     * @return int|mixed
     * @throws waPaymentException
     */
    protected function getTaxType($tax = null)
    {
        $bank_tax_codes = array(
            0 => 5,
            10 => 2,
            20 => 1
        );

        if ($tax === null) {
            return 6; //without tax
        }

        $tax = intval($tax);
        if (empty($bank_tax_codes[$tax])) {
            self::log(
                $this->id,
                "Unknown VAT rate: {$tax}. The list of available bets: see bank documentation."
            );
            throw new waPaymentException(BSPB_PAYMENT_MSG_PAYMENT_ERROR);
        }

        return $bank_tax_codes[$tax];
    }

    private function sendData($url, $data,  $headers = array())
    {

        if (!extension_loaded('curl') || !function_exists('curl_init')) {
            throw new waException('PHP extension cURL not found');
        }
        if (!($ch = curl_init())) {
            throw new waException('cURL init error');
        }
        if (curl_errno($ch) != 0) {
            throw new waException('cURL error: ' . curl_errno($ch));
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->certificate_path);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->private_key_path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        self::log(preg_replace("/payment$/", "", strtolower(__CLASS__)), "REQUEST: " . $url . "\n" . json_encode($data) . "\nRESPONSE: " . $response . "\nОшибка cURL: " . curl_error($ch));

        curl_close($ch);

        $app_error = null;

        if (curl_errno($ch) != 0) {
            $app_error = 'cURL error: ' . curl_errno($ch);
        }
        curl_close($ch);
        if ($app_error) {
            throw new waException($app_error);
        }
        if (empty($response)) {
            throw new waException('Empty server response');
        }

        return $response;
    }

    protected function callbackInit($request)
    {

        if (!empty($request['params'])) {
            $params = json_decode(base64_decode($request['params']), true);
            if (!empty($params['app_id']) && !empty($params['merchant_id'])) {
                $this->app_id = $params['app_id'];
                $this->merchant_id = $params['merchant_id'];
            }

            if (!empty($request['orderId'])) {
                $this->order_id = $request['orderId'];
            } elseif (!empty($request['mdOrder'])) {
                $this->order_id = $request['mdOrder'];
            }
        } elseif (!empty($request['app_id'])) {
            $this->app_id = $request['app_id'];
        }
        return parent::callbackInit($request);
    }

    protected function callbackHandler($request)
    {

        if (!$this->order_id) {
            throw new waPaymentException('Error. Unknown order ID.');
        }

        $url = $this->getUrl() . 'getOrderStatus.do';

        $params = array(
            'userName' => $this->userName,
            'password' => $this->password,
            'orderId' => $this->order_id,
        );

        $request = $this->sendData($url, $params);
        $transaction_data = $this->formalizeData($request);

        if ($request['ErrorCode'] == 0 && $request['OrderStatus'] == 2) {
            $message = $request['ErrorMessage'];
            $app_payment_method = self::CALLBACK_PAYMENT;
            $transaction_data['state'] = self::STATE_CAPTURED;
            $transaction_data['type'] = self::OPERATION_AUTH_CAPTURE;
            $url = $this->getAdapter()->getBackUrl(waAppPayment::URL_SUCCESS, $transaction_data);
        } elseif ($request['ErrorCode'] == 0 && $request['OrderStatus'] == 1) {
            $message = $request['ErrorMessage'];
            $app_payment_method = self::CALLBACK_PAYMENT;
            $transaction_data['state'] = self::STATE_CAPTURED;
            $transaction_data['type'] = self::OPERATION_AUTH_CAPTURE;
            $url = $this->getAdapter()->getBackUrl(waAppPayment::URL_SUCCESS, $transaction_data);
        } else {
            $message = $request['ErrorMessage'];

            switch ($request['ErrorCode']) {
                case 2:
                    //                    $message = 'Заказ отклонен по причине ошибки в реквизитах платежа.';
                    $app_payment_method = self::CALLBACK_DECLINE;
                    $transaction_data['state'] = self::STATE_DECLINED;
                    $transaction_data['type'] = self::OPERATION_CANCEL;
                    $url = $this->getAdapter()->getBackUrl(waAppPayment::URL_FAIL, $transaction_data);
                    break;

                case 5:
                    //                    $message = 'Ошибка значения параметра запроса.';
                    $app_payment_method = self::CALLBACK_DECLINE;
                    $transaction_data['state'] = self::STATE_DECLINED;
                    $transaction_data['type'] = self::OPERATION_CANCEL;
                    $url = $this->getAdapter()->getBackUrl(waAppPayment::URL_FAIL, $transaction_data);
                    break;

                case 6:
                    //                    $message = 'Незарегистрированный OrderId.';
                    $app_payment_method = self::CALLBACK_DECLINE;
                    $transaction_data['state'] = self::STATE_DECLINED;
                    $transaction_data['type'] = self::OPERATION_CANCEL;
                    $url = $this->getAdapter()->getBackUrl(waAppPayment::URL_FAIL, $transaction_data);
                    break;

                default:
                    //                    $message = $request['ErrorMessage'];
                    $app_payment_method = self::CALLBACK_DECLINE;
                    $transaction_data['state'] = self::STATE_DECLINED;
                    $transaction_data['type'] = self::OPERATION_CANCEL;
                    $url = $this->getAdapter()->getBackUrl(waAppPayment::URL_FAIL, $transaction_data);
                    break;
            }
        }

        $transaction_data = $this->saveTransaction($transaction_data, $request);
        $result = $this->execAppCallback($app_payment_method, $transaction_data);

        // Redirect customer
        if ($request['ErrorCode'] != '0') {
            wa()->getResponse()->redirect($this->getAdapter()->getBackUrl(waAppPayment::URL_FAIL));
        } else {
            wa()->getResponse()->redirect($this->getAdapter()->getBackUrl(waAppPayment::URL_SUCCESS));
        };
    }

    protected function formalizeData($transaction_raw_data)
    {
        $currency_id = $transaction_raw_data['currency'];

        $transaction_data = parent::formalizeData($transaction_raw_data);
        $transaction_data['native_id'] = $this->order_id;
        $order = explode('_', $transaction_raw_data['OrderNumber']);
        $transaction_data['order_id'] = $order[0];

        $arCurrency = unserialize($this->currency);
        $transaction_data['currency_id'] = $arCurrency[$currency_id];
        $transaction_data['amount'] = $transaction_raw_data['Amount'] / 100.0;

        return $transaction_data;
    }

    public function _sendGatewayData($data, $action_address, $headers = array())
    {

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_VERBOSE => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $action_address,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            //                CURLOPT_ENCODING, "gzip",
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
