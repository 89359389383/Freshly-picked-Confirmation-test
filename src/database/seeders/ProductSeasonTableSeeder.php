<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Season;

class ProductSeasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            1 => [3, 4], // キウイ → 秋、冬
            2 => [1],    // ストロベリー → 春
            3 => [4],    // オレンジ → 冬
            4 => [2],    // スイカ → 夏
            5 => [2],    // ピーチ → 夏
            6 => [2, 3], // シャインマスカット → 夏、秋
            7 => [1, 2], // パイナップル → 春、夏
            8 => [2, 3], // ブドウ → 夏、秋
            9 => [2],    // バナナ → 夏
            10 => [1, 2], // メロン → 春、夏
        ];

        // $data 配列を使って、商品と季節を関連付ける処理を行います。
        // $data の中身は、「どの商品IDにどの季節IDを関連付けるか」を示しています。
        foreach ($data as $productId => $seasonIds) {

            // $productId: 現在処理している商品のID
            // $seasonIds: この商品に関連付ける季節のID一覧 (例: [3, 4] なら秋と冬)

            // 商品ID ($productId) に対応する商品データをデータベースから取得します。
            // Product::find($productId) は、products テーブルから指定した ID の商品を探します。
            $product = Product::find($productId);

            // $product->seasons() は、商品と季節をつなぐ中間テーブル (product_season) にアクセスします。
            // ->attach($seasonIds) は、商品のIDと季節のIDを中間テーブルに保存して関連付けを作成します。
            // たとえば、商品「キウイ (ID: 1)」に「秋 (ID: 3)」と「冬 (ID: 4)」を関連付けます。
            $product->seasons()->attach($seasonIds);

            // この処理を $data のすべての要素について繰り返します。
            // つまり、各商品のIDと、それに対応する季節のIDを順番に関連付けていきます。
        }
    }
}
