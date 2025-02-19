<?php

namespace App\Http\Controllers;

use App\Models\Product; // 商品モデルを使うため
use App\Models\Season; // 季節モデルを使うため
use Illuminate\Http\Request; // HTTPリクエストを扱うため
use Illuminate\Support\Facades\Storage; // ファイルの保存と削除を扱うため
use App\Http\Requests\ProductRequest; // フォームリクエストでバリデーションを行うため
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private const ITEMS_PER_PAGE = 6; // ページごとの表示件数を定数として定義

    // 商品一覧ページを表示する
    public function index(Request $request)
    {
        // ユーザーが入力した商品名を取得
        $name = $request->input('name');

        // ユーザーが選んだ並び替え条件を取得（高い順や安い順）
        $sort = $request->input('sort');

        // 商品データを扱うためのクエリを準備し、関連する季節データも取得
        $query = Product::with('seasons');

        // 商品名が入力されている場合、名前で部分一致検索を追加
        if ($name) {
            $query->where('name', 'like', "%$name%"); // '%文字%' は部分一致の検索に使う
        }

        // 並び替え条件が「昇順（安い順）」の場合
        if ($sort === 'asc') {
            $query->orderBy('price', 'asc'); // 価格を安い順で並び替える
        }
        // 並び替え条件が「降順（高い順）」の場合
        elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc'); // 価格を高い順で並び替える
        }

        // 定数を利用してマジックナンバーを排除
        $products = $query->paginate(self::ITEMS_PER_PAGE)->appends($request->query());

        // 商品一覧ページを表示し、データを渡す
        return view('products.index', compact('products', 'name', 'sort'));
    }

    // 商品詳細ページを表示する
    public function show($productId)
    {
        // 指定されたIDの商品と関連する季節データを取得
        $product = Product::with('seasons')->findOrFail($productId);

        // 季節のリストをすべて取得
        $allSeasons = Season::all();

        // 商品詳細ページを表示し、商品と季節リストを渡す
        return view('products.show', compact('product', 'allSeasons'));
    }

    // 新規商品の登録フォームを表示する

    public function register()
    {
        // 季節のリストをすべて取得
        $seasons = Season::all();

        // 商品登録フォームを表示し、季節リストを渡す
        return view('products.register', compact('seasons'));
    }

    // 新規商品を保存する
    public function store(ProductRequest $request)
    {
        // アップロードされた画像ファイルを "public/storage/products" フォルダに保存する
        // "store" メソッドは指定したディレクトリにファイルを保存し、保存されたファイルのパスを返す
        // 第1引数はフォルダ名、第2引数 "public" は公開アクセス用に保存するディスクを指定
        $path = $request->file('image')->store('products', 'public');

        // 商品をデータベースに保存（画像のパスも含む）
        $product = Product::create(array_merge(
            $request->only(['name', 'price', 'description']), // 商品名、価格、説明を取得
            ['image' => $path] // 保存した画像のパスを追加
        ));

        // 商品に選ばれた季節を関連付ける
        $product->seasons()->attach($request->input('seasons'));

        // 商品一覧ページにリダイレクト
        return redirect()->route('products.index');
    }

    // 商品を更新する
    public function update(ProductRequest $request, $productId)
    {
        // 指定されたIDの商品を取得
        $product = Product::findOrFail($productId);

        // 新しい画像がアップロードされた場合
        if ($request->hasFile('image')) {
            // 古い画像が存在すれば削除
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // 新しい画像を保存し、そのパスを取得
            $path = $request->file('image')->store('products', 'public');
        } else {
            // 新しい画像がない場合は、古い画像のパスをそのまま使用
            $path = $product->image;
        }

        // 商品情報を更新
        $product->update(array_merge(
            $request->only(['name', 'price', 'description']), // 商品名、価格、説明を取得
            ['image' => $path] // 更新された画像のパスを追加
        ));

        // 季節情報を更新（古い関連付けを削除して新しい関連付けに置き換え）
        $product->seasons()->sync($request->input('seasons'));

        // 商品一覧ページにリダイレクト
        return redirect()->route('products.index');
    }

    // 商品を検索する
    public function search(Request $request)
    {
        // ユーザーが入力した商品名を取得
        $name = $request->input('name');

        // ユーザーが選んだ並び替え条件を取得
        $sort = $request->input('sort');

        // 商品データを扱うためのクエリを準備し、関連する季節データも取得
        $query = Product::with('seasons');

        // 商品名が入力されている場合、名前で部分一致検索を追加
        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        // 並び替え条件が「昇順（安い順）」の場合
        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        }
        // 並び替え条件が「降順（高い順）」の場合
        elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        }
        // 定数を利用してマジックナンバーを排除
        $products = $query->paginate(self::ITEMS_PER_PAGE)->appends($request->query());

        // 商品一覧ページを表示し、データを渡す
        return view('products.index', compact('products', 'name', 'sort'));
    }

    // 商品を削除する
    public function destroy($productId)
    {
        // 指定されたIDの商品を取得
        $product = Product::findOrFail($productId);

        // 商品を削除
        $product->delete();

        // 商品一覧ページにリダイレクト
        return redirect()->route('products.index');
    }
}
