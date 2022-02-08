<?php

use Illuminate\Support\Str;

if (!function_exists('applyDefaultFindById')) {
    function applyDefaultFindById($request, $query)
    {
        $query->when(request()->filled('with'), function ($query) {
            $withs = explode(',', request('with'));
            if ($withs) {
                $query->with($withs);
            }
            return $query;
        });

        return $query;
    }
}


if (!function_exists('applyDefaultFSW')) {
    function applyDefaultFSW($request, $query)
    {
        ////        ?filter=category:food,id:2
        $query->when(request()->filled('filter'), function ($query) {
            $filters = explode(',', request('filter'));
            foreach ($filters as $filter) {
                [$criteria, $value] = explode(':', $filter);
                $query->where($criteria, $value);
            }
            return $query;
        });


        //        ?sort=-id,price
        if ($request->sort) {
            $sorts =  explode(',', $request->input('sort', ''));
            foreach ($sorts as $sortColumn) {
                $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc' : 'asc';
                $sortColumn = ltrim($sortColumn, '-');
                $query->orderBy($sortColumn, $sortDirection);
            }
        }


        // ?with=shop,menu
        $query->when(request()->filled('with'), function ($query) {
            $withs = explode(',', request('with'));
            if ($withs) {
                $query->with($withs);
            }
            return $query;
        });

        $query->when(request()->filled('start') && request()->filled('end'), function ($query) {
            $query->whereBetween('created_at', [request('start'), request('end')]);
            return $query;
        });

        $query->when(request()->filled('up-start') && request()->filled('up-end'), function ($query) {
            $query->whereBetween('created_at', [request('up-start'), request('up-end')]);
            return $query;
        });

        return $query;
    }
}
