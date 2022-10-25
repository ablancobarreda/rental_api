<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\ShopProductPhoto;
use App\Models\ShopProductsHasCategoriesProduct;
use App\Models\ShopProductsPricesrate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;


class ProductController extends Controller
{

    //section Get_Products
    public function getProducts(){

        $products = ShopProduct::with('shopProductPhotos', 'shop', 'shopProductsHasCategoriesProducts.categoriesProduct', 'shopProductsPricesrates' )->get();

        foreach ($products as $product){
            if($product->shopProductsHasCategoriesProducts->count()>0){
                $product->category_name = $product->shopProductsHasCategoriesProducts->first()->categoriesProduct->name;
            }

            $product->photos = $product->shopProductPhotos;

            foreach ($product->photos as $prod_photo){
                unset($prod_photo->created_at);
                unset($prod_photo->updated_at);
            }

            $product->prices = $product->shopProductsPricesrates;

            foreach ($product->prices as $prod_prices){
                unset($prod_prices->created_at);
                unset($prod_prices->updated_at);
            }

            unset($product->shopProductPhotos);
            unset($product->shopProductsHasCategoriesProducts);
            unset($product->shopProductsPricesrates);
            unset($product->created_at);
            unset($product->updated_at);
            unset($product->shop_id);

        }

        return response()->json(
            [
                'code' => 'ok',
                'message' => 'Products',
                'products' => $products
            ]
        );
    }

    //section Get_Product_By_Slug
    public function getProductBySlug(Request $request){

        $product = ShopProduct::with('shopProductPhotos', 'shop', 'shopProductsHasCategoriesProducts.categoriesProduct', 'shopProductsPricesrates' )->whereSlug($request->productSlug)->first();

        if($product->shopProductsHasCategoriesProducts->count()>0){
            $product->category_name = $product->shopProductsHasCategoriesProducts->first()->categoriesProduct->name;
        }

        $product->photos = $product->shopProductPhotos;

        foreach ($product->photos as $prod_photo){
            unset($prod_photo->created_at);
            unset($prod_photo->updated_at);
        }

        $product->shop_name = $product->shop->name;

        $product->prices = $product->shopProductsPricesrates;

        foreach ($product->prices as $prod_prices){
            unset($prod_prices->created_at);
            unset($prod_prices->updated_at);
        }

        unset($product->shopProductPhotos);
        unset($product->shopProductsHasCategoriesProducts);
        unset($product->shopProductsPricesrates);
        unset($product->created_at);
        unset($product->updated_at);
        unset($product->shop);

        return response()->json(
            [
                'code' => 'ok',
                'message' => 'Product',
                'product' => $product
            ]
        );
    }

    //section Get_Product_By_Shop_Slug
    public function getProductByBusinessSlug(Request $request){

        $shop = Shop::with('shopProducts.shopProductPhotos','shopProducts','shopProducts.shopProductsHasCategoriesProducts.categoriesProduct', 'shopProducts.shopProductsPricesrates')->whereSlug($request->businessUrl)->first();

        $products =$shop->shopProducts;

        foreach ($products as $product){
            if($product->shopProductsHasCategoriesProducts->count()>0){
                $product->category_name = $product->shopProductsHasCategoriesProducts->first()->categoriesProduct->name;
            }

           $product->photos = $product->shopProductPhotos;

            foreach ($product->photos as $prod_photo){
                unset($prod_photo->created_at);
                unset($prod_photo->updated_at);
            }

           $product->prices = $product->shopProductsPricesrates;

            foreach ($product->prices as $prod_prices){
                unset($prod_prices->created_at);
                unset($prod_prices->updated_at);
            }

            unset($product->shopProductPhotos);
            unset($product->shopProductsHasCategoriesProducts);
            unset($product->shopProductsPricesrates);
            unset($product->created_at);
            unset($product->updated_at);

        }

        return response()->json(
            [
                'code' => 'ok',
                'message' => 'Products',
                'products' => $products
            ]
        );
    }

