<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Project;

/**
 * Pengecekan kepemilikan proyek untuk role Mitra Kontraktor.
 * Untuk role lain semua method di sini tidak berbuat apa-apa.
 */
trait AuthorizesContractorProject
{
    protected function isContractor(): bool
    {
        return auth()->user()?->isContractorContext() ?? false;
    }

    /**
     * Kontraktor hanya boleh menyentuh proyek Build yang ditugaskan kepadanya.
     * $project boleh Project, id proyek, atau null (null = ditolak untuk kontraktor).
     */
    protected function assertProjectAccess(Project|string|null $project): void
    {
        $user = auth()->user();

        if (! $user || ! $user->isContractorContext()) {
            return;
        }

        if (! $project instanceof Project) {
            $project = $project ? Project::find($project) : null;
        }

        abort_unless($project && $project->isAssignedToContractorUser($user), 403);
    }

    /**
     * Atribut harga yang tidak boleh ikut ke JSON untuk kontraktor.
     * Sesuaikan dengan nama kolom harga di tabel terkait bila berbeda.
     */
    protected function contractorHiddenAttributes(): array
    {
        return ['price', 'total', 'harga', 'harga_satuan', 'jumlah_harga', 'subtotal', 'unit_price'];
    }
}