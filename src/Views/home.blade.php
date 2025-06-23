@extends('logger::layouts.app')

@section('content')
    <style>
        main {
            padding: 0 !important;
            height: calc(100vh - 56px);
            background-color: #f5f7fa;
        }

        .layout-body {
            display: flex;
            height: 100%;
        }

        .sidebar {
            width: 300px;
            background-color: #fff;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            font-size: 15px;
            color: #495057;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .nav-link:hover {
            background-color: #e7f1ff;
            color: #0d6efd;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            border-left-color: #0d6efd;
        }

        .submenu {
            padding-left: 1.5rem;
            background-color: #f9fbff;
        }

        .submenu .nav-link {
            padding: 8px 20px;
            font-size: 14px;
        }

        .submenu .nav-link:hover {
            background-color: #dbe9ff;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            background-color: #fff;
            overflow-y: auto;
            text-align: left; /* 保证左对齐 */
        }
    </style>
    <main>
        <div class="layout-body">
            <div class="sidebar" id="sidebarMenu">
                <nav class="nav flex-column">
                    <a class="nav-link {{ empty($menus['directory']) ? 'active' : '' }}" href="{{ route('logger.home') }}">
                        <i class="bi bi-house-door"></i> 控制台
                    </a>
                    @foreach($menus['logs'] as $key => $value)
                        <div>
                            <a class="nav-link collapsed d-flex align-items-center justify-content-between"
                               data-bs-toggle="collapse"
                               href="#{{ $key }}"
                               role="button"
                               aria-expanded="{{ $key === $menus['directory'] ? 'true' : 'false' }}"
                               aria-controls="{{ $key }}">
                                <span><i class="bi bi-folder"></i> {{ $key }}</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                            <div class="collapse submenu {{ $key === $menus['directory'] ? 'show' : '' }}" id="{{ $key }}">
                                <nav class="nav flex-column">
                                    @foreach($value as $k => $v)
                                        <a class="nav-link {{ $v['filename'] === $menus['filename'] ? 'active' : '' }}"
                                           href="{{ route('logger.home',['directory' => $key,'filename' => $v['filename'],'path' => $v['path']]) }}">
                                            <i class="bi bi-file-earmark me-2"></i>
                                            {{ $v['filename'] }}
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                    @endforeach
                </nav>
            </div>
            <div class="content-area" id="log">
                @if($currentLogFile)
                    @include('logger::filelog',[
                        $page,
                        $limit,
                        $currentLogFile,
                        $logLines,
                        $totalLines,
                    ])
                @elseif($loggers)
                    @include('logger::loggers',[
                        $loggers,
                    ])
                @else
                    <h4></h4>
                    <br>
                    <h5>请点击左侧菜单查看日志内容。</h5>
                @endif
            </div>
        </div>
    </main>
@endsection
