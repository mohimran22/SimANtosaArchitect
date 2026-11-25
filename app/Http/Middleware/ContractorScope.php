<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kunci akses role Mitra Kontraktor: DEFAULT TOLAK.
 * Role lain tidak terpengaruh.
 */
class ContractorScope
{
    /** Boleh untuk semua method. */
    private const ALLOWED = [
        'dashboard',
        'switch-role',
        'logout',
        'profile',
        'customer/profile',
        'notifications/*',
        'verify-email*',
        'email/*',
        'confirm-password',
        'password',
        'api/cities/*',
        'api/districts/*',
        'api/sub_districts/*',
        'api/postal_codes/*',
        'api/banks',
    ];

    /**
     * Endpoint kerja kontraktor, per method HTTP.
     * Kepemilikan proyek dicek di middleware ini bila request membawa proyek
     * (route {project} atau input project_id), dan SELALU dicek lagi di controller
     * (trait AuthorizesContractorProject), terutama untuk route yang hanya membawa
     * ID anak seperti daily-work/{id}. Jangan menambah route ke sini tanpa pengecekan
     * di controllernya.
     */
    private const WORK_ROUTES = [
        'GET' => [
            'projects',
            'projects/create',
            'projects/*/continue',
            'projects/*/build-process-partial',
            'daily/*/detail',
            'reports/next-date/*',
        ],
        'POST' => [
            'build-weekly/update',
            'projects/*/weekly-report',
            'build-daily/store',
            'daily/*/update-all',
        ],
        'DELETE' => [
            'daily-work/*',
            'daily-worker/*',
            'daily-material/*',
            'daily/documentation/*',
        ],
    ];

    /**
     * Halaman data kontraktor: hanya milik sendiri. Dicocokkan per NAMA route
     * (bukan wildcard path) supaya contractors/create dan contractors.destroy
     * tidak ikut terbuka. contractors.index sudah memfilter ke data sendiri
     * di controller bila role tidak punya izin 'lihat daftar kontraktor'.
     */
    private const OWN_CONTRACTOR_ROUTES = [
        'contractors.index',
        'contractors.show',
        'contractors.edit',
        'contractors.update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isContractorContext()) {
            return $next($request);
        }

        $path   = trim($request->path(), '/');
        $method = $request->isMethod('HEAD') ? 'GET' : $request->method();

        if (Str::is(self::ALLOWED, $path)) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (in_array($routeName, self::OWN_CONTRACTOR_ROUTES, true)) {

            if ($routeName !== 'contractors.index' && ! $this->isOwnContractor($request, $user)) {
                abort(403, 'Anda hanya dapat mengakses data kontraktor milik sendiri.');
            }

            return $next($request);
        }

        if (Str::is(self::WORK_ROUTES[$method] ?? [], $path)) {

            $projectId = $this->requestedProjectId($request);

            if ($projectId !== null && ! $this->isAssigned($projectId, $user)) {
                abort(403, 'Proyek ini tidak ditugaskan kepada Anda.');
            }

            return $next($request);
        }

        abort(403, 'Akses tidak tersedia untuk role Mitra Kontraktor.');
    }

    private function requestedProjectId(Request $request): ?string
    {
        $project = $request->route('project');

        if ($project instanceof Model) {
            return (string) $project->getKey();
        }

        if (is_string($project) && $project !== '') {
            return $project;
        }

        $input = $request->input('project_id');

        return is_string($input) && $input !== '' ? $input : null;
    }

    private function isOwnContractor(Request $request, $user): bool
    {
        $param = $request->route('contractor');

        $id = $param instanceof Model ? $param->getKey() : $param;

        $ownId = $user->contractor?->id;

        return $id !== null && $ownId !== null && (string) $id === (string) $ownId;
    }

    private function isAssigned(string $projectId, $user): bool
    {
        $project = Project::find($projectId);

        return $project && $project->isAssignedToContractorUser($user);
    }
}