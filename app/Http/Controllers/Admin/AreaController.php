<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\AreaDto;
use App\DataTransferObjects\DataTableRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableAjaxRequest;
use App\Http\Requests\Web\Area\AreaRequest;
use App\Models\Area;
use App\Services\AreaService;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function __construct(protected AreaService $service)
    {
        //
    }

    public function index()
    {
        return view('admin.area.index');
    }

    public function indexAjax(DataTableAjaxRequest $request)
    {
        $dt = DataTableRequestDto::fromDataTableAjaxRequest($request);

        return response()->json($this->service->dataTableIndex($dt));
    }

    public function show(Area $area)
    {
        return response()->json([
            'area' => $area
        ]);
    }

    public function store(AreaRequest $request)
    {
        $this->service->store(AreaDto::fromAreaRequest($request));

        return response()->json([
            'type' => 'success',
            'content' => "New area created successfully!"
        ]);
    }

    public function update(AreaRequest $request, Area $area)
    {
        $this->service->update($area, AreaDto::fromAreaRequest($request));

        return response()->json([
            'type' => 'success',
            'content' => "Area updated successfully!"
        ]);
    }

    public function destroy(Area $area)
    {
        $this->service->delete($area);

        return response()->json([
            'type' => 'success',
            'content' => "Area deleted successfully!"
        ]);
    }
}
