// public function show(Project $project)
// {
//     $project->load([
//         'customer.user',
//         'employee.user',
//         'affiliator.user',
//         'levels',
//         'consultation.items',
//         'planning',
//         'survey.items'
//     ]);

//     $currentLevel = $project->levels()
//         ->where('is_completed', false)
//         ->orderBy('level_order')
//         ->first();

//     $consultation = $project->consultation->first();
//     $planning = $project->planning->first();

//     return view('projects.show', compact(
//         'project',
//         'currentLevel',
//         'consultation',
//         'planning'
//     ));
// }

//     public function edit(Project $project)
// {
//     $project->load([
//         'customer.user',
//         'employee.user',
//         'affiliator.user',
//         'levels',
//         'consultation.items',
//         'survey.items',
//         'designs',
//         'rabs',
//         'spks',
//     ]);

//     $consultation = $project->consultation->first();
//     $survey       = $project->survey->first();
//     $design       = $project->designs->first();
//     $rab          = $project->rabs->first();
//     $spk          = $project->spks->first();

//     $employees = Employee::with('user')->get();
//     $customers = Customer::with('user')->get();
//     $provinces = Province::all();


//     return view('projects.edit', compact(
//         'project',
//         'consultation',
//         'survey',
//         'design',
//         'rab',
//         'customers',
//         'employees',
//         'provinces', 'cities', 'districts', 'subDistricts', 'postalCodes'
//     ));
// }

//     public function update(ProjectRequest $request, Project $project)
// {
//     DB::transaction(function() use ($request, $project) {

//         // UPDATE PROJECT
//         $project->update($request->validated());

//         // === JIKA ADA KONSULTASI, UPDATE ===
//         if ($project->consultation->isNotEmpty()) {

//             $consultation = $project->consultation->first();

//             $consultation->update([
//                 'contact_name'      => $request->contact_name,
//                 'contact_phone'     => $request->contact_phone,
//                 'site_area'         => $request->site_area,
//                 'building_area'     => $request->building_area,
//                 'notes'             => $request->notes,
//                 'employee_id'       => $request->employee_id,
//             ]);

//             // UPDATE ITEMS
//             $consultation->items()->delete();
//             foreach ($request->items as $i => $item) {
//                 $consultation->items()->create([
//                     'order_no'    => $i + 1,
//                     'description' => $item['description'],
//                     'remark'      => $item['remark'] ?? null,
//                 ]);
//             }
//         }

//         // Tahap lain nanti bisa dilanjutkan di sini...
//     });

//     return redirect()
//             ->route('projects.show', $project->id)
//             ->with('success', 'Produk berhasil diperbarui.');
// }


                // ['level_order' => 7, 'level_name' => 'Form SPK Desain Denah'],
                // ['level_order' => 8, 'level_name' => 'Pengerjaan Desain Denah'],
                // ['level_order' => 9, 'level_name' => 'Revisi Desain Denah'],
                // ['level_order' => 10, 'level_name' => 'Form SPK 3D'],
                // ['level_order' => 11, 'level_name' => 'Pengerjaan 3D'],
                // ['level_order' => 12, 'level_name' => 'Revisi 3D'],
                // ['level_order' => 13, 'level_name' => 'Form SPK DED'],
                // ['level_order' => 14, 'level_name' => 'Pengerjaan DED'],
                // ['level_order' => 15, 'level_name' => 'Revisi DED'],
                // ['level_order' => 16, 'level_name' => 'Form SPK RAB'],
                
                // ['level_order' => 8, 'level_name' => 'Pengerjaan RAB'],
                // ['level_order' => 9, 'level_name' => 'Revisi RAB'],