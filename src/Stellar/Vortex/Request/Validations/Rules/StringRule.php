<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Override;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;
use Stellar\Vortex\Request\Validations\Rules\StringRule\Exceptions\InvalidSizeException;
use Stellar\Vortex\Request\Validations\Rules\StringRule\Traits\Rules;

class StringRule extends Rule
{
    use Rules;

    public const string STRING_VALIDATION = 'string';
    public const string MIN_LENGTH_VALIDATION = 'string.min';
    public const string MAX_LENGTH_VALIDATION = 'string.max';
    public const string SIZE_VALIDATION = 'string.size';
    public const string UPPER_CASE_VALIDATION = 'string.upper_case';
    public const string LOWER_CASE_VALIDATION = 'string.lower_case';
    public const string MIXED_CASE_VALIDATION = 'string.mixed_case';
    public const string MAX_WORDS_VALIDATION = 'string.max_words';
    public const string REGEX_VALIDATION = 'string.regex';
    public const string MAX_ATTRIBUTE_KEY = '$max';
    public const string MIN_ATTRIBUTE_KEY = '$min';
    public const string SIZE_ATTRIBUTE_KEY = '$size';
    public const string WORDS_ATTRIBUTE_KEY = '$words';

    private ?int $max = null;
    private ?int $min = null;
    private ?int $size = null;
    private ?int $max_words = null;
    private ?bool $lower_case = null;
    private ?bool $upper_case = null;
    private ?bool $mixed_case = null;
    private ?bool $pattern = null;

    protected array $feedback_messages = [
        self::STRING_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a string.',
        self::MIN_LENGTH_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' must be at least ' . self::MIN_ATTRIBUTE_KEY . ' characters.',
        self::MAX_LENGTH_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' may not be greater than ' . self::MAX_ATTRIBUTE_KEY . ' characters.',
        self::SIZE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' must be ' . self::SIZE_ATTRIBUTE_KEY . ' characters.',
        self::UPPER_CASE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be uppercase.',
        self::LOWER_CASE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be lowercase.',
        self::MIXED_CASE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be lowercase and uppercase.',
        self::MAX_WORDS_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field should have a maximum of $words words',
        self::REGEX_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field not match the format.',
    ];

    public function applyRule(Request $request): void
    {
        $this->checkString()
            ->checkSize()
            ->checkMax()
            ->checkMin()
            ->checkUpperCase()
            ->checkLowerCase()
            ->checkMixedCase()
            ->checkMaxWords()
            ->checkRegex();
    }

    public function getRuleKey(): string
    {
        return 'string';
    }

    public function customAttributes(): array
    {
        return array_merge(parent::customAttributes(), [
            self::MAX_ATTRIBUTE_KEY => $this->max,
            self::MIN_ATTRIBUTE_KEY => $this->min,
            self::SIZE_ATTRIBUTE_KEY => $this->size,
            self::WORDS_ATTRIBUTE_KEY => $this->max_words,
        ]);
    }

    public function max(int $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function min(int $min): static
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @param int $size
     * @return $this
     * @throws InvalidSizeException
     */
    public function size(int $size): static
    {
        if ($size < 0) {
            throw new InvalidSizeException($size);
        }

        $this->size = $size;

        return $this;
    }

    public function regexPattern(string $pattern): static
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function words(int $max_count): static
    {
        $this->max_words = $max_count;

        return $this;
    }
}