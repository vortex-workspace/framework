<?php

namespace Stellar\Boot\Application\Traits;

use Stellar\Adapters\RequestAdapter;
use Stellar\Boot\Application;
use Stellar\Boot\Application\Exceptions\InvalidProvider;
use Stellar\Core\Contracts\RequestInterface;
use Stellar\Provider;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Settings\Setting;

trait Providers
{
    /**
     * @return Application
     * @throws InvalidProvider
     * @throws InvalidSettingException
     */
    private function loadProviders(): Application
    {
        foreach (Setting::get(SettingKey::APP_PROVIDERS->value) as $provider) {
            if (!(($provider = (new $provider)) instanceof Provider)) {
                throw new InvalidProvider($provider);
            }

            $gateways = $this->gateways;

            if (!empty($provider_gateways = $provider::localGateways())) {
                $formated_gateways = [];

                foreach ($provider_gateways as $provider_gateway) {
                    $formated_gateways[$provider_gateway::baseInterface()] = $provider_gateway;
                }

                $gateways = array_merge($gateways, $formated_gateways);
            }

            $this->loadProviderSettings($provider);
            $provider::routes();
            $this->appendCommands($provider::commands());
            $this->bootProvider(RequestAdapter::getMatchClassObject(gateways: $gateways), new $provider);
        }

        return $this;
    }

    private function loadProviderSettings(Provider $provider): void
    {
        foreach ($provider::settings() as $setting) {
            Setting::uploadFileSetting($setting);
        }
    }

    private function bootProvider(RequestInterface $request, Provider $provider): void
    {
        if ($provider::canBoot($request, $this)) {
            $provider::boot($request, $this);
            $provider::afterBoot($request, $this);

            return;
        }

        $provider::afterNotBoot($request, $this);
    }
}