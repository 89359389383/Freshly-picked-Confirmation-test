<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フルーツショップ</title>
    <link rel="stylesheet" href="{{ asset('css/products-index.css') }}">
</head>

<style>
    .sort-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background-color: rgba(255, 236, 153, 0.1);
        border: 1px solid #FFEC99;
        border-radius: 100px;
        font-size: 14px;
        color: #333333;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 16px;
        /* 下に16pxの余白 */
        position: relative;
        /* 疑似要素の基準となる */
    }

    .sort-tag::after {
        content: "";
        /* 疑似要素を表示させる */
        position: absolute;
        bottom: -16px;
        /* 要素の下16pxに配置 */
        left: 50%;
        /* 線を中央に配置 */
        transform: translateX(-50%);
        /* 中央寄せ */
        width: 200px;
        /* 線の幅 */
        height: 1px;
        /* 線の高さ */
        background-color: #666;
        /* 線の色 */
    }
</style>

<body>
    <header class="header">
        <h1 class="page-title">商品一覧</h1>
        <a href="{{ route('products.register') }}" class="add-cart-button">+商品を追加</a>
    </header>
    <div class="container">
        <aside class="sidebar">
            <div class="search-controls">
                <form action="{{ route('products.search') }}" method="GET" class="search-form">
                    <input type="text" name="name" class="search-input" placeholder="商品名で検索" value="{{ request('name') }}">
                    <button type="submit" class="search-button">検索</button>
                </form>

                <form action="{{ route('products.index') }}" method="GET" class="sort-form">
                    <h4>価格順で表示</h4>
                    <select name="sort" class="price-select" onchange="this.form.submit()">
                        <option value="" disabled {{ is_null(request('sort')) ? 'selected' : '' }}>価格順で並び替え</option>
                        <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>価格が安い順</option>
                        <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>価格が高い順</option>
                    </select>
                </form>

                <!-- 並び替えタグ -->
                @if(request('sort'))
                <div class="sort-tag">
                    {{ request('sort') === 'asc' ? '安い順に表示' : '高い順に表示' }}
                    <a href="{{ route('products.index', array_merge(request()->except('sort'))) }}" class="reset-sort">×</a>
                </div>
                @endif
            </div>
        </aside>

        <main class="main-content">
            <div class="product-grid">
                <!-- 商品情報を動的に表示 -->
                @foreach ($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img
                            src="{{ Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : asset('storage/products/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="product-image">
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
                {{ $products->appends(request()->query())->links('vendor.pagination.default') }}
            </div>
        </main>
    </div>
</body>

</html>