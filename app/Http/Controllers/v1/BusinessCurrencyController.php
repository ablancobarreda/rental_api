<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Shop;
use App\Models\ShopCurrency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class BusinessCurrencyController extends Controller
{

    //section Get_Business_Currency
    public function getBusinessCurrencies(){

        $shopCurrencies = ShopCurrency::all();

        return response()->json(
            [
                'code' => 'ok',
                'message' => 'Shop currencies',
                'shopCurrencies' => $shopCurrencies
            ]
        );
    }

    //section Get_Business_Currency
    public function getBusinessCurrencyById(Request $request){

        $shopCurrency = ShopCurrency::find($request->shopCurrencyId);

        if($shopCurrency){
            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Shop Curency',
                    'shopCurrency' => $shopCurrency
                ]
            );
        }
        else{
            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Shop Curency not found'
                ]
            );
        }


    }

    //section Get_Business_Currency_By_Business
    public function getBusinessCurrencyBySlug(Request $request){

        $shopCurrency =  DB::table('view_shopcurrencies_shopslug')->whereSlug($request->businessUrl)->get();

        if($shopCurrency){
            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Shop currencies',
                    'currencies' => $shopCurrency
                ]
            );
        }

        return response()->json(
            [
                'code' => 'error',
                'message' => 'Business not found'
            ]
        );
    }

    //section New_Business_Currency
    public function newBusinessCurrency(Request $request){

        try{
            DB::beginTransaction();

            $result = ShopCurrency::where('shop_id', $request->shopCurrencyShopId)->where('currency_id', $request->shopCurrencyId)->first();

            if($result){
                return response()->json(
                    [
                        'code' => 'error',
                        'message' => 'The store already has that currency'
                    ]
                );
            }

            $shopCurrency = new ShopCurrency();

            $shopCurrency->shop_id = $request->shopCurrencyShopId;
            $shopCurrency->currency_id = $request->shopCurrencyId;
            $shopCurrency->rate = $request->shopCurrencyRate;
            $shopCurrency->main = $request->shopCurrencyMain;

            $shopCurrency->save();

            DB::commit();

            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Shop currency created successfully'
                ]
            );
        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    //section Update_Business_Currency
    public function updateBusinessCurrency(Request $request){

        try{
            DB::beginTransaction();

            $shopCurrency = ShopCurrency::whereId($request->shopCurrencyId)->first();

            $shopCurrency->shop_id = $request->shopCurrencyShopId;
            $shopCurrency->currency_id = $request->shopCurrencyId;
            $shopCurrency->rate = $request->shopCurrencyRate;
            $shopCurrency->main = $request->shopCurrencyMain;

            $shopCurrency->update();

            DB::commit();

            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Shop currency updated successfully'
                ]
            );
        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    // section Delete_Business_Currency
    public function deleteBusinessCurrency(Request $request){
        try {
            DB::beginTransaction();

            $result = ShopCurrency::whereId($request->shopCurrencyId)->delete();

            DB::commit();

            if($result){
                return response()->json(
                    [
                        'code' => 'ok',
                        'message' => 'Shop currency deleted successfully'
                    ]
                );
            }

            return response()->json(
                [
                    'code' => 'error',
                    'message' => 'Shop currency not found'
                ]
            );

        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

}
