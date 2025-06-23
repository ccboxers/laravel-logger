<style>
    .form-inline-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-inline-group label {
        white-space: nowrap;
        margin-bottom: 0;
    }

</style>
<div class="container-fluid py-4 px-5">
    <form method="GET" action="{{ url()->current() }}" class="mb-4" autocomplete="off">
        <input type="hidden" name="directory" value="{{ request('directory') }}">
        <input type="hidden" name="filename" value="{{ request('filename') }}">

        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-4 form-inline-group">
                    <label for="userid">用户标识:</label>
                    <input type="text" name="userid" id="userid" value="{{ request('userid') }}" class="form-control">
                </div>

                <div class="col-md-4 form-inline-group">
                    <label for="model">数据模型:</label>
                    <input type="text" name="model" id="model" value="{{ request('model') }}" class="form-control">
                </div>

                <div class="col-md-4 form-inline-group">
                    <label for="old">修改前值:</label>
                    <input type="text" name="old" id="old" value="{{ request('old') }}" class="form-control">
                </div>

                <div class="col-md-4 form-inline-group">
                    <label for="new">修改后值:</label>
                    <input type="text" name="new" id="new" value="{{ request('new') }}" class="form-control">
                </div>

                <div class="col-md-4 form-inline-group">
                    <label>日期范围:</label>
                    <div class="input-group">
                        <input type="date" name="created_ats" value="{{ request('created_ats') }}" class="form-control">
                        <span class="input-group-text">~</span>
                        <input type="date" name="created_ate" value="{{ request('created_ate') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4 form-inline-group">
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
                    <td class="text-center text-muted small">{{ $logger->model ?? '---' }}</td>
                    <td
                        class="text-truncate"
                        style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#logModal"
                        data-bs-old="{{ json_encode($logger->old, JSON_UNESCAPED_UNICODE) }}"
                        data-bs-new="{{ json_encode($logger->new, JSON_UNESCAPED_UNICODE) }}"
                    >
                        {{ json_encode($logger->old, JSON_UNESCAPED_UNICODE) }}
                    </td>
                    <td
                        class="text-truncate"
                        style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#logModal"
                        data-bs-old="{{ json_encode($logger->old, JSON_UNESCAPED_UNICODE) }}"
                        data-bs-new="{{ json_encode($logger->new, JSON_UNESCAPED_UNICODE) }}"
                    >
                        {{ json_encode($logger->new, JSON_UNESCAPED_UNICODE) }}
                    </td>
                    <td class="text-center text-muted small">{{ $logger->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">没有日志记录</td>
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
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 1200px;">
        <div class="modal-content" style="height: 600px;">
            <div class="modal-header">
                <h5 class="modal-title" id="logModalLabel">日志详情（旧值 VS 新值）</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
            </div>
            <div class="modal-body" style="height: calc(100% - 58px);overflow-y: auto;overflow-x: hidden;padding: 16px;background-color: #f8f9fa;box-sizing: border-box;">
                <div class="d-flex gap-3" style="width: 100%;box-sizing: border-box;background-color: #f8f9fa;padding: 4px;border-radius: 0.25rem;flex-wrap: nowrap;">
                    <div style="width: 50%;background-color: #f8d7da;color: #721c24;padding: 12px;border: 1px solid #f5c6cb;border-radius: 0.5rem;box-sizing: border-box;overflow: visible;">
                        <pre id="logOld" style="margin: 0;padding: 0;line-height: 1.4;font-size: 14px;white-space: pre-wrap;word-break: break-word;"></pre>
                    </div>

                    <div style="width: 50%;background-color: #d4edda;color: #155724;padding: 12px;border: 1px solid #c3e6cb;border-radius: 0.5rem;box-sizing: border-box;overflow: visible;">
                        <pre id="logNew" style="margin: 0;padding: 0;line-height: 1.4;font-size: 14px;white-space: pre-wrap;word-break: break-word;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const logModal = document.getElementById('logModal');
        logModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            const oldContent = trigger.getAttribute('data-bs-old') || '{}';
            const newContent = trigger.getAttribute('data-bs-new') || '{}';

            const oldPre = logModal.querySelector('#logOld');
            const newPre = logModal.querySelector('#logNew');

            try {
                const parsedOld = JSON.parse(oldContent);
                const parsedNew = JSON.parse(newContent);
                oldPre.textContent = JSON.stringify(parsedOld, null, 2).trim();
                newPre.textContent = JSON.stringify(parsedNew, null, 2).trim();
            } catch {
                oldPre.textContent = oldContent.trim();
                newPre.textContent = newContent.trim();
            }
        });
    </script>
@endpush


