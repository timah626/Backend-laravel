<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    App\Modules\Users\UserServiceProvider::class,
    App\Modules\Auth\AuthServiceProvider::class,
    App\Modules\Organisation\OrganisationServiceProvider::class,
    App\Modules\Onboarding\OnboardingServiceProvider::class,

    
    App\Modules\EvidenceAndScoring\EvidenceAndScoringServiceProvider::class,

    App\Modules\OfficeNetworks\OfficeNetworksServiceProvider::class,

    App\Modules\Attendance\AttendanceServiceProvider::class,

    App\Modules\Settings\SettingsServiceProvider::class,













];
