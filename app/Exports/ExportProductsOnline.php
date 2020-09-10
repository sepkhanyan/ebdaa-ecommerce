<?php


namespace App\Exports;


use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProductsOnline implements FromCollection, WithHeadings, WithMapping
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
            'title',
            'description',
            'availability',
            'inventory',
            'condition',
            'price',
            'link',
            'image_link',
            'brand',
            'color'
        ];
    }

    /**
     * @param $item
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function map($item): array
    {
        return $this->getProductDetails($item);

    }

    /**
     *
     * return array of order details
     * @param $model
     * @param int $index index for order products
     * @return array
     * @throws \Aimeos\MW\Translation\Exception
     */
    public function getProductDetails($product)
    {
        $sku = $product->code;
        $link = 'https://shops.syaanh.com/?productId=' . $product->id;
        $removeEntities = html_entity_decode($product->description());
        $description = strip_tags($removeEntities);
        $name = $product->label;
        $price = $product->price()->value . ' QAR';
        $brand = $product->brand ? $product->brand->value : '-';
        $color = $product->color() ? $product->color() : '-';
        $inventory = $product->stock->stocklevel;

        $fileName = $sku . '.jpg';
        $path = public_path('fbimages');
        $files = scandir($path);
        $fbmediaLink = 'https://shops.syaanh.com/fbimages/' . $fileName;
        $mediaLink = 'https://shops.syaanh.com/csv_media_files/' . $fileName;

        $data = [
            'id' => $sku,
            'title' => $name,
            'description' => $description,
            'availability' => 'in stock',
            'inventory' => $inventory,
            'condition' => 'new',
            'price' => $price,
            'link' => $link,
            'image_link' => in_array($fileName, $files) ? $fbmediaLink : $mediaLink,
            'brand' => $brand,
            'color' => $color
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
