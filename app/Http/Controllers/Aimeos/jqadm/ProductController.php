<?php

namespace App\Http\Controllers\Aimeos\jqadm;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * @param Request $request
     */
    public function importCsv(Request $request)
    {

        //save file and get path for new csv file
        $site = $request['site'];
        $path = $request->file('csv_file')->store('public/csv_uploads');

        $files = Storage::allFiles('public/csv_uploads');

        try {
            Excel::import(new ProductImport, $path);

        } catch(\Throwable $e) {
            // if validation fails remove csv file
            Storage::delete($files);

            File::deleteDirectory(storage_path('framework/laravel-excel'));

            return response()->json(['success' => false, 'status' => 'validation_error', 'message' => $e->errors()]);
        }
        try {
            // run aimeos job
            Artisan::call('aimeos:jobs', ['jobs' => 'product/import/csv', 'site' => $site]);

        } catch (\Throwable $e) {
            // if artisan command fails remove csv file
            Storage::delete($files);


            return response()->json(['success' => false,'message' => $e->getMessage()]);
        }
        //after importing the csv file delete it
        Storage::delete($files);

        File::deleteDirectory(storage_path('framework/laravel-excel'));

        return response()->json(['success' => true]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateCsv(Request $request)
    {
        $validator = Validator::make(
            [
                'file' => $request->csv_file,
                'extension' => strtolower($request->csv_file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:csv',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }


    /**
     *
     * generate media files after importing csv
     * @param $site_name
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaScale($site_name){

        try {
            // run aimeos job
            Artisan::call('aimeos:jobs', ['jobs' => 'media/scale','site' => $site_name]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }

    public function getAllProductsOnline(Request $request)
    {
        return Excel::download(new \App\Exports\ExportProductsOnline, 'products.csv');
    }

    public function getAllProductsOnlineFbLanguages(Request $request)
    {
        return Excel::download(new \App\Exports\ExportProductsOnlineFbLanguages(), 'fblanguages.csv');
    }

    public function getAllProductsOnlineGoogle(Request $request)
    {
        return Excel::download(new \App\Exports\ExportProductsOnlineGoogle(), 'google.csv');
    }

}
