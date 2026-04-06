<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingAccount;
use App\Models\Student;
use App\Models\Employee;
use App\Models\User;

class AjaxController extends Controller
{
    public function getAccounts()
    {
        $licenseId = config('app.license_id');

        $accounts = AccountingAccount::where('license_id', $licenseId)
            ->where('is_parent', false)
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get([
                'id',
                'account_code',
                'account_name',
                'person_type'
            ]);

        return response()->json($accounts);
    }

    public function getStudents()
    {
        return response()->json(
            Student::select('id', 'fullname as name')->get()
        );
    }

    public function getEmployees()
    {
        $employees = Employee::join('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.id', 'users.fullname as name')
            ->get();

        return response()->json($employees);
    }

    public function getLicenseholders()
    {
        return response()->json(
            User::select('id', 'fullname')->get()
        );
    }
}