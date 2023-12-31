<?php

namespace App\Http\Controllers;

use App\Models\ProductoDiseno;
use App\Http\Requests\StoreProductoDisenoRequest;
use App\Http\Requests\UpdateProductoDisenoRequest;

class ProductoDisenoController extends Controller{
    public function index(){ return ProductoDiseno::all(); }
    public function show(ProductoDiseno $productoDiseno){ return $productoDiseno; }
    public function store(StoreProductoDisenoRequest $request){
        $productoDiseno = ProductoDiseno::create($request->all());
        return response()->json($productoDiseno, 201);
    }
    public function update(UpdateProductoDisenoRequest $request, $id){
        $productoDiseno = ProductoDiseno::findOrFail($id);
        $productoDiseno->update($request->all());
        return response()->json($productoDiseno, 200);
    }
    public function destroy($id){
        $productoDiseno = ProductoDiseno::findOrFail($id);
        $productoDiseno->delete();
        return response()->json(null, 204);
    }
}