    //section New_Product
    public function newProduct(Request $request){

        try{
            DB::beginTransaction();

            $product = new ShopProduct();

            $product->name = $request->productName;
            $product->stock = $request->productStock;
            $product->quantity_min = $request->productQuantityMin;
            $product->slug = Str::slug($request->productSlug);
            $product->shop_id = $request->productShopId;

            $product->save();

            $lengthArrayProductImage = count($request->productImage);

            if($lengthArrayProductImage != 0){
                for($i=0; $i<$lengthArrayProductImage; $i++){
                    $productPhoto = new ShopProductPhoto();
                    $productPhoto->shop_product_id = $product->id;
                    $productPhoto->main = $request->productImage[$i]['main'];
                    $productPhoto->path_photo = self::uploadImage($request->productImage[$i]['image'], $request->productName);

                    $productPhoto->save();
                }
            }

            $productCategory = new ShopProductsHasCategoriesProduct();
            $productCategory->shop_product_id = $product->id;
            $productCategory->category_product_id = $request->productCategory;
            $productCategory->save();

            $lengthArrayProductPrice = count($request->productPrice);

            for($i=0; $i<$lengthArrayProductPrice; $i++){
                $productPrice = new ShopProductsPricesrate();
                $productPrice->shop_product_id = $product->id;
                $productPrice->currency_code = $request->productPrice[$i]['currencyCode'];
                $productPrice->price = $request->productPrice[$i]['value'];
                $productPrice->save();
            }

            DB::commit();

            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Product created successfully'
                ]
            );
        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    //section Update_Product
    public function updateProduct(Request $request){

        try{
            DB::beginTransaction();

            $product = ShopProduct::whereId($request->productId)->first();

            $product->name = $request->productName;
            $product->stock = $request->productStock;
            $product->quantity_min = $request->productQuantityMin;
            $product->slug = Str::slug($request->productSlug);

            $product->update();

            $lengthArrayProductImageDeleted = count($request->productImageDeleted);

            for($i=0; $i<$lengthArrayProductImageDeleted; $i++){
                ShopProductPhoto::whereId($request->productImageDeleted[$i])->delete();
            }

            $lengthArrayProductImage = count($request->productImage);

            if($lengthArrayProductImage != 0){
                for($i=0; $i<$lengthArrayProductImage; $i++){
                    $productPhoto = new ShopProductPhoto();
                    $productPhoto->shop_product_id = $product->id;
                    $productPhoto->main = $request->productImage[$i]['main'];
                    $productPhoto->path_photo = self::uploadImage($request->productImage[$i]['image'], $request->productName);
                    $productPhoto->save();
                }
            }

            $productMain = ShopProductPhoto::where('shop_product_id',$request->productId)->whereMain(true)->count();

            if($productMain == 0){
                $productPhotoMain = ShopProductPhoto::where('shop_product_id',$request->productId)->first();

                $productPhotoMain->main = true;
                $productPhotoMain->update();
            }

            $lengthArrayProductCategory = count($request->productCategory);

            ShopProductsHasCategoriesProduct::where('shop_product_id',$request->productId)->delete();

            if($lengthArrayProductCategory != 0){
                for($i=0; $i<$lengthArrayProductCategory; $i++){
                    $productCategory = new ShopProductsHasCategoriesProduct();
                    $productCategory->shop_product_id = $request->productId;
                    $productCategory->category_product_id = $request->productCategory[$i];
                    $productCategory->save();
                }
            }

            $lengthArrayProductPrice= count($request->productPrice);

            ShopProductsPricesrate::where('shop_product_id',$request->productId)->delete();

            for($i=0; $i<$lengthArrayProductPrice; $i++){
                $productPrice = new ShopProductsPricesrate();
                $productPrice->shop_product_id = $request->productId;
                $productPrice->currency_code = $request->productPrice[$i]['currencyCode'];
                $productPrice->rate = $request->productPrice[$i]['value'];
                $productPrice->main = $request->productPrice[$i]['main'];
                $productPrice->save();
            }

            DB::commit();

            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Product updated successfully'
                ]
            );
        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    // section Delete_Product
    public function deleteProduct(Request $request){
        try {
            DB::beginTransaction();

            $result = ShopProduct::whereId($request->productId)->delete();

            DB::commit();

            if($result){
                return response()->json(
                    [
                        'code' => 'ok',
                        'message' => 'Product deleted successfully'
                    ]
                );
            }

            return response()->json(
                [
                    'code' => 'error',
                    'message' => 'Product not found'
                ]
            );

        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    //section Upload_image
    public static function uploadImage($path, $name){
        $image = $path;

        $avatarName =  $name . substr(uniqid(rand(), true), 7, 7) . '.png';

        $img = Image::make($image->getRealPath())->encode('png', 50)->orientate();

        $img->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream(); // <-- Key point

        Storage::disk('public')->put('/productsImages' . '/' . $avatarName, $img, 'public');
        $path = '/productsImages/' . $avatarName;

        return $path;
    }

}
