<?php

namespace App\Modules\Onboarding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class InternDocuments extends Model
{
    use HasUlids;

    protected $table = 'intern_documents';
    protected $guarded = [];
}
