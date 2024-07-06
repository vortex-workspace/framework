<?php

namespace Stellar\Boot\Application\Traits;

use Core\Contracts\GatewayInterface;
use Core\Contracts\RequestInterface;
use Stellar\AdapterAlias;
use Stellar\Adapters\RequestAdapter;
use Stellar\Boot\Application;
use Stellar\Boot\Application\Exceptions\DuplicatedAdapter;
use Stellar\Boot\Application\Exceptions\InvalidGateway;
use Stellar\Boot\Application\Exceptions\InvalidProvider;
use Stellar\Boot\Application\Exceptions\TryRegisterDuplicatedGatewayMethod;
use Stellar\Composer\Package;
use Stellar\Gateway;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Provider;
use Stellar\Setting;

trait Providers
{
    /**
     * @param array $providers
     * @return Application
     * @throws DuplicatedAdapter
     * @throws InvalidGateway
     * @throws InvalidProvider
     * @throws TryRegisterDuplicatedGatewayMethod
     */
    private function loadProviders(array $providers): Application
    {
        foreach ($providers as $provider) {
            if (!(($provider = (new $provider)) instanceof Provider)) {
                throw new InvalidProvider($provider);
            }

            $this->loadProviderSettings($provider);
            $provider::routes();
            $this->appendCommands($provider::commands());
            $this->createProviderAdapters($provider);
            $this->loadProviderGateways($provider);
            $this->bootProvider(new RequestAdapter(), new $provider);
        }

        return $this;
    }

    private function loadProviderSettings(Provider $provider): void
    {
        foreach ($provider::settings() as $setting) {
            $this->setting_files[$provider::class][$setting] = $setting;
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
     * @throws DuplicatedAdapter
     * @throws InvalidGateway
     * @throws InvalidProvider
     * @throws TryRegisterDuplicatedGatewayMethod
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

    /**
     * @param Provider $provider
     * @return void
     * @throws InvalidGateway
     * @throws TryRegisterDuplicatedGatewayMethod
     */
    public function loadProviderGateways(Provider $provider): void
    {
        foreach ($provider::gateways() as $gateway) {
            if (!((new $gateway) instanceof GatewayInterface)) {
                throw new InvalidGateway($gateway);
            }

            $this->loadGateway($gateway);
        }
    }

    /**
     * @param Gateway|string $gateway
     * @return void
     * @throws TryRegisterDuplicatedGatewayMethod
     */
    public function loadGateway(Gateway|string $gateway): void
    {
        foreach ($gateway::staticMethods() as $method) {
            if (isset($this->gateways[$gateway::adapterClass()]['static'][$method->name]) ||
                isset($this->gateways[$gateway::adapterClass()]['non_static'][$method->name])) {
                throw new TryRegisterDuplicatedGatewayMethod($method->name);
            }

            $this->gateways[$gateway::adapterClass()]['static'][$method->name] = $method;
        }

        foreach ($gateway::nonStaticMethods() as $method) {
            if (isset($this->gateways[$gateway::adapterClass()]['static'][$method->name]) ||
                isset($this->gateways[$gateway::adapterClass()]['non_static'][$method->name])) {
                throw new TryRegisterDuplicatedGatewayMethod($method->name);
            }

            $this->gateways[$gateway::adapterClass()]['non_static'][$method->name] = $method;
        }
    }

    /**
     * @param string|Provider $provider
     * @return void
     * @throws DuplicatedAdapter
     */
    private function createProviderAdapters(string|Provider $provider): void
    {
        if (!empty($adapters = $provider::adapters())) {
            /** @var AdapterAlias $adapter */
            foreach ($adapters as $adapter) {
                $this->createAdapterClass($adapter);
            }
        }
    }

    /**
     * @param AdapterAlias $adapterAlias
     * @return void
     * @throws DuplicatedAdapter
     */
    private function createAdapterClass(AdapterAlias $adapterAlias): void
    {
        $full_class = $adapterAlias->namespace . '\\' . $adapterAlias->class_name;

        if (isset($this->adapters[$full_class])) {
            throw new DuplicatedAdapter($full_class);
        }

        $default_method = "public static function defaultClass(): string{return '$adapterAlias->default_class';}";
        eval("namespace $adapterAlias->namespace { class $adapterAlias->class_name extends \Stellar\Adapter { $default_method }}");
        $this->adapters[$full_class] = $adapterAlias;
    }

    public function getSettingFiles(): array
    {
        return $this->setting_files;
    }
}