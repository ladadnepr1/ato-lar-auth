<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\Http\Requests;
use App\Repositories\TaskRepository;

class TaskController extends Controller {

    /**
     * Экземпляр TaskRepository.
     *
     * @var TaskRepository
     */
    protected $tasks;

    public function __construct(TaskRepository $tasks) {
	$this->middleware('auth'); // Проверять авторизацию при доступе
	$this->tasks = $tasks;
    }

    /**
     * Показать список всех задач пользователя.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request) {
	//$tasks = $request->user()->tasks()->get();
	//для версии 5.1
	//$tasks = Task::where('user_id', $request->user()->id)->get();

	return view('tasks.index', [
	    'tasks' => $this->tasks->forUser($request->user()),
	]);
    }

    /**
     * Создание новой задачи.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
	$this->validate($request, [
	    'name' => 'required|max:255',
	]);

	$request->user()->tasks()->create([
	    'name' => $request->name,
	]);

	return redirect('/tasks');
    }

    public function destroy(Requests $request, Task $task) {
	$this->authorize('destroy', $task);
	$task->delete();

	return redirect('/tasks');
    }

}
