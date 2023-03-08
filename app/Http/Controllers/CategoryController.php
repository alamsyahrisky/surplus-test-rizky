<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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

        $category = Category::where('enable',$enable);
        
        if($name){
            $category->where('name','like','%'.$name.'%');
        }

        return response()->json([
            'status' => true,
            'data' => $category->paginate($limit)
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
            'enable' => ['required','boolean']
        ];

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }

        // store to database
        $category = Category::create($data);

        // response
        return response()->json([
            'status' => true,
            'message' => 'Success store category',
            'data' => $category
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
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'status' => false,
                'message' => 'category not found'
            ],404);
        }

        return response()->json([
            'status' => true,
            'data' => $category
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
        $category = Category::find($id);
        
        if(!$category){
            return response()->json([
                'status' => false,
                'message' => 'category not found'
            ],404);
        }

        $data = $request->all();

        if($request->isMethod('PUT')){
            // validation put
            $rules = [
                'name' => ['required','min:3','string','max:200'],
                'enable' => ['required','boolean']
            ];
        }else{
            // validation patch
            $rules = [
                'name' => ['sometimes','required','min:3','string','max:200'],
                'enable' => ['sometimes','required','boolean']
            ];
        }

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }

        // update category
        $category->update($data);

        // response
        return response()->json([
            'status' => true,
            'message' => 'Success update category',
            'data' => $category
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
        $category = Category::find($id);
        
        if(!$category){
            return response()->json([
                'status' => false,
                'message' => 'category not found'
            ],404);
        }

        // delete category
        $category->delete();

        // response
        return response()->json([
            'status' => true,
            'message' => 'Success delete category'
        ]);
    }
}
