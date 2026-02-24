<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use App\CommentUser;

use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tasks()
    {
        return $this->belongsToMany('App\Task')->withTimestamps();
    }

    public function todotasks()
    {
        return $this->belongsToMany('App\TodoTask')->withTimestamps();
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment');
    }

    public function getAvatarAttribute($value)
    {
        return $value ?: 'sinavatar.jpg';
    }

    public function roles_usuario()
    {
        return $this->hasMany('App\RolesUsuario', 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }

    public function departamento()
    {
        return $this->belongsTo('App\Departamento', 'role_id', 'id');
    }

    public function isRole($role)
    {
        $rol = Rol::where('slug', $role)->first();
        if (is_object($rol)) {
            $isrol = RolesUsuario::where('role_id', $rol->id)->where('user_id', $this->id)->first();
            if (isset($isrol->id)) {
                return true;
            }
        }
        return false;
    }

    public function isPermission($permission)
    {
        $permiso = Permission::where('slug', $permission)->first();
        if (is_object($permiso)) {
            $ispermission = PermissionsUsuario::where('permission_id', $permiso->id)->where('user_id', $this->id)->first();
            if (isset($ispermission->id)) {
                return true;
            }
        }
        return false;
    }

    public function compruebaSeguridad($permission)
    {
        $permiso = Permission::where('slug', $permission)->first();
        if (is_object($permiso)) {
            //Comprobamos primero si este permiso está activo en alguno de los roles asignados al usuario
            $rolesUsuario = RolesUsuario::where('user_id', $this->id)->get();
            foreach ($rolesUsuario as $rolUsuario) {
                $permissionRol = PermissionRole::where('permission_id', $permiso->id)->where('role_id', $rolUsuario->role_id)->first();
                if (isset($permissionRol->id)) {
                    return true;
                }
            }
            //Comprobamos si el permiso está asignado directamente al usuario
            $permissionUser = PermissionsUsuario::where('user_id', $this->id)->where('permission_id', $permiso->id)->first();
            if (isset($permissionUser->id)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Comprueba si el usuario tiene permiso de mostrar el módulo asignado al elemento de menú ($modulo).
     * @param $modulo
     */
    public function compruebaSeguridadMenu($modulo)
    {
        //Buscamos el permiso de tipo mostrar asignado al módulo que nos llega en la función
        $permiso = Permission::where('model', $modulo)->where('permission_type', 'mostrar')->first();
        if (is_object($permiso)) {
            //Comprobamos primero si este permiso está activo en alguno de los roles asignados al usuario
            $rolesUsuario = RolesUsuario::where('user_id', $this->id)->get();
            foreach ($rolesUsuario as $rolUsuario) {
                $permissionRol = PermissionRole::where('permission_id', $permiso->id)->where('role_id', $rolUsuario->role_id)->first();
                if (isset($permissionRol->id)) {
                    return true;
                }
            }
            //Comprobamos si el permiso está asignado directamente al usuario
            $permissionUser = PermissionsUsuario::where('user_id', $this->id)->where('permission_id', $permiso->id)->first();
            if (isset($permissionUser->id)) {
                return true;
            }
        }
        return false;
    }

    public function fichajes()
    {
        return $this->HasMany('App\Fichaje', 'user_id', 'id')->orderBy('fecha');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'empresa_id', 'id');
    }

    public function nactive_projects()
    {
        return Project::where('user_id', $this->id)
            ->where('estado_proyecto', '<>', '4')->get()->count();
    }

    public function nactive_tasks()
    {
        return TaskUser::join('tasks', 'task_user.task_id', 'tasks.id')
            ->where('user_id', $this->id)
            ->where('estado_tarea', '<>', '4')->get()->count();
    }

    public function dias_en_empresa()
    {
        $fechaEmision = Carbon::parse($this->created_at);
        $fechaExpiracion = Carbon::parse(date('Y-m-d H:i:s'));

        return $fechaExpiracion->diffInDays($fechaEmision);
    }

    public function ncomentarios()
    {
        return CommentUser::where('user_id', $this->id)->count();
    }

    public function userdata()
    {
        return $this->hasOne('App\Userdata', 'user_id', 'id');
    }

    public function devuelve_avatar()
    {
        if ($this->avatar != '')
            return asset('images/avatar/' . $this->avatar);
    }

    // Accessor para obtener la URL del avatar del usuario
    public function getAvatarUrlAttribute()
    {
        // Si no tiene avatar, usamos 'sinavatar.jpg' como valor por defecto
        $file = $this->avatar ?: 'sinavatar.jpg'; 
        $path = public_path('images/avatar/' . $file);

        // Devuelve la URL del avatar o del avatar por defecto
        return file_exists($path)
            ? asset('images/avatar/' . $file)
            : asset('images/avatar/sinavatar.jpg'); 
    }

    public function devuelveMesLetra($mes)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return $meses[$mes] ?? null;
    }

}
