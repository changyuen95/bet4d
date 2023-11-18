<?php

namespace App\Imports;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PermissionsImport implements ToModel, WithStartRow, WithUpserts
{
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return [
            "name",
            "guard_name"
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        [
            $name,
            $guard,
        ] = array_map(function($value) {
            $trimmed = trim($value);
            return (!isset($trimmed) && $trimmed === "") ? NULL : $trimmed;
        }, array_pad($row,2, null));

        return new Permission([
            "name" => $name,
            "guard_name" => $guard,
        ]);
    }
}
