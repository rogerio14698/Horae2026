<?php

namespace App\Http\Controllers;

use App\Rol;
use App\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->compruebaSeguridad('mostrar-roles') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $roles = Rol::all();
        return view('eunomia.roles.listado_roles', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->compruebaSeguridad('crear-rol') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        return view('eunomia.roles.form_ins_roles');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->compruebaSeguridad('crear-rol') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required'
        ]);

        $rol = new Rol();

        $rol->name = $request->name;
        $rol->slug = $request->slug;
        $rol->description = $request->description;

        $rol->save();

        return redirect('eunomia/roles');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->compruebaSeguridad('editar-rol') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $rol = Rol::findOrFail($id);
        return view('eunomia.roles.form_edit_roles',compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(\Auth::user()->compruebaSeguridad('editar-rol') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required'
        ]);

        $rol = Rol::findOrFail($id);

        $rol->name = $request->name;
        $rol->slug = $request->slug;
        $rol->description = $request->description;

        $rol->save();

        return redirect('eunomia/roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(\Auth::user()->compruebaSeguridad('eliminar-rol') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return redirect('eunomia/roles');
    }

    /**
     * A full matrix of roles and permissions.
     * @return Response
     */
    public function showRoleMatrix()
    {
        if(\Auth::user()->compruebaSeguridad('asignar-permisos-roles') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $roles = Rol::all();
        $permissions = Permission::orderBy('model')->get();
        $prs = DB::table('permission_role')->select('role_id as r_id','permission_id as p_id')->get();

        $pivot = [];
        foreach($prs as $p) {
            $pivot[] = $p->r_id.":".$p->p_id;
        }

        return view('eunomia.roles.matrix', compact('roles','permissions','pivot') );
    }

    /**
     * Sync roles and permissions.
     * @return Response
     */
    public function updateRoleMatrix(Request $request)
    {
        if(\Auth::user()->compruebaSeguridad('asignar-permisos-roles') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $bits = $request->get('perm_role');
        foreach($bits as $v) {
            $p = explode(":", $v);
            $data[] = array('role_id' => $p[0], 'permission_id' => $p[1]);
        }

        // Evitar ejecutar ALTER TABLE dentro de la transacción, algunos motores (o versiones)
        // realizan commits implícitos en ALTER TABLE y provocan rollback errors
        DB::transaction(function () use ($data) {
            DB::table('permission_role')->delete();
            DB::table('permission_role')->insert($data);
        });

        // Ejecutar ALTER TABLE fuera de la transacción
        try {
            DB::statement('ALTER TABLE permission_role AUTO_INCREMENT = 1');
        } catch (\Exception $e) {
            // No detener el flujo por este ajuste; registrar para diagnóstico
            \Log::warning('No se pudo reiniciar AUTO_INCREMENT en permission_role: ' . $e->getMessage());
        }

        $level = "success";
        $message = "<i class='fa fa-check-square-o fa-1x'></i> Success! Role permissions updated.";

        return redirect('eunomia/roles/matrix')
            ->with( ['flash' => ['message' => $message, 'level' =>  $level] ] );
    }
}
