<?php

namespace App\Http\Controllers\Web;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class TodoController extends Controller {
    public function index() {
        $todos = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        Todo::create([
            'name' => $request->name,
            'status' => 0, 
        ]);
    
        return redirect()->route('todos.index');
    }
    



    public function destroy(Todo $todo) {
        $todo->delete();
        return redirect()->route('todos.index');
    }
}
