<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$d = Department::all();
        //return response()->json($d,200);

        $page_size = request()->query('page_size');
        $pageSize = $page_size == null ? 5 : $page_size;

        //$d = Department::paginate($pageSize);
        
        //test relation

        $d = Department::orderBy('id','desc')->with(['officers'])->get();

        return response()->json([
            'data' => $d,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $d = new Department();
        $d->name = $request->name;
        $d->save();

        return response()->json("data added",201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $d = Department::find($id);

        if($d == null) {
            return response()->json([
                'error' => [
                    'status_code' => 404,
                    'message' => 'Department not found'
                ]
                ],404);
        }
        return response()->json($d,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($id != $request->id) {
            return response()->json([
                'error' => [
                    'status_code' => 400,
                    'message' => 'Bad Request'
                ]
                ],400);
        }

        $d = Department::find($id);
        $d->name = $request->name;
        $d->save();

        return response()->json([
            'message' => 'data updated',
            'data' => $d
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $d = Department::find($id);

        if($d == null) {
            return response()->json([
                'error' => [
                    'status_code' => 404,
                    'message' => 'Department not found'
                ]
                ],404);
        }

        $d->delete();
        return response()->json("data deleted",200);
    }
    public function search()
    {
        $query = request()->query('name');
        $keyword = "%".$query."%";
        $d = Department::where('name','like',$keyword)->get();

        if($d->isEmpty()) {
            return response()->json([
                'error' => [
                    'status_code' => 404,
                    'message' => 'Department not found'
                ]
                ],404);
        }

        return response()->json([
            'data' => $d,
        ],200); 
    }
}
