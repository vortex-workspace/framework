<?php

namespace Stellar;

use Cosmo\Command;
use Cosmo\Command\Enums\CommandResponse;
use ReflectionParameter;
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
        $adapters = [];

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

                    $adapters[$adapter][$method_name] = $formated_method;
                }
            }
        }

        $file_classes = $this->mountAdapterClasses($adapters);

        File::createWithContent(
            '_ide_adapters.php',
            storage_path() . '/internals/cache/ide',
            $file_classes,
            force: true,
            recursive: true
        );

        return CommandResponse::SUCCESS;
    }

    private function mountAdapterClasses(array $adapters): string
    {
        $string_classes = '<?php ' . PHP_EOL . PHP_EOL;

        foreach ($adapters as $adapter_name => $adapter) {
            $string_classes .= $this->mountClassString($adapter, $adapter_name) . PHP_EOL . PHP_EOL . PHP_EOL;
        }

        return $string_classes;
    }

    private function mountClassString(array $class_definition, string $adapter_name): string
    {
        $class_string = 'namespace ' .
            StrTool::replace(StrTool::beforeLast($adapter_name, '\\'), '/', '\\') .
            PHP_EOL .
            '{' .
            PHP_EOL .
            '    class ' .
            StrTool::afterLast($adapter_name, '\\') .
            ' {' .
            PHP_EOL;

        foreach ($class_definition as $method) {
            $class_string .= $this->mountMethodString($method) . PHP_EOL;
        }

        return $class_string .
            '    }' .
            PHP_EOL .
            '}';
    }

    private function mountMethodString(array $method): string
    {
        $method_parameters = $this->mountParametersString($method['parameters'] ?? null);

        return '        public ' .
            ($method['method_type'] === 'static' ? 'static ' : '') .
            'function ' .
            $method['name'] .
            '(' .
            $method_parameters .
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
            "($method_parameters);" .
            PHP_EOL .
            '        }';

    }

    private function mountParametersString(?array $parameters): string
    {
        if (empty($parameters)) {
            return '';
        }

        $parameters_string = '';

        /** @var ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            if (($type = $parameter->getType()) !== null) {
                $parameters_string .= $type->getName() . ' ';
            }

            $parameters_string .= $parameter->getName();
        }

        return $parameters_string;
    }
}