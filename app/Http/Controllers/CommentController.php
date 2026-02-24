<?php

namespace App\Http\Controllers;

use App\Comment;
use App\CommentTask;
use App\Task;
use App\Project;
use App\User;
use Mail;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->userc_id;
        $project_id = $request->projectc_id;
        $task_id = $request->taskc_id;
        $comentario = $request->comentario;
        $date = date('Y-m-d H:i');
        if ($request->comment_id > 0){
            if(\Auth::user()->compruebaSeguridad('editar-comentario') == false)
                return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
            $comment = Comment::findOrFail($request->comment_id);
        } else {
            if(\Auth::user()->compruebaSeguridad('crear-comentario') == false)
                return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
            $comment = new Comment;
            $comment->date = $date;
        }
        $comment->comment = $comentario;

        $comment->save();

        if ($request->comment_id == null) {
            $comment->users()->attach($request->input('userc_id'));
            if ($task_id == null || $task_id == '')
                $comment->projects()->attach($request->input('projectc_id'));
            if ($task_id > 0)
                $comment->tasks()->attach($request->input('taskc_id'));

            //Enviar email al/los usuario/s avisando que se ha introducido un nuevo comentario.
            setlocale(LC_TIME, 'Spanish');
            //Sacamos el/los usuario/s que tienen el proyecto o la tarea asignada para poder enviarles el email
            if ($project_id > 0) { //Es un proyecto
                $project = Project::FindOrFail($project_id);
                $usuarios = array($project->user_id);
            } else { //Es una tarea
                $task = Task::FindOrFail($task_id);
                $usuarios = $task->users()->pluck('id');
            }
            foreach ($usuarios as $user) {

                $usuario = User::findOrFail($user);
                $email = $usuario->email;
                if ($project_id > 0) { //Es proyecto
                    if ($user != $project->user_id) {
                        if (Mail::send('eunomia.includes.emails.email_comentario_proyecto', [
                                'titulo_proyecto' => $project->titulo_proyecto,
                                'comentario' => $comentario,
                                'link_proyecto' => 'http://horae.mglab.es/eunomia/projects/'.$project_id
                            ], function ($msj) use ($email, $usuario) {
                                $msj->subject(\Auth::user()->name . ' ha insertado un nuevo comentario');
                                $msj->to($email);
                                $msj->bcc('sistemas@mglab.es');
                            }) == true) {

                        }
                    }
                } else { //Es tarea
                    if ($user != \Auth::user()->id) {
                        if (Mail::send('eunomia.includes.emails.email_comentario_tarea', [
                                'titulo_proyecto' => $task->project->titulo_proyecto,
                                'titulo_tarea' => $task->titulo_tarea,
                                'comentario' => $comentario,
                                'link_proyecto' => 'http://horae.mglab.es/eunomia/projects/'.$project_id,
                                'link_tarea' => 'http://horae.mglab.es/eunomia/tasks/'.$task->id.'/edit'
                            ], function ($msj) use ($email, $usuario) {
                                $msj->subject(\Auth::user()->name . ' ha insertado un nuevo comentario');
                                $msj->to($email);
                                $msj->bcc('sistemas@mglab.es');
                            }) == true) {

                        }
                    }
                }
            }
            //Enviamos email a los usuarios si ha sido citado en el comentario
            //Buscamos los @usuarios del comentario
            $array = explode(' ',str_replace('&nbsp;',' ',$comentario));
            $usuarios = [];
            foreach($array as $v){
                if(preg_match('/^@/i', $v)){
                    array_push($usuarios,strip_tags(str_replace('@','',$v)));
                }
            }
            foreach($usuarios as $user) {
                $usuario = User::where('name',$user)->first();
                if (is_object($usuario)) {
                    $email = $usuario->email;
                    if ($project_id > 0) { //Es proyecto
                        if (Mail::send('eunomia.includes.emails.email_comentario_proyecto', [
                                'titulo_proyecto' => $project->titulo_proyecto,
                                'comentario' => $comentario,
                                'link_proyecto' => 'http://horae.mglab.es/eunomia/projects/'.$project_id
                            ], function ($msj) use ($email, $usuario) {
                                $msj->subject(\Auth::user()->name . ' ha insertado un nuevo comentario');
                                $msj->to($email);
                                $msj->bcc('sistemas@mglab.es');
                            }) == true) {

                        }
                    } else { //Es tarea
                        if (Mail::send('eunomia.includes.emails.email_comentario_tarea', [
                                'titulo_proyecto' => $task->project->titulo_proyecto,
                                'titulo_tarea' => $task->titulo_tarea,
                                'comentario' => $comentario,
                                'link_proyecto' => 'http://horae.mglab.es/eunomia/projects/'.$task->project_id,
                                'link_tarea' => 'http://horae.mglab.es/eunomia/tasks/'.$task->id.'/edit'
                            ], function ($msj) use ($email, $usuario) {
                                $msj->subject(\Auth::user()->name . ' ha insertado un nuevo comentario');
                                $msj->to($email);
                                $msj->bcc('sistemas@mglab.es');
                            }) == true) {

                        }
                    }
                }
            }
        }

        if ($project_id > 0) {
            $project = Project::findOrFail($project_id);
            $comments = $project->comments()->orderBy('date', 'DESC')->get();
        }else {
            $task = Task::findOrFail($task_id);
            $comments = $task->comments()->orderBy('date', 'DESC')->get();
        }
        return view('eunomia.comments.list_comments',compact('comments'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(\Auth::user()->compruebaSeguridad('eliminar-comentario') == false)
            return view('eunomia.mensajes.mensaje_error')->with('msj','..no tiene permisos para acceder a esta sección');
        $comment_id = $request->comment_id;
        $task_id = $request->taskc_id;
        $project_id = $request->projectc_id;
        $tipo_comentario = $request->tipo_comentario;
        $comment = Comment::findOrFail($comment_id);
        if ($project_id > 0) {
            $project = Project::findOrFail($project_id);
            $comment->delete();
            $comments = $project->comments()->orderBy('date', 'DESC')->get();
        }else {
            $task = Task::findOrFail($task_id);
            $comment->delete();
            $comments = $task->comments()->orderBy('date', 'DESC')->get();
        }
        return view('eunomia.comments.list_comments',compact('comments'));
    }

    public function muestraComentariosTarea($task_id){
        $comments = Comment::join('comment_task','comment_id','id')
            ->where('task_id',$task_id)->orderBy('date', 'DESC')->get();

        return view('eunomia.comments.list_comments', compact('comments'));
    }

    public function muestraComentariosProyecto($project_id){
        $comments = Comment::join('comment_project','comment_id','id')
            ->where('project_id',$project_id)->orderBy('date', 'DESC')->get();

        return view('eunomia.comments.list_comments', compact('comments'));
    }
}
