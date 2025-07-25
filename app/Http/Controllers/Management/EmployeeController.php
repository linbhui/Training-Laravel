<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Employee;
use App\Mail\EmployeeCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teamName = Team::select('id', 'name')->get();
        $employees = Employee::with('team')->paginate(10);

        $sort = $request->input('sort');
        $by = $request->input('sortBy');
        if (!empty($sort) && !empty($by)) {
            $query = Employee::with(['team']);
            if ($by === 'name') {
                $query->orderBy('first_name', $sort);
                $employees = $query->paginate(10)->appends(['sort' => $sort, 'sortBy' => 'first_name']);
            } else {
                $query->orderBy($by, $sort);
                $employees = $query->paginate(10)->appends(['sort' => $sort, 'sortBy' => $by]);
            }
        }

        return view('management.contents.list_employee', compact('teamName', 'employees'));
    }

   public function add()
   {
       $teamName = Team::select('id', 'name')->get();
       return view('management.contents.add_employee', compact('teamName'));
   }

   public function add_confirm(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'email' => 'required|email|string|max:128|unique:employees,email',
            'first_name' => 'required|string|max:128',
            'last_name' => 'required|string|max:128',
            'password' => 'required',
            'gender' => 'required|in:1,2',
            'birthday' => 'required|date|before:today',
            'address' => 'required|string|max:256',
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'salary' => 'required|numeric|min:0',
            'position' => 'required|string|max:1',
            'status' => 'required|in:1,2',
            'type_of_work' => 'required|string|max:1'
        ]);

       if ($validator->fails()) {
           return redirect()->back()
               ->withErrors($validator)
               ->withInput();
       }

       $avatarPath = null;
       if ($request->hasFile('avatar')) {
           $avatar = $request->file('avatar');
           $avatarPath = $avatar->store('avatars', 'public');
       }

       $rawPassword = $request->input('password');
       $newEmployee = Employee::create([
           'team_id' => $request->input('team_id'),
           'email' => $request->input('email'),
           'first_name' => $request->input('first_name'),
           'last_name' => $request->input('last_name'),
           'password' => password_hash($request->input('password'), PASSWORD_DEFAULT),
           'gender' => $request->input('gender'),
           'birthday' => $request->input('birthday'),
           'address' => $request->input('address'),
           'avatar' => $avatarPath,
           'salary' => $request->input('salary'),
           'position' => $request->input('position'),
           'status' => $request->input('status'),
           'type_of_work' => $request->input('type_of_work'),
           'ins_id' => session('id')
       ]);

       Mail::to($newEmployee->email)->send(new EmployeeCreated($newEmployee, $rawPassword));

       return redirect()->route('employee.index')->with('notification', 'New employee added successfully!');
   }

   public function edit(string $id)
   {
        $teamName = Team::select('id', 'name')->get();
        $employee = Employee::findOrFail($id);

        return view('management.contents.edit_employee', compact('teamName', 'employee'));
   }

    public function edit_confirm(Request $request)
    {
        $id = $request->input('id');
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'team_id' => 'sometimes|exists:teams,id',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:128',
                Rule::unique('employees')->ignore($id)
            ],
            'first_name' => 'nullable|string|max:128',
            'last_name' => 'nullable|string|max:128',
            'password' => 'nullable|string',
            'gender' => 'nullable|in:1,2',
            'birthday' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:256',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'salary' => 'nullable|numeric|min:0',
            'position' => 'nullable|string|max:1',
            'status' => 'nullable|in:1,2',
            'type_of_work' => 'nullable|string|max:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $edited = $request->only([
            'team_id', 'email', 'first_name', 'last_name', 'gender', 'birthday',
            'address', 'salary', 'position', 'status', 'type_of_work'
        ]);

        foreach ($edited as $field => $value) {
            if ($value !== null && $value !== '') {
                $employee->$field = $value;
            }
        }

        if ($request->filled('password')) {
            $employee->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
        }

        if ($request->hasFile('avatar')) {
            $employee->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $employee->save();
        $employee->upd_id = session('id');

        return redirect()->route('employee.index')->with('notification', 'Employee updated successfully!');
    }

   public function delete(string $id)
   {
        $employee = Employee::findOrFail($id);
        $employee->update([
            'upd_id' => session('id'),
            'upd_datetime' => now(),
            'del_flag' => 1
        ]);

        return redirect()->route('employee.index')->with('notification', 'Deleted employee successfully.');
   }

   public function recover(string $id)
   {
        $employee = Employee::findOrFail($id);
        $employee->update([
            'upd_id' => session('id'),
            'upd_datetime' => now(),
            'del_flag' => 0
        ]);

        return redirect()->route('employee.index')->with('notification', 'Recovered employee successfully.');
   }

   public function search(Request $request)
   {
       $searchBy = $request->input('by');
       $search = $request->input('search');
       $employees = [];
       $result = '';
       $teamName = Team::select('id', 'name')->get();

       if ($searchBy === 'team') {
           $team = Team::findOrFail($search);
           $employees = Employee::where('del_flag', 0)->where('team_id', $team->id)->with('team')->paginate(10);
           $result = "Employees of " . $team->name;
       } else if ($searchBy === 'email') {
           $employees = Employee::where('del_flag', 0)->where('email', $search)->with('team')->paginate(10);
           $result = "Results for: " . $search;
       } else {
           $employees = Employee::where('del_flag', 0)
                   ->where(function ($query) use ($search) {
                       $query->where('last_name', 'LIKE', "%{$search}%")
                           ->orWhere('first_name', 'LIKE', "%{$search}%");
                   })->with('team')->paginate(10);
           $result = "Results for: " . $search;
       }

       return view('management.contents.list_employee',
           compact('employees', 'teamName', 'result'));
   }
}

