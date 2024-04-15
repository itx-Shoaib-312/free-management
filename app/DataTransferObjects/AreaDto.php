<?php

namespace App\DataTransferObjects;

use App\Http\Requests\Web\Area\AreaRequest;

class AreaDto
{
    public function __construct(
        public readonly string $ref,
        public readonly string $name,
    ) {
        //
    }

    public static function fromAreaRequest(AreaRequest $request){
        return new self(
            ref: $request->validated('ref'),
            name: $request->validated('name'),
        );
    }

    public function arrayForModel() : array {
        return [
            'ref' => str($this->ref)->slug(),
            'name' => $this->name,
        ];
    }
}
