<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; //TAMBAHKAN CODE INI
use Illuminate\Contracts\Queue\ShouldQueue; //IMPORT SHOUDLQUEUE
use Maatwebsite\Excel\Concerns\WithChunkReading; //IMPORT CHUNK READING
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ProductsImport implements ToModel, WithHeadingRow, ShouldQueue, WithChunkReading, WithBatchInserts
{
    private $rows = 0;
    // get last row : https://docs.laravel-excel.com/3.1/architecture/objects.html#getters
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->rows;

        //dd($row);
        return new Product([
            'title' => $row['title'],
            'slug' => $row['title'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock' => $row['stock']
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
