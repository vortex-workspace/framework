<?php

namespace Stellar\Services\Request;

use Core\Contracts\RequestInterface;
use Stellar\Request;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;

class RequestService extends AbstractRequestService
{
    protected function mount(): RequestInterface
    {
        return new (Setting::get(SettingKey::AppDefaultRequestClass->value, Request::class));
    }
}