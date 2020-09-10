<?php


namespace App\Exports;


use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProductsOnlineFbLanguages implements FromCollection, WithHeadings, WithMapping
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $collection = $this->getProducts();

        return $collection;
    }

    /**
     * @return array|string[]
     */
    public function headings(): array
    {
        return [
            'id',
            'override',
            'title',
            'description',
            'link',
            'brand',
        ];
    }

    /**
     * @param $item
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function map($item): array
    {

        $langs = ['en', 'ar'];
        $data = [];
        foreach ($langs as $lang){
            array_push($data, $this->getProductDetails($item, $lang));
        }
        return $data;

    }

    /**
     *
     * return array of order details
     * @param $model
     * @param int $index index for order products
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function getProductDetails($product, $lang)
    {
        $sku = $product->code;
        $link = 'https://shops.syaanh.com/?productId=' . $product->id;
        $removeEntities = html_entity_decode($product->description($lang));
        $description = strip_tags($removeEntities);
        $name = $lang == 'ar' ? $product->name($lang) : $product->label;
        $override = $lang == 'en' ? 'en_XX' : 'ar_AR';
        if($lang && $lang == 'ar'){
            $brand = $product->brandAr ? $product->brandAr->value : '-';
        }else {
            $brand = $product->brand ? $product->brand->value : '-';
        }

        $data = [
            'id' => $sku,
            'override' => $override,
            'title' => $name,
            'description' => $description,
            'link' => $link,
            'brand' => $brand,
        ];

        return $data;
    }

    public function getProducts()
    {

        $products = Product::where([['type', 'default'], ['status', 1]])
            ->whereHas('enableCatalogs')
            ->with(['stock', 'properties'])
            ->orderBy('id', 'ASC')->get();

        return $products;
    }

}
