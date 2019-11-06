<?php

namespace App\Http\Controllers;

use App\Permissions;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $response = $this->isNotSuperAdmin();
        if ($response) return $response;

        return User::paginate(10);
    }

    public function me()
    {
        return auth()->user();
    }

    public function store(Request $request)
    {
        $response = $this->isNotSuperAdmin();
        if ($response) return $response;
        // @TODO: add validations

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

    public function update(Request $request)
    {

        // is superadmin  -  user_id
        // true           - true
        // true           - false
        // false          - true  --> Only non feasible case
        // false          - false
        // user_id is sent in case of admin only, Otherwise no user_id is sent
        $notSuperAdminResponse = $this->isNotSuperAdmin();
        if ($notSuperAdminResponse && $request->user_id) return $notSuperAdminResponse;

        $userId = $request->user_id ?: auth()->id();

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = bcrypt('123456');

        $user->save();
    }

    public function updatePermissions(Request $request)
    {
        $response = $this->isNotSuperAdmin();
        if ($response) return $response;

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }

        $user->permissions = json_encode($request->permissions);
        $user->save();
    }

    public function listPermissions()
    {
        $response = $this->isNotSuperAdmin();
        if ($response) return $response;

        return Permissions::all();
    }

    private function isNotSuperAdmin()
    {
        if (!auth()->user()->is_super_admin) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
