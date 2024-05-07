<?php

namespace Stellar\Facades;

use Closure;

class Collection
{
    public function __construct(protected array $array)
    {
    }

    public function push(mixed $append): static
    {
        $this->array[] = $append;

        return $this;
    }

    public function values(): static
    {
        $this->array = array_values($this->array);

        return $this;
    }

    public static function from(array $array): static
    {
        return new static($array);
    }

    public function get(string|int $key): array
    {
        return $this->array[$key];
    }

    public function all(): array
    {
        return $this->array;
    }

    public function __isset(string $name): bool
    {
        return isset($this->array[$name]);
    }

    public function __get(string $name)
    {
        return $this->array[$name];
    }

    public function __set(string $name, $value): void
    {
        $this->array[$name] = $value;
    }

    public function __serialize(): array
    {
        return $this->array;
    }

    public function __unserialize(array $data): void
    {
        $this->array = $data;
    }

    public function map(Closure $callable): static
    {
        $this->array = array_map($callable, array_values($this->array), array_keys($this->array));

        return $this;
    }

    public function max()
    {
        return max($this->array);
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function slice(int $offset, ?int $length = null, bool $preserve_keys = false): static
    {
        $this->array = array_slice($this->array, $offset, $length, $preserve_keys);

        return $this;
    }

    public function last()
    {
        $array = array_reverse($this->array);

        return array_pop($array);
    }

    public function first()
    {
        return array_pop($this->array);
    }

    public function sum(): int|float
    {
        return array_sum($this->array);
    }

    public function implode(string $separator): string
    {
        return implode($separator, $this->array);
    }

    public function search(mixed $value, bool $strict = false): false|int|string
    {
        if (is_callable($value)) {
            foreach ($this->array as $index => $array_value) {
                if ($value($array_value) === true) {
                    return $index;
                }
            }

            return false;
        }

        return array_search($value, $this->array, $strict);
    }

    public function flatten(): static
    {
        $new_array = [];

        foreach ($this->array as $value) {
            if (is_array($value)) {
                $new_array = array_merge($new_array, $this->recursiveFlatten($value));
            }
        }

        $this->array = $new_array;

        return $this;
    }

    private function recursiveFlatten(array $array): array
    {
        $new_array = [];

        foreach ($array as $value) {
            if (is_array($value)) {
                $new_array = array_merge($new_array, $this->recursiveFlatten($value));
            } else {
                $new_array[] = $value;
            }
        }

        return $new_array;
    }

    public function contains(mixed $value): bool
    {
        return in_array($value, $this->array);
    }

    public function filter(?callable $callback = null): static
    {
        if ($callback === null) {
            return $this->filterByValue();
        }

        return $this->filterByCallback($callback);
    }

    private function filterByValue(): static
    {
        foreach ($this->array as $index => $value) {
            if (empty($value)) {
                unset($this->array[$index]);
            }
        }

        return $this;
    }

    private function filterByCallback(callable $callback): static
    {
        foreach ($this->array as $index => $value) {
            if (!$callback($value)) {
                unset($this->array[$index]);
            }
        }

        return $this;
    }

    public function merge(array|Collection $array_merge): static
    {
        $this->array = array_merge(
            $this->array,
            $array_merge instanceof Collection ? $array_merge->toArray() : $array_merge
        );

        return $this;
    }

    public function toArray(): array
    {
        return $this->array;
    }

    public function empty(): bool
    {
        return empty($this->array);
    }

    public function isNotEmpty(): bool
    {
        return !static::empty();
    }

    public function each(callable $closure): static
    {
        foreach ($this->array as $index => $item) {
            if ($closure($item, $index) === false) {
                return $this;
            }
        }

        return $this;
    }
}