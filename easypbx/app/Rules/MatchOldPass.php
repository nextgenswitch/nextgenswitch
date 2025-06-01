<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class MatchOldPass implements ValidationRule
{
    private $user;


    function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(! Hash::check($value, $this->user->password))
            $fail('The :attribute must match the old password');
    }
}
