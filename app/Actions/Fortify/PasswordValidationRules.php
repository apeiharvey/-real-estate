<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        $password = new Password;
        $password->length(8);
        $password->requireUppercase();
        $password->requireNumeric();
        $password->requireSpecialCharacter();
        return ['required', 'string', $password, 'confirmed'];
    }
}
