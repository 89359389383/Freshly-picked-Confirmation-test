<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule; // バリデーションルールの基盤クラス

class PriceValidationRule implements Rule
{
    protected $errors = []; // すべてのエラーメッセージを保存するための変数

    /**
     * 価格のバリデーションを実行
     *
     * @param  string  $attribute  バリデーション対象のフィールド名（今回は 'price'）
     * @param  mixed   $value      ユーザーが入力した値
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 値が空（null または 空文字 ''）の場合
        if (!isset($value) || trim($value) === '') {
            $this->errors[] = '値段を入力してください';
            $this->errors[] = '数値で入力してください';
            $this->errors[] = '0~10000円以内で入力してください';
        } else {
            // 値が数値でない場合
            if (!is_numeric($value)) {
                $this->errors[] = '数値で入力してください';
            }

            // 値が 0 未満 または 10000 を超える場合
            if ($value < 0 || $value > 10000) {
                $this->errors[] = '0~10000円以内で入力してください';
            }
        }

        // もしエラーが 1つ以上あるなら、バリデーションは失敗とする
        return empty($this->errors);
    }

    /**
     * バリデーションに失敗した場合のエラーメッセージを返す
     *
     * @return array
     */
    public function message()
    {
        return $this->errors; // 複数のエラーメッセージを配列で返す
    }
}
