<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // 商品一覧ページを表示
    public function index()
    {
        $products = Product::with('seasons')->paginate(6); // ページネーションで6件ずつ表示
        return view('products.index', compact('products'));
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
    public function create()
    {
        $seasons = Season::all(); // 季節のリストを取得
        return view('products.create', compact('seasons'));
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

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    }

    // 商品を更新
    public function update(ProductRequest $request, $productId)
    {
        // 商品IDを使ってデータベースから対象の商品を探す
        // 商品が見つからない場合はエラーを出す
        $product = Product::findOrFail($productId);

        // リクエストに画像ファイルがあるか確認する
        // 画像ファイルがある場合は保存して、そのパスを取得する
        // 画像ファイルがない場合は、既存の商品画像パスをそのまま使う
        $path = $request->file('image')
            ? $request->file('image')->store('products', 'public') // 新しい画像を保存
            : $product->image; // 既存の画像を使う

        // 商品データを更新する
        // 名前、価格、説明はリクエストから取得し、画像のパスも含めて更新する
        $product->update(array_merge(
            $request->only(['name', 'price', 'description']), // 入力データ（名前、価格、説明）を取得
            ['image' => $path] // 画像パスを設定
        ));

        // 商品に関連付けられている季節の情報を更新する
        // 新しい季節のデータで古いデータを置き換える
        $product->seasons()->sync($request->input('seasons'));

        return redirect()->route('products.index');
    }

    public function search(Request $request)
    {
        // フォームから送られた検索条件を取得します。
        $name = $request->input('name'); // 商品名

        // デバッグ: リクエストの全データをログに記録
        Log::info('検索リクエストデータ:', $request->all());

        // データベースから情報を検索する準備をします。
        $query = Product::with('seasons');
        Log::info('初期クエリ構築完了');

        // 商品名が入力されている場合
        if ($name) {
            $query->where('name', 'like', "%$name%");
            Log::info('商品名で絞り込み:', ['query' => $query->toSql()]);
        }

        // 検索条件に合ったデータを1ページに6件ずつ取得します。
        try {
            /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $products */
            $products = $query->paginate(6);
            Log::info('検索結果取得成功:', ['total' => $products->total()]);
        } catch (\Exception $e) {
            // デバッグ: エラー発生時に例外の情報をログ出力
            Log::error('検索結果取得時のエラー:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            dd('検索中にエラーが発生しました: ' . $e->getMessage());
        }

        // 検索結果やフォームに入力された条件をビュー（HTML画面）に渡します。
        return view('products.index', compact('products', 'name'));
    }

    // 商品を削除
    public function destroy($productId)
    {
        $product = Product::findOrFail($productId); // 商品IDを使って対象の商品を探す
        $product->delete(); // 見つけた商品をデータベースから削除する
        return redirect()->route('products.index');
    }
}
