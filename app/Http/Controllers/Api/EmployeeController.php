<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = DB::table('employees')
            ->join('company_departments', 'company_departments.id', '=', 'employees.company_department_id')
            ->join('company_areas', 'company_areas.id', '=', 'company_departments.company_area_id')
            ->join('companies', 'companies.id', '=', 'company_areas.company_id')
            ->select(
                'employees.id',
                'employees.fullName',
                'companies.name as company',
                'company_areas.name as area',
                'company_departments.name as department',
                'employees.position',
                'employees.photo',
                'employees.startDate',
                'employees.status'
            )->orderBy('company')->orderBy('fullName')->get();

        foreach ($employees as $employee) {
            $employee->photo = asset(Storage::url($employee->photo));;
        }

        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'fullName' => 'required',
            'company_department_id' => 'required',
            'position' => 'required',
            'photo' => 'image|nullable',
            'startDate' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (Employee::where('fullName', $input['fullName'])->exists()) {
            return response()->json(['msg' => 'El empleado ya existe.', 'success' => false]);
        }

        try {
            $input = $validator->validated();

            if ($request->hasFile('photo')) {
                $input['photo'] = $request->file('photo')->store('EmployeesImages', 'public');
            }

            $employee = Employee::create($input);
            $employee->save();
            DB::commit();

            return response()->json(['msg' => 'El empleado fue creado con exito.', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => 'Ocurrio un error al intentar crear el empleado. '
                . json_encode($e->getMessage()), 'success' => false]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = DB::table('employees')
            ->join('company_departments', 'company_departments.id', '=', 'employees.company_department_id')
            ->join('company_areas', 'company_areas.id', '=', 'company_departments.company_area_id')
            ->join('companies', 'companies.id', '=', 'company_areas.company_id')
            ->select(
                'employees.id',
                'employees.fullName',
                'companies.id as company_id',
                'company_areas.id as company_area_id',
                'company_departments.id as company_department_id',
                'employees.position',
                'employees.photo',
                'employees.startDate',
                'employees.status'
            )->where('employees.id', $id)->firstOrFail();

        // $employee = Employee::find($id);

        if (is_null($employee)) {
            return response()->json(['msg' => 'El empleado no existe.', 'success' => false]);
        }

        $employee->photo = asset(Storage::url($employee->photo));

        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $employee = Employee::find($input['id']);

        if (is_null($employee)) {
            return response()->json(['msg' => 'El empleado no existe.', 'success' => false]);
        }

        $validator = Validator::make($input, [
            'fullName' => 'required',
            'company_department_id' => 'required',
            'position' => 'required',
            'photo' => 'image|nullable',
            'startDate' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Los datos no son validos, ' . $validator->errors()->first(), 'success' => false]);
        }

        DB::beginTransaction();

        if (employee::where([['fullName', '=', $input['fullName']], ['id', '<>', $employee['id']]])->exists()) {
            return response()->json(['msg' => 'El empleado ya existe.', 'success' => false]);
        }

        try {
            $input = $validator->validated();

            if ($request->hasFile('photo')) {
                $input['photo'] = $request->file('photo')->store('EmployeesImages', 'public');
            }

            $employee->update($input);
            DB::commit();

            return response()->json(['msg' => 'El empleado fue actualizado con exito', 'success' => true]);
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
        $employee = Employee::find($id);

        if (is_null($employee)) {
            return response()->json(['msg' => 'El empleado no existe.', 'success' => false]);
        }

        try {
            $employee->delete();
            DB::commit();

            return response()->json(['msg' => 'El empleado fue eliminado con exito.', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => json_encode($e->getMessage()), 'success' => false]);
        }
    }
}
