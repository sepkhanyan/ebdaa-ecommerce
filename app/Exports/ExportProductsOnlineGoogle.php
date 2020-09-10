<?php


namespace App\Exports;


use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProductsOnlineGoogle implements FromCollection, WithHeadings, WithMapping
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
            'ID',
            'Item title',
            'Final URL',
            'Image URL',
            'Item description',
            'Price',
            'Final mobile URL',
            'Android app link',
            'iOS app link',
            'iOS app store ID'
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
        $price = $product->price()->value . ' QAR';

        $fileName = $sku . '.jpg';
        $path = public_path('fbimages');
        $files = scandir($path);
        $fbmediaLink = 'https://shops.syaanh.com/fbimages/' . $fileName;
        $mediaLink = 'https://shops.syaanh.com/csv_media_files/' . $fileName;

        $data = [
            'id' => $sku,
            'title' => $product->label,
            'final_url' => $link,
            'image_url' => in_array($fileName, $files) ? $fbmediaLink : $mediaLink,
            'description' => $description,
            'price' => $price,
            'final_mobile_url' => $link,
            'android_app_link' => 'android-app://com.ebdaadt.syaanhclient/syaanh/product?productId=' . $product->id,
            'ios_app_link' => 'syaanh://product?productId=' . $product->id,
            'ios_app_store_id' => 1105102444
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
