<form id="form_ins_comment">
    <div class="form-group">
        <label for="comentario">Nuevo Comentario</label>
        <textarea name="comentario" id="comentario" class="form-control" rows="3"></textarea>
    </div>
    <input type="hidden" name="userc_id" id="userc_id" value="{{ Auth::user()->id }}">
    <input type="hidden" name="projectc_id" id="projectc_id" value="{{ isset($project) && is_object($project) ? $project->id : (isset($task) && is_object($task) && isset($task->project) ? $task->project->id : '') }}">
    <input type="hidden" name="taskc_id" id="taskc_id" value="{{ isset($task) && is_object($task) ? $task->id : '' }}">
    <input type="hidden" name="comment_id" id="comment_id">
    <div class="mb-3">
        <button type="button" id="boton_comentarios" class="btn btn-primary">
            <i class="fas fa-comment"></i> Insertar Comentario
        </button>
    </div>
</form>