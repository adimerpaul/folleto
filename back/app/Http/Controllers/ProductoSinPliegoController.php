<?php

namespace App\Http\Controllers;

use App\Models\ProductoSinPliego;
use App\Http\Requests\StoreProductoSinPliegoRequest;
use App\Http\Requests\UpdateProductoSinPliegoRequest;

class ProductoSinPliegoController extends Controller{
    public function index(){ return ProductoSinPliego::all(); }
    public function show(ProductoSinPliego $productoSinPliego){ return $productoSinPliego; }
    public function store(StoreProductoSinPliegoRequest $request){
        $productoSinPliego = ProductoSinPliego::create($request->all());
        return response()->json($productoSinPliego, 201);
    }
    public function update(UpdateProductoSinPliegoRequest $request, $id){
        $productoSinPliego = ProductoSinPliego::findOrFail($id);
        $productoSinPliego->update($request->all());
        return response()->json($productoSinPliego, 200);
    }
    public function destroy($id){
        $productoSinPliego = ProductoSinPliego::findOrFail($id);
        $productoSinPliego->delete();
        return response()->json(null, 204);
    }
}
