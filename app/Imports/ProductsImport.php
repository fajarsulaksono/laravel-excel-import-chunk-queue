<?php

namespace App\Imports;

use App\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; //TAMBAHKAN CODE INI//IMPORT SHOUDLQUEUE
use Maatwebsite\Excel\Concerns\WithChunkReading; //IMPORT CHUNK READING
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Barryvdh\Debugbar\Facade as Debugbar;
use Imtigger\LaravelJobStatus\Trackable;

class ProductsImport implements WithEvents, ToModel, WithHeadingRow, ShouldQueue, WithChunkReading, WithBatchInserts
{
    // use Importable, RegistersEventListeners;
    private $rows = 0;
    // get last row : https://docs.laravel-excel.com/3.1/architecture/objects.html#getters

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => [self::class, 'beforeSheet']
        ];
    }

    public static function beforeSheet(BeforeSheet $event)
    {
        //$highestRow = $this->spreadsheet->getActiveSheet()->getHighestRow();
        $worksheet = $event->sheet;
        $highestRow = $worksheet->getHighestRow();
        Debugbar::addMessage('Highest row', $highestRow);
        session([
            'import_excel_creator' => $highestRow
        ]);
    }
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

    public function getRowCount(): array
    {
        return [
            session('import_excel_creator')
        ];
    }
}
