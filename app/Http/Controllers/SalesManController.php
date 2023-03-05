<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesMan;
use App\Imports\SalesmanImport;
use App\Exports\SalesmanExport;
use Maatwebsite\Excel\Facades\Excel;



class SalesManController extends Controller
{
    public function index()
    {
        $salesman = SalesMan::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Salesman list',
            'data' => $salesman,
        ]);
    }
    public function store(Request $request)
    {
        $salesman = SalesMan::create([
            'CODE' => $request->code,
            'DEFINITION_' => $request->definition,
            'TELNUMBER' => $request->phone,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Salesman added successfully',
            'data' => $salesman,
        ]);
    }
    public function update(Request $request, $id)
    {
        $slsman = SalesMan::where('LOGICALREF', $id)->first();
        if (!$slsman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Salesman not found',
            ], 404);
        }
        $oldValues = [];
        if ($request->has('code')) {
            $oldValues['code'] = $slsman->CODE;
            $slsman->CODE = $request->input('code');
        }
        if ($request->has('definition')) {
            $oldValues['definition'] = $slsman->DEFINITION_;
            $slsman->DEFINITION_ = $request->input('definition');
        }
        if ($request->has('phone')) {
            $oldValues['phone'] = $slsman->TELNUMBER;
            $slsman->TELNUMBER = $request->input('phone');
        }
        $slsman->save();
        foreach ($oldValues as $key => $value) {
            $slsman->$key = $value;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Salesman updated successfully',
            'data' => $slsman,
        ]);
    }
    public function destroy($id)
    {
        $slsman = SalesMan::where('LOGICALREF', $id)->first();
        if (!$slsman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Salesman not found',
            ],404);
        }
        $slsman->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Salesman deleted successfully',
        ],200);
    }
    public function import(Request $request){
        $type = Excel::import(new SalesmanImport, $request->file('file')->store('files'));
        return response()->json([
            'status' => 'success',
            'message' => 'Salesman imported successfully',
            'data' => $type,
        ],200);
     }

     public function export(Request $request){
        return Excel::download(new SalesmanExport, 'Salesman.xlsx');
     }
}
