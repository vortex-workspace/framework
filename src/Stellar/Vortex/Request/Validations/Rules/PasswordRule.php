<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Override;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;
use Stellar\Vortex\Request\Validations\Rules\PasswordRule\Traits\Rules;
use Stellar\Vortex\Request\Validations\Rules\StringRule\Exceptions\InvalidSizeException;

class PasswordRule extends Rule
{
    use Rules;

    public const string PASSWORD_VALIDATION = 'password';
    public const string MIN_LENGTH_VALIDATION = 'password.min';
    public const string MAX_LENGTH_VALIDATION = 'password.max';
    public const string SIZE_VALIDATION = 'password.size';
    public const string UPPER_CASE_VALIDATION = 'password.upper_case';
    public const string LOWER_CASE_VALIDATION = 'password.lower_case';
    public const string MIXED_CASE_VALIDATION = 'password.mixed_case';
    public const string SPECIAL_CHARACTER_VALIDATION = 'password.special_character';
    public const string NUMERIC_SEQUENCE_VALIDATION = 'password.numeric_sequence';
    public const string MAX_ATTRIBUTE_KEY = '$max';
    public const string MIN_ATTRIBUTE_KEY = '$min';
    public const string SIZE_ATTRIBUTE_KEY = '$size';

    private ?int $max = null;
    private ?int $min = null;
    private ?int $size = null;
    private ?int $special_characters = null;
    private ?string $available_characters = null;
    private ?int $numeric_sequence_count = null;
    private ?bool $disable_inverted_sequence = null;
    private ?bool $lower_case = null;
    private ?bool $upper_case = null;
    private ?bool $mixed_case = null;

    protected array $feedback_messages = [
        self::PASSWORD_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a string.',
        self::MIN_LENGTH_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be at least ' . self::MIN_ATTRIBUTE_KEY . ' characters.',
        self::MAX_LENGTH_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field may not be greater than ' . self::MAX_ATTRIBUTE_KEY . ' characters.',
        self::SIZE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be ' . self::SIZE_ATTRIBUTE_KEY . ' characters.',
        self::UPPER_CASE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be uppercase.',
        self::LOWER_CASE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be lowercase.',
        self::MIXED_CASE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be lowercase and uppercase.',
        self::SPECIAL_CHARACTER_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must have special characters.',
        self::NUMERIC_SEQUENCE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field cannot contain a numeric sequence.',
    ];

    #[Override] public function applyRule(Request $request): void
    {
        $this->checkString()
            ->checkSize()
            ->checkMin()
            ->checkMax()
            ->checkLowerCase()
            ->checkUpperCase()
            ->checkMixedCase()
            ->checkSpecialCharacters()
            ->checkNumericSequence();
    }

    #[Override] public function getRuleKey(): string
    {
        return 'password';
    }

    public function customAttributes(): array
    {
        return array_merge(parent::customAttributes(), [
            self::MAX_ATTRIBUTE_KEY => $this->max,
            self::MIN_ATTRIBUTE_KEY => $this->min,
            self::SIZE_ATTRIBUTE_KEY => $this->size,
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

    public function specialCharacters(
        int    $min_special_characters = 1,
        string $available_characters = '!@#$%^&*()'
    ): static
    {
        $this->special_characters = $min_special_characters;
        $this->available_characters = $available_characters;

        return $this;
    }

    public function numericSequence(int $sequence_count_to_fail = 2, bool $block_inverted_sequence = false): static
    {
        $this->numeric_sequence_count = $sequence_count_to_fail;
        $this->disable_inverted_sequence = $block_inverted_sequence;

        return $this;
    }
}