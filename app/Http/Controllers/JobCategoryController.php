<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\JobCategory;
use App\Models\LaborCost;
use App\Models\Equipment;
use App\Models\Product;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use DB;

class JobCategoryController extends Controller
{
    public function index()
    {
        $jobs = JobCategory::orderBy('kode_urut')->get();
        return view('job-categories.index', compact('jobs'));
    }
    
public function create()
{
    $groups = JobCategory::select('nama_group')
        ->distinct()
        ->orderBy('nama_group')
        ->pluck('nama_group');

    return view('job-categories.create', compact('groups'));
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'bidang' => 'required|string|max:50',
            'kode_group' => 'required|string|max:50',
            'nama_group' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'kode_urut' => 'required|string|max:100|unique:job_categories,kode_urut',
            'nama_pekerjaan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        JobCategory::create($data);

        return redirect()
            ->route('job-categories.index')
            ->with('success', 'Data pekerjaan berhasil ditambahkan.');
    }

public function edit(JobCategory $jobCategory)
{
$groups = JobCategory::select('bidang','nama_group')
    ->distinct()
    ->orderBy('bidang')
    ->get()
    ->groupBy('bidang');
        // $products = Product::all();
        // $laborcosts = LaborCost::all();
        // $equipments = EquipmentCost::all();

    return view(
        'job-categories.edit',
        compact('jobCategory', 'groups')
    );
}


    public function update(Request $request, JobCategory $jobCategory)
    {
        $data = $request->validate([
            'bidang' => 'required|string|max:50',
            'kode_group' => 'required|string|max:50',
            'nama_group' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'kode_urut' => 'required|string|max:100|unique:job_categories,kode_urut,' . $jobCategory->id,
            'nama_pekerjaan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        $jobCategory->update($data);

        return back()->with('success', 'Data pekerjaan berhasil diperbarui.');
    }

    public function destroy(JobCategory $jobCategory)
    {
        $jobCategory->delete();

        return redirect()
            ->route('job-categories.index')
            ->with('success', 'Data pekerjaan berhasil dihapus.');
    }
}