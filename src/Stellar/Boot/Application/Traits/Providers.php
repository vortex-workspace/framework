<?php

namespace Stellar\Boot\Application\Traits;

use Core\Contracts\RequestInterface;
use Stellar\Adapters\RequestAdapter;
use Stellar\Boot\Application;
use Stellar\Boot\Application\Exceptions\InvalidProvider;
use Stellar\Composer\Package;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Provider;
use Stellar\Setting;

trait Providers
{
    /**
     * @param array $providers
     * @return Application
     * @throws InvalidProvider
     */
    private function loadProviders(array $providers): Application
    {
        foreach ($providers as $provider) {
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
            $this->bootProvider(RequestAdapter::getMatchClassObjectForProvider(gateways: $gateways), new $provider);
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

    /**
     * @return $this
     * @throws InvalidProvider
     */
    private function loadProvidersFromPackages(): static
    {
        $providers = [];
        $final_providers = [];

        try {
            $providers = require storage_path(Package::PACKAGE_PROVIDERS_CACHE_PATH);
        } catch (PathNotFound) {
            $final_providers = [];
        }

        if (!empty($providers)) {
            foreach ($providers as $package_providers) {
                $final_providers = array_merge($final_providers, $package_providers);
            }
        }

        $this->loadProviders($final_providers);

        return $this;
    }
}