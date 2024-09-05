<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class DateTimeGreaterThan implements ValidationRule
{
    protected string $otherDateField;

    /**
     * Создать новый экземпляр правила.
     *
     * @param  string  $otherDateField  Имя поля, которое должно быть меньше, чем текущее поле
     */
    public function __construct(string $otherDateField)
    {
        $this->otherDateField = $otherDateField;
    }

    /**
     * Выполнить правило валидации.
     *
     * @param  string  $attribute  Название атрибута, который проверяется
     * @param  mixed  $value  Значение атрибута, которое проверяется
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail  Обратный вызов, который вызывается при неудаче валидации
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Получаем значение другого поля из запроса
        $startDateValue = request()->input($this->otherDateField);

        Log::info('Validating DateTimeGreaterThan', [
            'attribute' => $attribute,
            'value' => $value,
            'startDateField' => $this->otherDateField,
            'startDateValue' => $startDateValue,
        ]);

        // Проверяем, что оба значения существуют и что значение $value больше, чем $startDateValue
        if ($value && $startDateValue) {
            $valueTimestamp = strtotime($value);
            $startDateTimestamp = strtotime($startDateValue);

            if ($valueTimestamp === false || $startDateTimestamp === false) {
                $fail(__('The :attribute and :other must be valid dates.', [
                    'attribute' => $attribute,
                    'other' => $this->otherDateField,
                ]));
                return;
            }

            if ($valueTimestamp <= $startDateTimestamp) {
                $fail(__('The :attribute must be a date after :other.', [
                    'attribute' => $attribute,
                    'other' => $this->otherDateField,
                ]));
            }
        }
    }
}
