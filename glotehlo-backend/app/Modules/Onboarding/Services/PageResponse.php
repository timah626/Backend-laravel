<?php

namespace App\Modules\Onboarding\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PageResponse
{
    public static function from(LengthAwarePaginator $page): array
    {
        return [
            'data' => $page->items(),
            'meta' => [
                'page'      => $page->currentPage(),
                'per_page'  => $page->perPage(),
                'total'     => $page->total(),
                'last_page' => $page->lastPage(),
            ],
        ];
    }
}