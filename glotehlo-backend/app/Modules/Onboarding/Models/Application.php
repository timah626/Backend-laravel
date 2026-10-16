<?php

namespace App\Modules\Onboarding\Models;




use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Modules\Organisation\Models\Department;


class Application extends Model
{
    use HasUlids;

    protected $table = 'applications';
    protected $guarded = [];
    public $timestamps = false;


    public function suggestedDepartment()

        {

            return $this->belongsTo(Department::class, 'suggested_department_id');
            
        }



}
