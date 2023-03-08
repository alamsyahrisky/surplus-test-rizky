<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit') ? $request->input('limit') : 10;
        $product_id = $request->input('product_id');

        $image = ProductImage::with('image');
        
        if($product_id){
            $image->where('product_id',$product_id);
        }

        return response()->json([
            'status' => true,
            'data' => $image->paginate($limit)
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
            'name.*' => ['required'],
            'file.*' => ['required','image'],
            'enable.*' => ['required','boolean'],
            'product_id' => ['required','exists:product,id'],
        ];

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }

        if($request->hasfile('file'))
         {
            foreach($request->file('file') as $key => $file)
            {
                $insert['file'] = $file->store(
                    'assets/images/','public'
                );
                $insert['name'] = $data['name'][$key];
                $insert['enable'] = $data['enable'][$key];

                $image = Image::create($insert);

                $productImage = ProductImage::create([
                    'product_id' => $data['product_id'],
                    'image_id' => $image->id
                ]);
            }
         }

         $image = ProductImage::with('image')->where('product_id',$data['product_id'])->get();

         return response()->json([
            'status' => true,
            'data' => $image
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
        //
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
        $data = $request->all();

        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'product not found'
            ],404);
        }

        // validation
        $rules = [
            'name.*' => ['required'],
            'file.*' => ['required','image'],
            'enable.*' => ['required','boolean'],
        ];
        
        $validator = Validator::make($data,$rules);
        
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }
        
        if($request->hasfile('file'))
         {
            foreach($request->file('file') as $key => $file)
            {
                $insert['file'] = $file->store(
                    'assets/images/','public'
                );
                $insert['name'] = $data['name'][$key];
                $insert['enable'] = $data['enable'][$key];

                if($data['id'][$key] == ''){
                    $image = Image::create($insert);
                    $productImage = ProductImage::create([
                        'product_id' => $id,
                        'image_id' => $image->id
                    ]);
                }else{
                    $image = Image::find($data['id'][$key]);
                    $image->update($insert);
                }

            }
         }

         $image = ProductImage::with('image')->where('product_id',$id)->get();

         return response()->json([
            'status' => true,
            'data' => $image
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
        $image = Image::find($id);
        
        if(!$image){
            return response()->json([
                'status' => false,
                'message' => 'image not found'
            ],404);
        }

        // delete product
        $image->delete();

        // response
        return response()->json([
            'status' => true,
            'message' => 'Success delete image'
        ]);
    }
}
