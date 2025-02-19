<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PriceValidationRule; // カスタムバリデーションルールを使うために読み込む

class ProductRequest extends FormRequest
{
    const MAX_PRICE = 10000; // 価格の最大値を定数として定義

    /**
     * ユーザーがこのリクエストを許可されているかを判断する
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // 認証が必要な場合は false にするが、今回はすべてのユーザーに許可
    }

    /**
     * 適用されるバリデーションルールを定義
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255', // 商品名は必須、文字列、最大255文字
            'price' => 'required|integer|min:0|max:' . self::MAX_PRICE,
            'description' => 'required|string|max:120', // 商品説明は必須、最大120文字
            'image' => $this->isMethod('patch') ? 'nullable|mimes:png,jpeg' : 'required|mimes:png,jpeg',
            // PATCH（更新）リクエストなら画像は必須でなくてもOK
            'seasons' => 'required|array|min:1', // 季節の選択は必須（複数選択可）
            'seasons.*' => 'exists:seasons,id', // 季節のIDはデータベースに存在するもののみ許可
        ];
    }

    /**
     * 各バリデーションエラー時のメッセージを設定
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください', // 商品名の入力がない場合のメッセージ
            'price.required' => "値段を入力してください\n数値で入力してください\n0~10000円以内で入力してください", // 3行のメッセージを設定
            'price.integer' => '数値で入力してください',
            'price.min' => '0~10000円以内で入力してください',
            'price.max' => '0~10000円以内で入力してください',
            'description.required' => '商品説明を入力してください', // 商品説明の入力がない場合のメッセージ
            'description.max' => '商品説明は120文字以内で入力してください', // 商品説明の文字数制限
            'image.required' => '商品画像を登録してください', // 画像がアップロードされていない場合のメッセージ
            'image.mimes' => '画像は「.png」または「.jpeg」形式でアップロードしてください', // 画像の形式が不正な場合のメッセージ
            'seasons.required' => '季節を選択してください', // 季節が未選択の場合のメッセージ
            'seasons.*.exists' => '選択された季節が無効です', // 存在しない季節IDが選択された場合のメッセージ
        ];
    }
}
