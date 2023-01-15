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
        $products = Product::paginate(10);



        foreach($products AS $product){

            $updated_at_format = date('Y-m-j h:i A e',strtotime($product->updated_at));
            $created_at_format = date('Y-m-j h:i A e',strtotime($product->created_at));

            $_products [] = [
               'id' => $product->id,
               'name' =>  $product->name,
               'price' =>$product->price,
               'updated_at' => $updated_at_format,
               'created_at' => $created_at_format
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

            $updated_at_format = date('Y-m-j h:i A e',strtotime($product->updated_at));
            $created_at_format = date('Y-m-j h:i A e',strtotime($product->created_at));



            DB::commit();

            $data = [
                'status' => 'Success',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'updated_at' => $updated_at_format,
                    'created_at' => $created_at_format
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
        $product = Product::where('id',$id)->first();

        $updated_at_format = date('Y-m-j h:i A e',strtotime($product->updated_at));
        $created_at_format = date('Y-m-j h:i A e',strtotime($product->created_at));
        
        $_product= [
            'id' => $product->id,
            'name' =>  $product->name,
            'description' =>$product->description,
            'price' =>$product->price,
            'updated_at' => $updated_at_format,
            'created_at' => $created_at_format
        ];
        

        $data = [
            'products' => $_product
        ];

        return response()->json($_product);
    }
}
