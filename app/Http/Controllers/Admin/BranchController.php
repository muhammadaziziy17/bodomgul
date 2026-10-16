<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    use RespondsToAdminRequests;

    public function index(Request $request): View
    {
        $branches = Branch::query()
            ->with('translations')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view($this->wantsFragment($request) ? 'admin.branches._table' : 'admin.branches.index', [
            'branches' => $branches,
        ]);
    }

    public function create(Request $request): View
    {
        return view($this->wantsPanel($request) ? 'admin.branches._form' : 'admin.branches.form', [
            'branch' => new Branch([
                'is_active' => true,
                'sort_order' => (Branch::query()->max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(BranchRequest $request): RedirectResponse|JsonResponse
    {
        $branch = new Branch;
        $branch->fill($this->baseData($request));
        $branch->save();

        $branch->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.branches.index'), 'Filial qo‘shildi.');
    }

    public function edit(Request $request, Branch $branch): View
    {
        $branch->load('translations');

        return view($this->wantsPanel($request) ? 'admin.branches._form' : 'admin.branches.form', [
            'branch' => $branch,
        ]);
    }

    public function update(BranchRequest $request, Branch $branch): RedirectResponse|JsonResponse
    {
        $branch->fill($this->baseData($request))->save();
        $branch->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.branches.index'), 'Filial yangilandi.');
    }

    public function toggle(Branch $branch): JsonResponse
    {
        $branch->update(['is_active' => ! $branch->is_active]);

        return response()->json([
            'ok' => true,
            'message' => $branch->is_active ? 'Saytda ko‘rsatiladi.' : 'Yashirildi.',
            'value' => $branch->is_active,
        ]);
    }

    public function destroy(Request $request, Branch $branch): RedirectResponse|JsonResponse
    {
        $branch->delete();

        return $this->adminResponse($request, redirect()->route('admin.branches.index'), 'Filial o‘chirildi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function baseData(BranchRequest $request): array
    {
        $data = $request->safe()->except('translations');

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['map_url'] = $data['map_url'] ?? null;

        return $data;
    }
}
