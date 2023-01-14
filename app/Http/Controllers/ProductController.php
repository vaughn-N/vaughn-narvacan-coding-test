<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Validator;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('deprecated','0')->paginate(10);

        foreach($products AS $product){
            $_products [] = [
               'id' => $product->id,
               'name' =>  $product->name,
               'description' =>$product->description
            ];
        }

        $data = [
            'products' => $_products
        ];

        return response()->json($_products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
