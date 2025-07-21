<?php

namespace Framework;

class Validator
{
    protected $errors = [];

    public function __construct(
        protected array $data,
        protected array $rules = []
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        foreach ($this->rules as $field => $rules) {
            $rules = explode('|', $rules);
            $value = trim($this->data[$field]);

            foreach ($rules as $rule) {
                [$name, $param] = array_pad(explode(':', $rule), 2, null);

              
                if ($error = $this->hasError($name,$param,$field,$value)) {
                    $this->errors[] = $error;

                    break;                    
                }
            }            
        }
    }

    protected function hasError(string $name, ?string $param, string $field, mixed $value) : ?string
    {
          return match ($name) {
                    'required'  => $this->validateRequired($field,$value),
                    'min'       => strlen($value) < $param  ? "$field must be at least $param characters."           : null,
                    'max'       => strlen($value) > $param  ? "$field must not exceed $param characters."            : null,
                    'url'       => filter_var($value, FILTER_VALIDATE_URL) === false ? "$field must be a valid URL." : null,
                    default => null,
                };

    }

    public function validateRequired (string $field, mixed $value) : ?string{
        return  ($value === null || $value === '') ? "$field is required." : null;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}