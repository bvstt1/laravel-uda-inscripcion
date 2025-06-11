<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Evento;

class CategoriaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:7'
        ]);

        $categoria = Categoria::create($request->only('nombre', 'color'));

        return response()->json($categoria);
    }
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'color' => 'nullable|string|max:10'
        ]);

        $categoria->update($request->only('nombre', 'color'));

        return redirect()->back()->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        // Ponemos a null la categoría de los eventos que la usaban
        Evento::where('categoria_id', $categoria->id)->update(['categoria_id' => null]);

        $categoria->delete();

        return redirect()->back()->with('success', 'Categoría eliminada. Los eventos asociados ahora no tienen categoría.');
    }

}
