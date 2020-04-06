<?php
namespace App\Validators;

use App\Validator;

abstract class AbstractValidator {

    protected array $data;
    protected Validator $validator;

    public function __construct(array $data)
    {
        $this->data = $data;
        Validator::lang('fr');
        $this->validator = new Validator($data);
    }

    public function validate(): bool
    {
        return $this->validator->validate();
    }

    public function getErrors(): array
    {
        return $this->validator->errors();
    }


}