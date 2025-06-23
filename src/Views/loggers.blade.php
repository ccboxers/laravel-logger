<div class="container-fluid py-4 px-5">
    <form method="GET" action="{{ url()->current() }}" class="mb-4">
        <input type="hidden" name="directory" value="{{ request('directory') }}">
        <input type="hidden" name="filename" value="{{ request('filename') }}">

        <div class="container-fluid">
            <div class="row g-3 align-items-center">

                <div class="col-md-3 d-flex">
                    <label for="userid" class="form-label me-2 mb-0" style="white-space: nowrap;">用户标识:</label>
                    <input type="text" name="userid" id="userid" value="{{ request('userid') }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex">
                    <label for="model" class="form-label me-2 mb-0" style="white-space: nowrap;">数据模型:</label>
                    <input type="text" name="model" id="model" value="{{ request('model') }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex">
                    <label for="old" class="form-label me-2 mb-0" style="white-space: nowrap;">修改前:</label>
                    <input type="text" name="old" id="old" value="{{ request('old') }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex">
                    <label for="new" class="form-label me-2 mb-0" style="white-space: nowrap;">修改后:</label>
                    <input type="text" name="new" id="new" value="{{ request('new') }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex">
                    <label class="form-label me-2 mb-0" style="white-space: nowrap;">日期范围:</label>
                    <div class="input-group">
                        <input type="date" name="created_ats" value="{{ request('created_ats') }}" class="form-control">
                        <span class="input-group-text">~</span>
                        <input type="date" name="created_ate" value="{{ request('created_ate') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <button type="submit" class="btn btn-primary me-2">搜索</button>
                    <a href="{{ url()->current() }}?directory={{ request('directory') }}&filename={{ request('filename') }}" class="btn btn-secondary">重置</a>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm rounded bg-white" style="table-layout: fixed; width: 100%;">
            <thead class="table-light text-center">
            <tr>
                <th style="width: 80px;">用户标识</th>
                <th style="width: 80px;">事件类型</th>
                <th style="width: 200px;">模型</th>
                <th style="width: 200px;">旧数据</th>
                <th style="width: 200px;">新数据</th>
                <th style="width: 160px;">创建时间</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($loggers as $logger)
                <tr>
                    <td class="text-center text-muted small">{{ $logger->userid }}</td>
                    <td class="text-center text-muted small">{{ $logger->type ?? '---' }}</td>
                    <td class="text-center text-muted small">{{ $logger->model ?? '---'}}</td>

                    <td>
                        <div class="text-truncate" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;"
                             title="{{ json_encode($logger->old, JSON_UNESCAPED_UNICODE) }}"
                             data-bs-toggle="modal" data-bs-target="#logModal" data-bs-content="{{ json_encode($logger->old, JSON_UNESCAPED_UNICODE) }}">
                            {{ json_encode($logger->old, JSON_UNESCAPED_UNICODE) }}
                        </div>
                    </td>

                    <td>
                        <div class="text-truncate" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;"
                             title="{{ json_encode($logger->new, JSON_UNESCAPED_UNICODE) }}"
                             data-bs-toggle="modal" data-bs-target="#logModal" data-bs-content="{{ json_encode($logger->new, JSON_UNESCAPED_UNICODE) }}">
                            {{ json_encode($logger->new, JSON_UNESCAPED_UNICODE) }}
                        </div>
                    </td>

                    <td class="text-center text-muted small">{{ $logger->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">没有日志记录</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $loggers->withQueryString()->links() }}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logModalLabel">日志详情</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
            </div>
            <div class="modal-body">
                <pre id="logContent" style="white-space: pre-wrap;"></pre>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var logModal = document.getElementById('logModal');
        logModal.addEventListener('show.bs.modal', function (event) {
            var triggerDiv = event.relatedTarget;
            var content = triggerDiv.getAttribute('data-bs-content') || '';
            var modalBody = logModal.querySelector('#logContent');
            try {
                modalBody.textContent = JSON.stringify(JSON.parse(content), null, 2);
            } catch {
                modalBody.textContent = content;
            }
        });
    </script>
@endpush
