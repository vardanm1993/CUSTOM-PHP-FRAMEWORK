<?php

namespace Core;

class Validator
{
    protected array $data = [];
    protected array $rules = [];
    protected array $errors = [];
    protected array $cleanData = [];

    public static function make(array $data, array $rules, bool $sessionFlash = true): Validator
    {
        $validator = new self();
        $validator->data = $data;
        $validator->rules = $rules;
        $validator->validate();

        if ($sessionFlash && $validator->fails()) {
            Session::flash('errors', $validator->errors());
            Session::flash('old', $data);
        }

        if (!$validator->fails()){
            Session::unflash();
        }


        return $validator;
    }

    private function validate(): void
    {
        foreach ($this->rules as $field => $rulesForField) {
            $value = $this->data[$field] ?? null;

            foreach ($this->parseRule($rulesForField) as $rule) {
                $this->apply($rule, $value, $field);
            }

            if (!isset($this->errors[$field])) {
                $this->cleanData[$field] = $value;
            }
        }
    }

    private function parseRule(array|string $rulesForField): array
    {
        return is_array($rulesForField) ? $rulesForField : explode('|', $rulesForField);
    }

    private function apply(mixed $rule, mixed $value, int|string $field): void
    {
        if (str_contains($rule, ':')) {
            [$ruleName, $ruleValue] = explode(':', $rule);
        } else {
            $ruleName = $rule;
            $ruleValue = null;
        }

        switch ($ruleName) {
            case 'required':
                $this->required($field, $value);
                break;
            case 'string':
                $this->string($field, $value);
                break;
            case 'email':
                $this->email($field, $value);
                break;
            case 'min':
                $this->min($field, $value, (int)$ruleValue);
                break;
            case 'max':
                $this->max($field, $value, (int)$ruleValue);
                break;
            case 'password':
                $this->password($field, $value);
                break;
            case 'confirmed':
                $this->confirmed($field, $value, $ruleValue);
        }
    }

    private function required(string $field, mixed $value): void
    {
        if (is_null($value) || $value === '') {
            $this->errors[$field] = "{$field} required";
        }
    }

    private function string(string $field, mixed $value): void
    {
        if (!is_string($value)) {
            $this->errors[$field][] = "$field must be a string.";
        }
    }

    private function min(string $field, mixed $value, int|string $min): void
    {
        if (is_string($value) && strlen($value) < $min) {
            $this->errors[$field][] = "$field must be at least $min characters.";
        }
    }

    private function max(string $field, mixed $value, int|string $max): void
    {
        if (is_string($value) && strlen($value) > $max) {
            $this->errors[$field][] = "$field may not be greater than $max characters.";
        }
    }

    private function email(string $field, mixed $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "$field must be a valid email address.";
        }
    }

    private function password(string $field, int|string $value): void
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';

        if (!preg_match($pattern, $value)) {
            $this->errors[$field][] = "$field must be at least 8 characters long,
                                        contain at least one uppercase letter,
                                         one lowercase letter, one number,
                                          and one special character.";
        }
    }

    private function confirmed(string $field, mixed $value, string $confirmFieldName): void
    {
        $confirmField = $confirmFieldName ?: "{$field}_confirmation";
        $confirmValue = $this->data[$confirmField] ?? null;

        if ($value !== $confirmValue) {
            $this->errors[$field][] = "$field must match $confirmField.";
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validated(): Redirect|array
    {
        return $this->cleanData;
    }

    public function first(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    public function getData(): array
    {
        return $this->data;
    }

}



