<?php

namespace App\Services;

use App\DataTransferObjects\AreaDto;
use App\DataTransferObjects\DataTableRequestDto;
use App\Models\Area;

class AreaService
{
    private static $search_cols = [
        'ref',
        'name',
    ];

    private static $order_cols = [
        'ref',
        'name',
    ];

    public function dataTableIndex(DataTableRequestDto $dto): array
    {

        $area_count = Area::count();
        $areas = Area::query();

        // Filter the columns based on search query
        if ($dto->search['value'] != '') {
            foreach ($dto->columns as $col) {
                if (in_array($col['data'], self::$search_cols)) {
                    $areas = $areas->orWhere($col['data'], 'like', "%" . $dto->search['value'] . "%");
                }
            }
        }

        // Sort based on order column
        foreach ($dto->order as $order_type) {
            $col = $dto->columns[$order_type['column']]['data'];
            if (in_array($col, self::$order_cols)) {
                $areas = $areas->orderBy($col, $order_type['dir']);
            }
        }

        // Access Paginated Database row
        $filtered_count = $areas->count();
        $areas = $areas->skip($dto->start)->take($dto->length)->get();

        $areas->map(function ($area) {
            $area->action = view('admin.area.partials.index_actions', ['area_id' => $area->id])->render();
        });

        return [
            'areas' => $areas,
            'draw' => $dto->draw,
            'recordsTotal' => $area_count,
            'recordsFiltered' => $filtered_count,
        ];
    }

    public function store(AreaDto $dto): Area
    {
        return Area::create($dto->arrayForModel());
    }

    public function update(Area $area, AreaDto $dto): Area
    {
        return tap($area)->update($dto->arrayForModel());
    }

    public function delete(Area $area): bool
    {
        return $area->delete();
    }
}
