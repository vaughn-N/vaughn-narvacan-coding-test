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
               'description' =>$product->description,
               'updated_at' => $product->updated_at,
               'created_at' => $product->created_at
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
        $rules = [
            'name' => 'required',
			'description' => 'required|max:255',
			'price' => 'required|regex:/^\d*(\.\d{1,2})?$/'
        ];

        $_input = $request->input();

        $validator = Validator::make($_input, $rules);

        if ($validator->fails()) {
			$errors = $validator->errors()->toArray();

			$data = [
				'status' => 'Fail',
				'errors' => $errors
			];
        } else {
            DB::beginTransaction();

            $product = new Product($_input);
            $product->save();

            DB::commit();

            $data = [
                'status' => 'Success',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'updated_at' => $product->updated_at,
                    'created_at' => $product->created_at
                ]
            ];

        }

        return response()->json($data);

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
