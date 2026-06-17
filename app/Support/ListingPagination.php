<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListingPagination
{
    public const MIN_TOTAL_TO_SHOW = 10;

    /** @var list<int> */
    public const PER_PAGE_OPTIONS = [10, 25, 50];

    public const DEFAULT_PER_PAGE = 25;

    public static function perPage(Request $request): int
    {
        $perPage = (int) $request->input('per_page', self::DEFAULT_PER_PAGE);

        return in_array($perPage, self::PER_PAGE_OPTIONS, true)
            ? $perPage
            : self::DEFAULT_PER_PAGE;
    }

    public static function perPageChangeUrl(Request $request, int $perPage): string
    {
        return $request->fullUrlWithQuery([
            'per_page' => $perPage,
            'page' => null,
        ]);
    }

    /**
     * @param  Builder|\Illuminate\Database\Query\Builder  $query
     */
    public static function paginate($query, Request $request): LengthAwarePaginator
    {
        return $query->paginate(self::perPage($request))->withQueryString();
    }

    public static function shouldShow(LengthAwarePaginator $paginator): bool
    {
        return $paginator->total() > self::MIN_TOTAL_TO_SHOW;
    }
}
