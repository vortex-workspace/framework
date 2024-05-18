<?php

namespace Stellar;

use Cosmo\Command;
use Cosmo\Command\Enums\CommandResponse;
use ReflectionParameter;
use ReflectionUnionType;
use Stellar\Boot\Application;
use Stellar\Gateway\Method;
use Stellar\Helpers\StrTool;
use Stellar\Navigation\File;

class IdeAdapter extends Command
{
    protected function name(): string
    {
        return 'ide:gateways';
    }

    protected function handle(): CommandResponse
    {
        $gateway_adapters = [];
        $adapters = [];

        foreach (Application::getInstance()->getAdapters() as $adapter_class => $adapter) {
            $adapters[$adapter_class]['scope'] = $this->mountAdapterClass($adapter);
        }

        foreach (Application::getInstance()->getGateways() as $adapter => $method_types) {
            foreach ($method_types as $method_type => $methods) {
                /** @var Method $method */
                foreach ($methods as $method_name => $method) {
                    $formated_method = [];
                    $formated_method['name'] = $method_name;
                    $formated_method['instance_string'] = $adapter;
                    $formated_method['method_type'] = $method_type;

                    foreach ($method->getCallableReflection()->getParameters() as $parameter) {
                        if ($parameter->getPosition() === 0) {
                            if (($type = $parameter->getType()) !== null) {
                                $formated_method['instance'] = $type;
                                continue;
                            }
                        }

                        $formated_method['parameters'][] = $parameter;
                    }

                    $formated_method['return'] = $method->getCallableReflection()->getReturnType();

                    $gateway_adapters[$adapter][$method_name] = $formated_method;
                }
            }
        }

        $array_classes = $this->mountAdapterClasses($adapters, $gateway_adapters);
        $string_classes = '<?php ' . PHP_EOL . PHP_EOL;

        foreach ($array_classes as $class) {
            $string_classes .= $this->mountFinalStringClass($class) . PHP_EOL . PHP_EOL . PHP_EOL;
        }

        File::createWithContent(
            '_ide_adapters.php',
            storage_path() . '/internals/cache/ide',
            $string_classes,
            force: true,
            recursive: true
        );

        return CommandResponse::SUCCESS;
    }

    private function mountFinalStringClass(array $class)
    {
        return str_replace('$methods', $class['methods'] ?? '', $class['scope']);
    }

    private function mountAdapterClass(AdapterAlias $adapterAlias)
    {
        return 'namespace ' .
            $adapterAlias->namespace .
            ' {' .
            PHP_EOL .
            "    /** @mixin \\$adapterAlias->default_class */" .
            PHP_EOL .
            '    class ' .
            StrTool::afterLast($adapterAlias->class_name, '\\') .
            ' {' .
            PHP_EOL .
            '        $methods' .
            PHP_EOL .
            '    }' .
            PHP_EOL .
            '}';
    }

    private function mountAdapterClasses(array $adapters, array $gateway_adapters): array
    {
        foreach ($gateway_adapters as $adapter_name => $adapter) {
            if (!isset($adapters[$adapter_name])) {
                dd($adapters[$adapter_name]);
            }

            $adapters[$adapter_name]['methods'] .= $this->appendMethods($adapter);
        }

        return $adapters;
    }

    private function appendMethods(array $class_definition): string
    {
        $methods = '';

        foreach ($class_definition as $method) {
            $methods .= $this->mountMethodString($method) . PHP_EOL;
        }

        return $methods;
    }

    private function mountMethodString(array $method): string
    {
        return '        public ' .
            ($method['method_type'] === 'static' ? 'static ' : '') .
            'function ' .
            $method['name'] .
            '(' .
            $this->mountParametersString($method['parameters'] ?? null) .
            ')' .
            ($method['return'] ? (': \\' . $method['return']) : '') .
            PHP_EOL .
            '        {' .
            PHP_EOL .
            '            /** @var \\' .
            ($method['instance']->getName() ?? $method['instance_string']) .
            ' $adapter **/' .
            PHP_EOL .
            '            return $adapter->' .
            $method['name'] .
            '(' .
            $this->mountParametersString(($method['parameters'] ?? null), true) .
            ');' .
            PHP_EOL .
            '        }';

    }

    private function mountParametersString(?array $parameters, bool $without_type = false): string
    {
        if (empty($parameters)) {
            return '';
        }

        $parameters_string = '';

        $parameters_count = count($parameters);

        /** @var ReflectionParameter $parameter */
        foreach ($parameters as $index => $parameter) {
            if ($without_type === false && ($types = $parameter->getType()) !== null) {
                $types = $types instanceof ReflectionUnionType ? $types->getTypes() : [$types];

                $type_count = count($types);

                foreach ($types as $type_index => $type) {
                    $parameters_string .= $type->getName() . ($type_index + 1 === $type_count ? ' ' : '|');
                }
            }

            $parameters_string .= '$' . $parameter->getName();

            if ($without_type === false && $parameter->isDefaultValueAvailable()) {
                $parameters_string .= ' = ' . ($parameter->getDefaultValue() ?? 'null');
            }

            $parameters_string .= ($parameters_count === $index + 1 ? '' : ', ');
        }

        return $parameters_string;
    }
}