<?php

namespace App\Modules\Onboarding\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Modules\Onboarding\Repository\ApplicationRepository;

#[Signature('app:remove-rejected-applications')]
#[Description('Command description')]


class RemoveRejectedApplications extends Command
{
     public function handle(ApplicationRepository $applicationRepository)
     {
         $cutoff = now()->subDays(10);
         $expired = $applicationRepository->findRejectedBeforeTenDays($cutoff);
     }


}
