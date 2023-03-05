<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LG_CLCARD;
use App\Imports\CustomerImport;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $code = $request->header('code');
        $tableName = str_replace('{code}', $code, (new LG_CLCARD)->getTable());
        $data = DB::table($tableName)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Customer list',
            'data' => $data,
        ], 200);
    }
    public function store(Request $request)
    {
        $code = $request->header('code');
        $tableName = str_replace('{code}', $code, (new LG_CLCARD)->getTable());
        $validatedData = $request->validate([
            'CODE' => 'unique:LG_325_CLCARD,CODE',
        ]);
        $customer = new LG_CLCARD;
        $customer->setTable($tableName);
        $customer->CODE = $request->code;
        $customer->DEFINITION_ = $request->definition;
        $customer->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Customer added successfully',
            'data' => $customer,
        ], 200);
    }
    
    public function update(Request $request, $id)
    {
        $code = $request->header('code');
        $tableName = str_replace('{code}', $code, (new LG_CLCARD)->getTable());
        $customerClass = new LG_CLCARD;
        $customerClass->setTable($tableName);
        $customer = $customerClass->where('LOGICALREF', $id)->first();
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
            ], 404);
        }
        $oldValues = [];
        if ($request->has('code')) {
            $oldValues['code'] = $customer->CODE;
            $customer->CODE = $request->input('code');
        }
        if ($request->has('definition')) {
            $oldValues['definition'] = $customer->DEFINITION_;
            $customer->DEFINITION_ = $request->input('definition');
        }
        $customer->save();
        foreach ($oldValues as $key => $value) {
            $customer->$key = $value;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ], 200);
    }
    public function destroy(Request $request, $id)
    {
        $code = $request->header('code');
        $tableName = str_replace('{code}', $code, (new LG_CLCARD)->getTable());
        $customerClass = new LG_CLCARD;
        $customerClass->setTable($tableName);
        $customer = $customerClass->where('LOGICALREF', $id)->first();
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
            ], 404);
        }
        $customer->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully',
        ],200);
    }
    public function import(Request $request)
    {
        $import = new CustomerImport($request);
        $type = Excel::import($import, $request->file('file')->store('files'));
        return response()->json([
            'status' => 'success',
            'message' => 'Customer inserted successfully',
            'data' => $type,
        ]);
    }
    public function export(Request $request){
        return Excel::download(new CustomerExport($request), 'Cutomer.xlsx');        
    }
    public function importView(Request $request){
        return view('importFile');
    }
}
