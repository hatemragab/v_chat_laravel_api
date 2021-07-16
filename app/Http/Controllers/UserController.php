<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        return User::all();
    }


    public function store(Request $request)
    {









    }


    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            $output['success'] = true;
            $output['data'] = $user;
            return $output;
        } else {
            $output['success'] = false;
            $output['data'] = "not found";
            return response()->json($output, 404);
        }
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return $user;
    }


    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function search($name)
    {
        return User::where('name', 'like', '%' . $name . '%')->get();
    }
}
