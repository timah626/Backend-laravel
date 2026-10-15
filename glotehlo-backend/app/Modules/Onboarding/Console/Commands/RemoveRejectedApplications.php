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
     public function __construct (
        private ApplicationRepository $applicationRepository
     ){}

    public function handle()
    {
        $cutoff  = now()->subDays(10);
        $expiredApplications = $this ->applicationRepository->findRejectedBeforeTenDays($cutoff);
    }



}
