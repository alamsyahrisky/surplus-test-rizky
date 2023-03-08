<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit') ? $request->input('limit') : 10;
        $name = $request->input('name');
        $enable = $request->input('enable') ? $request->input('enable') : true;
        $show_image = $request->input('show_image');

        $product = Product::where('enable',$enable)->with('category_product.category');
        
        if($show_image){
            $product->with('product_image.image');
        }

        if($name){
            $product->where('name','like','%'.$name.'%');
        }


        return response()->json([
            'status' => true,
            'data' => $product->paginate($limit)
        ]);
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
        $data = $request->all();

        // validation
        $rules = [
            'name' => ['required','min:3','string','max:200'],
            'description' => ['required','min:3','string','max:200'],
            'enable' => ['required','boolean'],
            'category_id' => ['required','exists:category,id'],
        ];

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }

        // store to database
        $product = Product::create($data);

        $data['product_id'] = $product->id;
        $categoryProduct = CategoryProduct::create($data);

        // response
        return response()->json([
            'status' => true,
            'message' => 'Success store product',
            'data' => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category_product.category')->find($id);

        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'product not found'
            ],404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
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
         // check data
         $product = Product::find($id);
        
         if(!$product){
             return response()->json([
                 'status' => false,
                 'message' => 'product not found'
             ],404);
         }
 
         $data = $request->all();
 
         if($request->isMethod('PUT')){
             // validation put
             $rules = [
                'name' => ['required','min:3','string','max:200'],
                'description' => ['required','min:3','string','max:200'],
                'enable' => ['required','boolean'],
                'category_id' => ['required','exists:category,id'],
             ];
         }else{
             // validation patch
             $rules = [
                'name' => ['sometimes','required','min:3','string','max:200'],
                'description' => ['sometimes','required','min:3','string','max:200'],
                'enable' => ['sometimes','required','boolean'],
                'category_id' => ['sometimes','required','exists:category,id'],
             ];
         }
 
         $validator = Validator::make($data,$rules);
 
         if($validator->fails()){
             return response()->json([
                 'status' => false,
                 'message' => $validator->errors()
             ],400);
         }
 
         // update to database
         $product->update($data);

        if(isset($data['category_id'])){
            $categoryProduct = CategoryProduct::where([
                'product_id' => $product->id,
            ])->first();

            $categoryProduct->update([
                'category_id' => $data['category_id']
            ]);
        }
 
         // response
         return response()->json([
             'status' => true,
             'message' => 'Success update product',
             'data' => $product
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check data
        $product = Product::find($id);
        
        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'product not found'
            ],404);
        }

        // delete product
        $product->delete();

        // response
        return response()->json([
            'status' => true,
            'message' => 'Success delete product'
        ]);
    }
}
