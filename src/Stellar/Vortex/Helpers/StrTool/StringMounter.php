<?php

namespace Stellar\Vortex\Helpers\StrTool;

use Stellar\Vortex\Helpers\StrTool;

class StringMounter
{
    public function __construct(private string $string)
    {
    }

    public function __toString(): string
    {
        return $this->string;
    }

    public function get(): string
    {
        return $this->string;
    }

    public function startWith(string|array $needed): bool
    {
        return StrTool::startWith($this->string, $needed);
    }

    public function finishWith(string|array $needed): bool
    {
        return StrTool::finishWith($this->string, $needed);
    }

    public function isJson(): bool
    {
        return StrTool::isJson($this->string);
    }

    public function isAscii(): bool
    {
        return StrTool::isAscii($this->string);
    }

    public function isUuid(): bool
    {
        return StrTool::isUuid($this->string);
    }

    public function contains(string|array $needle): bool
    {
        return StrTool::contains($this->string, $needle);
    }

    public function containsAny(string|array $needle): bool
    {
        return StrTool::containsAny($this->string, $needle);
    }

    public function containsAll(string|array $needle): bool
    {
        return StrTool::containsAll($this->string, $needle);
    }

    public function match(string $pattern): bool
    {
        return StrTool::match($this->string, $pattern);
    }

    public function equal(string|array|int $needed, bool $strict = false): bool
    {
        return StrTool::equal($this->string, $needed, $strict);
    }

    public function length(): int
    {
        return StrTool::length($this->string);
    }

    public function wordCount(): int
    {
        return StrTool::wordCount($this->string);
    }

    public function split(): array
    {
        return StrTool::split($this->string);
    }

    public function camelCase(): static
    {
        $this->string = StrTool::camelCase($this->string);

        return $this;
    }

    public function pascalCase(): static
    {
        $this->string = StrTool::pascalCase($this->string);

        return $this;
    }

    public function snakeCase(): static
    {
        $this->string = StrTool::snakeCase($this->string);

        return $this;
    }

    public function slugCase(): static
    {
        $this->string = StrTool::slugCase($this->string);

        return $this;
    }

    public function kebabCase(): static
    {
        $this->string = StrTool::kebabCase($this->string);

        return $this;
    }

    public function humanReadableCase(): static
    {
        $this->string = StrTool::humanReadableCase($this->string);

        return $this;
    }

    public function titleCase(): static
    {
        $this->string = StrTool::titleCase($this->string);

        return $this;
    }

    public function lowerCase(): static
    {
        $this->string = StrTool::lowerCase($this->string);

        return $this;
    }

    public function upperCase(): static
    {
        $this->string = StrTool::upperCase($this->string);

        return $this;
    }

    public function forceFinishWith(string $force_string): static
    {
        $this->string = StrTool::forceFinishWith($this->string, $force_string);

        return $this;
    }

    public function forceStartWith(string $force_string): static
    {
        $this->string = StrTool::forceStartWith($this->string, $force_string);

        return $this;
    }

    public function capitalize(): static
    {
        $this->string = StrTool::capitalize($this->string);

        return $this;
    }

    public function pluralize(): static
    {
        $this->string = StrTool::pluralize($this->string);

        return $this;
    }

    public function singularize(): static
    {
        $this->string = StrTool::singularize($this->string);

        return $this;
    }

    public function unaccent(): static
    {
        $this->string = StrTool::unaccent($this->string);

        return $this;
    }

    public function absoluteUpperFistLetter(): static
    {
        $this->string = StrTool::absoluteUpperFistLetter($this->string);

        return $this;
    }

    public function firstLetterUppercase(): static
    {
        $this->string = StrTool::firstLetterUppercase($this->string);

        return $this;
    }

    public function between(string $start, string $end): static
    {
        $this->string = StrTool::between($this->string, $start, $end);

        return $this;
    }

    public function reverse(): static
    {
        $this->string = StrTool::reverse($this->string);

        return $this;
    }

    public function upperFirst(): static
    {
        $this->string = StrTool::upperFirst($this->string);

        return $this;
    }

    public function lowerFirst(): static
    {
        $this->string = StrTool::lowerFirst($this->string);

        return $this;
    }

    public function replace(
        array|string $search,
        array|string $replace,
        int          $limit = -1
    ): static
    {
        $this->string = StrTool::replace($this->string, $search, $replace, $limit);

        return $this;
    }

    public function replaceFirst(
        array|string $search,
        array|string $replace,
    ): static
    {
        $this->string = StrTool::replace($this->string, $search, $replace);

        return $this;
    }

    public function after(
        string $separator,
        bool   $with_separator = false,
        int    $ignore_first_separators = 0
    ): static
    {
        $result = StrTool::after($this->string, $separator, $with_separator, $ignore_first_separators);

        if ($result !== false) {
            $this->string = $result;
        }

        return $this;
    }

    public function before(
        string $separator,
        bool   $with_separator = false,
        int    $ignore_first_separators = 0
    ): static
    {
        $result = StrTool::before($this->string, $separator, $with_separator, $ignore_first_separators);

        if ($result !== false) {
            $this->string = $result;
        }

        return $this;
    }

    public function removeIfStartWith(string|array $needed): static
    {
        $this->string = StrTool::removeIfStartWith($this->string, $needed);

        return $this;
    }

    public function removeIfEndWith(string|array $needed): static
    {
        $this->string = StrTool::removeIfStartWith($this->string, $needed);

        return $this;
    }

    public function removeIfStartAndFinishWith(string|array $needed): static
    {
        $this->string = StrTool::removeIfStartAndFinishWith($this->string, $needed);

        return $this;
    }

    public function substring(int $start, ?int $length = null): static
    {
        $this->string = StrTool::substring($this->string, $start, $length);

        return $this;
    }

    public function afterLast(string $separator, bool $with_separator = false): static
    {
        $this->string = StrTool::afterLast($this->string, $separator, $with_separator);

        return $this;
    }
}