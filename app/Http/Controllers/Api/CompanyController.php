<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderBy('name')->select('id', 'name', 'rfc')->get();
        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'rfc' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (Company::where('name', $input['name'])->exists()) {
            return response()->json(['msg' => 'La compañia ya existe.', 'success' => false]);
        }

        if (Company::where('rfc', $input['rfc'])->exists()) {
            return response()->json(['msg' => 'El RFC ya fue asignado a otra empresa.', 'success' => false]);
        }

        try {
            $company = Company::create($validator->validated());
            $company->save();
            DB::commit();

            return response()->json(['msg' => 'Compañia creada exitosamente', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => json_encode($e->getMessage()), 'success' => false]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::find($id, ['id', 'name', 'rfc']);

        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $company = Company::find($input['id']);

        if (is_null($company)) {
            return response()->json(['msg' => 'La compañia no existe.', 'success' => false]);
        }

        $validator = Validator::make($input, [
            'name' => 'required',
            'rfc' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (Company::where([['name', '=', $input['name']], ['id', '<>', $company['id']]])->exists()) {
            return response()->json(['msg' => 'La compañia ya existe.', 'success' => false]);
        }

        if (Company::where([['rfc', '=', $input['rfc']], ['id', '<>', $company['id']]])->exists()) {
            return response()->json(['msg' => 'El RFC ya fue asignado a otra empresa.', 'success' => false]);
        }

        try {
            $company->update($validator->validated());
            DB::commit();

            return response()->json(['msg' => 'Compañia actualizada con exito', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => json_encode($e->getMessage()), 'success' => false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::find($id);

        if (is_null($company)) {
            return response()->json(['msg' => 'La compañia no existe.', 'success' => false]);
        }

        DB::beginTransaction();

        try {
            $company->delete();
            DB::commit();

            return response()->json(['msg' => 'Compañia eliminada con exito.', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => json_encode($e->getMessage()), 'success' => false]);
        }
    }
}
