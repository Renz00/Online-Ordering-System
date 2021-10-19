<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class ProductsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        $date = Carbon::parse($year."-".$month."-01");
        $start = $date->startOfMonth()->format('Y-m-d H:i:s');
        $end = $date->endOfMonth()->format('Y-m-d H:i:s');

        //Inner join bests and products table
        $products= DB::table('bests')
        ->join('products', 'products.id', '=', 'bests.product_id')
        ->orderBy('bests.count', 'DESC')
        ->whereBetween('bests.created_at', [$start, $end])
        ->limit(8)
        ->get();
        
        return view('pages/index')->with('products', $products);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //add record to bests table
        $product = new Products;
        $product->name = $request->name;
        $product->type = $request->type;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->discount_price = $request->price - ($request->price * ($request->discount / 100));
        $product->available = $request->available;
        $product->image = 'noimage.png';

        $result = $product->save();

        if ($result == 1){
            return response('stored', 201);
        }
        else {
            return 'Error';
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //will create an array using the slug string
        $slugged = explode('-',$id);

        //pulls the last value from the slugged array
        $id = $slugged[array_key_last($slugged)];
        
        try {
            $product = Products::find($id);
        }catch(Exception $e){
            return 'Error:'.$e;
        }

        Session::put('product_id', $id);

        if ($product->discount>0){
            Session::put('product_price', $product->discount_price);
        } else {
            Session::put('product_price', $product->price);
        }
            
        return view('pages/product')->with('product', $product);
        
    }

    public function showProduct($id){
        return Products::where('id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        $product->name = $request->name;
        $product->type = $request->type;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->discount_price = $request->price - ($request->price * ($request->discount / 100));
        $product->available = $request->available;
        //$product->image = 'noimage.png';

        $result = $product->save();

        if ($result == 1){
            return response('updated', 201);
        }
        else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);
        
        $result = $product->delete();

        if ($result == 1){
            return response('deleted', 201);
        }
        else {
            return 'Error';
        }
    }

    public function getProducts(){

        // return Products::orderBy('id', 'DESC')->paginate(2);

        return Products::orderBy('id', 'DESC')->get();

        
    }

    public function showMenu(){

        return view('pages/menu');
    }

    public function showFavorites(){

        return view('pages/favorites');
    }
}
