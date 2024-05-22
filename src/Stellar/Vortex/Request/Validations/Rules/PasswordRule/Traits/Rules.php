<?php

namespace Stellar\Vortex\Request\Validations\Rules\PasswordRule\Traits;

use Stellar\Vortex\Helpers\StrTool;

trait Rules
{
    private function checkString(): static
    {
        if (!is_string($this->value)) {
            $this->fireError(self::PASSWORD_VALIDATION);
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

    private function checkSpecialCharacters(): static
    {
        if ($this->special_characters !== null) {
            if (preg_match_all("/[$this->available_characters]/", $this->value) < $this->special_characters) {
                $this->fireError(self::SPECIAL_CHARACTER_VALIDATION);
            }
        }

        return $this;
    }

    private function checkNumericSequence(): static
    {
        if ($this->numeric_sequence_count !== null) {
            if ($this->hasNumericSequence()) {
                $this->fireError(self::NUMERIC_SEQUENCE_VALIDATION);

                return $this;
            }

            if ($this->disable_inverted_sequence && $this->hasNumericSequence(true)) {
                $this->fireError(self::NUMERIC_SEQUENCE_VALIDATION);
            }
        }

        return $this;
    }

    private function hasNumericSequence(bool $inverse = false): bool
    {
        $has_sequence = 0;
        $last_number = null;

        foreach (str_split($this->value) as $value) {
            if (is_numeric($value)) {
                if ($last_number === null || $value === $last_number + $last_number + ($inverse ? -1 : 1)) {
                    $has_sequence++;
                    $last_number = $value;

                    if ($has_sequence === $this->numeric_sequence_count) {
                        return true;
                    }

                    continue;
                }

                $last_number = $value;
                $has_sequence = 1;
            }
        }

        return false;
    }
}