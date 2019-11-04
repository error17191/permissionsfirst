<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        // @TODO: add validations

        if (!auth()->user()->is_super_admin) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = bcrypt('123456');
        $user->permissions = '[]';
        $user->save();

        return response()->json([
            'user_id' => $user->id
        ]);
    }

    public function updatePermissions(Request $request)
    {
        $user = User::find($request->user_id);
        if(!$user){
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }

        $user->permissions = json_encode($request->permissions);
        $user->save();
    }
}
