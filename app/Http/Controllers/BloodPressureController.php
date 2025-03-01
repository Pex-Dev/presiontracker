<?php

namespace App\Http\Controllers;

use App\Models\BloodPressure;
use Illuminate\Http\Request;

class BloodPressureController extends Controller
{
    public static function index()
    {
        return view('pages.blood-pressure.index',  [
            'title' => 'Historial de Presión Arterial'
        ]);
    }

    public static function create()
    {
        return view('pages.blood-pressure.create', [
            'title' => 'Registrar Presión Arterial'
        ]);
    }

    public static function store(Request $request)
    {
        //Verificar si el name de usuario es Invitado
        if (auth()->user()->name == 'Invitado') {
            //Verificar que el usuario no tenga mas de 15 registros
            if (BloodPressure::where('user_id', auth()->id())->count() >= 15) {
                return redirect()->back()->with('error', 'La cuenta de invitados no puede tener más de 15 registros.');
            }
        }

        //Validar los datos
        $request->validate([
            'measured_at' => ['required', 'date', 'before_or_equal:now'],
            'systolic' => ['required', 'numeric', 'min:50', 'max:250'],
            'diastolic' => ['required', 'numeric', 'min:30', 'max:150'],
            'pulse' => ['nullable', 'numeric', 'min:30', 'max:200'],
            'temperature' => ['nullable', 'numeric', 'min:35', 'max:42'],
            'notes' => ['nullable', 'max:350']
        ]);

        //Crear el registro
        BloodPressure::create([
            'user_id' => auth()->id(),
            'measured_at' => $request['measured_at'],
            'systolic' => $request['systolic'],
            'diastolic' => $request['diastolic'],
            'pulse' => $request['pulse'],
            'temperature' => $request['temperature'],
            'notes' => $request['notes'],
        ]);

        return redirect()->route('blood-pressure.index')->with('success', 'Registro guardado correctamente.');
    }

    public static function edit($id)
    {
        //Buscar el registro
        $bloodPressure = BloodPressure::find($id);

        //Retornar si no se encuentra el registro
        if (!$bloodPressure) {
            return redirect()->route('blood-pressure.index')->with('error', 'No se encontró el registro solicitado.');
        }

        return view('pages.blood-pressure.edit', [
            'title' => 'Editar Registro De Presión Arterial',
            'presion' => $bloodPressure
        ]);
    }

    public static function update(Request $request, $id)
    {
        //Validar el registro
        $validated = $request->validate([
            'measured_at' => ['required', 'date', 'before_or_equal:now'],
            'systolic' => ['required', 'numeric', 'min:50', 'max:250'],
            'diastolic' => ['required', 'numeric', 'min:30', 'max:150'],
            'pulse' => ['nullable', 'numeric', 'min:30', 'max:200'],
            'temperature' => ['nullable', 'numeric', 'min:35', 'max:42'],
            'notes' => ['nullable', 'max:350']
        ]);

        //Buscar el registro
        $bloodPressure = BloodPressure::findOrFail($id);

        //Actualizar el registro
        $bloodPressure->update([
            'measured_at' => $request['measured_at'],
            'systolic' => $request['systolic'],
            'diastolic' => $request['diastolic'],
            'pulse' => $request['pulse'],
            'temperature' => $request['temperature'],
            'notes' => $request['notes'],
        ]);

        return redirect()->route('blood-pressure.index')->with('success', 'Registro actualizado correctamente.');
    }
}
