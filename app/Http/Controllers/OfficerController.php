<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Officer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $of = Officer::with(['department','user'])->get();
        
        return response()->json([
            'data' => $of,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $of = new Officer();

            if ($request->has('picture')){
                $base64_image = $request->picture;
                @list($type,$file_data) = explode(';', $base64_image);
                @list(,$file_data) = explode(',',$file_data);

                $new_filename = uniqid() . '.png';

                if ($file_data != "") {
                    Storage::disk('public')->put('upload/'.$new_filename, base64_decode($file_data));
                }
                $of->firstname = $request->firstname;
                $of->lastname = $request->lastname;
                $of->dob = $request->dob;
                $of->salary = $request->salary;
                $of->user_id = $request->user_id;
                $of->department_id = $request->department_id;
                $of->picture = $new_filename;
            }
            else {
                $of->firstname = $request->firstname;
                $of->lastname = $request->lastname;
                $of->dob = $request->dob;
                $of->salary = $request->salary;
                $of->user_id = $request->user_id;
                $of->department_id = $request->department_id;
            
            }
            $of->save();
            DB::commit();

            return response()->json([
                'message' => 'Officer created successfully',
                'data' => $of
            ], 201);
        }
        catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create officer',
                'error' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
