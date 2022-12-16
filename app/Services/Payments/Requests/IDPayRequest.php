<?php
namespace App\Services\Payments\Requests;

use App\Services\Payments\Contracts\RequestInterface;

class IDPayRequest implements RequestInterface
{
    private $user;
    private $amount;
    private $orderId;

    public function __construct(array $data)
    {
        $this->user = $data['user'];
        $this->amount = $data['amount'];
        $this->orderId = $data['orderId'];
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getOrderID()
    {
        return $this->orderId;
    }
}
