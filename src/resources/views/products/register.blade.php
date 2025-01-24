<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <link rel="stylesheet" href="{{ asset('css/products-register.css') }}">
</head>

<body>
    <div class="container">
        <h1>商品登録</h1>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">商品名</label>
                <input type="text" name="name" placeholder="商品名を入力" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="required">価格</label>
                <input type="number" name="price" placeholder="価格を入力 (例: 800)" value="{{ old('price') }}" required>
            </div>

            <div class="form-group">
                <label class="required">商品画像</label>
                <div class="image-preview">
                    <img src="{{ asset('images/default-placeholder.png') }}" alt="商品画像プレビュー">
                </div>
                <div class="file-upload">
                    <input type="file" name="image" required>
                </div>
            </div>

            <div class="form-group">
                <label class="required">季節 <span class="sub-label">複数選択可能</span></label>
                <div class="checkbox-group">
                    @foreach ($seasons as $season)
                    <label class="checkbox-option">
                        <input type="checkbox" name="seasons[]" value="{{ $season->id }}">
                        <span>{{ $season->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="required">商品説明</label>
                <textarea name="description" placeholder="商品説明を入力してください" required>{{ old('description') }}</textarea>
            </div>

            <div class="button-group">
                <a href="{{ route('products.index') }}" class="btn btn-back">戻る</a>
                <button type="submit" class="btn btn-submit">登録</button>
            </div>
        </form>
    </div>
</body>

</html>