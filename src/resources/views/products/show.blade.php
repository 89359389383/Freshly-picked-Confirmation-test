<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品編集 - {{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/products-show.css') }}">
</head>

<body>
    <div class="container">
        <div class="logo">mogitate</div>
        <div class="breadcrumb">
            <a href="{{ route('products.index') }}">商品一覧</a> > {{ $product->name }}
        </div>

        <form class="product-form" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="image-section">
                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                <div class="file-input-container">
                    <input type="file" name="image" id="product-image">
                    <span class="file-name">{{ $product->image }}</span>
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">商品名</label>
                    <input type="text" name="name" class="form-input" value="{{ $product->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">値段</label>
                    <input type="number" name="price" class="form-input" value="{{ $product->price }}" required>
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
                                {{ in_array($season->id, $product->seasons->pluck('id')->toArray()) ? 'checked' : '' }}>
                            <span>{{ $season->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="description">
                <label class="form-label">商品説明</label>
                <textarea name="description" class="description-area" required>{{ $product->description }}</textarea>
            </div>

            <div class="button-group">
                <a href="{{ route('products.index') }}" class="btn btn-back">戻る</a>
                <button type="submit" class="btn btn-save">変更を保存</button>
            </div>
        </form>

        <form method="POST" action="{{ route('products.destroy', $product->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-icon">🗑</button>
        </form>
    </div>
</body>

</html>