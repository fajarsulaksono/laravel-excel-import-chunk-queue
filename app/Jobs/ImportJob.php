<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Excel;
use App\Imports\ProductsImport;
use Imtigger\LaravelJobStatus\Trackable;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;
    protected $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $import = new ProductsImport;
        try {
           Excel::import($import, 'public/' . $this->file);
            //dd('Row count: ' . $import->getRowCount());
            dd('Row count: ' . $import->getRowCount());
        }
        catch (\Exception $e){
            echo $e;
        }//end catch
        unlink(storage_path('app/public/' . $this->file));
    }
}
