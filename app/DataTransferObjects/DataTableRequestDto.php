<?php

namespace App\DataTransferObjects;

use App\Http\Requests\DataTableAjaxRequest;

class DataTableRequestDto
{
    public function __construct(
        public readonly int $draw = 0,
        public readonly array $columns = [],
        public readonly array $order = [],
        public readonly int $start = 0,
        public readonly int $length = 10,
        public readonly array $search = ['value' => null, 'regex' => false],
    ) {

    }

    public static function fromDataTableAjaxRequest(DataTableAjaxRequest $request){
        return new self(
            draw: $request->validated('draw'),
            columns: $request->validated('columns'),
            order: $request->validated('order'),
            start: $request->validated('start'),
            length: $request->validated('length'),
            search: $request->validated('search'),
        );
    }
}
