<?php

namespace App\Modules\Onboarding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class OnboardingDraft extends Model
{
    use HasUlids;

    protected $table = 'onboarding-drafts';
    protected $guarded = [];
}
