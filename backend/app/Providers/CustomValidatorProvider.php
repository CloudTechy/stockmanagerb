<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\ServiceProvider;

/**
 * Provider for custom validators. Handles registration for custom validators.
 *
 * @author Aderemi Dayo<akinsnazri@gmail.com>
 */
class CustomValidatorProvider extends ServiceProvider
{

    /**
     * An array of fully qualified class names of the custom validators to be
     * registered.
     *
     * @var array
     */
    protected $validators = [
        \App\Validators\MultipleUniqueValidator::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     * @throws \Exception
     */
    public function boot()
    {
        //register custom validators
        foreach ($this->validators as $class) {
            $customValidator = new $class();
            ValidatorFacade::extend($customValidator->getName(), function () use ($customValidator) {
                //set custom error messages on the validator
                func_get_args()[3]->setCustomMessages($customValidator->getCustomErrorMessages());
                return call_user_func_array([$customValidator, "validate"], func_get_args());
            });
            ValidatorFacade::replacer($customValidator->getName(), function () use ($customValidator) {
                return call_user_func_array([$customValidator, "replacer"], func_get_args());
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
