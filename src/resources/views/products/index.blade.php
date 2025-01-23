<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フルーツショップ</title>
    <link rel="stylesheet" href="{{ asset('css/products-index.css') }}">
</head>

<body>
    <header class="header">
        <h1>商品一覧</h1>
        <a href="{{ route('products.create') }}" class="add-cart-button">商品を追加</a>
    </header>
    <div class="container">
        <aside class="sidebar">
            <div class="search-controls">
                <!-- 検索フォーム -->
                <form action="{{ route('products.search') }}" method="GET">
                    <input type="text" name="name" class="search-input" placeholder="商品名を入力" value="{{ request('name') }}">
                    <button type="submit" class="search-button">検索</button>
                </form>
                <h4>価格順で表示</h4>
                <select class="price-select">
                    <option value="" disabled selected>価格順で並び替え</option>
                    <option value="asc">価格が安い順</option>
                    <option value="desc">価格が高い順</option>
                </select>
            </div>
        </aside>

        <main class="main-content">
            <div class="product-grid">
                <!-- 商品情報を動的に表示 -->
                @foreach ($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                        <div class="product-info">
                            <span class="product-name">{{ $product->name }}</span>
                            <span class="product-price">¥{{ number_format($product->price) }}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- ページネーション -->
            <div class="pagination">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </main>
    </div>
</body>

</html>