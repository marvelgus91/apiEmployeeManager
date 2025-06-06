<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CompanyDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyDepartments = DB::table('company_departments')
            ->join('company_areas', 'company_areas.id', '=', 'company_departments.company_area_id')
            ->join('companies', 'companies.id', '=', 'company_areas.company_id')
            ->select('company_departments.id', 'companies.name as company', 'company_areas.name as area', 'company_departments.name')->get();

        return response()->json($companyDepartments);
    }

    public function selector(string $company_department_id)
    {
        $companyDepartments = DB::table('company_departments')
            ->join('company_areas', 'company_areas.id', '=', 'company_departments.company_area_id')
            ->join('companies', 'companies.id', '=', 'company_areas.company_id')
            ->select('company_departments.id', 'companies.name as company', 'company_areas.name as area', 'company_departments.name')
            ->where('company_departments.company_area_id', $company_department_id)->get();

        return response()->json($companyDepartments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'company_area_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (CompanyDepartment::where([['name', '=', $input['name']], ['id', '<>', $input['company_area_id']]])->exists()) {
            return response()->json(['msg' => 'El departamento ya existe.', 'success' => false]);
        }

        try {
            $companyDepartment = CompanyDepartment::create($validator->validated());
            $companyDepartment->save();
            DB::commit();

            return response()->json(['msg' => 'Departamento creado con exito.', 'success' => true]);
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
        $companyDepartment = CompanyDepartment::find($id, ['id', 'name', 'company_area_id']);

        return response()->json($companyDepartment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $companyDepartment = CompanyDepartment::find($input['id']);

        if (is_null($companyDepartment)) {
            return response()->json(['msg' => 'El departamento no existe.', 'success' => false]);
        }

        $validator = Validator::make($input, [
            'name' => 'required',
            'company_area_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (CompanyDepartment::where([
            ['name', '=', $input['name']],
            ['company_area_id', '<>', $input['company_area_id']],
            ['id', '<>', $input['id']]
        ])->exists()) {
            return response()->json(['msg' => 'El departamento ya existe.', 'success' => false]);
        }

        try {
            $companyDepartment->update($validator->validated());
            DB::commit();

            return response()->json(['msg' => 'Departamento creado con exito.', 'success' => true]);
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
        $companyDepartment = CompanyDepartment::find($id);

        if (is_null($companyDepartment)) {
            return response()->json(['msg' => 'El departamento no existe.', 'success' => false]);
        }

        DB::beginTransaction();

        try {
            $companyDepartment->delete();
            DB::commit();

            return response()->json(['msg' => 'Departamento eliminado con exito.', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => json_encode($e->getMessage()), 'success' => false]);
        }
    }
}
