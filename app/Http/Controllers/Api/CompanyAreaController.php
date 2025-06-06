<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyArea;

class CompanyAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyAreas = DB::table('company_areas')
            ->join('companies', 'companies.id', '=', 'company_areas.company_id')
            ->select('company_areas.id', 'companies.name as Company', 'company_areas.name')->orderBy('company_areas.name')->get();

        return response()->json($companyAreas);
    }

    public function selector(string $company_id)
    {
        $companyAreas = DB::table('company_areas')
            ->join('companies', 'companies.id', '=', 'company_areas.company_id')
            ->select('company_areas.id', 'companies.name as Company', 'company_areas.name')
            ->where('company_areas.company_id', $company_id)->orderBy('company_areas.name')->get();

        return response()->json($companyAreas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'company_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (CompanyArea::where([['name', '=', $input['name']], ['id', '=', $input['company_id']]])->exists()) {
            return response()->json(['msg' => 'El area ya existe.', 'success' => false]);
        }

        try {
            $companyArea = CompanyArea::create($validator->validated());
            $companyArea->save();
            DB::commit();

            return response()->json(['msg' => 'Area creada exitosamente', 'success' => true]);
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
        $companyArea = CompanyArea::find($id, ['id', 'name', 'company_id']);

        return response()->json($companyArea);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $companyArea = CompanyArea::find($input['id']);

        if (is_null($companyArea)) {
            return response()->json(['msg' => 'El area no existe.', 'success' => false]);
        }

        $validator = Validator::make($input, [
            'name' => 'required',
            'company_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (CompanyArea::where([['name', '=', $input['name']], ['id', '<>', $input['company_id']]])->exists()) {
            return response()->json(['msg' => 'El nombre de area ya fue registrado..', 'success' => false]);
        }

        try {
            $companyArea->update($validator->validated());
            DB::commit();

            return response()->json(['msg' => 'Area creada exitosamente', 'success' => true]);
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
        $companyArea = CompanyArea::find($id);

        if (is_null($companyArea)) {
            return response()->json(['msg' => 'El area no existe.', 'success' => false]);
        }

        try {
            $companyArea->delete();
            DB::commit();

            return response()->json(['msg' => 'Area eliminada con exitoso.', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => json_encode($e->getMessage()), 'success' => false]);
        }
    }
}
