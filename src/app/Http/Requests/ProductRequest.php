<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

    const MAX_PRICE = 10000;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0|max:' . self::MAX_PRICE,
            'description' => 'required|string|max:120',
            'image' => $this->isMethod('patch') ? 'nullable|mimes:png,jpeg' : 'required|mimes:png,jpeg',
            // PATCH メソッドの場合は image フィールドを「nullable（省略可能）」にします
            'seasons' => 'required|array|min:1',
            'seasons.*' => 'exists:seasons,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.integer' => '数値で入力してください',
            'price.min' => '0~10000円以内で入力してください',
            'price.max' => '0~10000円以内で入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は120文字以内で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.mimes' => '画像は「.png」または「.jpeg」形式でアップロードしてください',
            'seasons.required' => '季節を選択してください',
            'seasons.*.exists' => '選択された季節が無効です',
        ];
    }
}
