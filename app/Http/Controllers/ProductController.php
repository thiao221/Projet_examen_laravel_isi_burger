<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Catalogue client avec filtres
    public function index(Request $request)
    {
        $query = Product::with('category')->disponible();

        // Filtre par nom
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Tri par prix
        if ($request->filled('sort')) {
            $query->orderBy('price', $request->sort);
        }

        $products   = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // Détail d'un produit
    public function show(string $slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}
