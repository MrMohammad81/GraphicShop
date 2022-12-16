<?php
namespace App\Services\Payments;

use App\Services\Payments\Contracts\RequestInterface;
use App\Services\Payments\Exception\ProviderNotFoundException;
use mysql_xdevapi\Exception;

class PaymentService
{
    public const IDPAY = 'IDPayProvider';
    public const ZARINPAL = 'ZarinpalProvider';

    public function __construct(private string $providerName, private RequestInterface $request)
    {

    }

    public function pay()
    {
        return $this->findProvider()->pay();
    }

    private function findProvider()
    {
        $className = 'App\\Services\\Payments\\Providers\\' . $this->providerName;

        if (!class_exists($className))
        {
            throw new ProviderNotFoundException('درگاه پرداخت موردنظر یافت نشد');
        }

        return new $className($this->request);
    }
}
