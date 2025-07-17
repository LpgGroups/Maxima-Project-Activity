<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardDevController extends Controller
{
    public function index()
    {
        return view('dashboard.dev.index', [
            'title' => 'Dewa Dev',
        ]);
    }

    public function allUser()
    {
        $users = User::all();

        return view('dashboard.dev.alluser.tableuser', [
            'title' => 'Management User',
            'users' => $users
        ]);
    }
    public function editUser($id)
    {
        $users = User::findOrFail($id);
        return view('dashboard.dev.alluser.edituser', [
            'title' => 'Edit User By Dev',
            'users' => $users
        ]);
    }
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard.dev.alluser')->with('success', 'User berhasil dihapus.');
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'phone' => "nullable|numeric|digits_between:10,13|unique:users,phone,{$id}",
            'company' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin,management,dev',
            'is_active' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('dashboard.dev.edit', ['id' => $user->id])->with('success', 'Data user berhasil diperbarui.');
    }
}
