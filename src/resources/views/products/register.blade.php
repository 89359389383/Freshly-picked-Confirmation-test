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
            <!-- 商品名 -->
            <div class="form-group">
                <label class="required">商品名</label>
                <input type="text" name="name" placeholder="商品名を入力" value="{{ old('name') }}">
                @error('name')
                <p class="error-message" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <!-- 価格 -->
            <div class="form-group">
                <label class="required">価格</label>
                <input type="number" name="price" placeholder="価格を入力 " value="{{ old('price') }}">
                @error('price')
                <p class="error-message" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <!-- 商品画像 -->
            <div class="form-group">
                <label class="required">商品画像</label>
                <div class="image-preview">
                    <img id="image-preview" src="{{ asset('images/default-placeholder.png') }}">
                </div>
                <div class="file-upload">
                    <input type="file" name="image" id="image-input">
                </div>
                @error('image')
                <p class="error-message" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <!-- 季節 -->
            <div class="form-group">
                <label class="season-name">
                    季節
                    <span class="required-badge"></span>
                    <span class="sub-label">複数選択可</span>
                </label>
                <div class="checkbox-group">
                    @foreach ($seasons as $season)
                    <label class="checkbox-option">
                        <input type="checkbox" name="seasons[]" value="{{ $season->id }}">
                        <span>{{ $season->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('seasons')
                <p class="error-message" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <!-- 商品説明 -->
            <div class="form-group">
                <label class="required">商品説明</label>
                <textarea name="description" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
                @error('description')
                <p class="error-message" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <!-- ボタン -->
            <div class="button-group">
                <a href="{{ route('products.index') }}" class="btn btn-back">戻る</a>
                <button type="submit" class="btn btn-submit">登録</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('image-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>