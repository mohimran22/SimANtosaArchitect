<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $orderby = in_array($request->query('orderby'), ['name', 'articles_count']) ? $request->query('orderby') : 'name';
        $order   = $request->query('order') === 'desc' ? 'desc' : 'asc';

        $cats = Category::withCount('articles')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy($orderby, $order)
            ->paginate(20)
            ->withQueryString();

        return view('admin.categories.index', compact('cats', 'orderby', 'order'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'slug' => 'nullable|string|max:100|unique:categories,slug',
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category ditambahkan.');
    }

    public function edit(Category $cat)
    {
        return view('admin.categories.edit', compact('cat'));
    }

    public function update(Request $request, Category $cat)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $cat->id,
            'slug' => 'nullable|string|max:100|unique:categories,slug,' . $cat->id,
        ]);

        $cat->update($data);

        return redirect()->route('categories.index')->with('success', 'Tag diperbarui.');
    }

    public function destroy(Tag $cat)
    {
        $cat->articles()->detach();
        $cat->delete();

        return redirect()->route('categories.index')->with('success', 'Tag dihapus.');
    }

    public function bulk(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        if ($request->input('bulk_action') === 'delete' && $ids) {
            foreach (Category::whereIn('id', $ids)->get() as $cat) {
                $cat->articles()->detach();
                $cat->delete();
            }
            return redirect()->route('categories.index')->with('success', count($ids) . ' tag dihapus.');
        }

        return redirect()->route('categories.index');
    }
}