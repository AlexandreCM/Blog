<?php
namespace App\Validators;

use App\Table\CategoryTable;

class CategoryValidator extends AbstractValidator {

    public function __construct(array $data, CategoryTable $table, ?int $postId = null)
    {
        parent::__construct($data);
        $this->validator->rule('required', ['name', 'slug']);
        $this->validator->rule('lengthBetween', ['name', 'slug'], 3, 50);
        $this->validator->rule('slug', 'slug');
        $this->validator->rule(function ($field, $value) use ($table, $postId) {
            return !$table->hasThisDataInTable($field, $value, $postId);
        }, ['slug', 'name'])->message('Cette valeur est deja utilisee');
    }
}