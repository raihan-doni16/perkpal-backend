<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PerkResource;
use App\Models\Category;
use App\Models\Perk;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::with('subcategories')
            ->active()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories)
        ]);
    }

   
    public function show(Request $request, $slug)
    {
        $category = Category::with('subcategories')
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $query = Perk::with(['category', 'subcategory', 'statistics', 'media', 'locationOption'])
            ->active()
            ->published()
            ->byCategory($slug)
            ->bySubcategory($request->input('subcategory'))
            ->byLocation($request->input('location'))
            ->search($request->input('search'));

        $sort = $request->input('sort', 'latest');
        if ($sort === 'popular') {
            $query->popular();
        } elseif ($sort === 'ending_soon') {
            $query->orderByRaw('CASE WHEN valid_until IS NULL THEN 1 ELSE 0 END')
                  ->orderBy('valid_until', 'asc');
        } else {
            $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
        }

        $perPage = (int) $request->input('per_page', 12);
        $perks = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'category' => new CategoryResource($category),
            'perks' => PerkResource::collection($perks),
            'meta' => [
                'current_page' => $perks->currentPage(),
                'last_page' => $perks->lastPage(),
                'per_page' => $perks->perPage(),
                'total' => $perks->total(),
            ],
            'current' => [
                'category' => $slug,
                'subcategory' => (string) $request->input('subcategory', ''),
                'location' => $request->input('location', ''),
                'search' => $request->input('search', ''),
                'sort' => $sort,
            ],
        ]);
    }
}
