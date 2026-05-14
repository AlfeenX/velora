<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreCollectionRequest;
use Illuminate\Http\Request;
use App\Models\Collection;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = (int) $request->paginate ?? 10;
        $search = $request->search ?? '';
        $sort = $request->sort ?? 'desc';

        $collection = Collection::paginate($paginate);
        return response()->json([
            'status' => 'success',
            'data' => $collection,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCollectionRequest $request)
    {
        $validatedData = $request->validated();

        $collection = Collection::create($validatedData);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Collection created successfully',
            'data' => $collection,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        return response()->json([   
            'status' => 'success',
            'data' => $collection,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCollectionRequest $request, Collection $collection)
    {
        $validatedData = $request->validated();

        $collection->update($validatedData);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Collection updated successfully',
            'data' => $collection,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $collection->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Collection deleted successfully',
        ]);
    }
}
