<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ImportJob;

class ProductController extends Controller
{
    public function storeData(Request $request)
    {
        dump('productController:storeData');
        //VALIDASI
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        if ($request->hasFile('file')) {
            //UPLOAD FILE
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs(
                'public', $filename
            );

            //MEMBUAT JOB DENGAN MENGIRIMKAN PARAMETER FILENAME
            $job = ImportJob::dispatch($filename);
            dump('productController:afterDispatch : ');
            return redirect()->back()->with(['success' => 'Success dispatch import job into queue']);
        }
        return redirect()->back()->with(['error' => 'Please choose file before']);
    }
}
