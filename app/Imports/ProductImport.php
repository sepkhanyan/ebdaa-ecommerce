<?php

namespace App\Imports;

use App\Catalog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToCollection, WithValidation, WithStartRow
{
    use Importable;

    public function collection(Collection $rows)
    {
       //
    }

    public function startRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        $files = [];
        $path = public_path('csv_media_files');
        $dataFiles = scandir($path);
        foreach ($dataFiles as $file){
            $csvMediaFile = 'csv_media_files/' . $file;
            array_push($files, $csvMediaFile);
        }
        $mediaArray = range(8, 22);

        $rules = [
            '*.0' => 'required',
            '*.1' => 'required',
            '*.2' => 'required',
            '*.3' => 'required',
            '*.58' => 'required',
            '*.59' => 'required',
            '*.61' => 'required',
            '*.62' => 'required',
            '*.63' => 'required',
            '*.64' => 'required',
            '*.65' => 'required',
            '*.66' => 'required',
            '*.67' => 'required',
            '*.71' => 'required',
        ];
        foreach ($mediaArray as $mediaKey => $mediaValue) {
            $rules['*.' . $mediaValue] = [Rule::requiredIf($mediaValue == 8), function ($attribute, $value, $onFailure) use ($files, $mediaKey, $mediaValue) {
                if (($value != null || $value != '') && !in_array($value, $files)) {
                    $number = $mediaKey + 1;
                    $message = $mediaValue != 8 ? 'The media.url ' . $number . ' does not exist in csv_media_files.' : 'The media.url does not exist in csv_media_files.';
                    $onFailure($message);
                }
            }];
        }

        $catalogsArray = range(71, 80);
        $catalogs = Catalog::query()->pluck('code')->toArray();

        foreach ($catalogsArray as $catalogKey => $catalogValue) {
            $rules['*.' . $catalogValue] = [Rule::requiredIf($catalogValue == 71), function ($attribute, $value, $onFailure) use ($catalogs, $catalogKey, $catalogValue) {
                if (($value != null || $value != '') && !in_array($value, $catalogs)) {
                    $number = $catalogKey + 1;
                    $message = $catalogValue != 71 ? 'The category code ' . $number . ' does not exist in categories list.' : 'The category code does not exist in categories list.';
                    $onFailure($message);
                }
            }];
        }

        $attributes = DB::table('mshop_attribute')->where('status',1)->pluck('code')->toArray();
        $attrArray = range(232,268, 4);
        foreach ($attrArray as $attrKey => $attrValue){
            $rules['*.' . $attrValue] = [function ($attribute, $value, $onFailure) use ($attributes, $attrKey, $attrValue) {
                if (($value != null || $value != '') && !in_array($value, $attributes)) {
                    $number = $attrKey + 1;
                    $message = $attrValue != 232 ? 'The attribute code ' . $number . ' does not exist in attribute list.' : 'The attribute code does not exist in attribute list.';
                    $onFailure($message);
                }
            }];
        }

        return $rules;
    }

    public function customValidationMessages()
    {
        return [
            '*.0.required' => 'The product_code() is required.',
            '*.0.unique' => 'The product_code() has already been taken.',
            '*.1.required' => 'The product.label is required.',
            '*.2.required' => 'The Product type is required.',
            '*.3.required' => 'The product.status is required.',
            '*.8.required' => 'The media.url is required.',
            '*.58.required' => 'The price.currencyid is required.',
            '*.59.required' => 'The price.quantity is required.',
            '*.60.required' => 'The price.value is required.',
            '*.61.required' => 'The price.taxrate is required.',
            '*.62.required' => 'The price rebate is required.',
            '*.63.required' => 'The price costs is required.',
            '*.64.required' => 'The price status is required.',
            '*.65.required' => 'The price cost-price is required.',
            '*.66.required' => 'The price commission is required.',
            '*.67.required' => 'The stock lavel is required.',
            '*.71.required' => 'The category code is required.',
            '*.232.required' => 'The attribute code is required.',
        ];
    }
}
