<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 商品一覧ページを表示
    public function index(Request $request)
    {
        $name = $request->input('name');
        $sort = $request->input('sort');
        $query = Product::with('seasons');

        // 検索条件を追加
        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        // 並び替え条件を追加
        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        }

        define('ITEMS_PER_PAGE', 6);
        $products = $query->paginate(ITEMS_PER_PAGE)->appends($request->query());

        return view('products.index', compact('products', 'name', 'sort'));
    }

    // 商品詳細ページを表示
    public function show($productId)
    {
        $product = Product::with('seasons')->findOrFail($productId);
        $allSeasons = Season::all(); // 季節のリストを取得
        return view('products.show', compact('product', 'allSeasons'));
    }

    // 新規商品の登録フォームを表示
    public function register()
    {
        $seasons = Season::all();
        return view('products.register', compact('seasons'));
    }

    // 新規商品を保存
    public function store(ProductRequest $request)
    {
        // 画像を保存
        $path = $request->file('image')->store('products', 'public');

        // 商品を作成
        $product = Product::create(array_merge(
            $request->only(['name', 'price', 'description']),
            ['image' => $path]
        ));

        // 季節を関連付け
        $product->seasons()->attach($request->input('seasons'));

        return redirect()->route('products.index');
    }

    // 商品を更新
    public function update(ProductRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // 新しい画像があれば保存
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image); // 古い画像を削除
            }
            $path = $request->file('image')->store('products', 'public');
        } else {
            $path = $product->image;
        }

        // 商品情報を更新
        $product->update(array_merge(
            $request->only(['name', 'price', 'description']),
            ['image' => $path]
        ));

        // 季節を更新
        $product->seasons()->sync($request->input('seasons'));

        return redirect()->route('products.index');
    }

    // 商品を検索
    public function search(Request $request)
    {
        $name = $request->input('name');
        $sort = $request->input('sort');
        $query = Product::with('seasons');

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(6)->appends($request->query());
        return view('products.index', compact('products', 'name', 'sort'));
    }

    // 商品を削除
    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();
        return redirect()->route('products.index');
    }
}
