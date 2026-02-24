<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Fichaje;
use App\ImageSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Customer;
use App\Project;
use App\Task;
use Carbon\Carbon;
use App\HolidayDay;
use App\PartyDay;
use App\TaskState;
use DB;
use App\ProjectState;
use DateTime;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $yo = Auth::user();
        $users = User::all();
        $usuarios = User::where('empresa_id', '1')->orderBy('created_at','ASC')->get();
        $customers = Customer::orderBy('nombre_cliente')->get();
        $projects = Project::where('role_id','=',Auth::user()->role_id)->get();
        $tasks = Task::where('role_id','=',Auth::user()->role_id)->where('estado_tarea','!=',4)->get();

        $fechadehoy = Carbon::now('Europe/Madrid');
        $fechayesterday = Carbon::yesterday('Europe/Madrid');

        $estasemana = new Carbon('next sunday');
        $estemes = new Carbon('next month');

        if (!Auth::user()->isRole('cliente')) {
            $tareassemana = $yo->tasks()->with(['project.customer'])->where('tasks.role_id', '=', Auth::user()->role_id)
                ->where('fechaentrega_tarea', '<=', $estasemana)->where('estado_tarea', '!=', 4)->orderBy('fechaentrega_tarea')->get();
        } else {
            $tareassemana = Task::with(['project.customer'])->join('projects', 'project_id', 'projects.id')
                ->where('customer_id', Auth::user()->customer->id)
                ->where('fechaentrega_tarea', '<=', $estasemana)->where('estado_tarea', '!=', 4)->orderBy('fechaentrega_tarea')->get();
        }

        if (!Auth::user()->isRole('cliente')) {
            $tareasmes = $yo->tasks()->with(['project.customer'])->where('tasks.role_id', '=', Auth::user()->role_id)
                ->where('fechaentrega_tarea', '<=', $estemes)->where('fechaentrega_tarea', '>', $estasemana)->where('estado_tarea', '!=', 4)->orderBy('fechaentrega_tarea')->get();
        } else {
            $tareasmes = Task::with(['project.customer'])->join('projects', 'project_id', 'projects.id')
                ->where('customer_id', Auth::user()->customer->id)
                ->where('fechaentrega_tarea', '<=', $estemes)->where('fechaentrega_tarea', '>', $estasemana)->where('estado_tarea', '!=', 4)->orderBy('fechaentrega_tarea')->get();
        }

        if (!Auth::user()->isRole('cliente')) {
            $tareasparamastarde = $yo->tasks()->with(['project.customer'])->where('tasks.role_id', '=', Auth::user()->role_id)
                ->where('fechaentrega_tarea', '>', $estemes)->where('estado_tarea', '!=', 4)->orderBy('fechaentrega_tarea')->get();
        } else {
            $tareasparamastarde = Task::with(['project.customer'])->join('projects', 'project_id', 'projects.id')
                ->where('customer_id', Auth::user()->customer->id)
                ->where('fechaentrega_tarea', '>', $estemes)->where('estado_tarea', '!=', 4)->orderBy('fechaentrega_tarea')->get();
        }

        $tareascalendario = $yo->tasks()->with(['project.customer'])->where('estado_tarea','!=',4)->get();
        $todotasks = $yo->todotasks()->orderBy('orden')->get();

        $array_roles = [1,2];
        $holiday_days = HolidayDay::join('users','user_id','users.id')
            ->whereIn('role_id',$array_roles)->get();

        $party_days = PartyDay::all();

        $today = Carbon::createFromDate(date('Y'),date('m'),date('d'));

        $holidays = HolidayDay::distinct()->select('name')
            ->join('users','user_id','users.id')
            ->where('date','>',$today->format('Y-m-d'))
            ->where('date','<=',$today->endOfWeek()->addDays(15)->format('Y-m-d'))->groupBy('name')->get()->pluck('name');

        //Charts
        $values = DB::table('tasks')
            ->select('estado_tarea', DB::raw('count(*) as total'))
            ->where('estado_tarea','!=',4)
            ->groupBy('estado_tarea')
            ->pluck('total');
        $labels = TaskState::select('state')->where('id','!=',4)->pluck('state');
        $backgroundColors = TaskState::select('color_code')->where('id','!=',4)->pluck('color_code');

        $valuesP = DB::table('projects')
            ->select('estado_proyecto', DB::raw('count(*) as total'))
            ->where('estado_proyecto','!=',4)
            ->groupBy('estado_proyecto')
            ->pluck('total');
        $labelsP = ProjectState::select('state')->where('id','!=',4)->pluck('state');
        $backgroundColorsP = ProjectState::select('color_code')->where('id','!=',4)->pluck('color_code');

        $fichaje = Fichaje::where('user_id',\Auth::user()->id)->orderBy('fecha')->get()->last();

        if(is_object($fichaje))
            $ultimo_estado_fichaje = $fichaje->tipo;
        else
            $ultimo_estado_fichaje = 'salida';

        $semana_actual = $this->inicio_fin_semana(date('Y-m-d H:i:s'));

        return view('eunomia.dashboard', compact('users', 'customers', 'projects', 'tasks', 'yo', 'fechadehoy', 'estasemana', 'tareassemana', 'tareasparamastarde', 'tareascalendario', 'fechayesterday','todotasks','tareasmes','estemes','holiday_days','party_days','holidays','backgroundColors','labels','values','backgroundColorsP','labelsP','valuesP','ultimo_estado_fichaje','semana_actual','usuarios'));
    }

    public function reordenaTabla(Request $request){
        if ($request->newPosition > $request->oldPosition) {
            switch ($request->tabla){
                case 'images_slide':
                    ImageSlide::where('orden','<=',$request->newPosition)
                        ->where('orden','>',$request->oldPosition)
                        ->where('slide_id',$request->slide_id)
                        ->decrement('orden');
                    ImageSlide::where('id',$request->id)
                        ->update(['orden' => $request->newPosition]);
                    break;
            }
        } else {
            switch ($request->tabla){
                case 'images_slide':
                    ImageSlide::where('orden','>=',$request->newPosition)
                        ->where('orden','<',$request->oldPosition)
                        ->where('slide_id',$request->slide_id)
                        ->increment('orden');
                    ImageSlide::where('id',$request->id)
                        ->update(['orden' => $request->newPosition]);
                    break;
            }
        }
    }

    public function cargaComentarios(Request $request){
        $fecha_inicio = Carbon::createFromFormat('Y-m-d',date('Y-m-d'))->subMonth();
        $fecha_fin = Carbon::createFromFormat('Y-m-d',date('Y-m-d'));
        $comments = Comment::whereBetween('date',[$fecha_inicio,$fecha_fin])->orderBy('date')->get();

        return view('eunomia.includes.comentarios', compact('comments'));
    }

    private function inicio_fin_semana($fecha){

        $diaInicio="Monday";
        $diaFin="Friday";

        $strFecha = strtotime($fecha);

        $fechaInicio = date('Y-m-d H:i:s',strtotime('last '.$diaInicio,$strFecha));
        $fechaFin = date('Y-m-d H:i:s',strtotime('next '.$diaFin,$strFecha));

        if(date("l",$strFecha)==$diaInicio){
            $fechaInicio= date("Y-m-d H:i:s",$strFecha);
        }
        if(date("l",$strFecha)==$diaFin){
            $fechaFin= date("Y-m-d H:i:s",$strFecha);
        }
        return ["fechaInicio"=>Carbon::createFromFormat('Y-m-d H:i:s',$fechaInicio)->format('d/m/Y'),"fechaFin"=>Carbon::createFromFormat('Y-m-d H:i:s',$fechaFin)->format('d/m/Y')];
    }
}
