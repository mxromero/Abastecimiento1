<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $user;
    public function index()
    {
        $User = $this->user;
        return view('auth.role.index', compact('User'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validaDatos = $request->validate([
            'name'  => 'required|unique:roles,name|string|min:4|max:255',
        ]);

        $validaDatos['guard_name'] = $validaDatos['name'];



        Role::create($validaDatos);

        // Redirigir a la vista de listado de roles con un mensaje de éxito
        return redirect()->route('role.index')->with('success', 'Rol creado exitosamente');
    }

    public function showDataTable(){

        $roles = Role::where('name', '!=', 'Administrador')->get();

        return DataTables::of($roles)
        ->addColumn('acciones', function($role){
            $editUrl = route('role.edit', $role->id);
            $deleteUrl = route('role.destroy', $role->id);
            return '<a href="' . $editUrl . '" class="btn btn-warning btn-sm">Editar</a>
                    <form action="' . $deleteUrl . '" method="POST" style="display: inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este rol?\')">Eliminar</button>
                    </form>';
        })
        ->rawColumns(['acciones']) // Permite HTML en la columna 'acciones'
        ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('auth.role.edit',compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|min:4|max:255|unique:roles,name,' . $id,
        ]);

        // Asignar el valor de 'name' a 'guard_name'
        $validatedData['guard_name'] = $validatedData['name'];

        // Encontrar el rol por su ID
        $role = Role::findOrFail($id);

        // Actualizar el rol con los datos validados
        $role->update($validatedData);

        // Redirigir a la vista de listado de roles con un mensaje de éxito
        return redirect()->route('role.index')->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Retrieve the role by ID, or fail if not found
        $Estamento = Role::findOrFail($id);

        // Check if the role was found
        if (!$Estamento) {
            return redirect()->route('role.index')->with('error', 'Rol no encontrado.');
        }

        // Attempt to delete the role
        try {
            $Estamento->delete();
        } catch (\Exception $e) {
            return redirect()->route('role.index')->with('error', 'Ocurrió un error al eliminar el rol.');
        }

        // Redirect with a success message
        return redirect()->route('role.index')->with('success', 'Rol eliminado exitosamente');

    }
}
