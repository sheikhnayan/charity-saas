<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyCategoryController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'PropertyCategoryController::index (stub)']);
    }

    public function create()
    {
        return response()->json(['message' => 'PropertyCategoryController::create (stub)']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'PropertyCategoryController::store (stub)']);
    }

    public function edit($id)
    {
        return response()->json(['message' => "PropertyCategoryController::edit($id) (stub)"]); 
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => "PropertyCategoryController::update($id) (stub)"]); 
    }

    public function destroy($id)
    {
        return response()->json(['message' => "PropertyCategoryController::destroy($id) (stub)"]); 
    }

    public function getByWebsite($websiteId)
    {
        return response()->json(['message' => "PropertyCategoryController::getByWebsite($websiteId) (stub)"]); 
    }
}
