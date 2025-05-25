<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Extension;
use App\Models\Func;

class CallParking extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'name', 'extension_no', 'no_of_slot', 'music_on_hold', 'timeout', 'function_id', 'destination_id', 'record'];

    public function addExtensions(){
        $func = Func::select('id')->where('func', 'call_parking')->first();
        for($i = $this->extension_no; $i <= $this->extension_no + $this->no_of_slot; $i++){

            Extension::create([
                'organization_id' => auth()->user()->organization->id,
                'name' => $this->name,
                'code' => $i,
                'extension_type' => '5',
                'function_id' => $func->id,
                'destination_id' => $this->id,
                'status' => '1',
            ]);
        }
    }

    public function removeExtensions(){
        Extension::where('organization_id', auth()->user()->organization->id)->where('code', '>=', $this->extension_no)->where('code', '<=', $this->extension_no + $this->no_of_slot)->delete();
    }

    public function musicOnHold()
    {
        return $this->belongsTo('App\Models\VoiceFile','music_on_hold');
    }

    /**
     * Get the function for this model.
     *
     * @return App\Models\Function
     */
    public function function()
    {
        return $this->belongsTo('App\Models\Func','function_id');
    }

}
