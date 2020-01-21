<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Validator;

class TaskController extends Controller
{
    public function getAll(Request $request){
        $hash = $request->header('Auth', null);

        $jwtauth = new JwtAuth();
        $checkToken = $jwtauth->checkToken($hash);

        if ($checkToken){
            $tasks = Task::all();
            return $tasks;
        }else{
            return $response = [
                'succes' => false,
                'mesage' => 'Error de autenticacion'
            ];
        }
    }

    public function createTask(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()){
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }

        $input = $request->all();
        //$input['password'] = bcrypt($input['password']);
        $task = Task::create($input);

        return $response = [
            'success' => true,
            'message' => 'task created successfully',
            'status' => 200,
            'task' => $task
        ];
    }

    public function getByID($id){
        $task = Task::find($id);

        if (is_null($task)) {
            return $response = [
                'status' => 400,
                'message' => 'Task not found'
            ];
        }

        return $response = [
            'status' => 200,
            'mesage' => 'task found correctly',
            'task' => $task
        ];

    }

    public function updateTask(Request $request, $id){
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()){
            return $response = [
                'success' => false,
                'status' => 400,
                'message' => $validator->errors()
            ];
        }

        $task = Task::find($id);

        if (is_null($task)) {
            return $response = [
                'success' => false,
                'status' => 400,
                'message' => 'Task not found'
            ];
        }

        $task->name = $input['name'];
        $task->detail = $input['detail'];
        $task->save();

        return $response = [
            'success' => true,
            'message' => 'task updated successfully',
            'status' => 200,
            'task' => $task
        ];

    }

    public function destroyTask($id){
        $task = Task::find($id);

        if (is_null($task)) {
            return $response = [
                'success' => false,
                'status' => 400,
                'message' => 'Task not found'
            ];
        }

        $task->delete();

        return $response = [
            'success' => true,
            'status' => 201,
            'message' => 'task deleted successfully'
        ];
    }


}
