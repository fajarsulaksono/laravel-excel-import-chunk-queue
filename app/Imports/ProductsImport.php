<?php

namespace App\Imports;

use App\Product;
use \Maatwebsite\Excel\Reader;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; //TAMBAHKAN CODE INI//IMPORT SHOUDLQUEUE
use Maatwebsite\Excel\Concerns\WithChunkReading; //IMPORT CHUNK READING
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Events\BeforeImport;
use Imtigger\LaravelJobStatus\Trackable;

class ProductsImport implements WithEvents, ToModel, WithHeadingRow, ShouldQueue, WithChunkReading, WithBatchInserts
{
    use Importable, RegistersEventListeners, Trackable;

    private $current_progress = 0;
    private static $total_row;
    // get last row : https://docs.laravel-excel.com/3.1/architecture/objects.html#getters
    public function  __construct($job_id)
    {
        $this->job_id = $job_id;
    }
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => [self::class, 'beforeImport']
        ];
    }

    public static function beforeImport(BeforeImport $event)
    {
        static::$total_row = $event->reader->getTotalRows();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->current_progress;
        $this->setProgressNow($this->current_progress);
        //dd($row);
        return new Product([
            'job_id' => $this->job_id,
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

    public function getRowCount()
    {
        return static::$total_row;
    }

    public function getCurrentProgress(): int
    {
        return $this->current_progress;
    }
}
