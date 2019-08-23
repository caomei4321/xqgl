<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(User $user)
    {
        $users = $user->paginate(15);
        return view('admin.user.index', compact('users'));
    }

    public function create(User $user)
    {
        return view('admin.user.create_and_edit', compact('user'));
    }


    public function store(Request $request, User $user)
    {
        $data = $request->only(['name', 'phone', 'password', 'age', 'duty', 'from']);
        $data['password'] = Hash::make($data['password']);
        $user->fill($data);
        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function show($id)
    {
        //
    }


    public function edit(User $user)
    {
        return view('admin.user.create_and_edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {

        if (Hash::check($request->password,$user->password)) {
            $user->update($request->only(['phone', 'name', 'age', 'duty', 'from']));
        } else {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'duty' => $request->duty,
                'from' => $request->from,
            ]);
        }
        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
