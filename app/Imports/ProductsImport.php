<?php

namespace App\Imports;

use App\Events\excelInsertedEvent;
use App\Events\excelImportFinishedEvent;
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
use Maatwebsite\Excel\Events\AfterImport;

class ProductsImport implements WithEvents, ToModel, WithHeadingRow, ShouldQueue, WithChunkReading, WithBatchInserts
{
    use Importable, RegistersEventListeners;

    private static $current_progress = 0;
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
            BeforeImport::class => [self::class, 'beforeImport'],
            AfterImport::class => [self::class, 'afterImport'],
        ];
    }

    public static function beforeImport(BeforeImport $event)
    {
        dump('beforeImport');
        $total_rows = $event->reader->getTotalRows();

        // getTotalRows for 1st sheet only
        $key = $value = NULL;
        foreach ($total_rows as $key => $value) {
            break;
        }
        static::$total_row = ($value - 1); // -1 karena withHeadingRow

    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++static::$current_progress;
        //dump(static::$current_progress);
        //event(new excelInsertedEvent($this->job_id, static::$current_progress));
        if (((static::$current_progress % $this->batchSize()) === 0) ||
               (static::$current_progress == static::$total_row)
        ) {
            $percentage = round((static::$current_progress / static::$total_row) * 100);
            $percentage = $percentage.'%';
            //dump($percentage);
            event(new excelInsertedEvent($this->job_id, $percentage));
        }

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
        return 5000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function getRowCount()
    {
        return static::$total_row;
    }

    public static function afterImport(AfterImport $event)
    {
        dump('afterImport');
        dump(static::$current_progress);
        event(new excelImportFinishedEvent($this->job_id, static::$total_row));
    }

    public function getCurrentProgress()
    {
        dump(static::$current_progress);
        return static::$current_progress;
    }

}
