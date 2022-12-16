<?php
namespace App\Services\Payments\Providers;

use App\Services\Contracts\AbstractProvider;
use App\Services\Contracts\PayableInterface;
use App\Services\Contracts\VerifaibleInterface;

class IDPayProvider extends AbstractProvider implements PayableInterface , VerifaibleInterface
{
    public function pay()
    {
        // TODO: Implement pay() method.
    }

    public function verify()
    {
        // TODO: Implement verify() method.
    }
}
