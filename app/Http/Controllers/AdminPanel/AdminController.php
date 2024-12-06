<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function stats()
    {
        $activeUsersCount = User::where('delete_flag', 0)->count();
        $activeRolesCount = Role::where('delete_flag', 0)->count();

        return view('admin.home', compact('activeUsersCount', 'activeRolesCount'));
    }

    public function getUsers()
    {
        $users = User::leftJoin('roles', 'users.role', '=', 'roles.role_id')
            ->select(
                'users.user_id',
                DB::raw('CONCAT(users.firstname, \' \', users.lastname) AS fullname'),
                'users.email',
                DB::raw('CONVERT(varchar, users.created_at, 106) as created_at'),
                'users.type',
                'roles.role_name'
            )
            ->where('users.delete_flag', 0)
            ->get();

        return view('admin.users', compact('users'));
    }

    public function passwordForm()
    {
        return view('admin.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current-password' => 'required',
            'new-password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $user = Auth::user();

        if (!Hash::check($request->input('current-password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is wrong.'
            ]);
        }

        if (Hash::check($request->input('new-password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'New password cannot be same as current password.'
            ]);
        }

        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        // Auth::logoutOtherDevices($request->input('current-password'));

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit-user-id' => 'required|exists:users,user_id',
            'user-password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            $user = User::where('delete_flag', 0)->findOrFail($request->input('edit-user-id'));

            $user->password = Hash::make($request->input('user-password'));
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User password updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user password.' . $e->getMessage(),
            ]);
        }
    }

    public function createUserForm()
    {
        $roles = Role::active()->get();
        return view('admin.create-user', compact('roles'));
    }

    public function createUser(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email-address' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $user = new User();
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email-address');
            $user->password = Hash::make($request->input('password'));

            if ($type === 'role') {
                $validator = Validator::make($request->all(), [
                    'user-role' => 'required|exists:roles,role_id',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ]);
                }

                $user->role = $request->input('user-role');
            } elseif ($type === 'admin') {
                $user->type = 1;
            } else {
                $user->type = 2;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user.' . e($e->getMessage())
            ]);
        }
    }

    public function deleteUser($id) {}

    public function getRoles()
    {
        $roles = Role::active()->get();
        return view('admin.roles', compact('roles'));
    }

    public function roleForm()
    {
        $companies = DB::table('companies')
            ->select('company_id', 'company_name')
            ->where('delete_flag', 0)
            ->orderBy('company_name', 'asc')
            ->get();
        return view('admin.create-role', compact('companies'));
    }


    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role-name' => 'required|string|max:150|unique:roles,role_name',
            'inventory' => 'required|array',
            'inventory.*' => 'in:1,2,3,4,5,6,7',
            'department' => 'required|array',
            'department.*' => 'in:FR,DC,INS',
            'permissions' => 'required|array',
            'permissions.*' => 'array',
            'permissions.*.*' => 'in:read,write',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        DB::beginTransaction();

        try {
            $role = new Role();
            $role->role_name = $request->input('role-name');
            $role->inventory = implode(',', $request->input('inventory'));
            $role->save();

            foreach ($request->input('permissions') as $department => $perms) {
                foreach ($perms as $perm) {
                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $role->role_id;
                    $rolePermission->department = $department;
                    $rolePermission->permission = $perm;
                    $rolePermission->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the role: ' . $e->getMessage()
            ]);
        }
    }

    public function updateRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:150|unique:roles,role_name,' . $id . ',role_id',
            'inventory' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = Role::findOrFail($id);
        $role->update([
            'role_name' => $request->role_name,
            'inventory' => $request->inventory,
        ]);

        return redirect()->route('admin.roles')->with('success', 'Role updated successfully');
    }


    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete_flag = 1;
        $role->save();

        return redirect()->route('admin.roles')->with('success', 'Role deleted successfully');
    }
}
