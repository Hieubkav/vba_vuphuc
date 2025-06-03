<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Post;

class SearchController extends Controller
{
    /**
     * Tìm kiếm sản phẩm
     */
    public function products(Request $request)
    {
        $query = $request->get('q', '');
        
        // TODO: Implement product search logic
        // Hiện tại trả về empty response để tránh lỗi
        
        if ($request->ajax()) {
            return response()->json([
                'products' => [],
                'total' => 0
            ]);
        }
        
        return view('search.products', [
            'query' => $query,
            'products' => collect([])
        ]);
    }
    
    /**
     * Tìm kiếm bài viết
     */
    public function posts(Request $request)
    {
        $query = $request->get('q', '');
        
        // TODO: Implement post search logic
        // Hiện tại trả về empty response để tránh lỗi
        
        if ($request->ajax()) {
            return response()->json([
                'posts' => [],
                'total' => 0
            ]);
        }
        
        return view('search.posts', [
            'query' => $query,
            'posts' => collect([])
        ]);
    }
}
