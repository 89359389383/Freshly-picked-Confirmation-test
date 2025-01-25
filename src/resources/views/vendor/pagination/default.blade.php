@if ($paginator->hasPages())
<nav class="pagination" style="margin-top: 30px;">
    {{-- 前のページリンク --}}
    @if ($paginator->onFirstPage())
    <span class="pagination-item disabled" style="width: 30px; text-align: center;" aria-disabled="true">‹</span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-item" style="width: 30px; text-align: center; background-color: #f9f9f9; color: black;" rel="prev">‹</a>
    @endif

    {{-- ページネーションの各要素をループで表示 --}}
    @foreach ($elements as $element)

    {{-- ページが多すぎる場合に「...」で省略表示される部分 --}}
    @if (is_string($element))
    <span class="pagination-item disabled" style="width: 30px; text-align: center; background-color: #f9f9f9; color: black;">{{ $element }}</span>
    @endif

    {{-- ページリンクを配列として処理 --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="pagination-item active" style="width: 30px; text-align: center; background-color: #FFD700; color: white;">{{ $page }}</span>
    @else
    <a href="{{ $url }}" class="pagination-item" style="width: 30px; text-align: center; background-color: #f9f9f9; color: black;">{{ $page }}</a>
    @endif
    @endforeach
    @endif

    @endforeach

    {{-- 次のページリンク --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-item" style="width: 30px; text-align: center; color: black;" rel="next">›</a>
    @else
    <span class="pagination-item disabled" style="width: 30px; text-align: center;" aria-disabled="true">›</span>
    @endif
</nav>
@endif