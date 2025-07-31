<?php

namespace App\Validators;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

/**
 * Multiple field uniqueness in laravel
 *
 * @author Aderemi Dayo<akinsnazri@gmail.com>
 */
class MultipleUniqueValidator
{

    /**
     * Name of the validator.
     *
     * @var string
     */
    protected $name = "multiple_unique";

    /**
     * Return the name of the validator. This is the name that is used to specify
     * that this validator be used.
     *
     * @return string name of the validator
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replacer(string $message, string $attribute, string $rule, array $parameters): string
    {
        unset($parameters[0]);
        $replacement = implode(", ", $parameters);
        $replacement = str_replace("_", " ", $replacement);
        $replacement = Str::replaceLast(", ", " & ", $replacement);
        $replacement = Str::title($replacement);
        return str_replace(":fields", "$replacement", $message);
    }

    /**
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     * @throws \Exception
     */
    public function validate(string $attribute, $value, array $parameters, Validator $validator): bool
    {
        $model = new $parameters[0];
        if (!$model instanceof Model) {
            throw new \Exception($parameters[0] . " is not an Eloquent model");
        }
        unset($parameters[0]);
        $this->fields = $parameters;

        $query = $model->query();
        $request = app("request");
        foreach ($parameters as $parameter) {
            $query->where($parameter, $request->get($parameter));
        }

        return $query->count() == 0;
    }

    /**
     * Custom error messages
     *
     * @return array
     */
    public function getCustomErrorMessages(): array
    {
        return [
            $this->getName() => ":fields fields should be unique",
        ];
    }

}
