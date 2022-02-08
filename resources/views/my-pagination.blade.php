<style>
    .pagination {
        display: inline-block;
    }

    .pagination a,
    .pagination span {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
        margin: 0 4px;
    }

    /* .pagination a.active,
    .pagination span.active {
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
    } */

    .pagination a:hover:not(.active) {
        background-color: #ddd;
    }

</style>

@if ($paginator->hasPages())
<div class="pagination ">
    @if ($paginator->onFirstPage())
    <span><i class="fas fa-caret-left"></i></span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-caret-left"></i></a>
    @endif

    @if($paginator->currentPage() > 3)
    <a href="{{ $paginator->url(1) }}">1</a>
    @endif
    @if($paginator->currentPage() > 4)
    <span>...</span>
    @endif
    @foreach(range(1, $paginator->lastPage()) as $i)
    @if($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
        @if ($i == $paginator->currentPage())
        <span class="active bg-blue-500 text-white" style="background: #3b82f6;color:white">{{ $i }}</span>
        @else
        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
        @endif
        @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <span>...</span>
            @endif
            @if($paginator->currentPage() < $paginator->lastPage() - 2)
                <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                @endif

                @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas fa-caret-right"></i></a>
                @else
                <span><i class="fas fa-caret-right"></i></span>
                @endif
</div>
@endif
