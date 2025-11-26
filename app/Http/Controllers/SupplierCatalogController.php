<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupplierCatalogController extends Controller
{
    /**
     * AJAX Search produk
     */
    public function searchProduct(Request $request)
    {
        $keyword = $request->keyword;

        $products = Product::where('name', 'ILIKE', "%{$keyword}%") 
        ->limit(10)
        ->get();

        return response()->json([
            'found' => $products->count() > 0,
            'html'  => view('suppliers.catalog.search-results', compact('products'))->render()
        ]);
    }


    /**
     * AJAX Detail produk
     */
    public function productDetail($id)
    {
        $product = Product::with(['brand', 'category', 'type', 'color'])->findOrFail($id);

        return response()->json([
            'id'                 => $product->id,
            'name'               => $product->name,
            'description'        => $product->description,
            'size'               => $product->product_size,
            'volume'             => $product->product_volume,
            'brand_id'           => $product->brand_id,
            'category_id'        => $product->category_id,
            'type_id'            => $product->type_id,
            'color_id'           => $product->color_id,
            'color_name'         => $product->color->name ?? '',
            'brand_name'         => $product->brand->name ?? '',
            'category_name'      => $product->category->name ?? '',
            'type_name'          => $product->type->name ?? '',

            'unit_1_name'        => $product->unit_1_name,
            'unit_1_value'       => $product->unit_1_value,
            'unit_2_name'        => $product->unit_2_name,
            'unit_2_value'       => $product->unit_2_value,
            'unit_3_name'        => $product->unit_3_name,
            'unit_3_value'       => $product->unit_3_value,
            'unit_4_name'        => $product->unit_4_name,
            'unit_4_value'       => $product->unit_4_value,

            'default_buying_prices' => $product->default_buying_prices ?? 0,
            'default_discount'      => $product->default_discount ?? 0,
            'tax_percentage'        => $product->tax_percentage ?? 0,

            'photo_url' => $product->photo 
                ? asset('storage/' . $product->photo) 
                : asset('images/logo-putih.png'),
        ]);
    }


    /**
     * Simpan produk ke supplier (pivot)
     */
    public function storeSupplierProduct(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'product_id'     => 'required|exists:products,id',
            'stock'          => 'required|numeric',
            'buying_prices'  => 'required|numeric',
            'tax_percentage' => 'nullable|numeric',
            'discount'       => 'nullable|numeric',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);

        $supplier->products()->attach($request->product_id, [
            'stock'           => $request->stock,
            'buying_prices'   => $request->buying_prices,
            'tax_percentage'  => $request->tax_percentage ?? 0,
            'discount'        => $request->discount ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk supplier berhasil ditambahkan'
        ]);
    }



public function updatePrice(Request $request, $supplierId, $productId)
{
    $request->validate([
        'buying_prices' => 'required|numeric',
        'selling_price' => 'required|numeric',
        'special_price' => 'required|numeric',
        'tax_percentage' => 'required|numeric',
        'discount' => 'required|numeric',
        'stock' => 'nullable|string',
    ]);

    Supplier::findOrFail($supplierId)
        ->products()
        ->updateExistingPivot($productId, [
            'buying_prices' => $request->buying_prices,
            'stock' => $request->stock,
        ]);

    return back()->with('success', 'Harga berhasil diperbarui.');
}

}
