<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Excel;
use App\Imports\ProductsImport;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
        $import = new ProductsImport($this->job->getJobId());
        try {
            dump('ImportJob:handle():try');
            Excel::import($import, 'public/' . $this->file);
        }
        catch (\Exception $e){
            dump('ImportJob:handle():exception');
            echo $e;
        }//end catch


        $this->setProgressMax($import->getRowCount()); // -1 karena withHeadingRow
        dump($this->job->getJobId());
        dump($import->getRowCount());
        dump($import->getCurrentProgress());
        unlink(storage_path('app/public/' . $this->file));
        //dd($import->getRowCount());
    }
}
