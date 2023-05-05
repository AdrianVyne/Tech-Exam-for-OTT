<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'phone_number' => 'required',
            'email' => 'required|email|unique:users',
            'profile_picture' => 'nullable|image|max:1024',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->phone_number = $request->input('phone_number');
        $user->email = $request->input('email');

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile_pictures'), $fileName);
            $user->profile_picture = 'profile_pictures/' . $fileName;
        }

        // Calculate age
        $dob = Carbon::parse($user->date_of_birth);
        $user->age = $dob->age;

        $user->save();

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->date_of_birth = $request->date_of_birth;
        $user->phone_number = $request->phone_number;
        $user->save();

        return response()->json([
            'name' => $user->name,
            'date_of_birth' => $user->date_of_birth,
            'phone_number' => $user->phone_number
        ]);
    }




    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }



}