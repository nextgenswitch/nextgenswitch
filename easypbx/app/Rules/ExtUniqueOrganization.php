<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Extension;

class ExtUniqueOrganization implements ValidationRule
{

    private $id = null;

    function __construct($id = null){
        $this->id = $id;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->id !== null && Extension::where('extension', $value)->where('id', $this->id)->count());

        else if(Extension::where('extension', $value)->where('organization_id', auth()->user()->organization_id)->count() )
            $fail('The :attribute already exists.');
    }
}
