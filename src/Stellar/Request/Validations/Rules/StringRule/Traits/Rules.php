<?php

namespace Stellar\Request\Validations\Rules\StringRule\Traits;

use Stellar\Helpers\StrTool;

trait Rules
{
    private function checkString(): static
    {
        if (!is_string($this->value)) {
            $this->fireError(self::STRING_VALIDATION);
        }

        return $this;
    }

    private function checkSize(): static
    {
        if ($this->size !== null && StrTool::length($this->value) < $this->min) {
            $this->fireError(self::MIN_LENGTH_VALIDATION);
        }

        return $this;
    }

    private function checkMin(): static
    {
        if ($this->min !== null && StrTool::length($this->value) < $this->min) {
            $this->fireError(self::MIN_LENGTH_VALIDATION);
        }

        return $this;
    }

    private function checkMax(): static
    {
        if ($this->max !== null && StrTool::length($this->value) > $this->max) {
            $this->fireError(self::MAX_LENGTH_VALIDATION);
        }

        return $this;
    }


    private function checkUpperCase(): static
    {
        if ($this->upper_case !== null && !ctype_upper($this->value)) {
            $this->fireError(self::UPPER_CASE_VALIDATION);
        }

        return $this;
    }

    private function checkLowerCase(): static
    {
        if ($this->lower_case !== null && !ctype_lower($this->value)) {
            $this->fireError(self::LOWER_CASE_VALIDATION);
        }

        return $this;
    }

    private function checkMixedCase(): static
    {
        if ($this->mixed_case !== null && (ctype_lower($this->value) || ctype_upper($this->value))) {
            $this->fireError(self::MIXED_CASE_VALIDATION);
        }

        return $this;
    }

    private function checkMaxWords(): static
    {
        if ($this->max_words !== null && str_word_count($this->value) > $this->max_words) {
            $this->fireError(self::MAX_WORDS_VALIDATION);
        }

        return $this;
    }

    private function checkRegex(): static
    {
        if ($this->pattern !== null && !preg_match($this->pattern, $this->value)) {
            $this->fireError(self::REGEX_VALIDATION);
        }

        return $this;
    }
}