<style>
    .search-bar {
        margin-bottom: 20px;
        max-width: 420px;
    }

    .log-content-wrapper pre {
        background: #1e1e1e;
        color: #d4d4d4;
        font-family: Consolas, monospace, 'Courier New', monospace;
        font-size: 16px;
        line-height: 1.4;
        border-radius: 6px;
        user-select: text;
        padding: 20px;
        margin: 0;
        white-space: pre-wrap;
        word-break: break-word;
        text-align: left;
        text-indent: 0;
    }

    .pagination a, .pagination span {
        margin-right: 2px;
    }

    .pagination .disabled {
        pointer-events: none;
        opacity: 0.5;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 5px;
    }
</style>
@if($currentLogFile)
    <div class="search-bar">
        <form method="GET" action="{{ url()->current() }}" class="d-flex">
            <input type="hidden" name="directory" value="{{ request('directory') }}">
            <input type="hidden" name="filename" value="{{ request('filename') }}">
            <input type="hidden" name="path" value="{{ request('path') }}">

            <div class="input-group border rounded-pill px-2 py-1 shadow-sm bg-white" style="width: 100%;">
                                <span class="input-group-text bg-transparent border-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control border-0 px-1"
                       placeholder="搜索日志..."
                       style="box-shadow: none;"
                       autocomplete="off"/>
                @if(request('search'))
                    <span class="input-group-text bg-transparent border-0 p-0">
                                        <a href="{{ url()->current() }}?directory={{ request('directory') }}&filename={{ request('filename') }}&path={{ request('path') }}"
                                           class="btn btn-link text-muted m-0 p-0"
                                           style="line-height: 1;">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    </span>
                @endif
            </div>
        </form>
    </div>
@endif
@if($currentLogFile && $logLines)
    <div class="log-content-wrapper">
        <pre>{{ implode("\n", array_map('trim', $logLines)) }}</pre>
    </div>

    @php
        $totalPages = ceil($totalLines / $limit);
        $pageWindow = 2;
        $startPage = max(1, $page - $pageWindow);
        $endPage = min($totalPages, $page + $pageWindow);
    @endphp

    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <div class="text-muted">
            第 {{ $page }} 页，共 {{ $totalPages }} 页（共 {{ $totalLines }} 行）
        </div>
        <div class="pagination">
            @if($page > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" class="btn btn-outline-primary btn-sm">上一页</a>
            @else
                <span class="btn btn-outline-primary btn-sm disabled">上一页</span>
            @endif

            @if($startPage > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}" class="btn btn-outline-primary btn-sm">1</a>
                @if($startPage > 2)
                    <span class="px-2">...</span>
                @endif
            @endif

            @for ($i = $startPage; $i <= $endPage; $i++)
                @if($i == $page)
                    <span class="btn btn-primary btn-sm disabled">{{ $i }}</span>
                @else
                    <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="btn btn-outline-primary btn-sm">{{ $i }}</a>
                @endif
            @endfor

            @if($endPage < $totalPages)
                @if($endPage < $totalPages - 1)
                    <span class="px-2">...</span>
                @endif
                <a href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}" class="btn btn-outline-primary btn-sm">{{ $totalPages }}</a>
            @endif

            @if($page < $totalPages)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" class="btn btn-outline-primary btn-sm">下一页</a>
            @else
                <span class="btn btn-outline-primary btn-sm disabled">下一页</span>
            @endif
        </div>
    </div>
@else
    <p class="text-muted">日志为空或读取失败。</p>
@endif
