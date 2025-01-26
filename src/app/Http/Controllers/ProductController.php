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

        $products = $query->paginate(6)->appends($request->query());

        return view('products.index', compact('products', 'name', 'sort'));
    }

    // 商品詳細ページを表示
    public function show($productId)
    {
        $product = Product::with('seasons')->findOrFail($productId);
        // 商品を取得するためのコード
        // 1. 商品ID ($productId) を使って、データベースからその商品を探します。
        // 2. 商品が見つからない場合は、自動的にエラー (404エラー) を出します。
        // 3. 商品に関連する季節情報 (seasons) も一緒に取得します。
        $allSeasons = Season::all(); // すべての季節を取得
        $product = Product::with('seasons')->findOrFail($productId);

        return view('products.show', compact('product', 'allSeasons'));
    }

    // 新規商品の登録フォームを表示
    public function register()
    {
        $seasons = Season::all(); // 季節のリストを取得
        return view('products.register', compact('seasons'));
    }

    // 新規商品を保存
    public function store(ProductRequest $request)
    {
        // 送信されたフォームデータから「image」フィールドにあるファイルを取得して保存します。
        // ファイルは「public/storage/products」フォルダに保存され、パスを変数 $path に格納します。
        $path = $request->file('image')->store('products', 'public');

        // フォームから送られた「name」「price」「description」を使って新しい商品を作ります。
        // さらに、保存した画像のパス（$path）も一緒に商品データに追加します。
        $product = Product::create(array_merge(
            $request->only(['name', 'price', 'description']), // 名前、値段、説明を取得
            ['image' => $path] // 画像のパスを追加
        ));

        // 商品に関連付ける「季節」を、データベースの中間テーブル（product_season）に保存します。
        // 入力された季節（seasons）は配列で受け取り、それを紐付けます。
        $product->seasons()->attach($request->input('seasons'));

        return redirect()->route('products.index');
    }

    // 商品を更新
    public function update(ProductRequest $request, $productId)
    {
        // 商品を取得
        $product = Product::findOrFail($productId);

        // 新しい画像がアップロードされた場合
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // 新しい画像を保存し、パスを取得
            $path = $request->file('image')->store('products', 'public');
        } else {
            // 画像がアップロードされていない場合、既存の画像を使用
            $path = $product->image;
        }

        // 商品情報を更新
        $product->update(array_merge(
            $request->only(['name', 'price', 'description']),
            ['image' => $path] // 画像パスを更新
        ));

        // 季節データを更新
        $product->seasons()->sync($request->input('seasons'));

        // 商品一覧ページにリダイレクト
        return redirect()->route('products.index');
    }

    public function search(Request $request)
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

        $products = $query->paginate(6)->appends($request->query());

        return view('products.index', compact('products', 'name', 'sort'));
    }

    // 商品を削除
    public function destroy($productId)
    {
        $product = Product::findOrFail($productId); // 商品IDを使って対象の商品を探す
        $product->delete(); // 見つけた商品をデータベースから削除する
        return redirect()->route('products.index');
    }
}
