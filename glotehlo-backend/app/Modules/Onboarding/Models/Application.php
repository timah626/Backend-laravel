<?php

namespace App\Modules\Onboarding\Models;




use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;



class Application extends Model
{
    use HasUlids;

    protected $table = 'applications';
    protected $guarded = [];
    public $timestamps = false;
   

   

}
