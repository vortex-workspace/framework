<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Override;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;
use Stellar\Vortex\Request\Validations\Rules\DateRule\Exceptions\InvalidDateFormatException;
use Stellar\Vortex\Settings\Enum\SettingKey;
use Stellar\Vortex\Settings\Setting;

class DateRule extends Rule
{
    public const string DATE_VALIDATION = 'date';
    public const string FORMAT_VALIDATION = 'date.format';
    public const string PAST_VALIDATION = 'date.past';
    public const string FUTURE_VALIDATION = 'date.future';
    public const string MAX_VALIDATION = 'date.max';
    public const string MIN_VALIDATION = 'date.min';
    public const string BETWEEN_VALIDATION = 'date.between';
    public const string TODAY_VALIDATION = 'date.today';
    private ?string $format = null;
    private null|string|Carbon $max = null;
    private null|string|Carbon $min = null;
    private null|string|Carbon $between_start = null;
    private null|string|Carbon $between_end = null;
    private null|string|Carbon $past = null;
    private null|string|Carbon $future = null;
    private null|bool $today = null;

    protected array $feedback_messages = [
        self::DATE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid date with $format format.',
        self::PAST_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be in the past.',
        self::FUTURE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be in the future.',
        self::MAX_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be before $max.',
        self::MIN_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be after $min.',
        self::BETWEEN_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be between $start and $end.',
        self::TODAY_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be today.',
    ];

    /**
     * @return void
     * @throws InvalidDateFormatException
     */
    #[Override] public function applyRule(Request $request): void
    {
        $this->format = $this->format ?? Setting::get('app.' . SettingKey::APP_DEFAULT_DATE_FORMAT->value);

        try {
            $date = Carbon::createFromFormat($this->format, $this->value);
        } catch (InvalidFormatException) {
            $this->fireError(self::DATE_VALIDATION);

            return;
        }

        if ($this->format !== null && !Carbon::hasFormat($this->value, $this->format)) {
            $this->fireError(self::FORMAT_VALIDATION);
        }

        if ($this->past !== null) {
            if ($date instanceof Carbon && !$date->isPast()) {
                $this->fireError(self::PAST_VALIDATION);
            }
        }

        if ($date instanceof Carbon) {
            if ($this->future !== null && !$date->isFuture()) {
                $this->fireError(self::FUTURE_VALIDATION);
            }

            if ($this->max !== null) {
                if (!$date->lessThanOrEqualTo($this->checkFormat($this->max))) {
                    $this->fireError(self::MAX_VALIDATION);
                }
            }

            if ($this->min !== null) {
                if (!$date->greaterThanOrEqualTo($this->checkFormat($this->min))) {
                    $this->fireError(self::MIN_VALIDATION);
                }
            }

            if ($this->between_start !== null) {
                $this->checkBetween($date);
            }

            if ($this->today !== null && !$date->isToday()) {
                $this->fireError(self::TODAY_VALIDATION);
            }
        }
    }

    /**
     * @param Carbon $date
     * @return void
     * @throws InvalidDateFormatException
     */
    private function checkBetween(Carbon $date): void
    {
        $this->between_start = $this->checkFormat($this->between_start);
        $this->between_end = $this->checkFormat($this->between_end);

        if (!$date->between($this->between_start, $this->between_end)) {
            $this->fireError(self::BETWEEN_VALIDATION);
        }
    }

    /**
     * @param Carbon|string $format
     * @return Carbon
     * @throws InvalidDateFormatException
     */
    private function checkFormat(Carbon|string $format): Carbon
    {
        if (is_string($format)) {
            $result = Carbon::createFromFormat($this->format, $format);

            if ($result === false) {
                throw new InvalidDateFormatException($format);
            }

            return $result;
        }

        return $format;
    }

    #[Override] public function getRuleKey(): string
    {
        return 'date';
    }

    public function format(string $date_format = 'Y-m-d'): static
    {
        $this->format = $date_format;

        return $this;
    }

    public function max(Carbon|string $maxDate): static
    {
        $this->max = $maxDate;

        return $this;
    }

    public function min(Carbon|string $minDate): static
    {
        $this->min = $minDate;

        return $this;
    }

    public function between(Carbon|string $start, Carbon|string $end = 'now'): static
    {
        $this->between_start = $start;
        $this->between_end = $end;

        return $this;
    }

    /**
     * - Note: Validate if date is in the past.
     * @return static
     */
    public function past(): static
    {
        $this->past = true;

        return $this;
    }

    /**
     * - Note: Validate if date is in the future.
     * @return static
     */
    public function future(): static
    {
        $this->future = true;

        return $this;
    }

    /**
     * - Note: Validate if date is in today.
     * @return static
     */
    public function today(): static
    {
        $this->today = true;

        return $this;
    }

    public function customAttributes(): array
    {
        return array_merge(parent::customAttributes(), [
            '$min' => $this->min,
            '$max' => $this->max,
            '$start' => $this->between_start?->toDateTimeString(),
            '$end' => $this->between_end?->toDateTimeString(),
            '$format' => $this->format,
        ]);
    }
}