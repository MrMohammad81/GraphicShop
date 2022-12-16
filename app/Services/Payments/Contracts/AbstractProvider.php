<?php

namespace App\Services\Contracts;

abstract class AbstractProvider
{
    public function __construct(RequestInterface $request)
    {

    }
}
