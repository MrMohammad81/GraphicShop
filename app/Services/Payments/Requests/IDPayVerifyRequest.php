<?php

namespace App\Services\Payments\Requests;

class IDPayVerifyRequest implements \App\Services\Payments\Contracts\RequestInterface
{
    private $id;
    private $orderId;
    private $apiKey;

    public function __construct(array $data)
    {
        $this->orderId = $data['order_id'];
        $this->id = $data['id'];
        $this->apiKey = $data['apiKey'];
    }

    public function getId()
    {
        return $this->id;
    }


    public function getOrderID()
    {
        return $this->orderId;
    }

    public function getAPIKey()
    {
        return $this->apiKey;
    }
}
