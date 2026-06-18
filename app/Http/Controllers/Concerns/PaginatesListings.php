<?php

namespace App\Http\Controllers\Concerns;

use App\Support\ListingPagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

trait PaginatesListings
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     */
    protected function paginateListing($query, Request $request): LengthAwarePaginator
    {
        return ListingPagination::paginate($query, $request);
    }
}
