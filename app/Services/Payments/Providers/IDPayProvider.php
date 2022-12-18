<?php
namespace App\Services\Payments\Providers;

use App\Services\Payments\Contracts\AbstractProvider;
use App\Services\Payments\Contracts\PayableInterface;
use App\Services\Payments\Contracts\VerifaibleInterface;

class IDPayProvider extends AbstractProvider implements PayableInterface , VerifaibleInterface
{
    private $statusOk = 100;

    public function pay()
    {
        $params = array(
            'order_id' => $this->request->getOrderId(),
            'amount' => $this->request->getAmount(),
            'name' => $this->request->getUser()->name,
            'phone' => $this->request->getUser()->mobile,
            'mail' => $this->request->getUser()->email,
            'callback' => route('payments.callback'),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: ' . $this->request->getAPIKey() . '',
            'X-SANDBOX: 1'
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result,true);

        if (isset($result['error_code']))
        {
            throw new \InvalidArgumentException($result['error_message']);
        }

        return redirect()->away($result['link']);
    }

    public function verify()
    {
        $requestParams = $this->request;

        $params = array(
            'id' => $this->request->getId(),
            'order_id' => $this->request->getOrderId(),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/verify');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: ' . $this->request->getAPIKey() .'',
            'X-SANDBOX: 1',
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result,true);

        if (isset($result['error_code']))
        {
            return [
                'status' => false,
                'statusCode' => $result['error_code'],
                'msg' => $result['error_message']
            ];
        }
        if ($result['status'] === $this->statusOk)
        {
            return [
                'status' => true,
                'statusCode' => $result['status'],
                'data' => $result
            ];
        }
        return ['status' => true,
                'statusCode' => $result['status'],
                'data' => $result];
    }
}
