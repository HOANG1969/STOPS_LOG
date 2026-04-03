<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập quản lý sản phẩm.');
        }
        
        $query = Product::with('category');
        
        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by category
        if ($request->has('category_id') && $request->category_id !== '') {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by status
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }
        
        $products = $query->orderBy('name')->paginate(15);
        $categories = Category::active()->orderBy('name')->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền tạo sản phẩm.');
        }
        
        $categories = Category::active()->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền tạo sản phẩm.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        Product::create($request->all());
        
        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền chỉnh sửa sản phẩm.');
        }
        
        $categories = Category::active()->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền chỉnh sửa sản phẩm.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $product->update($request->all());
        
        return redirect()->route('products.show', $product)
            ->with('success', 'Sản phẩm đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền xóa sản phẩm.');
        }
        
        // Kiểm tra xem có request items nào đang sử dụng product này không
        if ($product->requestItems()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Không thể xóa sản phẩm này vì đã có yêu cầu sử dụng.');
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được xóa!');
    }

    /**
     * Get products for AJAX requests
     */
    public function getProducts(Request $request)
    {
        $query = Product::active();
        
        if ($request->has('category_id') && $request->category_id !== '') {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->with('category')->get();
        
        return response()->json($products);
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền thay đổi trạng thái sản phẩm.');
        }
        
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        
        return redirect()->route('products.index')
            ->with('success', "Đã {$status} sản phẩm thành công!");
    }
}