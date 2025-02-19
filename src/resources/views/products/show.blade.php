<!-- cSpell: disable -->

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品編集 - {{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/products-show.css') }}">
</head>

<style>
    .breadcrumb {
        color: #0099ff;
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .breadcrumb a {
        color: #0099ff;
        text-decoration: none;
    }
</style>

<body>
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('products.index') }}">商品一覧</a> > {{ $product->name }}
        </div>

        <!-- 更新用フォーム -->
        <form class="product-form" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="image-section">
                <img
                    src="{{ Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : asset('storage/products/' . $product->image) }}"
                    alt="{{ $product->name }}"
                    class="product-image">

                <div class="file-input-container">
                    <label for="product-image">画像を選択:</label>
                    <input type="file" name="image" id="product-image">

                    <span class="file-name">
                        @if($product->image)
                        {{ $product->image }}
                        @else
                        ファイルが選択されていません
                        @endif
                    </span>

                    @error('image')
                    <p class="error-message" style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">商品名</label>
                    <input type="text" name="name" class="form-input" value="{{ $product->name }}">
                    @error('name')
                    <p class="error-message" style="color: red;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">値段</label>
                    <input type="number" name="price" class="form-input" value="{{ $product->price }}">
                    @error('price')
                    <p class="error-message" style="color: red;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">季節</label>
                    <div class="season-options">
                        @foreach ($allSeasons as $season)
                        <label class="season-option">
                            <input
                                type="checkbox"
                                name="seasons[]"
                                value="{{ $season->id }}"
                                {{ in_array($season->id, old('seasons', $product->seasons->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <span>{{ $season->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('seasons')
                    <p class="error-message" style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="description">
                <label class="form-label">商品説明</label>
                <textarea name="description" class="description-area">{{ $product->description }}</textarea>
                @error('description')
                <p class="error-message" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <div class="button-group">
                <a href="{{ route('products.index') }}" class="btn btn-back">戻る</a>
                <button type="submit" class="btn btn-save">変更を保存</button>
            </div>
        </form>

        <!-- 削除用フォーム -->
        <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-icon">🗑</button>
        </form>
    </div>

    <script>
        document.getElementById('product-image').addEventListener('change', function(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const reader = new FileReader();

            if (file) {
                reader.onload = function(e) {
                    // プレビュー画像を更新
                    const previewImage = document.querySelector('.product-image');
                    previewImage.src = e.target.result;

                    // ファイル名を更新
                    const fileNameSpan = document.querySelector('.file-name');
                    fileNameSpan.textContent = file.name;
                };

                reader.readAsDataURL(file); // ファイルを Data URL に変換してプレビュー表示
            }
        });
    </script>
</body>

</html>