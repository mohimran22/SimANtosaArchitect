<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Project;
use App\Models\TechnicalJustification;
use App\Models\TechnicalJustificationItem;
use App\Models\TechnicalJustificationCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class JustekController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => [
                'required',
                'uuid',
                'exists:projects,id',
            ],
            'contact_name' => [
                'required',
                'string',
                'max:255',
            ],
            'offer_date' => [
                'required',
                'date',
            ],
            'job_duration' => [
                'nullable',
                'string',
                'max:255',
            ],

            'discount' => [
                'nullable',
                'numeric',
            ],

            'tax_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'shipping' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'profit' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'overhead' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'analisa_version' => [
                'nullable',
                'integer',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.floor_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'items.*.category_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'items.*.job_name' => [
                'required',
                'string',
                'max:255',
            ],

            'items.*.description' => [
                'nullable',
                'string',
            ],

            'items.*.volume' => [
                'required',
                'numeric',
            ],

            'items.*.satuan' => [
                'required',
                'string',
                'max:255',
            ],

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.total' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.profit' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'items.*.overhead' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'items.*.base_price' => [
                'nullable',
                'numeric',
            ],

            'items.*.volume1' => [
                'nullable',
                'numeric',
            ],

            'items.*.volume2' => [
                'nullable',
                'numeric',
            ],
        ]);
        $project = Project::findOrFail(
            $validated['project_id']
        );
        return DB::transaction(function () use ($validated, $project) {

            $offerDate = Carbon::parse($validated['offer_date']);

            $number = $this->generateJustekNumber($offerDate);

            $justek = TechnicalJustification::create([
                'project_id' => $project->id,

                'justek_sequence' => $number['sequence'],
                'justek_number' => $number['number'],
                'justek_period' => $number['period'],

                'offer_date' => $offerDate->toDateString(),

                'contact_name' => $validated['contact_name'],
                'job_duration' => $validated['job_duration'] ?? null,

                'discount' => $validated['discount'] ?? 0,

                'tax_rate' => $validated['tax_rate'] ?? 0,

                'shipping' => $validated['shipping'] ?? 0,

                'profit' => $validated['profit'] ?? 0,
                'overhead' => $validated['overhead'] ?? 0,

                'notes' => $validated['notes'] ?? null,

                'analisa_version' =>
                    $validated['analisa_version'] ?? null,

                'created_by' => Auth::id(),
            ]);

            foreach ($validated['items'] as $index => $item) {

                $justek->items()->create([
                    'floor_name' => $item['floor_name'] ?? null,
                    'category_name' => $item['category_name'] ?? null,
                    'job_name' => $item['job_name'],
                    'description' => $item['description'] ?? null,
                    'volume' => $item['volume'],
                    'satuan' => $item['satuan'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'profit' => $item['profit'] ?? 0,
                    'overhead' => $item['overhead'] ?? 0,
                    'base_price' => $item['base_price'] ?? null,
                    'volume1' => $item['volume1'] ?? null,
                    'volume2' => $item['volume2'] ?? null,
                    'is_draft' => false,
                    'order_no' => $index + 1,
                ]);
            }

            $subtotal = $justek->items()->sum('total');
            $discount = (float) $justek->discount;
            $subtotalAfterDiscount = $subtotal - $discount;
            $taxTotal = $subtotalAfterDiscount * ((float) $justek->tax_rate / 100);
            $grandTotal = $subtotalAfterDiscount + $taxTotal + (float) $justek->shipping;

            $justek->update([
                'subtotal' => $subtotal,
                'subtotal_after_discount' => $subtotalAfterDiscount,
                'tax_total' => $taxTotal,
                'grand_total' => $grandTotal,
                'base_subtotal' => $subtotal,
                'updated_by' => Auth::id(),
            ]);

            return redirect()
                ->route('projects.create', [
                    'project_id' => $justek->project_id,
                    'step' => 9,
                    'justek_id' => $justek->id,
                ])
                ->with(
                    'success',
                    "Justifikasi Teknis {$justek->justek_number} berhasil dibuat."
                );
        });
    }
    private function generateJustekNumber(Carbon $offerDate): array
    {
        $period = $offerDate->format('Y-m');

        DB::statement(
            '
            INSERT INTO technical_justification_counters
                (period, last_sequence, created_at, updated_at)
            VALUES
                (?, 0, NOW(), NOW())
            ON CONFLICT (period)
            DO NOTHING
            ',
            [$period]
        );

        $counter = TechnicalJustificationCounter::where(
            'period',
            $period
        )
            ->lockForUpdate()
            ->firstOrFail();

        $counter->increment('last_sequence');

        $sequence = $counter->fresh()->last_sequence;

        $romanMonth = GeneralHelper::bulanRomawi(
            $offerDate->month
        );

        $justekNumber = sprintf(
            'PH/BLD/%d/%s/%03d',
            $offerDate->year,
            $romanMonth,
            $sequence
        );

        return [
            'period' => $period,
            'sequence' => $sequence,
            'number' => $justekNumber,
        ];
    }
public function pdf(TechnicalJustification $technicalJustification)
{
    $technicalJustification->load([
        'project',
        'items',
    ]);

    $items = $technicalJustification->items->sortBy('order_no')->values();

    $grouped = $items
        ->groupBy('floor_name')
        ->map(function ($floorItems) {
            return $floorItems
                ->groupBy('category_name')
                ->map(function ($categoryItems) {
                    return [
                        'items'    => $categoryItems->values(),
                        'subtotal' => $categoryItems->sum('total'),
                    ];
                });
        });

    $pdf = Pdf::loadView(
        'justek.pdf',
        compact('technicalJustification', 'grouped')
    );

    $pdf->setPaper('A4', 'portrait');

    return $pdf->stream(
        $technicalJustification->contact_name . '.pdf'
    );
}
public function justekDetail($id)
{
    $technicalJustification = TechnicalJustification::with('items')->findOrFail($id);

    // Kirim juga $ReadOnly kalau partial detailnya butuh (misal true kalau cuma view dari modal)
    return view('projects.partials.justek-detail-content', [
        'technicalJustification' => $technicalJustification,
        'ReadOnly' => true, // opsional, biar modal cuma nampilin, gak ada info dibuat/diubah oleh
    ])->render();
}
}