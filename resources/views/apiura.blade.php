<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>APIura</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        ide: {
                            bg: 'var(--ide-bg)',
                            surface: 'var(--ide-surface)',
                            sidebar: 'var(--ide-sidebar)',
                            border: 'var(--ide-border)',
                            primary: 'var(--ide-primary)',
                            secondary: 'var(--ide-secondary)',
                            fg: 'var(--ide-fg)',
                            muted: 'var(--ide-muted)',
                            gutter: 'var(--ide-gutter)',
                            'line-active': 'var(--ide-line-active)',
                            'tab-active': 'var(--ide-tab-active)',
                            'tab-inactive': 'var(--ide-tab-inactive)',
                        },
                        method: {
                            get: '#4ade80',
                            post: '#60a5fa',
                            put: '#fbbf24',
                            delete: '#f87171',
                            patch: '#fb923c',
                        }
                    },
                    fontSize: {
                        'ui': '13px',
                        'code': '12px',
                        'label': '11px',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'Fira Code', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- js-yaml for flow import/export -->
    <script src="https://cdn.jsdelivr.net/npm/js-yaml@4/dist/js-yaml.min.js"></script>

    <!-- Alpine.js CDN with collapse plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.8/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        /* CSS Variables for Light and Dark Themes - matched to Design B palette */
        :root {
            --ide-bg: #ffffff;
            --ide-surface: #f5f5f8;
            --ide-sidebar: #f0f0f5;
            --ide-border: #e2e4e8;
            --ide-primary: #ffc857;
            --ide-secondary: #bdd9bf;
            --ide-fg: #2e4052;
            --ide-muted: #6b7280;
            --ide-gutter: #b0b0b8;
            --ide-line-active: #f0f0f5;
            --ide-tab-active: #ffffff;
            --ide-tab-inactive: #f0f0f5;
            --ide-success-bg: rgba(34,197,94,0.1);
            --ide-success-text: #16a34a;
            --ide-warning-bg: rgba(234,179,8,0.1);
            --ide-warning-text: #a16207;
            --ide-error-bg: rgba(239,68,68,0.1);
            --ide-error-text: #dc2626;
            --ide-info-bg: rgba(59,130,246,0.1);
            --ide-info-text: #2563eb;
            --ide-required-bg: rgba(239,68,68,0.1);
            --ide-required-text: #b91c1c;
            --ide-optional-bg: #f3f4f6;
            --ide-optional-text: #6b7280;
            --ide-nullable-bg: rgba(234,179,8,0.1);
            --ide-nullable-text: #a16207;
            --ide-type-object-bg: rgba(168,85,247,0.1);
            --ide-type-object-text: #7c3aed;
            --ide-type-string-bg: rgba(34,197,94,0.1);
            --ide-type-string-text: #15803d;
            --ide-type-number-bg: rgba(59,130,246,0.1);
            --ide-type-number-text: #2563eb;
            --ide-type-boolean-bg: rgba(245,158,11,0.1);
            --ide-type-boolean-text: #b45309;
            --ide-json-key: #38bdf8;
            --ide-json-string: #4ade80;
            --ide-json-number: #fb923c;
            --ide-json-boolean: #c084fc;
            --ide-json-null: #f87171;
            --ide-json-punct: #64748b;
            --ide-header-key: #f472b6;
            --ide-header-value: #a78bfa;
            --ide-scrollbar-thumb: #c4c4cc;
            --ide-scrollbar-hover: #a4a4ac;
        }
        html.dark, .dark {
            --ide-bg: #1e1e2e;
            --ide-surface: #252536;
            --ide-sidebar: #1a1a2a;
            --ide-border: #2e2e44;
            --ide-primary: #84dcc6;
            --ide-secondary: #4b4e6d;
            --ide-fg: #eaecf0;
            --ide-muted: #8b8fa3;
            --ide-gutter: #3a3a52;
            --ide-line-active: #2a2a40;
            --ide-tab-active: #252536;
            --ide-tab-inactive: #1e1e2e;
            --ide-success-bg: rgba(34,197,94,0.15);
            --ide-success-text: #86efac;
            --ide-warning-bg: rgba(234,179,8,0.15);
            --ide-warning-text: #fde68a;
            --ide-error-bg: rgba(239,68,68,0.15);
            --ide-error-text: #fca5a5;
            --ide-info-bg: rgba(59,130,246,0.15);
            --ide-info-text: #93c5fd;
            --ide-required-bg: rgba(239,68,68,0.15);
            --ide-required-text: #fca5a5;
            --ide-optional-bg: #2a2a40;
            --ide-optional-text: #8b8fa3;
            --ide-nullable-bg: rgba(234,179,8,0.12);
            --ide-nullable-text: #fde68a;
            --ide-type-object-bg: rgba(168,85,247,0.15);
            --ide-type-object-text: #d8b4fe;
            --ide-type-string-bg: rgba(34,197,94,0.15);
            --ide-type-string-text: #86efac;
            --ide-type-number-bg: rgba(59,130,246,0.15);
            --ide-type-number-text: #93c5fd;
            --ide-type-boolean-bg: rgba(245,158,11,0.15);
            --ide-type-boolean-text: #fde68a;
            --ide-json-key: #7dd3fc;
            --ide-json-string: #86efac;
            --ide-json-number: #fdba74;
            --ide-json-boolean: #d8b4fe;
            --ide-json-null: #fca5a5;
            --ide-json-punct: #94a3b8;
            --ide-header-key: #f9a8d4;
            --ide-header-value: #c4b5fd;
            --ide-scrollbar-thumb: #3a3a52;
            --ide-scrollbar-hover: #4a4a62;
        }

        /* Base Styles */
        [x-cloak] { display: none !important; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; font-family: 'Inter', sans-serif; }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--ide-scrollbar-thumb); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--ide-scrollbar-hover); }

        /* Fix native date/select icons in dark mode */
        .dark input[type="date"],
        .dark input[type="datetime-local"] {
            color-scheme: dark;
        }

        /* Activity bar tooltip */
        .activity-tooltip {
            position: absolute;
            left: 56px;
            background: #3a3a52;
            color: #eaecf0;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 100;
        }
        .activity-item:hover .activity-tooltip { opacity: 1; }

        /* Action button tooltips */
        .action-btn { position: relative; }
        .action-tooltip {
            display: none;
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            padding: 3px 8px;
            background: var(--ide-fg);
            color: var(--ide-bg);
            font-size: 10px;
            border-radius: 4px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 50;
        }
        .action-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: var(--ide-fg);
        }
        .action-btn:hover .action-tooltip { display: block; }

        /* Tree guide lines */
        .tree-item { position: relative; }
        .tree-indent { position: relative; }
        .tree-indent::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: currentColor;
            opacity: 0.15;
        }

        /* Syntax highlighting - uses CSS variables for theme support */
        .syn-key, .json-key { color: var(--ide-json-key); }
        .syn-string, .json-string { color: var(--ide-json-string); }
        .syn-number, .json-number { color: var(--ide-json-number); }
        .syn-bool, .json-boolean { color: var(--ide-json-boolean); }
        .syn-null, .json-null { color: var(--ide-json-null); }
        .syn-punct { color: var(--ide-json-punct); }
        .syn-comment { color: var(--ide-muted); font-style: italic; }
        pre code { color: var(--ide-json-punct); }

        /* Header Syntax Highlighting */
        .header-key { color: var(--ide-header-key); font-weight: 600; }
        .header-value { color: var(--ide-header-value); }

        /* Gutter line numbers */
        .gutter-line {
            display: inline-block;
            width: 36px;
            text-align: right;
            padding-right: 12px;
            user-select: none;
            color: var(--ide-gutter);
        }

        /* Minimap */
        .minimap {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 48px;
            opacity: 0.5;
        }
        .minimap-line {
            height: 2px;
            margin: 1px 4px;
            border-radius: 1px;
        }

        /* Tab close button */
        .tab-close { opacity: 0; transition: opacity 0.1s; }
        .tab-item:hover .tab-close { opacity: 0.7; }
        .tab-close:hover { opacity: 1 !important; }

        /* Resizer visual */
        .panel-resizer {
            cursor: row-resize;
            position: relative;
        }
        .panel-resizer::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 32px;
            height: 3px;
            border-radius: 2px;
            opacity: 0;
            transition: opacity 0.15s;
        }
        .panel-resizer:hover::after { opacity: 0.5; }
        .panel-resizer::after { background: var(--ide-primary); }

        /* Sidebar resizer */
        .sidebar-resizer {
            cursor: col-resize;
            position: relative;
            z-index: 10;
        }
        .sidebar-resizer::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 3px;
            height: 32px;
            border-radius: 2px;
            background: var(--ide-primary);
            opacity: 0;
            transition: opacity 0.15s;
        }
        .sidebar-resizer:hover::after { opacity: 0.5; }

        /* Method badge colors - Design B (Swagger-inspired) */
        .badge-get { background: rgba(97,175,254,0.15); color: #61affe; }
        .badge-post { background: rgba(73,204,144,0.15); color: #49cc90; }
        .badge-put { background: rgba(252,161,48,0.15); color: #fca130; }
        .badge-delete { background: rgba(249,62,62,0.15); color: #f93e3e; }
        .badge-patch { background: rgba(80,227,194,0.15); color: #50e3c2; }

        /* Folder expand animation */
        .folder-children { overflow: hidden; transition: max-height 0.2s ease-out; }

        /* Toast animations */
        .toast-container {
            display: flex;
            flex-direction: column-reverse;
            gap: 0.5rem;
        }
        .toast-item {
            animation: toast-slide-in 0.3s ease-out forwards;
        }
        .toast-item.toast-exit {
            animation: toast-slide-out 0.2s ease-in forwards;
        }
        @keyframes toast-slide-in {
            from { opacity: 0; transform: translateX(1rem); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes toast-slide-out {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(1rem); }
        }

        /* Skeleton loading animation */
        @keyframes skeleton-pulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.7; }
        }
        .skeleton {
            animation: skeleton-pulse 1.5s ease-in-out infinite;
            background: linear-gradient(90deg, var(--ide-border) 25%, var(--ide-surface) 50%, var(--ide-border) 75%);
            background-size: 200% 100%;
        }

        /* Collapse transition */
        .collapse-transition {
            overflow: hidden;
            transition: max-height 0.2s ease-out, opacity 0.15s ease-out;
        }

        /* Field disabled state */
        .field-disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        /* Button hover effects */
        .btn-hover {
            transition: all 0.15s ease-out;
        }
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .btn-hover:active {
            transform: translateY(0);
        }

        /* Card hover effects */
        .card-hover {
            transition: box-shadow 0.2s ease-out, border-color 0.2s ease-out;
        }
        .card-hover:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Keyboard shortcut badge */
        .kbd {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.125rem 0.375rem;
            font-size: 0.625rem;
            font-family: ui-monospace, monospace;
            background: rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 0.25rem;
            box-shadow: 0 1px 0 rgba(0,0,0,0.1);
        }
        .dark .kbd {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.15);
        }

        /* Smooth content transitions */
        .content-fade {
            animation: content-fade-in 0.2s ease-out forwards;
        }
        @keyframes content-fade-in {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Focus visible for accessibility */
        .focus-visible-ring:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible {
            outline: 2px solid var(--ide-primary);
            outline-offset: 2px;
        }
    </style>
</head>

<body x-data="ideExplorer()" class="h-screen overflow-hidden bg-ide-bg text-ide-fg">
    <!-- IDE Layout -->
    <div class="flex flex-col h-screen">
        <div class="flex flex-1 overflow-hidden">

            <!-- Activity Bar -->
            <div class="hidden lg:flex w-12 bg-ide-surface border-r border-ide-border flex-col items-center py-2 flex-shrink-0">
                <!-- Explorer -->
                <button @click="showFlowsPanel = false; activeActivity = (activeActivity === 'explorer' ? (sidebarVisible = !sidebarVisible, activeActivity) : (sidebarVisible = true, 'explorer'))"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors"
                    :class="activeActivity === 'explorer' ? 'text-ide-fg bg-ide-bg border-l-2 border-ide-primary' : 'text-ide-muted hover:text-ide-fg'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span class="activity-tooltip">Explorer</span>
                </button>
                <!-- Saved Requests -->
                <button @click="showFlowsPanel = false; activeActivity = (activeActivity === 'saved' ? (sidebarVisible = !sidebarVisible, activeActivity) : (sidebarVisible = true, 'saved')); loadSavedRequests()"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors"
                    :class="activeActivity === 'saved' ? 'text-ide-fg bg-ide-bg border-l-2 border-ide-primary' : 'text-ide-muted hover:text-ide-fg'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    <span class="activity-tooltip">Saved</span>
                </button>
                <!-- Flows -->
                <button @click="activeActivity = 'flows'; showFlowsPanel = true; loadFlows(); loadModules()"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors"
                    :class="activeActivity === 'flows' ? 'text-ide-fg bg-ide-bg border-l-2 border-ide-primary' : 'text-ide-muted hover:text-ide-fg'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="activity-tooltip">Flows</span>
                </button>
                <!-- History -->
                <button @click="showFlowsPanel = false; activeActivity = (activeActivity === 'history' ? (sidebarVisible = !sidebarVisible, activeActivity) : (sidebarVisible = true, 'history'))"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors"
                    :class="activeActivity === 'history' ? 'text-ide-fg bg-ide-bg border-l-2 border-ide-primary' : 'text-ide-muted hover:text-ide-fg'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="activity-tooltip">History</span>
                    <span x-show="requestHistory.length > 0" class="absolute top-1 right-1 w-2 h-2 bg-ide-primary rounded-full"></span>
                </button>
                <!-- Telescope -->
                <template x-if="telescopeEnabled">
                    <button @click="showTelescope = !showTelescope; if(showTelescope && telescopeEntries.length === 0) loadTelescopeEntries()"
                        class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors text-ide-muted hover:text-ide-fg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span class="activity-tooltip">Telescope</span>
                    </button>
                </template>
                <!-- Database Schema -->
                <button @click="showSchemaViewer = true; loadDbSchema()"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors text-ide-muted hover:text-ide-fg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    <span class="activity-tooltip">Database Schema</span>
                </button>
                <!-- API Map -->
                <button @click="showRelationshipGraph = true"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors text-ide-muted hover:text-ide-fg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span class="activity-tooltip">API Map</span>
                </button>
                <!-- Export -->
                <div class="relative" x-data="{ exportMenuOpen: false }" @click.away="exportMenuOpen = false">
                    <button @click="exportMenuOpen = !exportMenuOpen"
                        class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors text-ide-muted hover:text-ide-fg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="activity-tooltip">Export</span>
                    </button>
                    <div x-show="exportMenuOpen" x-transition x-cloak
                        class="absolute left-12 bottom-0 w-52 bg-ide-surface border border-ide-border rounded-lg shadow-lg py-1 z-50">
                        <button @click="exportToPostman(); exportMenuOpen = false" class="w-full px-3 py-2 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Postman Collection
                        </button>
                        <button @click="exportToMarkdown(); exportMenuOpen = false" class="w-full px-3 py-2 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Markdown Docs
                        </button>
                        <div class="px-3 py-1 text-[10px] font-semibold text-ide-muted uppercase tracking-wider">OpenAPI Export</div>
                        <a href="/apiura/export-openapi/with-cases" class="w-full px-3 py-2 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2 no-underline" @click="exportMenuOpen = false">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            OpenAPI + Cases
                        </a>
                        <button @click="exportOpenApiJson(); exportMenuOpen = false" class="w-full px-3 py-2 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            OpenAPI + Examples
                        </button>
                        <button @click="exportOpenApiClean(); exportMenuOpen = false" class="w-full px-3 py-2 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            OpenAPI Clean
                        </button>
                        <button @click="copyAllEndpointsAsCurl(); exportMenuOpen = false" class="w-full px-3 py-2 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            Copy All as cURL
                        </button>
                        <div class="border-t border-ide-border my-1"></div>
                        <a href="/apiura/export-md" class="w-full px-3 py-2 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2 no-underline">
                            <svg class="w-4 h-4 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Full MD
                        </a>
                    </div>
                </div>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg mb-1 transition-colors text-ide-muted hover:text-ide-fg">
                    <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/></svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                    <span class="activity-tooltip" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                </button>
                <!-- Settings -->
                <button @click="showSettings = true"
                    class="activity-item relative w-10 h-10 flex items-center justify-center rounded-lg transition-colors text-ide-muted hover:text-ide-fg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="activity-tooltip">Settings</span>
                </button>
            </div>

            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="lg:hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-sm" x-cloak></div>

            <!-- Sidebar -->
            <aside
                x-show="(sidebarVisible || sidebarOpen) && !showFlowsPanel"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-2"
                class="bg-ide-sidebar border-r border-ide-border flex-col flex-shrink-0 overflow-hidden hidden lg:flex"
                :class="sidebarOpen ? '!flex fixed inset-y-0 left-0 z-50 shadow-2xl w-64' : ''"
                :style="!sidebarOpen ? 'width: ' + sidebarWidth + 'px' : ''"
            >
                <!-- Mobile Activity Tabs (visible only in mobile overlay) -->
                <div class="lg:hidden flex items-center border-b border-ide-border bg-ide-surface overflow-x-auto flex-shrink-0">
                    <button @click="activeActivity = 'explorer'" class="flex-shrink-0 px-3 py-2 text-[10px] font-medium uppercase border-b-2 transition-colors" :class="activeActivity === 'explorer' ? 'border-ide-primary text-ide-fg' : 'border-transparent text-ide-muted'">Explorer</button>
                    <button @click="activeActivity = 'saved'; loadSavedRequests()" class="flex-shrink-0 px-3 py-2 text-[10px] font-medium uppercase border-b-2 transition-colors" :class="activeActivity === 'saved' ? 'border-ide-primary text-ide-fg' : 'border-transparent text-ide-muted'">Saved</button>
                    <button @click="activeActivity = 'flows'; showFlowsPanel = true; loadFlows(); loadModules(); sidebarOpen = false;" class="flex-shrink-0 px-3 py-2 text-[10px] font-medium uppercase border-b-2 transition-colors" :class="activeActivity === 'flows' ? 'border-ide-primary text-ide-fg' : 'border-transparent text-ide-muted'">Flows</button>
                    <button @click="activeActivity = 'history'" class="flex-shrink-0 px-3 py-2 text-[10px] font-medium uppercase border-b-2 transition-colors" :class="activeActivity === 'history' ? 'border-ide-primary text-ide-fg' : 'border-transparent text-ide-muted'">History</button>
                    <div class="flex-1"></div>
                    <button @click="sidebarOpen = false" class="flex-shrink-0 p-2 text-ide-muted hover:text-ide-fg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <!-- Sidebar Header -->
                <div class="px-3 py-2 text-[11px] font-semibold uppercase tracking-wider text-ide-muted border-b border-ide-border flex items-center justify-between">
                    <span x-text="activeActivity === 'explorer' ? 'EXPLORER' : activeActivity === 'saved' ? 'SAVED REQUESTS' : activeActivity === 'history' ? 'HISTORY' : 'PANEL'"></span>
                    <span class="text-[10px] font-normal" x-text="endpointCount + ' endpoints'"></span>
                </div>

                <!-- Explorer Panel -->
                <div x-show="activeActivity === 'explorer'" class="flex-1 flex flex-col overflow-hidden">
                    <!-- Search Input -->
                    <div class="p-2 border-b border-ide-border">
                        <div class="relative">
                            <input
                                type="text"
                                x-ref="searchInput"
                                x-model="searchQuery"
                                placeholder="Search endpoints..."
                                class="w-full px-2 py-1.5 pl-8 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg placeholder-ide-muted focus:outline-none focus:ring-1 focus:ring-ide-primary"
                            >
                            <svg class="absolute left-2 top-2 h-3.5 w-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <button x-show="searchQuery" x-cloak @click="searchQuery = ''" class="absolute right-2 top-2 text-ide-muted hover:text-ide-fg">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Endpoint Tree -->
                    <nav class="flex-1 overflow-y-auto px-1 py-1">
                        <template x-for="(endpoints, tag) in filteredEndpointsByTag" :key="tag">
                            <div class="mb-0.5">
                                <!-- Tag Folder -->
                                <button
                                    @click="toggleTag(tag)"
                                    class="w-full flex items-center gap-1.5 px-2 py-1 text-xs text-ide-fg hover:bg-ide-line-active rounded transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5 text-ide-muted transition-transform duration-150 flex-shrink-0" :class="{ 'rotate-90': isTagExpanded(tag) }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" :class="isTagExpanded(tag) ? 'text-yellow-500' : 'text-ide-muted'" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                    <span class="truncate font-medium" x-text="tag"></span>
                                    <span class="ml-auto text-[10px] text-ide-muted" x-text="endpoints.length"></span>
                                </button>

                                <!-- Endpoints in tag -->
                                <div x-show="isTagExpanded(tag)" class="ml-3 tree-indent">
                                    <template x-for="endpoint in endpoints" :key="endpoint.method + endpoint.path">
                                        <div>
                                            <div class="flex items-center">
                                                <button
                                                    @click="selectEndpoint(endpoint)"
                                                    class="tree-item flex-1 flex items-center gap-2 px-2 py-1 text-xs rounded transition-colors text-left min-w-0"
                                                    :class="isSelectedEndpoint(endpoint)
                                                        ? 'bg-ide-line-active text-ide-fg'
                                                        : 'text-ide-muted hover:text-ide-fg hover:bg-ide-line-active'"
                                                >
                                                    <span class="flex-shrink-0 px-1 py-0.5 text-[9px] font-bold rounded uppercase min-w-[2.5rem] text-center"
                                                        :class="'badge-' + endpoint.method.toLowerCase()"
                                                        x-text="endpoint.method"></span>
                                                    <span class="truncate font-mono text-[11px]" x-text="endpoint.path"></span>
                                                    <!-- Saved count & comment status badges -->
                                                    <template x-if="getEndpointSavedInfo(endpoint.method, endpoint.path)">
                                                        <span class="flex items-center gap-1 ml-auto flex-shrink-0">
                                                            <span class="px-1 py-0.5 text-[9px] rounded bg-ide-border text-ide-muted"
                                                                x-text="getEndpointSavedInfo(endpoint.method, endpoint.path).count + ' saved'"></span>
                                                            <template x-if="getEndpointSavedInfo(endpoint.method, endpoint.path).commentsCount > 0">
                                                                <span class="flex items-center gap-0.5">
                                                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                                                        :class="getEndpointSavedInfo(endpoint.method, endpoint.path).highestStatus ? getStatusDotColor(getEndpointSavedInfo(endpoint.method, endpoint.path).highestStatus) : 'bg-gray-400'"></span>
                                                                    <span class="text-[9px] text-ide-muted" x-text="getEndpointSavedInfo(endpoint.method, endpoint.path).commentsCount"></span>
                                                                </span>
                                                            </template>
                                                        </span>
                                                    </template>
                                                </button>
                                                <!-- Expand arrow for saved requests preview -->
                                                <template x-if="getEndpointSavedInfo(endpoint.method, endpoint.path)">
                                                    <button
                                                        @click.stop="toggleEndpointSaved(endpoint.method, endpoint.path)"
                                                        class="p-1 text-ide-muted hover:text-ide-fg transition-colors flex-shrink-0"
                                                        title="Show saved requests"
                                                    >
                                                        <svg class="w-3 h-3 transition-transform duration-150" :class="{ 'rotate-90': isEndpointSavedExpanded(endpoint.method, endpoint.path) }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                                    </button>
                                                </template>
                                            </div>
                                            <!-- Collapsible saved requests preview -->
                                            <template x-if="getEndpointSavedInfo(endpoint.method, endpoint.path) && isEndpointSavedExpanded(endpoint.method, endpoint.path)">
                                                <div class="ml-6 mt-0.5 mb-1 space-y-0.5">
                                                    <template x-for="saved in getEndpointSavedInfo(endpoint.method, endpoint.path).requests" :key="saved.id">
                                                        <div class="group flex items-center gap-1 px-2 py-0.5 text-[10px] rounded hover:bg-ide-line-active transition-colors text-ide-muted hover:text-ide-fg">
                                                            <button @click="loadSavedRequest(saved)" class="flex-1 flex items-center gap-1.5 text-left min-w-0">
                                                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                                                    :class="saved.highest_comment_status ? getStatusDotColor(saved.highest_comment_status) : 'bg-gray-600'"></span>
                                                                <span class="truncate" x-text="saved.name || saved.path"></span>
                                                                <span x-show="saved.response_status" class="flex-shrink-0 font-mono font-bold"
                                                                    :class="saved.response_status >= 500 ? 'text-red-500' : saved.response_status >= 400 ? 'text-orange-500' : saved.response_status >= 300 ? 'text-yellow-500' : 'text-green-500'"
                                                                    x-text="saved.response_status"></span>
                                                            </button>
                                                            <button @click.stop="viewSavedRequestComments(saved)"
                                                                class="flex-shrink-0 p-0.5 rounded transition-colors"
                                                                :class="saved.comments_count > 0 ? getStatusTextColor(saved.highest_comment_status) : 'opacity-0 group-hover:opacity-100 text-ide-muted'"
                                                                title="Comments">
                                                                <span x-show="saved.comments_count > 0" class="text-[9px]" x-text="saved.comments_count"></span>
                                                                <svg class="w-2.5 h-2.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <template x-if="getEndpointSavedInfo(endpoint.method, endpoint.path).hasMore">
                                                        <button
                                                            @click="activeActivity = 'saved'; loadSavedRequests()"
                                                            class="w-full text-left px-2 py-0.5 text-[10px] text-ide-primary hover:underline"
                                                        >
                                                            View all...
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- No results -->
                        <div x-show="Object.keys(filteredEndpointsByTag).length === 0" class="text-center py-6 text-ide-muted">
                            <p class="text-xs">No endpoints found</p>
                        </div>
                    </nav>
                </div>

                <!-- Saved Requests Panel -->
                <div x-show="activeActivity === 'saved'" class="flex-1 flex flex-col overflow-hidden">
                    <div class="p-2 border-b border-ide-border">
                        <input type="text" x-model="savedRequestFilter" placeholder="Filter saved..."
                            class="w-full px-2 py-1.5 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg placeholder-ide-muted focus:outline-none focus:ring-1 focus:ring-ide-primary">
                    </div>
                    <div class="flex-1 overflow-y-auto p-1">
                        <template x-for="req in getFilteredSavedRequests()" :key="req.id">
                            <div class="group flex items-center gap-1 px-2 py-1.5 text-xs rounded hover:bg-ide-line-active transition-colors">
                                <button @click="loadSavedRequest(req)" class="flex-1 flex items-center gap-2 text-left min-w-0">
                                    <span class="flex-shrink-0 px-1 py-0.5 text-[9px] font-bold rounded uppercase"
                                        :class="'badge-' + req.method.toLowerCase()" x-text="req.method"></span>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="truncate text-ide-fg" x-text="req.name || req.path"></span>
                                            <span x-show="req.response_status" class="flex-shrink-0 px-1 py-0.5 text-[9px] font-mono font-bold rounded"
                                                :class="req.response_status >= 500 ? 'bg-[var(--ide-error-bg)] text-[var(--ide-error-text)]' : req.response_status >= 400 ? 'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]' : req.response_status >= 300 ? 'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]' : 'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)]'"
                                                x-text="req.response_status"></span>
                                        </div>
                                        <div class="truncate text-[10px] text-ide-muted font-mono" x-text="req.path"></div>
                                    </div>
                                </button>
                                <button @click.stop="viewSavedRequestComments(req)"
                                    class="flex-shrink-0 p-1 rounded transition-colors relative"
                                    :class="req.comments_count > 0 ? getStatusTextColor(req.highest_comment_status) : 'text-ide-muted opacity-0 group-hover:opacity-100 hover:text-ide-fg'"
                                    title="Comments">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <span x-show="req.comments_count > 0" class="absolute -top-1 -right-1 w-3 h-3 text-white text-[7px] font-bold rounded-full flex items-center justify-center"
                                        :class="req.highest_comment_status ? getStatusDotColor(req.highest_comment_status) : 'bg-gray-400'"
                                        x-text="req.comments_count"></span>
                                </button>
                                <button @click.stop="deleteSavedRequest(req.id, $event)"
                                    class="flex-shrink-0 p-1 rounded transition-colors text-ide-muted opacity-0 group-hover:opacity-100 hover:text-red-500"
                                    title="Delete">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="getFilteredSavedRequests().length === 0" class="text-center py-6 text-ide-muted text-xs">
                            No saved requests yet.
                        </div>
                    </div>
                </div>

                <div x-show="activeActivity === 'history'" class="flex-1 flex flex-col overflow-hidden">
                    <div class="p-2 border-b border-ide-border flex gap-1">
                        <input type="text" x-model="historyFilter" placeholder="Filter history..."
                            class="flex-1 px-2 py-1.5 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg placeholder-ide-muted focus:outline-none focus:ring-1 focus:ring-ide-primary">
                        <button @click="clearHistory()" class="px-2 py-1.5 text-xs text-ide-muted hover:text-red-500 rounded hover:bg-ide-line-active" title="Clear history">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-1">
                        <template x-for="(item, idx) in getFilteredHistory()" :key="idx">
                            <button @click="let ep = allEndpoints.find(e => e.method === item.method && e.path === item.path); if(ep) selectEndpoint(ep);"
                                class="w-full flex items-center gap-2 px-2 py-1.5 text-xs rounded hover:bg-ide-line-active transition-colors text-left">
                                <span class="flex-shrink-0 px-1 py-0.5 text-[9px] font-bold rounded uppercase"
                                    :class="'badge-' + item.method.toLowerCase()" x-text="item.method"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="truncate font-mono text-ide-fg text-[11px]" x-text="item.path"></div>
                                    <div class="flex items-center gap-2 text-[10px] text-ide-muted">
                                        <span x-show="item.status" class="font-mono font-bold"
                                            :class="item.status >= 400 ? 'text-red-500' : 'text-green-500'"
                                            x-text="item.status"></span>
                                        <span x-show="item.duration" x-text="item.duration + 'ms'"></span>
                                        <span x-text="formatHistoryTime(item.timestamp)"></span>
                                    </div>
                                </div>
                            </button>
                        </template>
                        <div x-show="getFilteredHistory().length === 0" class="text-center py-6 text-ide-muted text-xs">
                            <template x-if="requestHistory.length === 0">
                                <div>
                                    <p>No request history yet.</p>
                                    <p class="mt-1 text-[10px]">Send a request to start recording.</p>
                                </div>
                            </template>
                            <template x-if="requestHistory.length > 0">
                                <p>No matching history entries.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Resize Handle -->
            <div
                x-show="sidebarVisible && !sidebarOpen && !showFlowsPanel"
                class="sidebar-resizer w-1.5 bg-ide-border/30 flex-shrink-0 hover:bg-ide-primary/30 transition-colors hidden lg:block"
                @mousedown="startSidebarResize($event)"
                @dblclick="sidebarWidth = 256"
            ></div>

            <!-- Main Editor Area -->
            <div x-show="!showFlowsPanel" class="flex-1 flex flex-col overflow-hidden">
                <!-- Tab Bar -->
                <div class="h-9 bg-ide-tab-inactive border-b border-ide-border flex items-center overflow-x-auto flex-shrink-0">
                    <!-- Hamburger menu for mobile/tablet -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden flex-shrink-0 w-9 h-9 flex items-center justify-center text-ide-muted hover:text-ide-fg hover:bg-ide-line-active border-r border-ide-border">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <template x-for="tab in openTabs" :key="tab.id">
                        <div class="tab-item flex items-center h-full px-3 gap-2 border-r border-ide-border cursor-pointer text-xs whitespace-nowrap select-none"
                            :class="activeTabId === tab.id ? 'bg-ide-tab-active text-ide-fg border-t-2 border-t-ide-primary' : 'bg-ide-tab-inactive text-ide-muted hover:bg-ide-line-active'"
                            @click="switchToTab(tab.id)">
                            <span class="px-1 py-0.5 text-[9px] font-bold rounded uppercase" :class="'badge-' + tab.method.toLowerCase()" x-text="tab.method"></span>
                            <span class="font-mono text-[11px]" x-text="tab.path.split('/').slice(-2).join('/')"></span>
                            <button class="tab-close ml-1 p-0.5 rounded hover:bg-ide-border" @click.stop="closeTab(tab.id)">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                    <!-- New tab / welcome -->
                    <div x-show="openTabs.length === 0" class="px-3 h-full flex items-center text-xs text-ide-muted">
                        <span>Welcome</span>
                    </div>
                </div>

                <!-- Auth Bar (compact) -->
                <div class="px-3 py-1.5 bg-ide-surface border-b border-ide-border flex items-center gap-2 text-xs flex-shrink-0" x-show="selectedEndpoint">
                    <!-- Auth Status -->
                    <div class="flex items-center gap-2">
                        <template x-if="authToken">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                <span class="text-ide-muted">Authenticated</span>
                                <code class="text-[10px] text-ide-muted font-mono" x-text="'Bearer ' + authToken.substring(0, 16) + '...'"></code>
                                <button @click="logout()" class="text-red-400 hover:text-red-300 ml-1">Logout</button>
                            </div>
                        </template>
                        <template x-if="!authToken">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                <span class="text-ide-muted">Not authenticated</span>
                                <button @click="showLoginForm = !showLoginForm" class="text-ide-primary hover:underline ml-1" x-text="showLoginForm ? 'Hide' : 'Auth'"></button>
                            </div>
                        </template>
                    </div>
                    <!-- Auth Form (inline) -->
                    <div x-show="showLoginForm && !authToken" class="flex items-center gap-2 ml-2" x-cloak>
                        <!-- Mode Toggle -->
                        <div class="flex bg-ide-border rounded p-0.5">
                            <button @click="authMode = 'token'" class="px-2 py-0.5 text-[10px] font-medium rounded transition-colors" :class="authMode === 'token' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Token</button>
                            <button @click="authMode = 'login'" class="px-2 py-0.5 text-[10px] font-medium rounded transition-colors" :class="authMode === 'login' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Login</button>
                        </div>
                        <!-- Token Input -->
                        <template x-if="authMode === 'token'">
                            <div class="flex items-center gap-1.5">
                                <input type="password" x-model="tokenInput" placeholder="Paste Bearer token..." @keydown.enter="setToken(tokenInput)" class="px-2 py-1 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg w-56 font-mono">
                                <button @click="setToken(tokenInput)" :disabled="!tokenInput" class="px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-500 disabled:opacity-50 transition-colors">Set</button>
                            </div>
                        </template>
                        <!-- Login Form -->
                        <template x-if="authMode === 'login'">
                            <div class="flex items-center gap-1.5">
                                <input type="text" x-model="loginEmail" placeholder="Email" class="px-2 py-1 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg w-36" :class="{ 'border-red-400': loginError }">
                                <input type="password" x-model="loginPassword" placeholder="Password" @keydown.enter="performLogin()" class="px-2 py-1 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg w-28" :class="{ 'border-red-400': loginError }">
                                <button @click="performLogin()" :disabled="loginLoading" class="px-2 py-1 text-xs bg-ide-primary text-white rounded hover:opacity-90 disabled:opacity-50 flex items-center gap-1">
                                    <svg x-show="loginLoading" class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                    <span x-text="loginLoading ? 'Logging in...' : 'Login'"></span>
                                </button>
                                <span x-show="loginError" x-text="loginError" class="text-[10px] text-red-500 max-w-48 truncate" :title="loginError"></span>
                            </div>
                        </template>
                    </div>
                    <!-- Environment Switcher -->
                    <div class="ml-auto flex items-center gap-2">
                        <select x-model="currentEnvironment" @change="switchEnvironment(currentEnvironment)"
                            class="px-2 py-0.5 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg">
                            <option value="default">Default</option>
                            <template x-for="env in environments" :key="env.id">
                                <option :value="env.id" x-text="env.name"></option>
                            </template>
                        </select>
                        <button @click="showEnvModal = true" class="text-ide-muted hover:text-ide-fg" title="Manage Environments">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Editor Content Area -->
                <div class="flex-1 flex flex-col bg-ide-bg overflow-hidden">

                    <!-- Welcome Screen (no endpoint selected) -->
                    <template x-if="!selectedEndpoint">
                        <div class="flex-1 overflow-y-auto flex items-center justify-center">
                            <div class="max-w-lg w-full px-6 content-fade-enter">
                                <div class="text-center mb-8">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-ide-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h2 class="text-xl font-bold text-ide-fg mb-2">Welcome to APIura</h2>
                                    <p class="text-ui text-ide-muted">Select an endpoint from the sidebar to get started.</p>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="bg-ide-surface border border-ide-border rounded-lg p-3 text-center">
                                        <p class="text-label text-ide-muted mb-1">API Title</p>
                                        <p class="text-ui font-semibold text-ide-fg truncate" x-text="spec.info?.title || 'N/A'"></p>
                                    </div>
                                    <div class="bg-ide-surface border border-ide-border rounded-lg p-3 text-center">
                                        <p class="text-label text-ide-muted mb-1">Version</p>
                                        <p class="text-ui font-semibold text-ide-fg" x-text="spec.info?.version || 'N/A'"></p>
                                    </div>
                                    <div class="bg-ide-surface border border-ide-border rounded-lg p-3 text-center">
                                        <p class="text-label text-ide-muted mb-1">Base URL</p>
                                        <p class="text-code font-semibold text-ide-fg truncate" x-text="spec.servers?.[0]?.url || 'N/A'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Endpoint Details (shown when endpoint selected) -->
                    <template x-if="selectedEndpoint">
                        <div class="flex-1 flex flex-col overflow-hidden content-fade-enter">

                            <!-- Breadcrumb Bar -->
                            <div class="h-7 flex items-center px-4 gap-1.5 text-label bg-ide-surface border-b border-ide-border flex-shrink-0">
                                <span :class="getMethodBadgeClass(selectedEndpoint.method)" class="px-1.5 py-0.5 text-[9px] font-bold rounded uppercase" x-text="selectedEndpoint.method"></span>
                                <svg class="w-3 h-3 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="text-ide-muted" x-text="selectedEndpoint.tags?.[0] || 'Untagged'"></span>
                                <svg class="w-3 h-3 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="font-mono text-ide-fg truncate" x-text="selectedEndpoint.path"></span>
                                <template x-if="isAuthRequired(selectedEndpoint)">
                                    <span class="ml-1 px-1.5 py-0.5 text-[9px] font-medium bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)] rounded">Auth</span>
                                </template>
                                <span x-show="selectedEndpoint.operationId" class="ml-auto text-ide-muted font-mono truncate max-w-[150px]" x-text="selectedEndpoint.operationId"></span>
                            </div>

                            <!-- URL Bar -->
                            <div class="flex items-center gap-2 px-4 py-2 border-b border-ide-border bg-ide-bg flex-shrink-0">
                                <span :class="getMethodBadgeClass(selectedEndpoint.method)" class="px-2 py-1 text-xs font-bold rounded uppercase flex-shrink-0" x-text="selectedEndpoint.method"></span>
                                <code class="flex-1 text-ui font-mono text-ide-fg truncate" x-text="builtUrl || getFullPath(selectedEndpoint.path)"></code>
                                <!-- Send Button -->
                                <button
                                    @click="sendRequest()"
                                    :disabled="requestLoading"
                                    class="btn-hover px-4 py-1.5 bg-ide-primary hover:opacity-90 text-white text-xs font-bold rounded flex items-center gap-1.5 disabled:opacity-50 flex-shrink-0"
                                >
                                    <svg x-show="!requestLoading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                    <svg x-show="requestLoading" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                    <span x-text="requestLoading ? 'Sending...' : 'Send'"></span>
                                    <span class="hidden sm:inline-flex items-center gap-0.5 ml-1 opacity-60">
                                        <span class="kbd text-[8px]" x-text="isMac ? '⌘' : 'Ctrl'"></span>
                                        <span class="kbd text-[8px]">↵</span>
                                    </span>
                                </button>
                                <!-- Action Buttons -->
                                <div class="flex items-center gap-1 flex-shrink-0 border-l border-ide-border pl-2">
                                    <button x-show="methodRequiresBody(selectedEndpoint?.method)" @click="rawJsonMode = !rawJsonMode; if (rawJsonMode) { syncToRawJson() } else { syncFromRawJson() }" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active" :class="rawJsonMode ? 'text-ide-primary' : ''">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                        <span class="action-tooltip">Raw JSON</span>
                                    </button>
                                    <button @click="prefillWithExamples()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span class="action-tooltip">Fill Examples</span>
                                    </button>
                                    <button @click="clearEndpointState()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span class="action-tooltip">Clear</span>
                                    </button>
                                    <button @click="openSaveModal()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                        <span class="action-tooltip">Save</span>
                                    </button>
                                    <button @click="activeActivity = 'saved'; sidebarVisible = true; loadSavedRequests()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                        <span class="action-tooltip">Saved</span>
                                    </button>
                                    <button x-show="selectedSavedRequest" @click="viewSavedRequestComments(selectedSavedRequest)" class="action-btn p-1.5 rounded hover:bg-ide-line-active relative"
                                        :class="selectedSavedRequest?.highest_comment_status ? getStatusTextColor(selectedSavedRequest.highest_comment_status) : 'text-ide-muted hover:text-ide-fg'">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        <span x-show="selectedSavedRequest?.comments_count > 0" class="absolute -top-1 -right-1 w-3.5 h-3.5 text-white text-[8px] font-bold rounded-full flex items-center justify-center"
                                            :class="selectedSavedRequest?.highest_comment_status ? getStatusDotColor(selectedSavedRequest.highest_comment_status) : 'bg-gray-400'"
                                            x-text="selectedSavedRequest.comments_count"></span>
                                        <span class="action-tooltip">Comments</span>
                                    </button>
                                    <button @click="copyAsJson()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span class="action-tooltip">Copy JSON</span>
                                    </button>
                                    <button @click="copyCurlCommand()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="action-tooltip">Copy cURL</span>
                                    </button>
                                    <button @click="copyAsJavaScriptFetch()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0V0zm22.034 18.276c-.175-1.095-.888-2.015-3.003-2.873-.736-.345-1.554-.585-1.797-1.14-.091-.33-.105-.51-.046-.705.15-.646.915-.84 1.515-.66.39.12.75.42.976.9 1.034-.676 1.034-.676 1.755-1.125-.27-.42-.405-.6-.586-.78-.63-.705-1.469-1.065-2.834-1.034l-.705.089c-.676.165-1.32.525-1.71 1.005-1.14 1.291-.811 3.541.569 4.471 1.365 1.02 3.361 1.244 3.616 2.205.24 1.17-.87 1.545-1.966 1.41-.811-.18-1.26-.586-1.755-1.336l-1.83 1.051c.21.48.45.689.81 1.109 1.74 1.756 6.09 1.666 6.871-1.004.029-.09.24-.705.074-1.65l.046.067zm-8.983-7.245h-2.248c0 1.938-.009 3.864-.009 5.805 0 1.232.063 2.363-.138 2.711-.33.689-1.18.601-1.566.48-.396-.196-.597-.466-.83-.855-.063-.105-.11-.196-.127-.196l-1.825 1.125c.305.63.75 1.172 1.324 1.517.855.51 2.004.675 3.207.405.783-.226 1.458-.691 1.811-1.411.51-.93.402-2.07.397-3.346.012-2.054 0-4.109 0-6.179l.004-.056z"/></svg>
                                        <span class="action-tooltip">Copy JS</span>
                                    </button>
                                    <button @click="copyAsTypeScriptFetch()" class="action-btn p-1.5 text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M1.125 0C.502 0 0 .502 0 1.125v21.75C0 23.498.502 24 1.125 24h21.75c.623 0 1.125-.502 1.125-1.125V1.125C24 .502 23.498 0 22.875 0zm17.363 9.75c.612 0 1.154.037 1.627.111a6.38 6.38 0 0 1 1.306.34v2.458a3.95 3.95 0 0 0-.643-.361 5.093 5.093 0 0 0-.717-.26 5.453 5.453 0 0 0-1.426-.2c-.3 0-.573.028-.819.086a2.1 2.1 0 0 0-.623.242c-.17.104-.3.229-.393.374a.888.888 0 0 0-.14.49c0 .196.053.373.156.529.104.156.252.304.443.444s.42.276.69.394.57.24.905.346c.478.148.89.313 1.24.494.35.182.642.394.874.636.232.242.402.52.512.835.108.315.163.682.163 1.101 0 .608-.118 1.12-.354 1.533-.235.413-.563.75-.985 1.01-.42.26-.92.445-1.5.555-.572.11-1.2.165-1.887.165-.681 0-1.33-.062-1.946-.185-.615-.124-1.177-.315-1.686-.572v-2.69a5.2 5.2 0 0 0 .856.496c.32.146.642.26.968.34.324.08.634.129.93.148a5.76 5.76 0 0 0 .677-.015c.228-.03.427-.075.598-.137.168-.064.3-.15.398-.263a.592.592 0 0 0 .146-.411c0-.202-.059-.38-.176-.532-.118-.152-.29-.293-.52-.424-.228-.128-.507-.252-.84-.37a8.1 8.1 0 0 0-1.101-.358c-.466-.15-.87-.33-1.21-.539a3.048 3.048 0 0 1-.832-.696 2.654 2.654 0 0 1-.476-.933 4.016 4.016 0 0 1-.15-1.14c0-.573.108-1.074.324-1.502s.52-.787.911-1.078c.39-.29.854-.506 1.392-.647.536-.142 1.12-.213 1.748-.213zM14.09 9.918h2.844v.082H14.09v4.95h-2.01V9.918H9.243V9.75h4.847v.168z"/></svg>
                                        <span class="action-tooltip">Copy TS</span>
                                    </button>
                                </div>
                            </div>

                            <!-- ========== TRY IT MODE: Split Panel Layout ========== -->
                            <div x-show="activeTab === 'try-it'" class="flex-1 flex flex-col overflow-hidden">

                                <!-- Top Panel: Editor -->
                                <div class="flex-1 flex flex-col overflow-hidden" style="min-height: 120px">

                                    <!-- Editor Tab Bar -->
                                    <div class="flex items-center border-b border-ide-border bg-ide-surface flex-shrink-0">
                                        <template x-for="tab in [
                                            {id: 'request', label: 'Request'},
                                            {id: 'preview', label: 'Preview'},
                                            {id: 'docs', label: 'Docs'}
                                        ]" :key="tab.id">
                                            <button
                                                @click="editorTab = tab.id"
                                                class="px-3 py-2 text-code font-medium border-b-2 transition-colors flex items-center gap-1.5"
                                                :class="editorTab === tab.id
                                                    ? 'border-ide-primary text-ide-fg'
                                                    : 'border-transparent text-ide-muted hover:text-ide-fg'"
                                            >
                                                <span x-text="tab.label"></span>
                                            </button>
                                        </template>
                                        <!-- Right side: mode tabs -->
                                        <div class="ml-auto flex items-center gap-1 pr-2">
                                            <button @click="activeTab = 'visual'" class="px-2 py-1 text-label text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">Visual</button>
                                            <button @click="activeTab = 'all'" class="px-2 py-1 text-label text-ide-muted hover:text-ide-fg rounded hover:bg-ide-line-active">All</button>
                                        </div>
                                    </div>

                                    <!-- Editor Tab Content -->
                                    <div class="flex-1 overflow-y-auto" @input="debouncedSaveState()" @change="debouncedSaveState()">

                                        <!-- ===== Request Tab (Params + Body + Headers combined) ===== -->
                                        <div x-show="editorTab === 'request'" class="px-4 py-3">
                                            <!-- Form View -->
                                            <div class="space-y-6">
                                                <!-- Path Parameters -->
                                                <template x-if="getPathParameters(selectedEndpoint).length > 0">
                                                    <div>
                                                        <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider mb-3 flex items-center gap-2">
                                                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                                                            Path Parameters
                                                        </h4>
                                                        <div class="space-y-2">
                                                            <template x-for="param in getPathParameters(selectedEndpoint)" :key="param.name">
                                                                <div class="flex items-center gap-3">
                                                                    <label class="w-36 text-code font-medium text-ide-fg flex items-center gap-1">
                                                                        <span x-text="param.name"></span>
                                                                        <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input
                                                                        type="text"
                                                                        :placeholder="getParamPlaceholder(param)"
                                                                        x-model="requestState.pathParams[param.name]"
                                                                        class="flex-1 px-3 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary focus:border-ide-primary"
                                                                    >
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Query Parameters -->
                                                <template x-if="selectedEndpoint">
                                                    <div>
                                                        <div class="flex items-center justify-between mb-3">
                                                            <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider flex items-center gap-2">
                                                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                                                Query Parameters
                                                            </h4>
                                                            <div class="flex flex-wrap items-center gap-1">
                                                                <button @click="addCustomQueryParam()" type="button" class="px-2 py-0.5 text-[10px] font-semibold text-[var(--ide-success-text)] border border-[var(--ide-success-text)]/50 hover:bg-[var(--ide-success-text)] hover:text-white rounded transition-colors flex items-center gap-0.5">
                                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                                    Add Param
                                                                </button>
                                                                <template x-if="isListEndpoint(selectedEndpoint)">
                                                                    <span class="contents">
                                                                        <span class="w-px h-3 bg-ide-border"></span>
                                                                        <button x-show="showQuickAddCategory(selectedEndpoint, 'pagination')" @click="addPaginationParams()" class="px-1.5 py-0.5 text-[10px] font-medium text-[var(--ide-info-text)] bg-[var(--ide-info-bg)] rounded hover:opacity-80 transition-colors">+ Pagination</button>
                                                                        <button x-show="showQuickAddCategory(selectedEndpoint, 'sorting')" @click="addSortParams()" class="px-1.5 py-0.5 text-[10px] font-medium text-ide-primary bg-ide-primary/10 rounded hover:bg-ide-primary/20 transition-colors">+ Sort</button>
                                                                        <button x-show="showQuickAddCategory(selectedEndpoint, 'date_range')" @click="addDateRangeParams()" class="px-1.5 py-0.5 text-[10px] font-medium text-[var(--ide-warning-text)] bg-[var(--ide-warning-bg)] rounded hover:opacity-80 transition-colors">+ Date Range</button>
                                                                        <button x-show="showQuickAddCategory(selectedEndpoint, 'search')" @click="addSearchParam()" class="px-1.5 py-0.5 text-[10px] font-medium text-[var(--ide-success-text)] bg-[var(--ide-success-bg)] rounded hover:opacity-80 transition-colors">+ Search</button>
                                                                    </span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                        <div class="space-y-2">
                                                            <template x-for="param in getAllQueryParameters(selectedEndpoint)" :key="param.name">
                                                                <div class="flex items-center gap-2 group">
                                                                    <input type="checkbox" :checked="requestState.queryParamsEnabled[param.name]" @change="toggleQueryParam(param.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                    <div class="w-32 flex flex-col">
                                                                        <template x-if="!param.isDynamic">
                                                                            <label class="text-code font-medium text-ide-fg flex items-center gap-1">
                                                                                <span x-text="param.name"></span>
                                                                                <template x-if="param.required"><span class="text-red-500">*</span></template>
                                                                            </label>
                                                                        </template>
                                                                        <template x-if="param.isDynamic">
                                                                            <input type="text" :value="param.name" @change="renameQueryParam(param.name, $event.target.value)" @keydown.enter="$event.target.blur()" placeholder="param name" class="text-code font-medium text-ide-fg bg-transparent border-b border-dashed border-ide-border focus:border-blue-500 outline-none px-0 py-0 w-full">
                                                                        </template>
                                                                        <span class="text-[10px] px-1 py-0.5 rounded w-fit font-medium" :class="getParamTypeColor(param)" x-text="getParamTypeLabel(param)"></span>
                                                                    </div>
                                                                    <div class="flex-1" :class="{ 'field-disabled': !requestState.queryParamsEnabled[param.name] }">
                                                                        <template x-if="param.schema?.enum">
                                                                            <select x-model="requestState.queryParams[param.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                <option value="">Select...</option>
                                                                                <template x-for="opt in param.schema.enum" :key="opt"><option :value="opt" x-text="opt"></option></template>
                                                                            </select>
                                                                        </template>
                                                                        <template x-if="!param.schema?.enum && param.schema?.type === 'boolean'">
                                                                            <select x-model="requestState.queryParams[param.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                <option value="">Select...</option>
                                                                                <option value="true">true</option>
                                                                                <option value="false">false</option>
                                                                            </select>
                                                                        </template>
                                                                        <template x-if="!param.schema?.enum && param.schema?.type === 'integer'">
                                                                            <input type="number" x-model="requestState.queryParams[param.name]" :placeholder="param.default !== undefined ? 'Default: ' + param.default : (cleanDescription(param.description) || 'Enter number...')" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                        </template>
                                                                        <template x-if="!param.schema?.enum && param.schema?.type !== 'boolean' && param.schema?.type !== 'integer' && param.schema?.format === 'date'">
                                                                            <input type="date" x-model="requestState.queryParams[param.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                        </template>
                                                                        <template x-if="!param.schema?.enum && param.schema?.type !== 'boolean' && param.schema?.type !== 'integer' && param.schema?.format !== 'date' && isQueryParamDateField(param)">
                                                                            <input type="datetime-local" x-model="requestState.queryParams[param.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                        </template>
                                                                        <template x-if="!param.schema?.enum && param.schema?.type !== 'boolean' && param.schema?.type !== 'integer' && param.schema?.format !== 'date' && !isQueryParamDateField(param)">
                                                                            <input type="text" :placeholder="param.default !== undefined ? 'Default: ' + param.default : (cleanDescription(param.description) || 'Enter value...')" x-model="requestState.queryParams[param.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                        </template>
                                                                    </div>
                                                                    <div class="flex items-center gap-0.5">
                                                                        <button x-show="requestState.queryParams[param.name] && requestState.queryParams[param.name] !== ''" @click="clearQueryParamValue(param.name)" class="p-1 text-gray-400 hover:text-ide-fg rounded transition-colors" title="Clear">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                        </button>
                                                                        <template x-if="param.isDynamic">
                                                                            <button @click="removeQueryParam(param.name)" class="p-1 text-[var(--ide-error-text)] hover:opacity-80 rounded transition-colors" title="Remove">
                                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                            </button>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <template x-if="getAllQueryParameters(selectedEndpoint).length === 0">
                                                                <div class="text-center py-3 text-ide-muted text-code">No query parameters defined. Use "Add Param" or the preset buttons above.</div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>


                                                <!-- No Path Parameters Message (query params section always shows its own empty state) -->
                                                <template x-if="getPathParameters(selectedEndpoint).length === 0 && getAllQueryParameters(selectedEndpoint).length === 0 && !selectedEndpoint">
                                                    <div class="text-center py-6 text-ide-muted">
                                                        <svg class="mx-auto h-6 w-6 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        <p class="text-code">This endpoint does not require any parameters.</p>
                                                    </div>
                                                </template>

                                                <!-- ===== Headers Section ===== -->
                                                <div class="border-t border-ide-border pt-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                                                    Custom Headers
                                                </h4>
                                                <button
                                                    x-on:click.prevent.stop="requestState.headerIdCounter++; requestState.customHeaders.push({ id: requestState.headerIdCounter, key: '', value: '', enabled: true })"
                                                    type="button"
                                                    class="px-2 py-0.5 text-[10px] font-semibold text-[var(--ide-success-text)] border border-[var(--ide-success-text)]/50 hover:bg-[var(--ide-success-text)] hover:text-white rounded transition-colors flex items-center gap-0.5"
                                                >
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                    Add Header
                                                </button>
                                            </div>
                                            <div class="space-y-2">
                                                <template x-for="header in requestState.customHeaders" :key="header.id">
                                                    <div class="flex items-center gap-2">
                                                        <input type="checkbox" x-model="header.enabled" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                        <input type="text" x-model="header.key" placeholder="Header name" class="flex-1 px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary font-mono" :class="{ 'opacity-50': !header.enabled }">
                                                        <input type="text" x-model="header.value" placeholder="Value" class="flex-1 px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary font-mono" :class="{ 'opacity-50': !header.enabled }">
                                                        <button x-on:click.prevent.stop="requestState.customHeaders = requestState.customHeaders.filter(h => h.id !== header.id)" type="button" class="p-1 text-gray-400 hover:text-red-500 transition-colors" x-show="requestState.customHeaders.length > 1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                                </div>

                                                <!-- ===== Body Section (hidden for GET/DELETE requests and in raw JSON mode) ===== -->
                                                <div x-show="!rawJsonMode && methodRequiresBody(selectedEndpoint?.method)">
                                                <template x-if="hasRequestBody(selectedEndpoint)">
                                                    <div>
                                                        <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider mb-3 flex items-center gap-2">
                                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                            Request Body
                                                        </h4>
                                                        <div class="space-y-2">
                                                            <template x-for="field in getRequestBodyFieldsForBuilder(selectedEndpoint)" :key="field.name">
                                                                <div>
                                                                    <template x-if="field.type === 'object' && field.nestedFields && field.nestedFields.length > 0">
                                                                        <div class="border border-ide-border rounded bg-ide-bg" x-data="{ collapsed: true }">
                                                                            <button @click="collapsed = !collapsed" type="button" class="w-full px-3 py-2 flex items-center justify-between text-left hover:bg-ide-line-active rounded-t transition-colors">
                                                                                <div class="flex items-center gap-2">
                                                                                    <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @click.stop="toggleBodyField(field.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                    <span class="font-medium text-code text-ide-fg" x-text="field.name"></span>
                                                                                    <template x-if="field.required"><span class="text-red-500 text-code">*</span></template>
                                                                                    <template x-if="field.nullable"><span class="text-[10px] text-gray-500 bg-ide-border px-1.5 py-0.5 rounded">nullable</span></template>
                                                                                    <span class="text-[10px] text-ide-primary bg-ide-primary/10 px-1.5 py-0.5 rounded">object</span>
                                                                                </div>
                                                                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': !collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                                            </button>
                                                                            <div x-show="!collapsed" class="px-3 py-2 border-t border-ide-border" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <div class="space-y-2">
                                                                                    <template x-for="nestedField in field.nestedFields" :key="nestedField.name">
                                                                                        <div class="flex items-center gap-2">
                                                                                            <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name + '.' + nestedField.name]" @change="toggleBodyField(field.name + '.' + nestedField.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                            <label class="w-32 text-code font-medium text-ide-fg flex items-center gap-0.5"><span x-text="nestedField.name"></span><template x-if="nestedField.required"><span class="text-red-500">*</span></template></label>
                                                                                            <div class="flex-1" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name + '.' + nestedField.name] }">
                                                                                                <template x-if="nestedField.enum && nestedField.enum.length > 0">
                                                                                                    <select x-model="requestState.body[field.name][nestedField.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary"><option value="">Select...</option><template x-for="opt in nestedField.enum" :key="opt"><option :value="opt" x-text="opt"></option></template></select>
                                                                                                </template>
                                                                                                <template x-if="!nestedField.enum && nestedField.type === 'boolean'">
                                                                                                    <input type="checkbox" x-model="requestState.body[field.name][nestedField.name]" class="w-4 h-4 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                                </template>
                                                                                                <template x-if="!nestedField.enum && (nestedField.type === 'integer' || nestedField.type === 'number')">
                                                                                                    <input type="number" :step="nestedField.type === 'number' ? 'any' : '1'" :placeholder="getFieldPlaceholder(nestedField)" x-model="requestState.body[field.name][nestedField.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                                </template>
                                                                                                <template x-if="!nestedField.enum && nestedField.type !== 'boolean' && nestedField.type !== 'integer' && nestedField.type !== 'number' && isDateField(nestedField)">
                                                                                                    <input type="datetime-local" x-model="requestState.body[field.name][nestedField.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                                </template>
                                                                                                <template x-if="!nestedField.enum && nestedField.type !== 'boolean' && nestedField.type !== 'integer' && nestedField.type !== 'number' && !isDateField(nestedField) && !nestedField.isFile">
                                                                                                    <input type="text" :placeholder="getFieldPlaceholder(nestedField)" x-model="requestState.body[field.name][nestedField.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                                </template>
                                                                                            </div>
                                                                                        </div>
                                                                                    </template>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <!-- Single file field (non-array) -->
                                                                    <template x-if="field.isFile && !field.isArray">
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @change="toggleBodyField(field.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                            <label class="w-36 text-code font-medium text-ide-fg flex items-center gap-0.5">
                                                                                <span x-text="field.name" class="break-all"></span>
                                                                                <template x-if="field.required"><span class="text-red-500">*</span></template>
                                                                            </label>
                                                                            <div class="flex-1 flex items-center gap-2" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <label class="flex-1 flex items-center gap-2 px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg cursor-pointer hover:border-ide-primary transition-colors">
                                                                                    <svg class="w-4 h-4 text-ide-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                                                    <span x-text="requestState.bodyFiles[field.name]?.name || 'Choose file...'" class="truncate" :class="requestState.bodyFiles[field.name] ? 'text-ide-fg' : 'text-ide-muted'"></span>
                                                                                    <input type="file" class="hidden" @change="requestState.bodyFiles[field.name] = $event.target.files[0] || null">
                                                                                </label>
                                                                                <template x-if="requestState.bodyFiles[field.name]">
                                                                                    <button @click="requestState.bodyFiles[field.name] = null" class="p-1 text-gray-400 hover:text-red-500 transition-colors shrink-0">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                                    </button>
                                                                                </template>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <!-- Array of files -->
                                                                    <template x-if="field.isArray && field.arrayItemIsFile">
                                                                        <div class="border border-ide-border rounded bg-ide-bg" x-data="{ collapsed: true }">
                                                                            <button @click="collapsed = !collapsed" type="button" class="w-full px-3 py-2 flex items-center justify-between text-left hover:bg-ide-line-active rounded-t transition-colors">
                                                                                <div class="flex items-center gap-2">
                                                                                    <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @click.stop="toggleBodyField(field.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                    <span class="font-medium text-code text-ide-fg" x-text="field.name"></span>
                                                                                    <template x-if="field.required"><span class="text-red-500 text-code">*</span></template>
                                                                                    <span class="text-[10px] text-orange-600 bg-orange-100 dark:text-orange-400 dark:bg-orange-900/30 px-1.5 py-0.5 rounded">file[]</span>
                                                                                    <span class="text-[10px] text-gray-500" x-text="(requestState.bodyFiles[field.name] || []).length + ' files'"></span>
                                                                                </div>
                                                                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': !collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                                            </button>
                                                                            <div x-show="!collapsed" class="px-3 py-2 border-t border-ide-border" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <div class="space-y-1.5">
                                                                                    <template x-for="(file, idx) in (requestState.bodyFiles[field.name] || [])" :key="idx">
                                                                                        <div class="flex items-center gap-1.5 px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-xs">
                                                                                            <svg class="w-3.5 h-3.5 text-ide-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                                                            <span class="flex-1 truncate text-ide-fg" x-text="file.name"></span>
                                                                                            <button @click="requestState.bodyFiles[field.name].splice(idx, 1)" class="p-0.5 text-gray-400 hover:text-red-500 transition-colors shrink-0">
                                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </template>
                                                                                    <label class="inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-semibold text-[var(--ide-success-text)] border border-[var(--ide-success-text)]/50 hover:bg-[var(--ide-success-text)] hover:text-white rounded transition-colors cursor-pointer">
                                                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                                                        Add File
                                                                                        <input type="file" class="hidden" @change="if (!Array.isArray(requestState.bodyFiles[field.name])) requestState.bodyFiles[field.name] = []; if ($event.target.files[0]) requestState.bodyFiles[field.name].push($event.target.files[0]); $event.target.value = ''">
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <!-- Array of dates -->
                                                                    <template x-if="field.isArray && isArrayItemDate(field) && (!field.nestedFields || field.nestedFields.length === 0) && !field.arrayItemIsFile">
                                                                        <div class="border border-ide-border rounded bg-ide-bg" x-data="{ collapsed: true }">
                                                                            <button @click="collapsed = !collapsed" type="button" class="w-full px-3 py-2 flex items-center justify-between text-left hover:bg-ide-line-active rounded-t transition-colors">
                                                                                <div class="flex items-center gap-2">
                                                                                    <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @click.stop="toggleBodyField(field.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                    <span class="font-medium text-code text-ide-fg" x-text="field.name"></span>
                                                                                    <template x-if="field.required"><span class="text-red-500 text-code">*</span></template>
                                                                                    <span class="text-[10px] text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900/30 px-1.5 py-0.5 rounded">date[]</span>
                                                                                    <span class="text-[10px] text-gray-500" x-text="(requestState.body[field.name] || []).length + ' items'"></span>
                                                                                </div>
                                                                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': !collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                                            </button>
                                                                            <div x-show="!collapsed" class="px-3 py-2 border-t border-ide-border" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <div class="space-y-1.5">
                                                                                    <template x-for="(item, idx) in (requestState.body[field.name] || [])" :key="idx">
                                                                                        <div class="flex items-center gap-1.5">
                                                                                            <input type="datetime-local" :value="item" @input="requestState.body[field.name][idx] = $event.target.value" class="flex-1 px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary text-xs">
                                                                                            <button @click="requestState.body[field.name].splice(idx, 1)" class="p-0.5 text-gray-400 hover:text-red-500 transition-colors shrink-0">
                                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </template>
                                                                                    <button @click="if (!Array.isArray(requestState.body[field.name])) requestState.body[field.name] = []; requestState.body[field.name].push('')" class="px-2 py-0.5 text-[10px] font-semibold text-[var(--ide-success-text)] border border-[var(--ide-success-text)]/50 hover:bg-[var(--ide-success-text)] hover:text-white rounded transition-colors flex items-center gap-0.5">
                                                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                                                        Add Item
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <!-- Array of objects -->
                                                                    <template x-if="field.isArray && field.nestedFields && field.nestedFields.length > 0">
                                                                        <div class="border border-ide-border rounded bg-ide-bg" x-data="{ collapsed: true }">
                                                                            <button @click="collapsed = !collapsed" type="button" class="w-full px-3 py-2 flex items-center justify-between text-left hover:bg-ide-line-active rounded-t transition-colors">
                                                                                <div class="flex items-center gap-2">
                                                                                    <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @click.stop="toggleBodyField(field.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                    <span class="font-medium text-code text-ide-fg" x-text="field.name"></span>
                                                                                    <template x-if="field.required"><span class="text-red-500 text-code">*</span></template>
                                                                                    <span class="text-[10px] text-ide-primary bg-ide-primary/10 px-1.5 py-0.5 rounded">object[]</span>
                                                                                    <span class="text-[10px] text-gray-500" x-text="(requestState.body[field.name] || []).length + ' items'"></span>
                                                                                </div>
                                                                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': !collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                                            </button>
                                                                            <div x-show="!collapsed" class="px-3 py-2 border-t border-ide-border" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <div class="space-y-3">
                                                                                    <template x-for="(entry, entryIdx) in (requestState.body[field.name] || [])" :key="entryIdx">
                                                                                        <div class="border border-ide-border/60 rounded p-2 bg-ide-bg/50">
                                                                                            <div class="flex items-center justify-between mb-2">
                                                                                                <span class="text-[10px] font-semibold text-ide-muted uppercase tracking-wider" x-text="'Item ' + (entryIdx + 1)"></span>
                                                                                                <button @click="requestState.body[field.name].splice(entryIdx, 1)" class="p-0.5 text-gray-400 hover:text-red-500 transition-colors">
                                                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="space-y-1.5">
                                                                                                <template x-for="nf in field.nestedFields" :key="nf.name">
                                                                                                    <div class="flex items-center gap-2">
                                                                                                        <label class="w-28 text-code text-xs font-medium text-ide-fg flex items-center gap-0.5"><span x-text="nf.name"></span><template x-if="nf.required"><span class="text-red-500">*</span></template></label>
                                                                                                        <div class="flex-1">
                                                                                                            <template x-if="nf.enum && nf.enum.length > 0">
                                                                                                                <select x-model="requestState.body[field.name][entryIdx][nf.name]" class="w-full px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary text-xs"><option value="">Select...</option><template x-for="opt in nf.enum" :key="opt"><option :value="opt" x-text="opt"></option></template></select>
                                                                                                            </template>
                                                                                                            <template x-if="!nf.enum && nf.type === 'boolean'">
                                                                                                                <input type="checkbox" x-model="requestState.body[field.name][entryIdx][nf.name]" class="w-4 h-4 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                                            </template>
                                                                                                            <template x-if="!nf.enum && (nf.type === 'integer' || nf.type === 'number')">
                                                                                                                <input type="number" :step="nf.type === 'number' ? 'any' : '1'" :placeholder="nf.name" x-model="requestState.body[field.name][entryIdx][nf.name]" class="w-full px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary text-xs">
                                                                                                            </template>
                                                                                                            <template x-if="!nf.enum && nf.type !== 'boolean' && nf.type !== 'integer' && nf.type !== 'number' && isDateField(nf)">
                                                                                                                <input type="datetime-local" x-model="requestState.body[field.name][entryIdx][nf.name]" class="w-full px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary text-xs">
                                                                                                            </template>
                                                                                                            <template x-if="!nf.enum && nf.type !== 'boolean' && nf.type !== 'integer' && nf.type !== 'number' && !isDateField(nf)">
                                                                                                                <input type="text" :placeholder="nf.name" x-model="requestState.body[field.name][entryIdx][nf.name]" class="w-full px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary text-xs">
                                                                                                            </template>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </template>
                                                                                            </div>
                                                                                        </div>
                                                                                    </template>
                                                                                    <button @click="if (!Array.isArray(requestState.body[field.name])) requestState.body[field.name] = []; const newItem = {}; field.nestedFields.forEach(nf => newItem[nf.name] = nf.type === 'boolean' ? false : ''); requestState.body[field.name].push(newItem)" class="px-2 py-0.5 text-[10px] font-semibold text-[var(--ide-success-text)] border border-[var(--ide-success-text)]/50 hover:bg-[var(--ide-success-text)] hover:text-white rounded transition-colors flex items-center gap-0.5">
                                                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                                                        Add Item
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <!-- Array field (primitive items: text/number, not dates or files) -->
                                                                    <template x-if="field.isArray && (!field.nestedFields || field.nestedFields.length === 0) && !field.isFile && !field.arrayItemIsFile && !isArrayItemDate(field)">
                                                                        <div class="border border-ide-border rounded bg-ide-bg" x-data="{ collapsed: true }">
                                                                            <button @click="collapsed = !collapsed" type="button" class="w-full px-3 py-2 flex items-center justify-between text-left hover:bg-ide-line-active rounded-t transition-colors">
                                                                                <div class="flex items-center gap-2">
                                                                                    <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @click.stop="toggleBodyField(field.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                    <span class="font-medium text-code text-ide-fg" x-text="field.name"></span>
                                                                                    <template x-if="field.required"><span class="text-red-500 text-code">*</span></template>
                                                                                    <span class="text-[10px] text-teal-600 bg-teal-100 dark:text-teal-400 dark:bg-teal-900/30 px-1.5 py-0.5 rounded" x-text="field.arrayItemType + '[]'"></span>
                                                                                    <span class="text-[10px] text-gray-500" x-text="(requestState.body[field.name] || []).length + ' items'"></span>
                                                                                </div>
                                                                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': !collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                                            </button>
                                                                            <div x-show="!collapsed" class="px-3 py-2 border-t border-ide-border" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <div class="space-y-1.5">
                                                                                    <template x-for="(item, idx) in (requestState.body[field.name] || [])" :key="idx">
                                                                                        <div class="flex items-center gap-1.5">
                                                                                            <input :type="field.arrayItemType === 'integer' || field.arrayItemType === 'number' ? 'number' : 'text'" :step="field.arrayItemType === 'number' ? 'any' : '1'" :value="item" @input="requestState.body[field.name][idx] = field.arrayItemType === 'integer' ? parseInt($event.target.value) || 0 : field.arrayItemType === 'number' ? parseFloat($event.target.value) || 0 : $event.target.value" :placeholder="'Item ' + (idx + 1)" class="flex-1 px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary text-xs">
                                                                                            <button @click="requestState.body[field.name].splice(idx, 1)" class="p-0.5 text-gray-400 hover:text-red-500 transition-colors shrink-0">
                                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </template>
                                                                                    <button @click="if (!Array.isArray(requestState.body[field.name])) requestState.body[field.name] = []; requestState.body[field.name].push('')" class="px-2 py-0.5 text-[10px] font-semibold text-[var(--ide-success-text)] border border-[var(--ide-success-text)]/50 hover:bg-[var(--ide-success-text)] hover:text-white rounded transition-colors flex items-center gap-0.5">
                                                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                                                        Add Item
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <!-- Object fields without nested properties (rendered as JSON textarea) -->
                                                                    <template x-if="!field.isFile && !field.isArray && field.type === 'object' && (!field.nestedFields || field.nestedFields.length === 0)">
                                                                        <div class="flex items-start gap-2">
                                                                            <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @change="toggleBodyField(field.name)" class="w-3.5 h-3.5 mt-2 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                            <label class="w-36 text-code font-medium text-ide-fg flex items-center gap-0.5 mt-1.5">
                                                                                <span x-text="field.name" class="break-all"></span>
                                                                                <template x-if="field.required"><span class="text-red-500">*</span></template>
                                                                            </label>
                                                                            <div class="flex-1" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <textarea rows="3" :placeholder="'{ JSON object }'" x-model="requestState.body[field.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary font-mono text-xs"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <!-- Scalar fields (non-file, non-array, non-object) -->
                                                                    <template x-if="!field.isFile && !field.isArray && field.type !== 'object'">
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="checkbox" :checked="requestState.bodyFieldsEnabled[field.name]" @change="toggleBodyField(field.name)" class="w-3.5 h-3.5 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                            <label class="w-36 text-code font-medium text-ide-fg flex items-center gap-0.5">
                                                                                <span x-text="field.name" class="break-all"></span>
                                                                                <template x-if="field.required"><span class="text-red-500">*</span></template>
                                                                            </label>
                                                                            <div class="flex-1" :class="{ 'field-disabled': !requestState.bodyFieldsEnabled[field.name] }">
                                                                                <template x-if="field.enum && field.enum.length > 0">
                                                                                    <select x-model="requestState.body[field.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary"><option value="">Select...</option><template x-for="opt in field.enum" :key="opt"><option :value="opt" x-text="opt"></option></template></select>
                                                                                </template>
                                                                                <template x-if="!field.enum && field.type === 'boolean'">
                                                                                    <input type="checkbox" x-model="requestState.body[field.name]" class="w-4 h-4 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                                </template>
                                                                                <template x-if="!field.enum && (field.type === 'integer' || field.type === 'number')">
                                                                                    <input type="number" :step="field.type === 'number' ? 'any' : '1'" :placeholder="getFieldPlaceholder(field)" x-model="requestState.body[field.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                </template>
                                                                                <template x-if="!field.enum && field.type !== 'boolean' && field.type !== 'integer' && field.type !== 'number' && isDateField(field)">
                                                                                    <input type="datetime-local" x-model="requestState.body[field.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                </template>
                                                                                <template x-if="!field.enum && field.type !== 'boolean' && field.type !== 'integer' && field.type !== 'number' && !isDateField(field)">
                                                                                    <input type="text" :placeholder="getFieldPlaceholder(field)" x-model="requestState.body[field.name]" class="w-full px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                                </template>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                                <!-- Custom Fields -->
                                                <div class="border-t border-ide-border pt-3">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider flex items-center gap-2">Custom Fields</h4>
                                                        <button @click="if (!requestState.customFields) requestState.customFields = []; requestState.customFields.push({ id: Date.now(), name: '', value: '', type: 'string', enabled: true })" class="px-2 py-0.5 text-[10px] font-semibold text-[var(--ide-success-text)] border border-[var(--ide-success-text)]/50 hover:bg-[var(--ide-success-text)] hover:text-white rounded transition-colors flex items-center gap-0.5">
                                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                            Add Field
                                                        </button>
                                                    </div>
                                                    <template x-if="requestState.customFields && requestState.customFields.length > 0">
                                                        <div class="space-y-2">
                                                            <template x-for="(field, idx) in requestState.customFields" :key="field.id">
                                                                <div class="flex items-start gap-2">
                                                                    <input type="checkbox" x-model="field.enabled" class="w-3.5 h-3.5 mt-2 text-primary-600 border-ide-border rounded focus:ring-primary-500">
                                                                    <div class="flex-1 grid grid-cols-3 gap-2">
                                                                        <input type="text" x-model="field.name" placeholder="Field name" class="px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                        <select x-model="field.type" class="px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg focus:ring-1 focus:ring-ide-primary">
                                                                            <option value="string">String</option><option value="integer">Integer</option><option value="number">Number</option><option value="boolean">Boolean</option><option value="array">Array (JSON)</option><option value="object">Object (JSON)</option><option value="file">File</option>
                                                                        </select>
                                                                        <template x-if="field.type === 'file'"><input type="file" @change="field.value = $event.target.files[0]; field.fileName = $event.target.files[0]?.name" class="px-2 py-1 border border-ide-border rounded text-code bg-ide-bg text-ide-fg"></template>
                                                                        <template x-if="field.type === 'boolean'"><select x-model="field.value" class="px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg"><option value="true">true</option><option value="false">false</option></select></template>
                                                                        <template x-if="field.type !== 'file' && field.type !== 'boolean'"><input type="text" x-model="field.value" :placeholder="field.type === 'integer' ? '0' : field.type === 'array' ? '[1, 2, 3]' : 'Value'" class="px-2 py-1.5 border border-ide-border rounded text-code bg-ide-bg text-ide-fg"></template>
                                                                    </div>
                                                                    <button @click="requestState.customFields.splice(idx, 1)" class="p-1 text-gray-400 hover:text-red-500 transition-colors mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                                <template x-if="!hasRequestBody(selectedEndpoint) && (!requestState.customFields || requestState.customFields.length === 0)">
                                                    <div class="text-center py-6 text-ide-muted"><p class="text-code">No request body for this endpoint.</p></div>
                                                </template>
                                                </div>
                                            <!-- Raw JSON View (hidden for GET/DELETE requests) -->
                                            <div x-show="rawJsonMode && methodRequiresBody(selectedEndpoint?.method)">
                                                <textarea x-model="rawJsonBody" @input="syncFromRawJson(); debouncedSaveState()" rows="12" class="w-full px-3 py-2 font-mono text-code border border-ide-border rounded bg-ide-surface text-ide-fg focus:ring-1 focus:ring-ide-primary resize-none" placeholder="{ }" spellcheck="false"></textarea>
                                                <template x-if="jsonParseError"><p class="mt-1 text-code text-[var(--ide-error-text)]" x-text="jsonParseError"></p></template>
                                            </div>
                                            </div>
                                        </div>

                                        <!-- ===== Preview Tab ===== -->
                                        <div x-show="editorTab === 'preview'" class="px-4 py-3 space-y-4">
                                            <div>
                                                <label class="block text-label font-semibold text-ide-muted uppercase tracking-wider mb-1">URL</label>
                                                <div class="flex items-center gap-2">
                                                    <span :class="getMethodBadgeClass(selectedEndpoint.method)" class="px-1.5 py-0.5 text-[10px] font-bold rounded uppercase" x-text="selectedEndpoint.method"></span>
                                                    <code class="flex-1 px-2 py-1.5 bg-ide-surface rounded text-code font-mono text-ide-fg break-all" x-text="builtUrl"></code>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-label font-semibold text-ide-muted uppercase tracking-wider mb-1">Headers</label>
                                                <pre class="px-3 py-2 bg-ide-surface rounded text-code font-mono overflow-x-auto whitespace-pre-wrap break-all"><code x-html="getHeadersPreviewHighlighted()"></code></pre>
                                            </div>
                                            <template x-if="hasRequestBody(selectedEndpoint)">
                                                <div>
                                                    <label class="block text-label font-semibold text-ide-muted uppercase tracking-wider mb-1">Body</label>
                                                    <pre class="px-3 py-2 bg-ide-surface rounded text-code font-mono overflow-x-auto whitespace-pre-wrap"><code x-html="syntaxHighlightJson(builtRequestBody)"></code></pre>
                                                </div>
                                            </template>
                                            <div class="flex gap-2 pt-2 border-t border-ide-border">
                                                <button @click="copyAsJson()" class="px-3 py-1.5 text-label font-medium bg-ide-surface text-ide-fg border border-ide-border rounded hover:bg-ide-line-active transition-colors flex items-center gap-1.5">Copy JSON</button>
                                                <button @click="copyCurlCommand()" class="px-3 py-1.5 text-label font-medium bg-ide-surface text-ide-fg border border-ide-border rounded hover:bg-ide-line-active transition-colors flex items-center gap-1.5">Copy cURL</button>
                                                <button @click="copyAsJavaScriptFetch()" class="px-3 py-1.5 text-label font-medium bg-ide-surface text-ide-fg border border-ide-border rounded hover:bg-ide-line-active transition-colors flex items-center gap-1.5">Copy JS</button>
                                                <button @click="copyAsTypeScriptFetch()" class="px-3 py-1.5 text-label font-medium bg-ide-surface text-ide-fg border border-ide-border rounded hover:bg-ide-line-active transition-colors flex items-center gap-1.5">Copy TS</button>
                                            </div>
                                        </div>

                                        <!-- ===== Docs Tab ===== -->
                                        <div x-show="editorTab === 'docs'" class="px-4 py-3 space-y-6">
                                            <div x-show="selectedEndpoint.summary"><p class="text-ui text-ide-muted" x-text="selectedEndpoint.summary"></p></div>
                                            <template x-if="getQueryParameters(selectedEndpoint).length > 0">
                                                <div>
                                                    <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider mb-2 flex items-center gap-2"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Query Parameters</h4>
                                                    <table class="w-full text-code"><thead><tr class="text-left text-label text-ide-muted uppercase"><th class="pb-1.5 pr-3 font-medium">Name</th><th class="pb-1.5 pr-3 font-medium">Type</th><th class="pb-1.5 font-medium">Description</th></tr></thead>
                                                    <tbody class="divide-y divide-ide-border"><template x-for="param in getQueryParameters(selectedEndpoint)" :key="param.name"><tr>
                                                        <td class="py-1.5 pr-3 font-mono text-[var(--ide-info-text)]"><span x-text="param.name"></span><template x-if="param.required"><span class="text-red-500">*</span></template></td>
                                                        <td class="py-1.5 pr-3"><span class="px-1.5 py-0.5 bg-ide-border text-ide-muted rounded text-label" x-text="param.schema?.type || 'string'"></span></td>
                                                        <td class="py-1.5 text-ide-muted"><span x-text="cleanDescription(param.description) || '-'"></span>
                                                            <template x-if="param.schema?.enum"><div class="mt-1 flex flex-wrap gap-1"><template x-for="opt in param.schema.enum" :key="opt"><span class="px-1 py-0.5 bg-ide-primary/10 text-ide-primary rounded text-label font-mono" x-text="opt"></span></template></div></template>
                                                        </td>
                                                    </tr></template></tbody></table>
                                                </div>
                                            </template>
                                            <!-- Schema Documentation -->
                                            <div x-data="{ schemaTab: 'request' }" x-effect="if (selectedEndpoint) { if (selectedEndpoint.method === 'GET') { schemaTab = 'response'; } else { const hasReqParams = getPathParameters(selectedEndpoint).length > 0 || getQueryParameters(selectedEndpoint).length > 0 || hasRequestBody(selectedEndpoint); schemaTab = hasReqParams ? 'request' : 'response'; } }">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider">Schema Documentation</h4>
                                                    <div class="flex bg-ide-border rounded p-0.5">
                                                        <button @click="schemaTab = 'request'" class="px-2 py-0.5 text-label font-medium rounded transition-colors" :class="schemaTab === 'request' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Request</button>
                                                        <button @click="schemaTab = 'response'" class="px-2 py-0.5 text-label font-medium rounded transition-colors" :class="schemaTab === 'response' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Response</button>
                                                    </div>
                                                </div>
                                                <div x-show="schemaTab === 'request'" x-data="{ reqExpandedNodes: {} }" x-effect="if (selectedEndpoint) { reqExpandedNodes = {}; }">
                                                    <template x-if="getPathParameters(selectedEndpoint).length > 0 || hasRequestBody(selectedEndpoint)">
                                                        <div class="border border-ide-border rounded overflow-hidden">
                                                            <!-- Header -->
                                                            <div class="grid grid-cols-[minmax(180px,2fr)_100px_60px_1fr] gap-2 px-3 py-1.5 bg-ide-surface text-label text-ide-muted uppercase font-medium border-b border-ide-border">
                                                                <span>Property</span><span>Type</span><span>Status</span><span>Description</span>
                                                            </div>
                                                            <!-- Path Parameters -->
                                                            <template x-if="getPathParameters(selectedEndpoint).length > 0">
                                                                <div>
                                                                    <div class="px-3 py-1 bg-ide-surface/50 text-[10px] text-ide-muted uppercase font-semibold tracking-wider border-b border-ide-border">Path Parameters</div>
                                                                    <div x-html="renderSchemaTree(getPathParameters(selectedEndpoint).map(p => ({ name: p.name, type: p.schema?.type || 'string', required: true, nullable: false, description: p.description || '', nestedFields: [], enum: p.schema?.enum, example: p.schema?.example, constraints: '' })), reqExpandedNodes, 'path', 0, 'reqExpandedNodes')"></div>
                                                                </div>
                                                            </template>
                                                            <!-- Request Body -->
                                                            <template x-if="hasRequestBody(selectedEndpoint)">
                                                                <div>
                                                                    <div class="px-3 py-1 bg-ide-surface/50 text-[10px] text-ide-muted uppercase font-semibold tracking-wider border-b border-ide-border">Request Body</div>
                                                                    <div x-html="renderSchemaTree(getRequestBodyFields(selectedEndpoint), reqExpandedNodes, 'body', 0, 'reqExpandedNodes')"></div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                    <template x-if="getPathParameters(selectedEndpoint).length === 0 && !hasRequestBody(selectedEndpoint)"><p class="text-code text-ide-muted italic py-3">No request schema parameters.</p></template>
                                                </div>
                                                <div x-show="schemaTab === 'response'" x-data="{ selectedResponseCode: '200' }" x-effect="if (selectedEndpoint?.responses) { const codes = Object.keys(selectedEndpoint.responses); const validCode = codes.find(c => { const r = selectedEndpoint.responses[c]; return r?.content?.['application/json']?.schema || r?.description; }) || codes[0]; if (validCode) selectedResponseCode = validCode; }">
                                                    <template x-if="selectedEndpoint.responses && Object.keys(selectedEndpoint.responses).length > 0">
                                                        <div>
                                                            <div class="flex flex-wrap gap-1.5 mb-3"><template x-for="(response, statusCode) in selectedEndpoint.responses" :key="statusCode"><button @click="selectedResponseCode = statusCode" class="px-2 py-1 text-label font-bold rounded transition-colors" :class="selectedResponseCode === statusCode ? 'ring-1 ring-offset-1 ring-primary-500 ' + getStatusBadgeClass(statusCode) : getStatusBadgeClass(statusCode) + ' opacity-60 hover:opacity-100'" x-text="statusCode"></button></template></div>
                                                            <div class="mb-2 p-2 bg-ide-surface rounded text-code text-ide-fg" x-text="selectedEndpoint.responses[selectedResponseCode]?.description || getStatusText(selectedResponseCode)"></div>
                                                            <template x-if="getResponseSchemaFields(selectedEndpoint, selectedResponseCode).length > 0">
                                                                <div x-data="{ expandedNodes: {} }" x-init="$watch('selectedResponseCode', () => { expandedNodes = {}; initAutoExpand() }); initAutoExpand = () => { const fields = getResponseSchemaFields(selectedEndpoint, selectedResponseCode); fields.forEach(f => { if (f.nestedFields.length > 0 && (f.name === 'data' || f.name === 'meta' || f.name === 'errors' || f.name === 'error' || f.name === 'message')) expandedNodes[f.name] = true; }); }; initAutoExpand();">
                                                                    <div class="border border-ide-border rounded overflow-hidden">
                                                                        <!-- Header -->
                                                                        <div class="grid grid-cols-[minmax(180px,2fr)_100px_60px_1fr] gap-2 px-3 py-1.5 bg-ide-surface text-label text-ide-muted uppercase font-medium border-b border-ide-border">
                                                                            <span>Property</span><span>Type</span><span>Status</span><span>Description</span>
                                                                        </div>
                                                                        <!-- Rows -->
                                                                        <div x-html="renderSchemaTree(getResponseSchemaFields(selectedEndpoint, selectedResponseCode), expandedNodes, '')"></div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <template x-if="getResponseSchemaFields(selectedEndpoint, selectedResponseCode).length === 0"><p class="text-code text-ide-muted italic py-2">No schema defined for this response.</p></template>
                                                        </div>
                                                    </template>
                                                    <template x-if="!selectedEndpoint.responses || Object.keys(selectedEndpoint.responses).length === 0"><p class="text-code text-ide-muted italic py-3">No response schemas defined.</p></template>
                                                </div>
                                                <!-- Field Options Summary -->
                                                <template x-if="getAllEnums(selectedEndpoint).length > 0">
                                                    <div class="mt-4">
                                                        <h5 class="text-label font-semibold text-ide-fg uppercase tracking-wider mb-2">Field Options Summary</h5>
                                                        <div class="space-y-2"><template x-for="enumField in getAllEnums(selectedEndpoint)" :key="enumField.name + enumField.location">
                                                            <div class="py-1.5 border-b border-ide-border last:border-0">
                                                                <div class="flex items-center gap-2 mb-1"><code class="font-mono text-code font-semibold text-ide-fg" x-text="enumField.name"></code><span class="text-label text-ide-muted bg-ide-border px-1.5 py-0.5 rounded" x-text="enumField.location"></span></div>
                                                                <div class="flex flex-wrap gap-1"><template x-for="option in enumField.values" :key="option"><code class="px-1.5 py-0.5 text-label bg-ide-primary/10 text-ide-primary rounded font-mono" x-text="option"></code></template></div>
                                                            </div>
                                                        </template></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Top Panel -->

                                <!-- Panel Resizer -->
                                <div class="panel-resizer h-1.5 bg-ide-border/50 flex-shrink-0 hover:bg-ide-primary/30 transition-colors" @mousedown="startResize($event)"></div>

                                <!-- Bottom Panel: Response -->
                                <div class="flex flex-col overflow-hidden flex-shrink-0" :style="'height: ' + bottomPanelHeight + 'px'" style="min-height: 80px">
                                    <!-- Response Tab Bar -->
                                    <div class="flex items-center border-b border-ide-border bg-ide-surface flex-shrink-0">
                                        <template x-for="tab in [{id: 'response', label: 'Response'}, {id: 'headers', label: 'Headers'}, {id: 'console', label: 'Console'}]" :key="tab.id">
                                            <button @click="responseTab = tab.id" class="px-3 py-2 text-code font-medium border-b-2 transition-colors" :class="responseTab === tab.id ? 'border-ide-primary text-ide-fg' : 'border-transparent text-ide-muted hover:text-ide-fg'"><span x-text="tab.label"></span></button>
                                        </template>
                                        <div class="ml-auto flex items-center gap-2 pr-3">
                                            <template x-if="apiResponse && !requestLoading">
                                                <div class="flex items-center gap-2 text-label">
                                                    <span class="w-1.5 h-1.5 rounded-full" :class="apiResponse.status >= 200 && apiResponse.status < 300 ? 'bg-green-500' : apiResponse.status >= 400 ? 'bg-red-500' : 'bg-yellow-500'"></span>
                                                    <span class="font-bold" :class="getStatusBadgeClass(apiResponse.status)" x-text="apiResponse.status"></span>
                                                    <span class="text-ide-muted" x-text="apiResponse.statusText || getStatusText(String(apiResponse.status))"></span>
                                                    <span class="text-ide-muted">|</span>
                                                    <span class="text-ide-muted" x-text="apiResponse.time + 'ms'"></span>
                                                    <button @click="copyResponseBody()" class="p-0.5 text-ide-muted hover:text-ide-fg rounded" title="Copy"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></button>
                                                </div>
                                            </template>
                                            <template x-if="requestLoading">
                                                <div class="flex items-center gap-1.5 text-label text-ide-muted">
                                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                                    <span>Sending...</span>
                                                </div>
                                            </template>
                                            <div class="flex bg-ide-border rounded p-0.5 ml-2">
                                                <button @click="responseMode = 'preview'" class="px-2 py-0.5 text-label font-medium rounded transition-colors" :class="responseMode === 'preview' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Mock</button>
                                                <button @click="responseMode = 'request'" class="px-2 py-0.5 text-label font-medium rounded transition-colors" :class="responseMode === 'request' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Live</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Response Tab Content -->
                                    <div class="flex-1 overflow-y-auto">
                                        <div x-show="responseTab === 'response'" class="px-4 py-3">
                                            <!-- Preview Mode -->
                                            <div x-show="responseMode === 'preview'" x-data="{ previewStatusCode: '200' }" x-effect="if (selectedEndpoint?.responses) { const codes = Object.keys(selectedEndpoint.responses); const validCode = codes.find(c => { const r = selectedEndpoint.responses[c]; return r?.content?.['application/json']?.schema || r?.description; }) || codes[0] || '200'; previewStatusCode = validCode; }">
                                                <template x-if="selectedEndpoint?.responses && Object.keys(selectedEndpoint.responses).length > 0">
                                                    <div>
                                                        <div class="flex flex-wrap gap-1.5 mb-3"><template x-for="(response, statusCode) in selectedEndpoint.responses" :key="statusCode"><button @click="previewStatusCode = statusCode" class="px-2 py-1 text-label font-bold rounded transition-colors" :class="previewStatusCode === statusCode ? 'ring-1 ring-offset-1 ring-primary-500 ' + getStatusBadgeClass(statusCode) : getStatusBadgeClass(statusCode) + ' opacity-60 hover:opacity-100'" x-text="statusCode"></button></template></div>
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span class="px-1.5 py-0.5 text-label font-bold rounded" :class="getStatusBadgeClass(previewStatusCode)" x-text="previewStatusCode"></span>
                                                            <span class="text-code text-ide-muted" x-text="selectedEndpoint.responses[previewStatusCode]?.description || getStatusText(previewStatusCode)"></span>
                                                            <span class="text-label text-ide-muted ml-auto">Mock Response</span>
                                                        </div>
                                                        <pre class="px-3 py-2 bg-ide-surface rounded text-code font-mono overflow-auto"><code x-html="syntaxHighlightJson(getMockResponseForStatus(selectedEndpoint, previewStatusCode))"></code></pre>
                                                    </div>
                                                </template>
                                                <template x-if="!selectedEndpoint?.responses || Object.keys(selectedEndpoint.responses).length === 0">
                                                    <div class="text-center py-6 text-ide-muted"><p class="text-code">No response schemas defined.</p></div>
                                                </template>
                                            </div>
                                            <!-- Live Mode -->
                                            <div x-show="responseMode === 'request'" id="response-section">
                                                <template x-if="!requestLoading && !apiResponse && !apiError">
                                                    <div class="text-center py-8 text-ide-muted border border-dashed border-ide-border rounded">
                                                        <svg class="mx-auto h-8 w-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        <p class="text-code font-medium">No response yet</p>
                                                        <p class="text-label mt-1">Click "Send" to make an API call</p>
                                                    </div>
                                                </template>
                                                <template x-if="apiResponse && !requestLoading">
                                                    <div>
                                                        <template x-if="responseTruncated && !showFullHighlighted">
                                                            <div>
                                                                <div class="px-3 py-2 mb-2 bg-[var(--ide-warning-bg)] border border-[var(--ide-warning-text)]/20 rounded text-xs text-[var(--ide-warning-text)] flex items-center justify-between">
                                                                    <span>Response too large to highlight (<span x-text="Math.round(JSON.stringify(apiResponse.data).length / 1024)"></span> KB). Showing raw text.</span>
                                                                    <button @click="showFullHighlighted = true" class="ml-2 px-2 py-1 bg-[var(--ide-warning-text)]/20 rounded hover:opacity-80 text-[10px] font-medium">Show highlighted</button>
                                                                </div>
                                                                <pre class="px-3 py-2 bg-ide-surface rounded text-code font-mono overflow-auto"><code x-text="formatJson(apiResponse.data)"></code></pre>
                                                            </div>
                                                        </template>
                                                        <template x-if="!responseTruncated || showFullHighlighted">
                                                            <pre class="px-3 py-2 bg-ide-surface rounded text-code font-mono overflow-auto"><code x-html="syntaxHighlightJson(apiResponse.data)"></code></pre>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <!-- Response Headers Tab -->
                                        <div x-show="responseTab === 'headers'" class="px-4 py-3">
                                            <template x-if="apiResponse && apiResponse.headers">
                                                <div class="text-code font-mono space-y-0.5"><template x-for="(value, key) in apiResponse.headers" :key="key"><div class="flex gap-2 py-0.5"><span class="header-key" x-text="key + ':'"></span><span class="header-value break-all" x-text="value"></span></div></template></div>
                                            </template>
                                            <template x-if="!apiResponse || !apiResponse.headers"><div class="text-center py-6 text-ide-muted"><p class="text-code">No response headers. Send a request first.</p></div></template>
                                        </div>
                                        <!-- Console Tab -->
                                        <div x-show="responseTab === 'console'" class="px-4 py-3">
                                            <template x-if="apiError && !requestLoading">
                                                <div class="bg-[var(--ide-error-bg)] border border-[var(--ide-error-text)]/20 rounded p-3 mb-3">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-4 h-4 text-[var(--ide-error-text)] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        <div class="flex-1"><h4 class="text-code font-medium text-[var(--ide-error-text)]">Request Failed</h4><p class="text-code text-[var(--ide-error-text)] mt-0.5" x-text="apiError"></p></div>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="apiResponse && apiResponse.status >= 400 && !requestLoading">
                                                <div class="mb-3 p-3 rounded border" :class="apiResponse.status >= 500 ? 'bg-[var(--ide-error-bg)] border-[var(--ide-error-text)]/20' : 'bg-[var(--ide-warning-bg)] border-[var(--ide-warning-text)]/20'">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" :class="apiResponse.status >= 500 ? 'text-red-500' : 'text-amber-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="text-code font-semibold" :class="apiResponse.status >= 500 ? 'text-[var(--ide-error-text)]' : 'text-[var(--ide-warning-text)]'" x-text="apiResponse.status >= 500 ? 'Server Error' : 'Client Error'"></h4>
                                                            <p class="text-code mt-0.5" :class="apiResponse.status >= 500 ? 'text-[var(--ide-error-text)]' : 'text-[var(--ide-warning-text)]'" x-text="apiResponse.data?.message || apiResponse.data?.error || apiResponse.statusText || 'An error occurred'"></p>
                                                            <template x-if="apiResponse.data?.exception"><div class="mt-1.5 p-2 rounded bg-[var(--ide-error-bg)]"><code class="text-label font-mono text-[var(--ide-error-text)] break-all" x-text="apiResponse.data.exception"></code><template x-if="apiResponse.data?.file"><p class="text-label font-mono text-[var(--ide-error-text)] mt-0.5"><span x-text="apiResponse.data.file"></span><span x-show="apiResponse.data?.line">:<span x-text="apiResponse.data.line"></span></span></p></template></div></template>
                                                            <template x-if="apiResponse.status === 422 && apiResponse.data?.errors"><div class="mt-2 space-y-0.5"><template x-for="(messages, field) in apiResponse.data.errors" :key="field"><div class="flex items-start gap-1.5 text-code"><code class="text-label font-mono px-1 py-0.5 rounded bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)] flex-shrink-0" x-text="field"></code><span class="text-[var(--ide-warning-text)]" x-text="Array.isArray(messages) ? messages.join(', ') : messages"></span></div></template></div></template>
                                                            <template x-if="apiResponse.status === 401"><p class="text-label mt-1 text-[var(--ide-warning-text)] italic">Hint: Check your authentication token.</p></template>
                                                            <template x-if="apiResponse.status === 403"><p class="text-label mt-1 text-[var(--ide-warning-text)] italic">Hint: Your account may not have permission.</p></template>
                                                            <template x-if="apiResponse.status === 404"><p class="text-label mt-1 text-[var(--ide-warning-text)] italic">Hint: Resource not found. Check the ID.</p></template>
                                                            <template x-if="apiResponse.status === 419"><p class="text-label mt-1 text-[var(--ide-warning-text)] italic">Hint: CSRF token expired. Refresh the page.</p></template>
                                                            <template x-if="apiResponse.status === 429"><p class="text-label mt-1 text-[var(--ide-warning-text)] italic">Hint: Rate limited. Wait before retrying.</p></template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="!apiError && (!apiResponse || apiResponse.status < 400) && !requestLoading"><div class="text-center py-6 text-ide-muted"><p class="text-code">No errors or warnings.</p></div></template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Try It Mode -->

                            <!-- ========== VISUAL / ALL MODE: Single Scroll Pane ========== -->
                            <div x-show="activeTab === 'visual' || activeTab === 'all'" class="flex-1 overflow-y-auto">
                                <div class="max-w-5xl mx-auto px-4 py-4 space-y-6">
                                    <div class="flex items-center gap-2 text-label mb-2">
                                        <button @click="activeTab = 'try-it'" class="px-2 py-1 rounded bg-ide-surface border border-ide-border text-ide-muted hover:text-ide-fg transition-colors">&#8592; Try It</button>
                                        <span class="px-2 py-1 rounded-full bg-[var(--ide-success-bg)] text-[var(--ide-success-text)] font-medium">Backend</span>
                                        <span class="px-2 py-1 rounded-full bg-ide-primary/10 text-ide-primary font-medium">QA</span>
                                        <span class="text-ide-muted">|</span>
                                        <span class="text-ide-muted">Impact analysis and system relationships</span>
                                    </div>

                                        <!-- What Happens When Called -->
                                        <div class="bg-ide-surface border border-ide-border rounded-lg overflow-hidden">
                                            <div class="px-6 py-4 border-b border-ide-border bg-ide-bg">
                                                <h3 class="font-semibold text-ide-fg flex items-center gap-2">
                                                    <span>What Happens</span>
                                                </h3>
                                            </div>
                                            <div class="p-6">
                                                <div class="space-y-3">
                                                    <template x-for="(step, idx) in getVisualDataFlow()" :key="idx">
                                                        <div class="flex items-start gap-3">
                                                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" :class="{
                                                                'bg-[var(--ide-info-bg)] text-[var(--ide-info-text)]': step.type === 'input',
                                                                'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]': step.type === 'process',
                                                                'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)]': step.type === 'output',
                                                                'bg-[var(--ide-error-bg)] text-[var(--ide-error-text)]': step.type === 'side-effect'
                                                            }" x-text="idx + 1"></div>
                                                            <div>
                                                                <p class="font-medium text-ide-fg text-sm" x-text="step.title"></p>
                                                                <p class="text-ide-muted text-xs mt-0.5" x-text="step.description"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Related Endpoints -->
                                        <div class="bg-ide-surface border border-ide-border rounded-lg overflow-hidden">
                                            <div class="px-6 py-4 border-b border-ide-border bg-ide-bg">
                                                <h3 class="font-semibold text-ide-fg flex items-center gap-2">
                                                    <span>Related Endpoints</span>
                                                    <span class="text-xs font-normal text-ide-muted">Relationships based on data dependencies</span>
                                                </h3>
                                            </div>
                                            <div class="p-6">
                                                <template x-if="getRelatedEndpoints().length > 0">
                                                    <div class="space-y-3">
                                                        <template x-for="ep in getRelatedEndpoints()" :key="ep.type + ep.method + ep.path">
                                                            <div @click="selectEndpointByPath(ep.method, ep.path)"
                                                                 class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all hover:shadow-sm"
                                                                 :class="{
                                                                     'border-[var(--ide-warning-text)]/20 hover:bg-[var(--ide-warning-bg)]': ep.type === 'uses',
                                                                     'border-[var(--ide-info-text)]/20 hover:bg-[var(--ide-info-bg)]': ep.type === 'provides',
                                                                     'border-ide-primary/20 hover:bg-ide-primary/5': ep.type === 'polymorphic'
                                                                 }">
                                                                <div class="flex-shrink-0">
                                                                    <span :class="getMethodBadgeClass(ep.method)" class="px-2 py-0.5 rounded text-xs font-bold" x-text="ep.method"></span>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <code class="text-sm font-mono text-ide-fg truncate block" x-text="ep.path"></code>
                                                                    <div class="flex items-center gap-2 mt-1">
                                                                        <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded-full"
                                                                              :class="{
                                                                                  'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]': ep.type === 'uses',
                                                                                  'bg-[var(--ide-info-bg)] text-[var(--ide-info-text)]': ep.type === 'provides',
                                                                                  'bg-ide-primary/10 text-ide-primary': ep.type === 'polymorphic'
                                                                              }"
                                                                              x-text="ep.type"></span>
                                                                        <span class="text-xs text-ide-muted" x-text="ep.field"></span>
                                                                    </div>
                                                                </div>
                                                                <svg class="w-4 h-4 text-ide-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                </svg>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template x-if="getRelatedEndpoints().length === 0">
                                                    <p class="text-ide-muted text-sm italic">No data relationships detected for this endpoint.</p>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- API Dependencies -->
                                        <div class="bg-ide-surface border border-ide-border rounded-lg overflow-hidden">
                                            <div class="px-6 py-4 border-b border-ide-border bg-ide-bg">
                                                <h3 class="font-semibold text-ide-fg flex items-center gap-2">
                                                    <span>API Dependencies</span>
                                                    <span class="text-xs font-normal text-ide-muted">Endpoints this request depends on</span>
                                                </h3>
                                            </div>
                                            <div class="p-6">
                                                <!-- Requires data from -->
                                                <template x-if="getApiDependencies().requires.length > 0">
                                                    <div class="mb-5">
                                                        <h4 class="text-xs font-semibold text-ide-muted uppercase tracking-wider mb-3 flex items-center gap-2">
                                                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                                            Requires data from
                                                        </h4>
                                                        <div class="space-y-2">
                                                            <template x-for="dep in getApiDependencies().requires" :key="dep.field + dep.endpoint">
                                                                <div @click="selectEndpointByPath(dep.method, dep.endpoint)"
                                                                     class="flex items-center gap-3 p-3 rounded-lg border border-ide-border hover:bg-[var(--ide-info-bg)] cursor-pointer transition-colors group">
                                                                    <div class="flex-shrink-0">
                                                                        <span :class="getMethodBadgeClass(dep.method)" class="px-2 py-0.5 rounded text-xs font-bold" x-text="dep.method"></span>
                                                                    </div>
                                                                    <div class="flex-1 min-w-0">
                                                                        <code class="text-sm font-mono text-ide-fg group-hover:text-[var(--ide-info-text)] transition-colors" x-text="dep.endpoint"></code>
                                                                        <p class="text-xs text-ide-muted mt-0.5">
                                                                            <span class="text-[var(--ide-warning-text)] font-medium" x-text="dep.field"></span>
                                                                            <span class="mx-1">&larr;</span>
                                                                            <span x-text="dep.description"></span>
                                                                        </p>
                                                                    </div>
                                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                    </svg>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Provides data to -->
                                                <template x-if="getApiDependencies().providesTo.length > 0">
                                                    <div>
                                                        <h4 class="text-xs font-semibold text-ide-muted uppercase tracking-wider mb-3 flex items-center gap-2">
                                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                            Provides data to
                                                        </h4>
                                                        <div class="space-y-2">
                                                            <template x-for="dep in getApiDependencies().providesTo" :key="dep.field + dep.endpoint">
                                                                <div @click="selectEndpointByPath(dep.method, dep.endpoint)"
                                                                     class="flex items-center gap-3 p-3 rounded-lg border border-ide-border hover:bg-[var(--ide-success-bg)] cursor-pointer transition-colors group">
                                                                    <div class="flex-shrink-0">
                                                                        <span :class="getMethodBadgeClass(dep.method)" class="px-2 py-0.5 rounded text-xs font-bold" x-text="dep.method"></span>
                                                                    </div>
                                                                    <div class="flex-1 min-w-0">
                                                                        <code class="text-sm font-mono text-ide-fg group-hover:text-[var(--ide-success-text)] transition-colors" x-text="dep.endpoint"></code>
                                                                        <p class="text-xs text-ide-muted mt-0.5">
                                                                            <span>Uses </span>
                                                                            <span class="text-[var(--ide-warning-text)] font-medium" x-text="dep.field"></span>
                                                                            <span> from this endpoint's response</span>
                                                                        </p>
                                                                    </div>
                                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-green-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                    </svg>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- No dependencies -->
                                                <template x-if="getApiDependencies().requires.length === 0 && getApiDependencies().providesTo.length === 0">
                                                    <p class="text-ide-muted text-sm italic">No API dependencies detected for this endpoint.</p>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Impact Analysis -->
                                        <div class="bg-ide-surface border border-ide-border rounded-lg overflow-hidden">
                                            <div class="px-6 py-4 border-b border-ide-border bg-ide-bg">
                                                <h3 class="font-semibold text-ide-fg flex items-center gap-2">
                                                    <span>Impact Analysis</span>
                                                    <span class="text-xs font-normal px-2 py-0.5 rounded-full bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]">If this changes</span>
                                                </h3>
                                            </div>
                                            <div class="p-6 space-y-4">
                                                <template x-for="impact in getImpactAnalysis()" :key="impact.area">
                                                    <div class="flex items-start gap-3">
                                                        <span class="text-lg flex-shrink-0" x-text="impact.icon"></span>
                                                        <div>
                                                            <p class="font-medium text-ide-fg text-sm" x-text="impact.area"></p>
                                                            <p class="text-ide-muted text-xs mt-0.5" x-text="impact.description"></p>
                                                            <div class="flex flex-wrap gap-1 mt-2" x-show="impact.items?.length > 0">
                                                                <template x-for="item in impact.items" :key="item">
                                                                    <span class="px-2 py-0.5 bg-ide-border text-ide-muted rounded text-xs" x-text="item"></span>
                                                                </template>
                                                            </div>
                                                            <template x-if="impact.hasFlowAction">
                                                                <div class="mt-3">
                                                                    <button @click="createTestFlowFromEndpoint()" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-ide-primary bg-ide-primary/10 border border-ide-primary/20 rounded-lg hover:bg-ide-primary/20 transition-colors">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                                        Create Test Flow
                                                                    </button>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <!-- End Visual Tab -->

                        </div>
                    </template>
                </div><!-- end Editor Content Area -->

            </div><!-- end Main Editor Area -->

        </div><!-- end flex row -->

        <!-- Status Bar -->
        <div class="h-6 bg-ide-primary flex items-center px-2 text-[11px] text-white/90 gap-4 flex-shrink-0">
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span x-text="endpointCount + ' endpoints'"></span>
            </div>
            <div class="flex items-center gap-1.5" x-show="selectedEndpoint">
                <span x-text="selectedEndpoint?.method"></span>
                <span x-text="selectedEndpoint?.path"></span>
            </div>
            <template x-if="apiResponse">
                <div class="flex items-center gap-1.5">
                    <span :class="{'text-green-300': apiResponse.status >= 200 && apiResponse.status < 300, 'text-red-300': apiResponse.status >= 400}" x-text="apiResponse.status"></span>
                    <span x-text="apiResponse.time ? apiResponse.time + 'ms' : ''"></span>
                </div>
            </template>
            <div class="ml-auto flex items-center gap-3">
                <span x-show="authToken" class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> Auth</span>
                <span x-text="currentEnvironment === 'default' ? 'Default Env' : environments.find(e => e.id === currentEnvironment)?.name || 'Default Env'"></span>
                <button @click="showShortcuts = true" class="hover:text-white" title="Keyboard Shortcuts">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/></svg>
                </button>
            </div>
        </div>
    </div><!-- end flex col -->

    <!-- Command Palette -->
    <div x-show="showCommandPalette" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-start justify-center pt-[15vh]" @click.self="showCommandPalette = false" x-cloak>
        <div class="w-full max-w-lg bg-ide-surface border border-ide-border rounded-lg shadow-2xl overflow-hidden">
            <div class="p-2 border-b border-ide-border">
                <input type="text" x-model="commandPaletteQuery" x-ref="commandPaletteInput" @keydown.escape="showCommandPalette = false"
                    placeholder="Type to search endpoints..."
                    class="w-full px-3 py-2 text-sm bg-ide-bg border border-ide-border rounded text-ide-fg placeholder-ide-muted focus:outline-none focus:ring-1 focus:ring-ide-primary">
            </div>
            <div class="max-h-64 overflow-y-auto">
                <template x-for="endpoint in allEndpoints.filter(e => !commandPaletteQuery || (e.method + ' ' + e.path + ' ' + (e.summary || '')).toLowerCase().includes(commandPaletteQuery.toLowerCase())).slice(0, 20)" :key="endpoint.method + endpoint.path">
                    <button @click="selectEndpoint(endpoint); showCommandPalette = false; commandPaletteQuery = ''"
                        class="w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-ide-line-active transition-colors text-left">
                        <span class="px-1.5 py-0.5 text-[10px] font-bold rounded uppercase" :class="'badge-' + endpoint.method.toLowerCase()" x-text="endpoint.method"></span>
                        <span class="font-mono text-xs text-ide-fg" x-text="endpoint.path"></span>
                        <span class="text-ide-muted text-xs truncate ml-auto" x-text="endpoint.summary || ''"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Comments Modal -->
    <div
        x-show="showCommentsModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="closeCommentsModal()"
        x-cloak
    >
        <div
            x-show="showCommentsModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-ide-bg rounded-xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[80vh]"
        >
            <!-- Header -->
            <div class="px-6 py-4 border-b border-ide-border bg-ide-bg flex items-center justify-between shrink-0">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-ide-fg flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Comments
                    </h3>
                    <p class="text-xs text-ide-muted mt-0.5 truncate" x-text="selectedSavedRequest?.name || 'Saved Request'"></p>
                </div>
                <button @click="closeCommentsModal()" class="p-1.5 text-gray-400 hover:text-ide-fg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Comments List -->
            <div class="flex-1 overflow-y-auto p-6 space-y-3">
                <!-- Loading -->
                <template x-if="loadingComments">
                    <div class="text-center py-8 text-ide-muted">
                        <svg class="animate-spin mx-auto h-6 w-6 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm">Loading comments...</p>
                    </div>
                </template>

                <!-- Empty State -->
                <template x-if="!loadingComments && selectedSavedRequestComments.length === 0">
                    <div class="text-center py-8 text-ide-muted">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-sm font-medium">No comments yet</p>
                        <p class="text-xs mt-1">Be the first to add a comment below.</p>
                    </div>
                </template>

                <!-- Comment Items -->
                <template x-for="comment in selectedSavedRequestComments" :key="comment.id">
                    <div class="p-4 bg-ide-bg rounded-lg">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                    :class="{
                                        'bg-blue-500': comment.author_type === 'backend',
                                        'bg-green-500': comment.author_type === 'frontend',
                                        'bg-amber-500': comment.author_type === 'qa',
                                        'bg-gray-500': comment.author_type === 'other'
                                    }"
                                    x-text="(comment.author_name || '?')[0].toUpperCase()"></span>
                                <div>
                                    <span class="text-sm font-medium text-ide-fg" x-text="comment.author_name"></span>
                                    <span class="px-1.5 py-0.5 text-[10px] font-medium rounded ml-1.5"
                                        :class="getAuthorTypeColor(comment.author_type)"
                                        x-text="comment.author_type"></span>
                                    <span class="px-1.5 py-0.5 text-[10px] font-medium rounded ml-1"
                                        :class="getStatusColor(comment.status || 'info')"
                                        x-text="comment.status || 'info'"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <select
                                    :value="comment.status || 'info'"
                                    @change="updateCommentStatus(selectedSavedRequest.id, comment.id, $event.target.value)"
                                    class="px-1.5 py-0.5 text-[10px] border border-ide-border rounded bg-ide-surface text-ide-fg focus:ring-1 focus:ring-ide-primary focus:border-ide-primary"
                                >
                                    <option value="info">Info</option>
                                    <option value="warning">Warning</option>
                                    <option value="critical">Critical</option>
                                    <option value="resolved">Resolved</option>
                                </select>
                                <span class="text-xs text-ide-muted" x-text="formatDate(comment.created_at)"></span>
                                <button
                                    @click="deleteComment(selectedSavedRequest.id, comment.id)"
                                    class="p-1 text-ide-muted hover:text-[var(--ide-error-text)] transition-colors"
                                    title="Delete comment"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-ide-fg whitespace-pre-wrap pl-9" x-text="comment.content"></p>
                    </div>
                </template>
            </div>

            <!-- Add Comment Form -->
            <div class="px-6 py-4 border-t border-ide-border bg-ide-bg shrink-0 space-y-3">
                <div class="flex gap-2">
                    <input
                        type="text"
                        x-model="newComment.author_name"
                        placeholder="Your name"
                        class="flex-1 px-3 py-2 text-sm border border-ide-border rounded-lg bg-ide-surface text-ide-fg placeholder-ide-muted focus:ring-1 focus:ring-ide-primary focus:border-ide-primary"
                    >
                    <select
                        x-model="newComment.author_type"
                        class="px-3 py-2 text-sm border border-ide-border rounded-lg bg-ide-surface text-ide-fg focus:ring-1 focus:ring-ide-primary focus:border-ide-primary"
                    >
                        <option value="backend">Backend</option>
                        <option value="frontend">Frontend</option>
                        <option value="qa">QA</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-[10px] text-ide-muted mr-1">Status:</span>
                    <template x-for="st in ['info', 'warning', 'critical', 'resolved']" :key="st">
                        <button
                            type="button"
                            @click="newComment.status = st"
                            class="px-2 py-0.5 text-[10px] font-medium rounded transition-colors capitalize"
                            :class="newComment.status === st ? getStatusColor(st) : 'bg-ide-border text-ide-muted hover:text-ide-fg'"
                            x-text="st"
                        ></button>
                    </template>
                </div>
                <div class="flex gap-2">
                    <textarea
                        x-model="newComment.content"
                        placeholder="Add a comment..."
                        rows="2"
                        @keydown.meta.enter="submitComment(selectedSavedRequest?.id)"
                        @keydown.ctrl.enter="submitComment(selectedSavedRequest?.id)"
                        class="flex-1 px-3 py-2 text-sm border border-ide-border rounded-lg bg-ide-surface text-ide-fg placeholder-ide-muted focus:ring-1 focus:ring-ide-primary focus:border-ide-primary resize-none"
                    ></textarea>
                    <button
                        @click="submitComment(selectedSavedRequest?.id)"
                        :disabled="submittingComment || !newComment.content.trim() || !newComment.author_name.trim()"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed self-end"
                    >
                        <template x-if="submittingComment">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="!submittingComment">
                            <span>Add</span>
                        </template>
                    </button>
                </div>
                <p class="text-[10px] text-ide-muted text-right">Cmd+Enter to submit</p>
            </div>
        </div>
    </div>

    <!-- Save Request Modal -->
    <div
        x-show="showSaveModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="showSaveModal = false"
        x-cloak
    >
        <div
            x-show="showSaveModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-ide-bg rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-ide-border bg-ide-bg">
                <h3 class="text-lg font-semibold text-ide-fg flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span x-text="saveMode === 'update' ? 'Update Request' : 'Save Request'"></span>
                </h3>
            </div>
            <div class="p-6">
                <!-- Update/New toggle when a saved request is loaded -->
                <template x-if="selectedSavedRequest">
                    <div class="mb-4 flex rounded-lg overflow-hidden border border-ide-border">
                        <button
                            @click="saveMode = 'update'"
                            :class="saveMode === 'update' ? 'bg-purple-600 text-white' : 'bg-ide-surface text-ide-muted hover:text-ide-fg'"
                            class="flex-1 px-3 py-2 text-sm font-medium transition-colors"
                        >
                            Update Current
                        </button>
                        <button
                            @click="saveMode = 'new'; saveRequestName = ''; saveRequestPriority = ''; saveRequestTeam = ''"
                            :class="saveMode === 'new' ? 'bg-purple-600 text-white' : 'bg-ide-surface text-ide-muted hover:text-ide-fg'"
                            class="flex-1 px-3 py-2 text-sm font-medium transition-colors border-l border-ide-border"
                        >
                            Save as New
                        </button>
                    </div>
                </template>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-ide-fg mb-2">Request Name (optional)</label>
                    <input
                        type="text"
                        x-model="saveRequestName"
                        :placeholder="selectedEndpoint ? `${selectedEndpoint.method} ${selectedEndpoint.path}` : 'Enter a name...'"
                        @keydown.enter="saveMode === 'update' ? updateSavedRequest() : saveRequest()"
                        class="w-full px-4 py-2.5 border border-ide-border rounded-lg bg-ide-surface text-ide-fg placeholder-ide-muted focus:ring-1 focus:ring-ide-primary focus:border-ide-primary"
                    >
                </div>
                <div class="mb-4 flex gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-ide-fg mb-2">Priority</label>
                        <select
                            x-model="saveRequestPriority"
                            class="w-full px-3 py-2.5 border border-ide-border rounded-lg bg-ide-surface text-ide-fg focus:ring-1 focus:ring-ide-primary focus:border-ide-primary text-sm"
                        >
                            <option value="">None</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-ide-fg mb-2">Team</label>
                        <input
                            type="text"
                            x-model="saveRequestTeam"
                            placeholder="e.g. Backend, Frontend"
                            class="w-full px-3 py-2.5 border border-ide-border rounded-lg bg-ide-surface text-ide-fg placeholder-ide-muted focus:ring-1 focus:ring-ide-primary focus:border-ide-primary text-sm"
                        >
                    </div>
                </div>
                <div class="bg-ide-bg rounded-lg p-3 mb-4">
                    <div class="flex items-center gap-2 text-sm">
                        <span
                            class="px-1.5 py-0.5 text-[10px] font-bold rounded uppercase"
                            :class="selectedEndpoint ? getMethodBadgeClass(selectedEndpoint.method) : ''"
                            x-text="selectedEndpoint?.method || ''"
                        ></span>
                        <span class="font-mono text-ide-muted truncate" x-text="selectedEndpoint?.path || ''"></span>
                    </div>
                    <template x-if="apiResponse">
                        <div class="mt-2 pt-2 border-t border-ide-border text-xs text-ide-muted">
                            Last response: <span :class="getStatusBadgeClass(apiResponse.status)" class="px-1.5 py-0.5 rounded font-bold" x-text="apiResponse.status"></span> will be saved
                        </div>
                    </template>
                </div>
                <div class="flex gap-3">
                    <button
                        @click="showSaveModal = false"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-ide-fg bg-ide-border rounded-lg hover:bg-ide-line-active transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="saveMode === 'update' ? updateSavedRequest() : saveRequest()"
                        :disabled="savingRequest"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <template x-if="savingRequest">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="savingRequest ? (saveMode === 'update' ? 'Updating...' : 'Saving...') : (saveMode === 'update' ? 'Update Request' : 'Save Request')"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div
        x-show="showSettings"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="showSettings = false"
        x-cloak
    >
        <div
            x-show="showSettings"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-ide-bg rounded-xl shadow-2xl max-w-lg w-full p-6"
        >
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-ide-fg">Settings</h3>
                <button @click="showSettings = false" class="p-1 text-gray-500 hover:text-ide-fg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Base URL Setting -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-ide-fg mb-2">API Base URL</label>
                <div class="text-xs text-ide-muted mb-2">
                    Default from spec: <code class="bg-ide-border px-1 py-0.5 rounded" x-text="getDefaultBaseUrl()"></code>
                </div>
                <input
                    type="text"
                    x-model="customBaseUrl"
                    :placeholder="getDefaultBaseUrl()"
                    class="w-full px-3 py-2 border border-ide-border rounded-lg text-sm bg-ide-surface text-ide-fg focus:ring-1 focus:ring-ide-primary focus:border-ide-primary"
                >
                <p class="mt-1 text-xs text-ide-muted">
                    Leave empty to use the default. Example: <code class="bg-ide-border px-1 py-0.5 rounded">https://wealthy.test/api/v1</code>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button
                    @click="customBaseUrl = ''; saveBaseUrl(); showSettings = false"
                    class="px-4 py-2 text-sm font-medium text-ide-fg bg-ide-border rounded-lg hover:bg-ide-line-active transition-colors"
                >
                    Reset to Default
                </button>
                <button
                    @click="saveBaseUrl(); showSettings = false"
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 transition-colors"
                >
                    Save Settings
                </button>
            </div>
        </div>
    </div>

    <!-- Database Schema Modal -->
    <div
        x-show="showSchemaViewer"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="showSchemaViewer = false"
        x-cloak
    >
        <div
            x-show="showSchemaViewer"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-ide-bg rounded-xl shadow-2xl max-w-5xl w-full min-h-[50vh] max-h-[85vh] flex flex-col"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-ide-border">
                <h3 class="text-lg font-semibold text-ide-fg flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    Database Schema
                </h3>
                <button @click="showSchemaViewer = false" class="p-1 text-gray-500 hover:text-ide-fg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Table Selector Tabs -->
            <div class="flex flex-wrap gap-1 p-3 bg-ide-bg/50 border-b border-ide-border overflow-x-auto">
                <template x-for="table in databaseTables" :key="table.name">
                    <button
                        @click="selectedSchemaTable = table.name"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors whitespace-nowrap"
                        :class="selectedSchemaTable === table.name
                            ? 'bg-ide-primary/20 text-ide-primary'
                            : 'bg-ide-surface text-ide-muted hover:bg-ide-line-active'"
                        x-text="formatTableName(table.name)"
                    ></button>
                </template>
            </div>

            <!-- Table Content -->
            <div class="flex-1 overflow-auto p-4">
                <template x-for="table in databaseTables" :key="table.name">
                    <div x-show="selectedSchemaTable === table.name">
                        <!-- Table Title -->
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="text-lg font-semibold text-ide-fg" x-text="formatTableName(table.name)"></h4>
                            <code class="text-xs text-ide-muted bg-ide-border px-2 py-0.5 rounded" x-text="table.name"></code>
                        </div>
                        <!-- Table Description -->
                        <div class="mb-4" x-show="table.description">
                            <p class="text-sm text-ide-muted" x-text="table.description"></p>
                        </div>

                        <!-- Columns Table -->
                        <div class="overflow-x-auto rounded-lg border border-ide-border">
                            <table class="w-full text-sm">
                                <thead class="bg-ide-bg/50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-semibold text-ide-fg">Column</th>
                                        <th class="px-4 py-2 text-left font-semibold text-ide-fg">Type</th>
                                        <th class="px-4 py-2 text-left font-semibold text-ide-fg">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ide-border">
                                    <template x-for="col in table.columns" :key="col.name">
                                        <tr class="hover:bg-ide-line-active">
                                            <td class="px-4 py-2">
                                                <code class="text-ide-primary font-mono text-xs" x-text="col.name"></code>
                                                <span x-show="col.primary" class="ml-1 text-[10px] font-bold text-[var(--ide-warning-text)]">PK</span>
                                                <span x-show="col.foreign" class="ml-1 text-[10px] font-bold text-[var(--ide-info-text)]">FK</span>
                                                <span x-show="col.nullable" class="ml-1 text-[10px] text-gray-400">nullable</span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <code class="text-ide-primary font-mono text-xs" x-text="col.type"></code>
                                            </td>
                                            <td class="px-4 py-2 text-ide-muted text-xs" x-text="col.description"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Constraints/Notes -->
                        <div x-show="table.notes && table.notes.length > 0" class="mt-4">
                            <h4 class="text-xs font-semibold text-ide-fg uppercase tracking-wider mb-2">Notes</h4>
                            <ul class="list-disc list-inside text-xs text-ide-muted space-y-1">
                                <template x-for="note in table.notes" :key="note">
                                    <li x-text="note"></li>
                                </template>
                            </ul>
                        </div>

                        <!-- Computed Attributes -->
                        <div x-show="table.computed && table.computed.length > 0" class="mt-4">
                            <h4 class="text-xs font-semibold text-ide-fg uppercase tracking-wider mb-2">Computed Attributes (API Response)</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="attr in table.computed" :key="attr">
                                    <span class="px-2 py-1 bg-[var(--ide-success-bg)] text-[var(--ide-success-text)] rounded text-xs font-mono" x-text="attr"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- API Map Modal (Resource-Centric Visualization) -->
    <div
        x-show="showRelationshipGraph"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="showRelationshipGraph = false"
        x-cloak
    >
        <div
            x-show="showRelationshipGraph"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-ide-bg rounded-xl shadow-2xl max-w-6xl w-full max-h-[90vh] flex flex-col overflow-hidden"
        >
            <!-- Header with back button when not on overview -->
            <div class="flex items-center justify-between p-4 border-b border-ide-border">
                <div class="flex items-center gap-3">
                    <button
                        x-show="apiMapView !== 'overview'"
                        @click="apiMapView = 'overview'; selectedResource = null; selectedGraphNode = null"
                        class="p-2 hover:bg-ide-line-active rounded-lg transition-colors"
                        title="Back to overview"
                    >
                        <svg class="w-5 h-5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <h3 class="text-xl font-semibold text-ide-fg flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <span x-show="apiMapView === 'overview'">API Map</span>
                        <span x-show="apiMapView === 'resource'" x-text="selectedResource"></span>
                    </h3>
                </div>
                <button @click="showRelationshipGraph = false" class="p-2 text-gray-500 hover:text-ide-fg hover:bg-ide-line-active rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Overview: Resource Cards Grid -->
            <div x-show="apiMapView === 'overview'" class="flex-1 overflow-y-auto p-6">
                <p class="text-ide-muted mb-6">Click a resource to explore its relationships and endpoints</p>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="tag in Object.keys(endpointsByTag)" :key="tag">
                        <div
                            @click="selectResourceForMap(tag)"
                            class="p-4 border border-ide-border rounded-xl hover:border-[var(--ide-success-text)] hover:shadow-lg cursor-pointer transition-all group bg-ide-bg/50"
                        >
                            <h4 class="font-semibold text-lg mb-2 text-ide-fg group-hover:text-[var(--ide-success-text)] transition-colors" x-text="tag"></h4>
                            <div class="text-sm text-ide-muted mb-3">
                                <span x-text="getEndpointCountForTag(tag)"></span> endpoints
                            </div>
                            <!-- Method badges -->
                            <div class="flex flex-wrap gap-1 mb-3">
                                <template x-for="method in getMethodsForTag(tag)" :key="method">
                                    <span
                                        :class="getMethodBadgeClass(method)"
                                        class="px-2 py-0.5 text-xs rounded font-medium"
                                        x-text="method"
                                    ></span>
                                </template>
                            </div>
                            <!-- Connection indicator -->
                            <div class="text-xs text-ide-muted flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <span x-text="getConnectionCountForTag(tag)"></span> connections
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Resource Focus View -->
            <div x-show="apiMapView === 'resource'" class="flex flex-1 overflow-hidden" style="height: calc(90vh - 120px); min-height: 400px; max-height: 700px;">
                <!-- Left: Endpoints List -->
                <div class="w-72 border-r border-ide-border overflow-y-auto p-4 bg-ide-bg">
                    <h4 class="font-medium mb-3 text-ide-muted text-sm uppercase tracking-wide">Endpoints</h4>
                    <div class="space-y-2">
                        <template x-for="ep in resourceEndpoints" :key="ep.path + ep.method">
                            <div
                                @click="selectedGraphNode = ep"
                                :class="selectedGraphNode?.path === ep.path && selectedGraphNode?.method === ep.method
                                    ? 'bg-[var(--ide-success-bg)] border-[var(--ide-success-text)]'
                                    : 'border-ide-border hover:bg-ide-line-active'"
                                class="p-3 border rounded-lg cursor-pointer transition-all"
                            >
                                <span
                                    :class="getMethodColor(ep.method)"
                                    class="text-xs font-bold"
                                    x-text="ep.method"
                                ></span>
                                <div class="text-sm mt-1 truncate text-ide-fg" x-text="ep.path.split('/').pop() || ep.path"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Center: Relationship Diagram -->
                <div class="flex-1 p-6 flex flex-col overflow-y-auto">
                    <!-- Uses data from (incoming dependencies) -->
                    <template x-if="getResourceConnections().filter(c => c.direction === 'out').length > 0">
                        <div class="mb-4">
                            <h5 class="text-xs font-semibold uppercase tracking-wider text-[var(--ide-warning-text)] mb-2 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                Uses data from
                            </h5>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="conn in getResourceConnections().filter(c => c.direction === 'out')" :key="'out-' + conn.tag + '-' + conn.field">
                                    <div @click="selectResourceForMap(conn.tag)"
                                         class="px-3 py-2 rounded-lg border-2 border-[var(--ide-warning-text)]/30 bg-[var(--ide-warning-bg)] cursor-pointer hover:shadow-md transition-all hover:scale-105">
                                        <div class="font-medium text-sm text-ide-fg" x-text="conn.tag"></div>
                                        <div class="text-xs text-ide-muted mt-0.5" x-text="conn.field"></div>
                                        <span class="inline-block mt-1 text-[10px] px-1.5 py-0.5 rounded-full bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]"
                                              x-text="conn.type === 'polymorphic' ? 'polymorphic' : 'uses'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Center: Main Resource -->
                    <div class="flex justify-center py-6">
                        <div class="w-40 h-40 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 text-white flex items-center justify-center text-center font-semibold shadow-xl">
                            <div>
                                <span x-text="selectedResource" class="text-lg"></span>
                                <div class="text-xs opacity-75 mt-1" x-text="getEndpointCountForTag(selectedResource) + ' endpoints'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Provides data to (outgoing dependents) -->
                    <template x-if="getResourceConnections().filter(c => c.direction === 'in').length > 0">
                        <div class="mt-4">
                            <h5 class="text-xs font-semibold uppercase tracking-wider text-[var(--ide-info-text)] mb-2 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                Provides data to
                            </h5>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="conn in getResourceConnections().filter(c => c.direction === 'in')" :key="'in-' + conn.tag + '-' + conn.field">
                                    <div @click="selectResourceForMap(conn.tag)"
                                         class="px-3 py-2 rounded-lg border-2 border-[var(--ide-info-text)]/30 bg-[var(--ide-info-bg)] cursor-pointer hover:shadow-md transition-all hover:scale-105">
                                        <div class="font-medium text-sm text-ide-fg" x-text="conn.tag"></div>
                                        <div class="text-xs text-ide-muted mt-0.5" x-text="conn.field"></div>
                                        <span class="inline-block mt-1 text-[10px] px-1.5 py-0.5 rounded-full bg-[var(--ide-info-bg)] text-[var(--ide-info-text)]"
                                              x-text="conn.type === 'polymorphic' ? 'polymorphic' : 'provides'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- No connections message -->
                    <template x-if="getResourceConnections().length === 0">
                        <div class="flex-1 flex items-center justify-center">
                            <p class="text-ide-muted text-sm italic">No relationships detected for this resource.</p>
                        </div>
                    </template>

                    <!-- Legend -->
                    <div class="flex justify-center gap-6 pt-4 mt-auto border-t border-ide-border text-xs text-ide-muted">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded border-2 border-blue-400"></span>
                            <span>Provides data to</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded border-2 border-amber-400"></span>
                            <span>Uses data from</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Endpoint Details -->
                <div
                    x-show="selectedGraphNode"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="w-80 border-l border-ide-border p-4 overflow-y-auto bg-ide-bg"
                >
                    <h4 class="font-medium mb-4 text-ide-fg">Endpoint Details</h4>
                    <template x-if="selectedGraphNode">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-ide-muted uppercase tracking-wider">Method</label>
                                <div class="mt-1">
                                    <span
                                        class="inline-block px-2 py-1 rounded text-xs font-bold text-white"
                                        :class="{
                                            'bg-green-500': selectedGraphNode.method === 'GET',
                                            'bg-blue-500': selectedGraphNode.method === 'POST',
                                            'bg-amber-500': selectedGraphNode.method === 'PUT',
                                            'bg-orange-500': selectedGraphNode.method === 'PATCH',
                                            'bg-red-500': selectedGraphNode.method === 'DELETE'
                                        }"
                                        x-text="selectedGraphNode.method"
                                    ></span>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-ide-muted uppercase tracking-wider">Path</label>
                                <code class="block mt-1 text-sm text-ide-fg font-mono break-all bg-ide-bg p-2 rounded" x-text="selectedGraphNode.path"></code>
                            </div>

                            <div x-show="selectedGraphNode.summary">
                                <label class="text-xs font-semibold text-ide-muted uppercase tracking-wider">Summary</label>
                                <p class="mt-1 text-sm text-ide-muted" x-text="selectedGraphNode.summary"></p>
                            </div>

                            <!-- Required Fields (for POST/PUT/PATCH) -->
                            <div x-show="['POST', 'PUT', 'PATCH'].includes(selectedGraphNode.method) && hasRequestBody(selectedGraphNode)">
                                <label class="text-xs font-semibold text-ide-muted uppercase tracking-wider">Required Fields</label>
                                <div class="mt-2 space-y-1">
                                    <template x-for="field in getRequestBodyFields(selectedGraphNode).filter(f => f.required)" :key="field.name">
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="font-mono text-[var(--ide-success-text)]" x-text="field.name"></span>
                                            <span class="text-gray-400 text-xs" x-text="field.type"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Related Resources (fields ending with _id) -->
                            <div x-show="getEndpointRelatedResources(selectedGraphNode).length > 0">
                                <label class="text-xs font-semibold text-ide-muted uppercase tracking-wider">Related Resources</label>
                                <div class="mt-2 space-y-1">
                                    <template x-for="rel in getEndpointRelatedResources(selectedGraphNode)" :key="rel.field">
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="font-mono text-[var(--ide-info-text)]" x-text="rel.field"></span>
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                            <span
                                                class="text-ide-muted cursor-pointer hover:text-[var(--ide-success-text)]"
                                                x-text="rel.targetTag || rel.resource"
                                                @click="rel.targetTag && selectResourceForMap(rel.targetTag)"
                                            ></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <button
                                @click="navigateToEndpointFromGraph(selectedGraphNode)"
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-500 transition-colors mt-4"
                            >
                                Go to Endpoint
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Empty state when no endpoint selected -->
                <div
                    x-show="!selectedGraphNode"
                    class="w-80 border-l border-ide-border p-4 flex items-center justify-center bg-ide-bg"
                >
                    <div class="text-center text-ide-muted">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                        <p class="text-sm">Select an endpoint to view details</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between p-3 border-t border-ide-border bg-ide-bg/50 text-xs text-ide-muted">
                <span x-text="apiMapView === 'overview'
                    ? `${Object.keys(endpointsByTag).length} resources, ${endpoints.length} endpoints`
                    : `${resourceEndpoints.length} endpoints in ${selectedResource}`"></span>
                <span x-show="apiMapView === 'resource'" x-text="`${getResourceConnections().length} connected resources`"></span>
            </div>
        </div>
    </div>

    <!-- Request Flows Builder (sits next to sidebar) -->
    <div
        x-show="showFlowsPanel"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-0 right-0 z-30 bg-ide-bg flex flex-col"
        style="left: 48px; bottom: 24px"
        x-cloak
    >
        <!-- Top Bar -->
        <div class="h-11 border-b border-ide-border flex items-center justify-between px-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                <h1 class="text-sm font-semibold text-ide-fg">Request Flows</h1>
                <template x-if="editingFlow || isCreatingFlow">
                    <span class="text-[11px] text-ide-muted truncate max-w-[200px]" x-text="newFlow.name || 'Untitled Flow'"></span>
                </template>
            </div>
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <!-- New / Import / Module dropdown -->
                <div class="relative" x-data="{ showAddMenu: false }">
                    <button @click="showAddMenu = !showAddMenu"
                            class="p-1.5 text-ide-muted hover:text-ide-fg hover:bg-ide-line-active rounded-md transition-colors" title="New...">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    <div x-show="showAddMenu" @click.outside="showAddMenu = false" x-transition
                         class="absolute right-0 top-full mt-1 w-48 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1" x-cloak>
                        <button @click="createNewFlow(); showAddMenu = false"
                                class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            New Flow
                        </button>
                        <button @click="createModule(); showAddMenu = false"
                                class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                            New Module
                        </button>
                        <hr class="my-1 border-ide-border">
                        <button @click="importFlow(); showAddMenu = false"
                                class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Import Flow...
                        </button>
                    </div>
                </div>

                <template x-if="editingFlow || isCreatingFlow">
                    <div class="flex items-center gap-2 pl-2 border-l border-ide-border">
                        <!-- Continue on Error toggle -->
                        <label class="flex items-center gap-1.5 text-[11px] text-ide-muted cursor-pointer select-none hover:text-ide-fg transition-colors" title="Continue running steps even if one fails">
                            <input type="checkbox" x-model="newFlow.continueOnError" class="rounded border-ide-border text-violet-600 focus:ring-violet-500 w-3 h-3">
                            <span>Continue on error</span>
                        </label>

                        <!-- Save button -->
                        <div class="relative flex" x-data="{ showSaveMenu: false }">
                            <button @click="saveFlow()" :disabled="!newFlow.steps || newFlow.steps.length === 0"
                                    class="px-3 py-1.5 text-xs font-medium bg-violet-600 text-white rounded-l-md hover:bg-violet-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                Save
                            </button>
                            <button @click="showSaveMenu = !showSaveMenu"
                                    class="px-1 py-1.5 text-xs font-medium bg-violet-600 text-white rounded-r-md hover:bg-violet-700 transition-colors border-l border-violet-500/50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="showSaveMenu" @click.outside="showSaveMenu = false"
                                 x-transition class="absolute right-0 top-full mt-1 w-40 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1" x-cloak>
                                <button @click="saveFlow(); showSaveMenu = false"
                                        class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                    Save
                                </button>
                                <button @click="saveFlowAsNew(); showSaveMenu = false"
                                        class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Save as New
                                </button>
                            </div>
                        </div>

                        <!-- Run All button -->
                        <button @click="runFlow()" :disabled="runningFlow || getCurrentFlow().steps.length === 0"
                                class="px-3 py-1.5 text-xs font-medium bg-ide-fg text-ide-bg rounded-md hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5 transition-colors">
                            <svg x-show="!runningFlow" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <svg x-show="runningFlow" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span x-text="runningFlow ? 'Running...' : 'Run All'"></span>
                        </button>

                        <!-- Results summary (inline in top bar) -->
                        <template x-if="flowRunResults.filter(r => r).length > 0 && !runningFlow">
                            <div class="flex items-center gap-2 text-xs text-ide-muted">
                                <span>
                                    <span class="text-green-600 font-medium" x-text="flowRunResults.filter(r => r?.success).length"></span>/<span x-text="flowRunResults.filter(r => r).length"></span> passed
                                </span>
                                <span x-show="flowTotalDuration > 0" class="font-mono" x-text="flowTotalDuration + 'ms'"></span>
                                <button @click="flowRunResults = []; flowVariables = {}; flowTotalDuration = 0; flowRunError = null"
                                        class="text-ide-muted hover:text-ide-fg transition-colors">Clear</button>
                            </div>
                        </template>

                        <!-- More menu (export) -->
                        <div class="relative" x-data="{ showFlowMenu: false }">
                            <button @click="showFlowMenu = !showFlowMenu"
                                    class="p-1.5 text-ide-muted hover:text-ide-fg hover:bg-ide-line-active rounded-md transition-colors" title="More options">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </button>
                            <div x-show="showFlowMenu" @click.outside="showFlowMenu = false" x-transition
                                 class="absolute right-0 top-full mt-1 w-44 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1" x-cloak>
                                <button @click="exportFlowAsJson(); showFlowMenu = false"
                                        class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Export as JSON
                                </button>
                                <button @click="exportFlowAsYaml(); showFlowMenu = false"
                                        class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Export as YAML
                                </button>
                                <hr class="my-1 border-ide-border">
                                <button @click="importFlow(); showFlowMenu = false"
                                        class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Import Flow...
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Main Content - 2 Column Layout -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Left: Saved Flows List -->
            <div class="w-72 border-r border-ide-border flex flex-col bg-ide-bg/50" x-data="{ flowSelectMode: false }" x-effect="if (!flowSelectMode) selectedFlowIds = []">
                <div class="p-4 flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-ide-muted uppercase tracking-wider" x-show="!flowSelectMode">Saved Flows</h2>
                    <!-- Select mode header -->
                    <template x-if="flowSelectMode">
                        <div class="flex items-center gap-2 w-full">
                            <button @click="flowSelectMode = false" class="p-1 -ml-1 text-ide-muted hover:text-ide-fg rounded transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <span class="text-xs font-medium text-ide-fg" x-text="selectedFlowIds.length ? selectedFlowIds.length + ' selected' : 'Select flows'"></span>
                            <div class="flex-1"></div>
                            <button @click="selectedFlowIds.length === flows.length ? (selectedFlowIds = []) : (selectedFlowIds = flows.map(f => f.id))"
                                    class="text-[10px] text-violet-600 hover:text-violet-700 font-medium transition-colors"
                                    x-text="selectedFlowIds.length === flows.length ? 'None' : 'All'">
                            </button>
                        </div>
                    </template>
                    <!-- Enter select mode button -->
                    <template x-if="!flowSelectMode && flows.length > 1">
                        <button @click="flowSelectMode = true"
                                class="text-[10px] text-ide-muted hover:text-ide-fg transition-colors">
                            Select
                        </button>
                    </template>
                </div>
                <!-- Bulk actions bar -->
                <div x-show="flowSelectMode && selectedFlowIds.length > 0" x-collapse class="px-3 pb-2">
                    <div class="flex items-center gap-1 p-1.5 bg-violet-50 dark:bg-violet-900/20 rounded-lg border border-violet-200/60 dark:border-violet-500/20">
                        <button @click="exportSelectedFlows('json')"
                                class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 text-[11px] font-medium text-violet-700 dark:text-violet-300 hover:bg-violet-100 dark:hover:bg-violet-800/30 rounded transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            JSON
                        </button>
                        <button @click="exportSelectedFlows('yaml')"
                                class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 text-[11px] font-medium text-violet-700 dark:text-violet-300 hover:bg-violet-100 dark:hover:bg-violet-800/30 rounded transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            YAML
                        </button>
                        <div class="w-px h-5 bg-violet-200 dark:bg-violet-700/50"></div>
                        <button @click="deleteSelectedFlows()"
                                class="flex items-center justify-center gap-1.5 px-2 py-1.5 text-[11px] font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto px-3 pb-3">
                    <!-- Loading State -->
                    <div x-show="loadingFlows" class="flex items-center justify-center py-8">
                        <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>

                    <div x-show="!loadingFlows" class="space-y-1">
                        <!-- ===== Module Folders ===== -->
                        <template x-for="mod in modules" :key="'mod-'+mod.id">
                            <div class="mb-1">
                                <!-- Module folder header -->
                                <div class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg cursor-pointer transition-colors group"
                                     :class="isModuleCollapsed(mod.id) ? 'hover:bg-ide-surface' : 'bg-ide-surface/50'"
                                     @click="toggleModuleCollapse(mod.id)">
                                    <svg class="w-3.5 h-3.5 text-ide-muted transition-transform flex-shrink-0" :class="isModuleCollapsed(mod.id) ? '' : 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                    <template x-if="editingModuleName !== mod.id">
                                        <span class="flex-1 text-xs font-medium text-ide-fg truncate" @dblclick.stop="editingModuleName = mod.id" x-text="mod.name"></span>
                                    </template>
                                    <template x-if="editingModuleName === mod.id">
                                        <input type="text" :value="mod.name" class="flex-1 px-1 py-0 text-xs bg-ide-bg border border-ide-primary rounded text-ide-fg focus:outline-none"
                                            @click.stop @keydown.enter="renameModule(mod.id, $event.target.value); editingModuleName = null"
                                            @keydown.escape="editingModuleName = null" @blur="renameModule(mod.id, $event.target.value); editingModuleName = null"
                                            x-init="$nextTick(() => { $el.focus(); $el.select(); })">
                                    </template>
                                    <span class="text-[10px] text-ide-muted flex-shrink-0" x-text="getModuleItemCount(mod)"></span>
                                    <!-- Module actions dropdown -->
                                    <div class="relative opacity-0 group-hover:opacity-100 flex-shrink-0" x-data="{ open: false }" @click.stop>
                                        <button @click="open = !open" class="p-0.5 text-ide-muted hover:text-ide-fg rounded">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                        </button>
                                        <div x-show="open" @click.outside="open = false" class="absolute right-0 top-full mt-1 w-40 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1" x-cloak>
                                            <button @click="editingModuleName = mod.id; open = false" class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active">Rename</button>
                                            <button @click="createModule(mod.id); open = false" class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active">New Subfolder</button>
                                            <hr class="my-1 border-ide-border">
                                            <button @click="promptDeleteModule(mod.id); open = false" class="w-full px-3 py-1.5 text-left text-xs text-red-400 hover:bg-ide-line-active">Delete Module</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Module contents (when expanded) -->
                                <div x-show="!isModuleCollapsed(mod.id)" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l border-ide-border/40 pl-2">
                                    <!-- Subfolders -->
                                    <template x-for="child in (mod.children || [])" :key="'mod-'+child.id">
                                        <div class="mb-0.5">
                                            <div class="flex items-center gap-1.5 px-2 py-1 rounded cursor-pointer hover:bg-ide-surface transition-colors group/child"
                                                 @click="toggleModuleCollapse(child.id)">
                                                <svg class="w-3 h-3 text-ide-muted transition-transform flex-shrink-0" :class="isModuleCollapsed(child.id) ? '' : 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                <svg class="w-3.5 h-3.5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                                <template x-if="editingModuleName !== child.id">
                                                    <span class="flex-1 text-xs text-ide-fg truncate" @dblclick.stop="editingModuleName = child.id" x-text="child.name"></span>
                                                </template>
                                                <template x-if="editingModuleName === child.id">
                                                    <input type="text" :value="child.name" class="flex-1 px-1 py-0 text-xs bg-ide-bg border border-ide-primary rounded text-ide-fg focus:outline-none"
                                                        @click.stop @keydown.enter="renameModule(child.id, $event.target.value); editingModuleName = null"
                                                        @keydown.escape="editingModuleName = null" @blur="renameModule(child.id, $event.target.value); editingModuleName = null"
                                                        x-init="$nextTick(() => { $el.focus(); $el.select(); })">
                                                </template>
                                                <span class="text-[10px] text-ide-muted flex-shrink-0" x-text="getModuleItemCount(child)"></span>
                                                <button @click.stop="promptDeleteModule(child.id)" class="opacity-0 group-hover/child:opacity-100 p-0.5 text-ide-muted hover:text-red-500 flex-shrink-0" title="Delete subfolder">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                            <!-- Subfolder flows -->
                                            <div x-show="!isModuleCollapsed(child.id)" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l border-ide-border/30 pl-2">
                                                <template x-for="flow in (child.saved_flows || [])" :key="flow.id">
                                                    <div @click="flowSelectMode ? toggleFlowSelection(flow.id) : loadFlow(flow)"
                                                         :class="{
                                                             'bg-ide-bg shadow-sm border-ide-border': !flowSelectMode && editingFlow?.id === flow.id,
                                                             'bg-violet-50 dark:bg-violet-900/15 border-violet-300/40 dark:border-violet-500/30 shadow-sm': flowSelectMode && selectedFlowIds.includes(flow.id),
                                                             'hover:bg-ide-surface border-transparent': flowSelectMode ? !selectedFlowIds.includes(flow.id) : editingFlow?.id !== flow.id
                                                         }"
                                                         class="p-2 rounded-lg border cursor-pointer transition-all group/flow relative">
                                                        <div x-show="flowSelectMode && selectedFlowIds.includes(flow.id)"
                                                             class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-violet-600 dark:bg-violet-500 flex items-center justify-center shadow-sm z-10">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        </div>
                                                        <div class="flex items-start justify-between gap-2">
                                                            <div class="flex-1 min-w-0">
                                                                <div class="font-medium text-xs text-ide-fg truncate" x-text="flow.name || 'Untitled'"></div>
                                                                <div class="text-[10px] text-ide-muted mt-0.5" x-text="(flow.steps?.length || 0) + ' steps'"></div>
                                                            </div>
                                                            <div x-show="!flowSelectMode" class="flex items-center gap-0.5 opacity-0 group-hover/flow:opacity-100 flex-shrink-0">
                                                                <div class="relative" x-data="{ moveOpen: false }" @click.stop>
                                                                    <button @click="moveOpen = !moveOpen" class="p-1 text-ide-muted hover:text-ide-fg rounded" title="Move to module">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                                    </button>
                                                                    <div x-show="moveOpen" @click.outside="moveOpen = false" class="absolute right-0 top-full mt-1 w-44 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1 max-h-48 overflow-y-auto" x-cloak>
                                                                        <button @click="moveItemToModule('flow', flow.id, null); moveOpen = false" class="w-full px-3 py-1.5 text-left text-xs text-ide-muted hover:bg-ide-line-active">Unorganized</button>
                                                                        <template x-for="m in modules" :key="'mv-'+m.id">
                                                                            <div>
                                                                                <button @click="moveItemToModule('flow', flow.id, m.id); moveOpen = false" class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active truncate" x-text="m.name"></button>
                                                                                <template x-for="mc in (m.children || [])" :key="'mv-'+mc.id">
                                                                                    <button @click="moveItemToModule('flow', flow.id, mc.id); moveOpen = false" class="w-full px-3 py-1.5 pl-6 text-left text-xs text-ide-fg hover:bg-ide-line-active truncate" x-text="mc.name"></button>
                                                                                </template>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                                <button @click.stop="deleteFlow(flow.id)" class="p-1 text-ide-muted hover:text-[var(--ide-error-text)] rounded" title="Delete flow">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div x-show="(child.saved_flows || []).length === 0" class="px-2 py-1.5 text-[10px] text-ide-muted italic">Empty</div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Module's own flows -->
                                    <template x-for="flow in (mod.saved_flows || [])" :key="flow.id">
                                        <div @click="flowSelectMode ? toggleFlowSelection(flow.id) : loadFlow(flow)"
                                             :class="{
                                                 'bg-ide-bg shadow-sm border-ide-border': !flowSelectMode && editingFlow?.id === flow.id,
                                                 'bg-violet-50 dark:bg-violet-900/15 border-violet-300/40 dark:border-violet-500/30 shadow-sm': flowSelectMode && selectedFlowIds.includes(flow.id),
                                                 'hover:bg-ide-surface border-transparent': flowSelectMode ? !selectedFlowIds.includes(flow.id) : editingFlow?.id !== flow.id
                                             }"
                                             class="p-2 rounded-lg border cursor-pointer transition-all group/flow relative">
                                            <div x-show="flowSelectMode && selectedFlowIds.includes(flow.id)"
                                                 class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-violet-600 dark:bg-violet-500 flex items-center justify-center shadow-sm z-10">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium text-xs text-ide-fg truncate" x-text="flow.name || 'Untitled'"></div>
                                                    <div class="text-[10px] text-ide-muted mt-0.5" x-text="(flow.steps?.length || 0) + ' steps'"></div>
                                                </div>
                                                <div x-show="!flowSelectMode" class="flex items-center gap-0.5 opacity-0 group-hover/flow:opacity-100 flex-shrink-0">
                                                    <div class="relative" x-data="{ moveOpen: false }" @click.stop>
                                                        <button @click="moveOpen = !moveOpen" class="p-1 text-ide-muted hover:text-ide-fg rounded" title="Move to module">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                        </button>
                                                        <div x-show="moveOpen" @click.outside="moveOpen = false" class="absolute right-0 top-full mt-1 w-44 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1 max-h-48 overflow-y-auto" x-cloak>
                                                            <button @click="moveItemToModule('flow', flow.id, null); moveOpen = false" class="w-full px-3 py-1.5 text-left text-xs text-ide-muted hover:bg-ide-line-active">Unorganized</button>
                                                            <template x-for="m in modules" :key="'mv-'+m.id">
                                                                <div>
                                                                    <button @click="moveItemToModule('flow', flow.id, m.id); moveOpen = false" class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active truncate" x-text="m.name"></button>
                                                                    <template x-for="mc in (m.children || [])" :key="'mv-'+mc.id">
                                                                        <button @click="moveItemToModule('flow', flow.id, mc.id); moveOpen = false" class="w-full px-3 py-1.5 pl-6 text-left text-xs text-ide-fg hover:bg-ide-line-active truncate" x-text="mc.name"></button>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <button @click.stop="deleteFlow(flow.id)" class="p-1 text-ide-muted hover:text-[var(--ide-error-text)] rounded" title="Delete flow">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="(mod.saved_flows || []).length === 0 && (mod.children || []).length === 0" class="px-2 py-1.5 text-[10px] text-ide-muted italic">Empty module</div>
                                </div>
                            </div>
                        </template>

                        <!-- ===== Unorganized Flows (no module) ===== -->
                        <template x-if="getUnorganizedFlows().length > 0">
                            <div>
                                <div x-show="modules.length > 0" class="flex items-center gap-1.5 px-2 py-1.5 mt-2 mb-0.5 rounded-lg cursor-pointer hover:bg-ide-surface transition-colors"
                                     @click="toggleModuleCollapse('unorganized')">
                                    <svg class="w-3.5 h-3.5 text-ide-muted transition-transform flex-shrink-0" :class="isModuleCollapsed('unorganized') ? '' : 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/></svg>
                                    <span class="flex-1 text-xs font-medium text-ide-muted">Unorganized</span>
                                    <span class="text-[10px] text-ide-muted" x-text="getUnorganizedFlows().length"></span>
                                </div>
                                <div x-show="modules.length === 0 || !isModuleCollapsed('unorganized')" class="space-y-0.5" :class="modules.length > 0 ? 'ml-3 border-l border-ide-border/30 pl-2' : ''">
                                    <template x-for="flow in getUnorganizedFlows()" :key="flow.id">
                                        <div @click="flowSelectMode ? toggleFlowSelection(flow.id) : loadFlow(flow)"
                                             :class="{
                                                 'bg-ide-bg shadow-sm border-ide-border': !flowSelectMode && editingFlow?.id === flow.id,
                                                 'bg-violet-50 dark:bg-violet-900/15 border-violet-300/40 dark:border-violet-500/30 shadow-sm': flowSelectMode && selectedFlowIds.includes(flow.id),
                                                 'hover:bg-ide-surface border-transparent': flowSelectMode ? !selectedFlowIds.includes(flow.id) : editingFlow?.id !== flow.id
                                             }"
                                             class="p-2 rounded-lg border cursor-pointer transition-all group/flow relative">
                                            <div x-show="flowSelectMode && selectedFlowIds.includes(flow.id)"
                                                 class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-violet-600 dark:bg-violet-500 flex items-center justify-center shadow-sm z-10">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium text-xs text-ide-fg truncate" x-text="flow.name || 'Untitled'"></div>
                                                    <div class="text-[10px] text-ide-muted mt-0.5" x-text="(flow.steps?.length || 0) + ' steps'"></div>
                                                </div>
                                                <div x-show="!flowSelectMode" class="flex items-center gap-0.5 opacity-0 group-hover/flow:opacity-100 flex-shrink-0">
                                                    <div class="relative" x-data="{ moveOpen: false }" @click.stop>
                                                        <button @click="moveOpen = !moveOpen" class="p-1 text-ide-muted hover:text-ide-fg rounded" title="Move to module">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                        </button>
                                                        <div x-show="moveOpen" @click.outside="moveOpen = false" class="absolute right-0 top-full mt-1 w-44 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1 max-h-48 overflow-y-auto" x-cloak>
                                                            <template x-for="m in modules" :key="'mv-'+m.id">
                                                                <div>
                                                                    <button @click="moveItemToModule('flow', flow.id, m.id); moveOpen = false" class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active truncate" x-text="m.name"></button>
                                                                    <template x-for="mc in (m.children || [])" :key="'mv-'+mc.id">
                                                                        <button @click="moveItemToModule('flow', flow.id, mc.id); moveOpen = false" class="w-full px-3 py-1.5 pl-6 text-left text-xs text-ide-fg hover:bg-ide-line-active truncate" x-text="mc.name"></button>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <button @click.stop="deleteFlow(flow.id)" class="p-1 text-ide-muted hover:text-[var(--ide-error-text)] rounded" title="Delete flow">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Empty state -->
                        <div x-show="flows.length === 0" class="text-center py-12 text-gray-400">
                            <p class="text-sm">No saved flows</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Flow Editor -->
            <div class="flex-1 overflow-y-auto">
                <!-- Empty State - Only show when not creating/editing -->
                <div x-show="!editingFlow && !isCreatingFlow" class="h-full flex items-center justify-center p-6">
                    <!-- No flows exist at all -->
                    <div x-show="flows.length === 0" class="text-center max-w-md">
                        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-ide-bg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ide-fg mb-2">Create a Request Flow</h3>
                        <p class="text-ide-muted mb-6">
                            Chain multiple API requests together. Pass data from one response to the next request automatically.
                        </p>
                        <button @click="createNewFlow()"
                                class="px-6 py-3 bg-ide-fg text-ide-bg font-medium rounded-lg hover:opacity-90 transition-colors">
                            Create Your First Flow
                        </button>
                    </div>
                    <!-- Flows exist but none selected -->
                    <div x-show="flows.length > 0" class="text-center max-w-md">
                        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-ide-bg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ide-fg mb-2">Select a Flow</h3>
                        <p class="text-ide-muted mb-6">
                            Choose a flow from the sidebar to view or edit it, or create a new one.
                        </p>
                        <button @click="createNewFlow()"
                                class="px-6 py-3 bg-ide-fg text-ide-bg font-medium rounded-lg hover:opacity-90 transition-colors">
                            Create New Flow
                        </button>
                    </div>
                </div>

                <!-- Flow Editor Content -->
                <div x-show="editingFlow || isCreatingFlow" class="p-6 max-w-3xl mx-auto">
                    <!-- Flow Name & Description - Simple inline -->
                    <div class="mb-8">
                        <input type="text" x-model="getCurrentFlow().name" placeholder="Flow name..."
                               class="w-full text-2xl font-semibold bg-transparent border-0 border-b-2 border-transparent hover:border-ide-border focus:border-ide-fg text-ide-fg placeholder-ide-muted focus:ring-0 focus:outline-none pb-2 transition-colors">
                        <input type="text" x-model="getCurrentFlow().description" placeholder="Add a description..."
                               class="w-full mt-2 text-sm bg-transparent border-0 text-ide-muted placeholder-ide-muted focus:ring-0 focus:outline-none">
                    </div>

                    <!-- Flow Default Headers -->
                    <div class="mb-6 p-4 bg-ide-bg rounded-lg border border-ide-border" x-data="{ expanded: Object.keys(getCurrentFlow().defaultHeaders || {}).length > 0 }">
                        <button @click="expanded = !expanded" class="w-full flex items-center justify-between text-sm font-medium text-ide-fg">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Default Headers
                                <span class="px-1.5 py-0.5 text-xs bg-ide-border rounded" x-text="Object.keys(getCurrentFlow().defaultHeaders || {}).length"></span>
                            </span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <p class="text-xs text-ide-muted mt-1">Headers applied to all steps in this flow</p>

                        <div x-show="expanded" x-collapse class="mt-3 space-y-2">
                            <!-- Existing default headers -->
                            <template x-for="(value, key) in getCurrentFlow().defaultHeaders || {}" :key="key">
                                <div class="flex items-center gap-2">
                                    <input type="text" :value="key" readonly
                                           class="w-1/3 px-2 py-1.5 text-xs font-medium bg-ide-border border border-ide-border rounded text-ide-fg">
                                    <input type="text" x-model="getCurrentFlow().defaultHeaders[key]"
                                           @input="handleVariableAutocomplete($event, {}, 'defaultHeader', key)"
                                           class="flex-1 px-2 py-1.5 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg font-mono">
                                    <button @click="removeDefaultHeader(key)"
                                            class="p-1.5 text-ide-muted hover:text-[var(--ide-error-text)] hover:bg-[var(--ide-error-bg)] rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <!-- Add new header -->
                            <div class="flex items-center gap-2 pt-2 border-t border-ide-border">
                                <input type="text" x-model="newDefaultHeaderKey" placeholder="Header name"
                                       class="w-1/3 px-2 py-1.5 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400">
                                <input type="text" x-model="newDefaultHeaderValue" placeholder="Value (use @{{step1.var}})"
                                       class="flex-1 px-2 py-1.5 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400 font-mono">
                                <button @click="addDefaultHeader()"
                                        class="px-3 py-1.5 text-xs font-medium bg-ide-fg text-ide-bg rounded hover:opacity-90 transition-colors">
                                    Add
                                </button>
                            </div>

                            <!-- Common headers quick add -->
                            <div class="flex flex-wrap gap-1 pt-2">
                                <button @click="newDefaultHeaderKey = 'Authorization'; newDefaultHeaderValue = 'Bearer @{{step1.token}}'"
                                        class="px-2 py-0.5 text-xs bg-ide-primary/10 text-ide-primary rounded hover:bg-ide-primary/20"
                                    + Authorization
                                </button>
                                <button @click="newDefaultHeaderKey = 'X-Requested-With'; newDefaultHeaderValue = 'XMLHttpRequest'"
                                        class="px-2 py-0.5 text-xs bg-ide-primary/10 text-ide-primary rounded hover:bg-ide-primary/20"
                                    + X-Requested-With
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Steps Section -->
                    <div class="space-y-3">
                        <template x-for="(step, index) in getCurrentFlow().steps" :key="step.id || index">
                            <div class="relative group">
                                <!-- Step Number & Line -->
                                <div class="absolute left-0 top-0 bottom-0 w-10 flex flex-col items-center">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold z-10"
                                         :class="{
                                             'bg-green-500 text-white': flowRunResults[index]?.success,
                                             'bg-amber-500 text-white': flowRunResults[index]?.httpOk && !flowRunResults[index]?.allAssertionsPassed,
                                             'bg-red-500 text-white': flowRunResults[index]?.success === false && !(flowRunResults[index]?.httpOk && !flowRunResults[index]?.allAssertionsPassed),
                                             'bg-ide-fg text-ide-bg': !flowRunResults[index] && !(runningFlow && currentFlowStep === index),
                                             'bg-violet-500 text-white animate-pulse': runningFlow && currentFlowStep === index
                                         }"
                                         x-text="index + 1"></div>
                                    <div x-show="index < getCurrentFlow().steps.length - 1" class="flex-1 w-0.5 bg-ide-border my-1"></div>
                                </div>

                                <!-- Step Card -->
                                <div class="ml-12 border border-ide-border bg-ide-bg overflow-hidden"
                                     :class="{
                                         'rounded-lg': !flowRunResults[index],
                                         'rounded-t-lg': flowRunResults[index],
                                         'border-[var(--ide-success-text)]/30': flowRunResults[index]?.success,
                                         'border-amber-400/40': flowRunResults[index]?.httpOk && !flowRunResults[index]?.allAssertionsPassed,
                                         'border-[var(--ide-error-text)]/30': flowRunResults[index]?.success === false && !(flowRunResults[index]?.httpOk && !flowRunResults[index]?.allAssertionsPassed)
                                     }">
                                    <div class="p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="px-2 py-0.5 text-xs font-bold rounded" :class="getMethodBadgeClass(step.endpoint?.method)" x-text="step.endpoint?.method?.toUpperCase()"></span>
                                                    <span class="text-sm font-mono text-ide-muted truncate" x-text="step.endpoint?.path"></span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="text" x-model="step.name" placeholder="Add step name..."
                                                           class="flex-1 text-sm bg-transparent border-0 p-0 text-ide-fg placeholder-gray-400 focus:ring-0">
                                                    <!-- Last run status badge -->
                                                    <template x-if="flowRunResults[index]">
                                                        <span class="shrink-0 px-1.5 py-0.5 text-[10px] font-bold rounded font-mono"
                                                              :class="{
                                                                  'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': flowRunResults[index]?.success,
                                                                  'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': flowRunResults[index]?.httpOk && !flowRunResults[index]?.allAssertionsPassed,
                                                                  'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': !flowRunResults[index]?.httpOk
                                                              }"
                                                              x-text="flowRunResults[index]?.status + (flowRunResults[index]?.duration ? ' · ' + flowRunResults[index]?.duration + 'ms' : '')">
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-0.5">
                                                <!-- Play button (always visible) -->
                                                <button @click.stop="runSingleStep(index)" :disabled="runningFlow || runningSingleStep !== -1"
                                                        class="p-1.5 rounded transition-colors"
                                                        :class="runningSingleStep === index ? 'text-green-500 animate-pulse' : 'text-green-500 hover:bg-green-500/10'"
                                                        title="Run this step">
                                                    <svg x-show="runningSingleStep !== index" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    <svg x-show="runningSingleStep === index" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                </button>
                                            </div>
                                            <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click="flowDocEndpoint = getFullEndpointForStep(step); if (flowDocEndpoint) { showFlowDocDialog = true; } else { showToast('Endpoint not found in spec', 'error'); }" class="p-1.5 text-ide-muted hover:text-ide-primary hover:bg-ide-primary/10 rounded" title="View documentation">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                </button>
                                                <button @click="prefillStepWithExamples(step)" class="p-1.5 text-[var(--ide-warning-text)] hover:opacity-80 hover:bg-[var(--ide-warning-bg)] rounded" title="Fill with example values">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                </button>
                                                <button @click="showImportModal(index)" class="p-1.5 text-ide-primary hover:opacity-80 hover:bg-ide-primary/10 rounded" title="Import from saved request">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                </button>
                                                <button @click="saveStepAsRequest(step, index)" class="p-1.5 text-green-500 hover:opacity-80 hover:bg-green-500/10 rounded" title="Save as standalone request">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                                </button>
                                                <button @click="moveStepUp(index)" :disabled="index === 0" class="p-1.5 text-ide-muted hover:text-ide-fg disabled:opacity-30">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                                </button>
                                                <button @click="moveStepDown(index)" :disabled="index === getCurrentFlow().steps.length - 1" class="p-1.5 text-ide-muted hover:text-ide-fg disabled:opacity-30">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </button>
                                                <button @click="removeStepFromFlow(index)" class="p-1.5 text-gray-400 hover:text-red-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Expected Status -->
                                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-ide-border"
                                             x-data="{ get docCodes() { return getStatusCodesForEndpoint(step.endpoint) } }">
                                            <label class="text-xs text-ide-muted whitespace-nowrap">Expected Status</label>
                                            <select :value="step.expectedStatus ?? ''"
                                                    @change="step.expectedStatus = $event.target.value === '' ? null : Number($event.target.value)"
                                                    class="w-auto min-w-[120px] px-2 py-1 text-xs font-mono bg-ide-surface border border-ide-border rounded text-ide-fg focus:ring-1 focus:ring-ide-primary/50 focus:border-ide-primary/50 appearance-none cursor-pointer"
                                                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%236b7280%22 stroke-width=%222%22%3E%3Cpath d=%22M6 9l6 6 6-6%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 6px center; padding-right: 22px;">
                                                <option value="">Any 2xx</option>
                                                <template x-for="sc in docCodes" :key="'doc-' + sc.code">
                                                    <option :value="sc.code" x-text="sc.code + (sc.description ? ' \u2014 ' + sc.description : '')"></option>
                                                </template>
                                            </select>
                                            <template x-if="step.expectedStatus">
                                                <button @click="step.expectedStatus = null" class="p-0.5 text-ide-muted hover:text-ide-fg rounded transition-colors" title="Clear">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </template>
                                        </div>

                                        <!-- Expected Result Section -->
                                        <div class="mt-3 pt-3 border-t border-ide-border" x-data="{ showExpected: !!(step.expectedResult) }">
                                            <button @click="showExpected = !showExpected" class="text-xs text-gray-500 hover:text-ide-fg flex items-center gap-1.5 mb-2">
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': showExpected }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span>Expected Result</span>
                                                <span x-show="step.expectedResult" class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            </button>
                                            <div x-show="showExpected" x-collapse>
                                                <textarea x-model="step.expectedResult"
                                                          placeholder="Describe the expected response, paste a JSON sample, etc."
                                                          rows="3"
                                                          class="w-full px-3 py-2 text-xs font-mono bg-ide-surface border border-ide-border rounded-lg text-ide-fg placeholder-gray-400 focus:ring-1 focus:ring-ide-primary/50 focus:border-ide-primary/50 resize-y min-h-[60px]"></textarea>
                                            </div>
                                        </div>

                                        <!-- Path Parameters Section -->
                                        <div x-show="Object.keys(step.pathParams || {}).length > 0" class="mt-3 pt-3 border-t border-ide-border" x-data="{ showPathParams: true }">
                                            <button @click="showPathParams = !showPathParams" class="text-xs text-gray-500 hover:text-ide-fg flex items-center gap-1.5 mb-2">
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': showPathParams }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span>Path Parameters</span>
                                                <span class="px-1 py-0.5 text-[10px] bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)] rounded" x-text="Object.keys(step.pathParams || {}).length"></span>
                                            </button>
                                            <div x-show="showPathParams" x-collapse class="space-y-2">
                                                <template x-for="(value, key) in step.pathParams || {}" :key="key">
                                                    <div class="flex items-center gap-2">
                                                        <label class="w-24 text-xs font-medium text-ide-muted flex items-center gap-1">
                                                            <span x-text="'{' + key + '}'"></span>
                                                            <template x-if="step._autoFilledFields?.[key]">
                                                                <span class="inline-flex items-center px-1 py-0.5 text-[9px] rounded-sm animate-pulse"
                                                                      :class="step._autoFilledFields[key].type === 'discovered'
                                                                          ? 'bg-amber-500/15 text-amber-600 border border-amber-500/30'
                                                                          : 'bg-green-500/15 text-green-600 border border-green-500/30'"
                                                                      :title="'From ' + step._autoFilledFields[key].source"
                                                                      x-text="step._autoFilledFields[key].type === 'discovered' ? 'new' : step._autoFilledFields[key].source"></span>
                                                            </template>
                                                        </label>
                                                        <input type="text" x-model="step.pathParams[key]"
                                                               @input="handleVariableAutocomplete($event, step, 'pathParams', key)"
                                                               class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded font-mono"
                                                               :class="step._autoFilledFields?.[key]
                                                                   ? (step._autoFilledFields[key].type === 'discovered' ? 'text-amber-600 border-amber-500/40 bg-amber-500/5' : 'text-green-600 border-green-500/40 bg-green-500/5')
                                                                   : (String(step.pathParams[key] || '').match(/\x7b\x7b/) ? 'text-violet-400 border-violet-500/40 bg-violet-500/5' : 'text-ide-fg')"
                                                               :placeholder="'Enter ' + key">
                                                        <button @click="openVariablePicker(step, 'pathParams', key, $event)"
                                                                class="px-1.5 py-1 text-xs text-[var(--ide-info-text)] hover:opacity-80 hover:bg-[var(--ide-info-bg)] rounded"
                                                                title="Insert variable">
                                                            @{{ }}
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Headers Section -->
                                        <div class="mt-3 pt-3 border-t border-ide-border" x-data="{ showHeaders: false, newKey: '', newValue: '' }">
                                            <button @click="showHeaders = !showHeaders" class="text-xs text-gray-500 hover:text-ide-fg flex items-center gap-1.5 mb-2">
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': showHeaders }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span>Headers</span>
                                                <span class="px-1 py-0.5 text-[10px] bg-ide-border text-ide-muted rounded" x-text="Object.keys(step.headers || {}).length"></span>
                                                <span class="text-[10px] text-gray-400">(overrides defaults)</span>
                                            </button>
                                            <div x-show="showHeaders" x-collapse class="space-y-2">
                                                <!-- Existing headers -->
                                                <template x-for="(value, key) in step.headers || {}" :key="key">
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" :value="key" readonly
                                                               class="w-1/3 px-2 py-1 text-xs font-medium bg-ide-border border border-ide-border rounded text-ide-fg">
                                                        <input type="text" x-model="step.headers[key]"
                                                               @input="handleVariableAutocomplete($event, step, 'headers', key)"
                                                               class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded font-mono"
                                                               :class="String(step.headers[key] || '').match(/\x7b\x7b/) ? 'text-violet-400 border-violet-500/40 bg-violet-500/5' : 'text-ide-fg'"
                                                        <button @click="openVariablePicker(step, 'headers', key, $event)"
                                                                class="px-1.5 py-1 text-xs text-[var(--ide-info-text)] hover:opacity-80 hover:bg-[var(--ide-info-bg)] rounded">
                                                            @{{ }}
                                                        </button>
                                                        <button @click="removeStepHeader(step, key)"
                                                                class="p-1 text-gray-400 hover:text-red-500">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <!-- Add new header -->
                                                <div class="flex items-center gap-2">
                                                    <input type="text" x-model="newKey" placeholder="Header name"
                                                           class="w-1/3 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400">
                                                    <input type="text" x-model="newValue" placeholder="Value"
                                                           class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400 font-mono">
                                                    <button @click="if(newKey) { addStepHeader(step, newKey, newValue); newKey = ''; newValue = ''; }"
                                                            class="px-2 py-1 text-xs bg-ide-fg text-ide-bg rounded hover:opacity-90">Add</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Query Parameters Section -->
                                        <div class="mt-3 pt-3 border-t border-ide-border" x-data="{ showParams: false, newKey: '', newValue: '' }">
                                            <button @click="showParams = !showParams" class="text-xs text-gray-500 hover:text-ide-fg flex items-center gap-1.5 mb-2">
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': showParams }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span>Query Parameters</span>
                                                <span class="px-1 py-0.5 text-[10px] bg-ide-border text-ide-muted rounded" x-text="Object.keys(step.params || {}).length"></span>
                                            </button>
                                            <div x-show="showParams" x-collapse class="space-y-2">
                                                <!-- Existing params -->
                                                <template x-for="(value, key) in step.params || {}" :key="key">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-1/3 flex items-center gap-1">
                                                            <input type="text" :value="key" readonly
                                                                   class="flex-1 px-2 py-1 text-xs font-medium bg-ide-border border border-ide-border rounded text-ide-fg">
                                                            <template x-if="step._autoFilledFields?.[key]">
                                                                <span class="inline-flex items-center px-1 py-0.5 text-[9px] rounded-sm shrink-0 animate-pulse"
                                                                      :class="step._autoFilledFields[key].type === 'discovered'
                                                                          ? 'bg-amber-500/15 text-amber-600 border border-amber-500/30'
                                                                          : 'bg-green-500/15 text-green-600 border border-green-500/30'"
                                                                      :title="'From ' + step._autoFilledFields[key].source"
                                                                      x-text="step._autoFilledFields[key].type === 'discovered' ? 'new' : step._autoFilledFields[key].source"></span>
                                                            </template>
                                                        </div>
                                                        <input type="text" x-model="step.params[key]"
                                                               @input="handleVariableAutocomplete($event, step, 'params', key)"
                                                               class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded font-mono"
                                                               :class="step._autoFilledFields?.[key]
                                                                   ? (step._autoFilledFields[key].type === 'discovered' ? 'text-amber-600 border-amber-500/40 bg-amber-500/5' : 'text-green-600 border-green-500/40 bg-green-500/5')
                                                                   : (String(step.params[key] || '').match(/\x7b\x7b/) ? 'text-violet-400 border-violet-500/40 bg-violet-500/5' : 'text-ide-fg')">
                                                        <button @click="openVariablePicker(step, 'params', key, $event)"
                                                                class="px-1.5 py-1 text-xs text-[var(--ide-info-text)] hover:opacity-80 hover:bg-[var(--ide-info-bg)] rounded">
                                                            @{{ }}
                                                        </button>
                                                        <button @click="removeStepParam(step, key)"
                                                                class="p-1 text-gray-400 hover:text-red-500">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <!-- Suggested params from endpoint schema -->
                                                <div x-show="getQueryParamsForEndpoint(step.endpoint).length > 0" class="pt-1">
                                                    <div class="text-[10px] text-gray-400 mb-1">Quick add:</div>
                                                    <div class="flex flex-wrap gap-1">
                                                        <template x-for="param in getQueryParamsForEndpoint(step.endpoint).slice(0, 6)" :key="param.name">
                                                            <button @click="if(!step.params?.[param.name]) { step.params = step.params || {}; step.params[param.name] = ''; }"
                                                                    x-show="!step.params?.[param.name]"
                                                                    class="px-1.5 py-0.5 text-[10px] bg-ide-border text-ide-muted rounded hover:bg-ide-line-active"
                                                                    x-text="param.name">
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                                <!-- Add new param -->
                                                <div class="flex items-center gap-2">
                                                    <input type="text" x-model="newKey" placeholder="Param name"
                                                           class="w-1/3 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400">
                                                    <input type="text" x-model="newValue" placeholder="Value"
                                                           class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400 font-mono">
                                                    <button @click="if(newKey) { addStepParam(step, newKey, newValue); newKey = ''; newValue = ''; }"
                                                            class="px-2 py-1 text-xs bg-ide-fg text-ide-bg rounded hover:opacity-90">Add</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Request Body Section (for POST/PUT/PATCH) -->
                                        <div x-show="methodRequiresBody(step.endpoint?.method)"
                                             class="mt-3 pt-3 border-t border-ide-border"
                                             x-data="{ showBody: false, newKey: '', newValue: '' }">
                                            <button @click="showBody = !showBody" class="text-xs text-gray-500 hover:text-ide-fg flex items-center gap-1.5 mb-2">
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': showBody }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span>Request Body</span>
                                                <span class="px-1 py-0.5 text-[10px] bg-[var(--ide-info-bg)] text-[var(--ide-info-text)] rounded" x-text="Object.keys(step.body || {}).length + ' fields'"></span>
                                            </button>
                                            <div x-show="showBody" x-collapse class="space-y-2">
                                                <!-- Mode Toggle -->
                                                <div class="flex gap-1 mb-2">
                                                    <button @click="step.bodyMode = 'form'; if(!step.body) step.body = {};"
                                                            :class="(step.bodyMode || 'form') === 'form' ? 'bg-ide-fg text-ide-bg' : 'bg-ide-border text-ide-fg'"
                                                            class="px-2 py-0.5 text-xs rounded-l transition-colors">
                                                        Form
                                                    </button>
                                                    <button @click="step.bodyMode = 'json'; syncStepBodyToJson(step);"
                                                            :class="step.bodyMode === 'json' ? 'bg-ide-fg text-ide-bg' : 'bg-ide-border text-ide-fg'"
                                                            class="px-2 py-0.5 text-xs transition-colors">
                                                        JSON
                                                    </button>
                                                    <button @click="step.bodyMode = 'formdata'; if(!step.body) step.body = {};"
                                                            :class="step.bodyMode === 'formdata' ? 'bg-ide-fg text-ide-bg' : 'bg-ide-border text-ide-fg'"
                                                            class="px-2 py-0.5 text-xs transition-colors">
                                                        Form-Data
                                                    </button>
                                                    <button @click="step.bodyMode = 'urlencoded'; if(!step.body) step.body = {};"
                                                            :class="step.bodyMode === 'urlencoded' ? 'bg-ide-fg text-ide-bg' : 'bg-ide-border text-ide-fg'"
                                                            class="px-2 py-0.5 text-xs transition-colors">
                                                        URL-Encoded
                                                    </button>
                                                    <button @click="step.bodyMode = 'raw'; if(!step.rawBody) step.rawBody = ''; if(!step.rawContentType) step.rawContentType = 'text/plain';"
                                                            :class="step.bodyMode === 'raw' ? 'bg-ide-fg text-ide-bg' : 'bg-ide-border text-ide-fg'"
                                                            class="px-2 py-0.5 text-xs rounded-r transition-colors">
                                                        Raw
                                                    </button>
                                                </div>

                                                <!-- Form Mode -->
                                                <div x-show="step.bodyMode !== 'json' && step.bodyMode !== 'formdata' && step.bodyMode !== 'urlencoded' && step.bodyMode !== 'raw'" class="space-y-2">
                                                    <!-- Fields from schema -->
                                                    <template x-for="field in getBodyFieldsForEndpoint(step.endpoint)" :key="field.name">
                                                        <div class="flex items-start gap-2" x-data="{ useCustom: field.enum && field.enum.length > 0 && step.body[field.name] && !field.enum.includes(step.body[field.name]) }">
                                                            <label class="w-1/3 text-xs text-ide-muted flex flex-col gap-0.5 pt-1.5">
                                                                <span class="flex items-center gap-1">
                                                                    <span x-text="field.name"></span>
                                                                    <span x-show="field.required" class="text-red-500">*</span>
                                                                </span>
                                                                <template x-if="step._autoFilledFields?.[field.name]">
                                                                    <span class="inline-flex items-center gap-0.5 px-1 py-0.5 text-[9px] rounded-sm w-fit animate-pulse"
                                                                          :class="step._autoFilledFields[field.name].type === 'discovered'
                                                                              ? 'bg-amber-500/15 text-amber-600 border border-amber-500/30'
                                                                              : 'bg-green-500/15 text-green-600 border border-green-500/30'"
                                                                          :title="'From ' + step._autoFilledFields[field.name].source">
                                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                                        <span x-text="step._autoFilledFields[field.name].type === 'discovered' ? 'new' : step._autoFilledFields[field.name].source"></span>
                                                                    </span>
                                                                </template>
                                                            </label>
                                                            <template x-if="field.enum && field.enum.length > 0 && !useCustom">
                                                                <select x-model="step.body[field.name]"
                                                                        x-init="$nextTick(() => { if(step.body[field.name]) $el.value = step.body[field.name]; })"
                                                                        class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg">
                                                                    <option value="">Select...</option>
                                                                    <template x-for="opt in field.enum" :key="opt">
                                                                        <option :value="opt" x-text="opt"></option>
                                                                    </template>
                                                                </select>
                                                            </template>
                                                            <template x-if="field.enum && field.enum.length > 0 && useCustom">
                                                                <input type="text"
                                                                       x-model="step.body[field.name]"
                                                                       @input="handleVariableAutocomplete($event, step, 'body', field.name)"
                                                                       :placeholder="field.example || field.enum.join(', ')"
                                                                       class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded font-mono"
                                                                       :class="String(step.body[field.name] || '').match(/\x7b\x7b/) ? 'text-violet-400 border-violet-500/40 bg-violet-500/5' : 'text-ide-fg'">
                                                            </template>
                                                            <template x-if="field.enum && field.enum.length > 0">
                                                                <button @click="useCustom = !useCustom; if (!useCustom && step.body[field.name] && !field.enum.includes(step.body[field.name])) step.body[field.name] = ''"
                                                                        class="p-1 text-ide-muted hover:text-ide-fg rounded transition-colors flex-shrink-0" :title="useCustom ? 'Switch to dropdown' : 'Type custom value'">
                                                                    <svg x-show="!useCustom" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                    <svg x-show="useCustom" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                                                </button>
                                                            </template>
                                                            <template x-if="(!field.enum || field.enum.length === 0) && field.type === 'object'">
                                                                <div class="flex-1 flex flex-col gap-1" x-data="{ showObjPicker: false }">
                                                                    <textarea rows="3" x-model="step.body[field.name]"
                                                                              :placeholder="'{ JSON object }'"
                                                                              class="w-full px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded font-mono text-ide-fg"></textarea>
                                                                    <div class="relative" x-show="newFlow.steps.indexOf(step) > 0">
                                                                        <button @click="showObjPicker = !showObjPicker"
                                                                                class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[10px] text-ide-muted hover:text-ide-fg border border-ide-border rounded hover:bg-ide-surface transition-colors"
                                                                                title="Apply object from previous step">
                                                                            <span class="font-mono">{ }</span>
                                                                            <span>Apply from step</span>
                                                                        </button>
                                                                        <div x-show="showObjPicker" @click.outside="showObjPicker = false"
                                                                             class="absolute z-50 mt-1 left-0 bg-ide-bg border border-ide-border rounded-lg shadow-xl max-h-48 overflow-y-auto min-w-[200px]">
                                                                            <template x-for="obj in getObjectVariablesForStep(newFlow.steps.indexOf(step))" :key="obj.stepKey + '.' + obj.varName">
                                                                                <button @click="step.body[field.name] = JSON.stringify(obj.value, null, 2); showObjPicker = false"
                                                                                        class="w-full text-left px-3 py-1.5 text-xs hover:bg-ide-surface transition-colors border-b border-ide-border last:border-0">
                                                                                    <div class="font-mono text-[var(--ide-info-text)]" x-text="obj.stepKey + ' → ' + obj.varName"></div>
                                                                                    <div class="text-[10px] text-ide-muted truncate" x-text="JSON.stringify(obj.value).substring(0, 60) + (JSON.stringify(obj.value).length > 60 ? '...' : '')"></div>
                                                                                </button>
                                                                            </template>
                                                                            <template x-if="getObjectVariablesForStep(newFlow.steps.indexOf(step)).length === 0">
                                                                                <div class="px-3 py-2 text-xs text-ide-muted">No object data from previous steps</div>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <template x-if="(!field.enum || field.enum.length === 0) && field.type !== 'object'">
                                                                <input :type="String(step.body[field.name] || '').match(/\x7b\x7b/) ? 'text' : (field.type === 'integer' || field.type === 'number' ? 'number' : 'text')"
                                                                       x-model="step.body[field.name]"
                                                                       @input="handleVariableAutocomplete($event, step, 'body', field.name)"
                                                                       :placeholder="field.example || (field.nullable ? field.type + ',null' : field.type)"
                                                                       class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded font-mono"
                                                                       :class="step._autoFilledFields?.[field.name]
                                                                           ? (step._autoFilledFields[field.name].type === 'discovered' ? 'text-amber-600 border-amber-500/40 bg-amber-500/5' : 'text-green-600 border-green-500/40 bg-green-500/5')
                                                                           : (String(step.body[field.name] || '').match(/\x7b\x7b/) ? 'text-violet-400 border-violet-500/40 bg-violet-500/5' : 'text-ide-fg')">
                                                            </template>
                                                            <button @click="openVariablePicker(step, 'body', field.name, $event)"
                                                                    class="px-1.5 py-1 text-xs text-[var(--ide-info-text)] hover:opacity-80 hover:bg-[var(--ide-info-bg)] rounded flex-shrink-0">
                                                                @{{ }}
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <!-- Custom body fields -->
                                                    <template x-for="(value, key) in step.body || {}" :key="key">
                                                        <template x-if="!getBodyFieldsForEndpoint(step.endpoint).find(f => f.name === key)">
                                                            <div class="flex items-center gap-2">
                                                                <input type="text" :value="key" readonly
                                                                       class="w-1/3 px-2 py-1 text-xs font-medium bg-ide-border border border-ide-border rounded text-ide-fg">
                                                                <input type="text" x-model="step.body[key]"
                                                                       @input="handleVariableAutocomplete($event, step, 'body', key)"
                                                                       class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded font-mono"
                                                                       :class="String(step.body[key] || '').match(/\x7b\x7b/) ? 'text-violet-400 border-violet-500/40 bg-violet-500/5' : 'text-ide-fg'">
                                                                <button @click="openVariablePicker(step, 'body', key, $event)"
                                                                        class="px-1.5 py-1 text-xs text-[var(--ide-info-text)] hover:opacity-80 hover:bg-[var(--ide-info-bg)] rounded">
                                                                    @{{ }}
                                                                </button>
                                                                <button @click="delete step.body[key]"
                                                                        class="p-1 text-gray-400 hover:text-red-500">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </template>
                                                    <!-- Add custom field -->
                                                    <div class="flex items-center gap-2 pt-1">
                                                        <input type="text" x-model="newKey" placeholder="Field name"
                                                               class="w-1/3 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400">
                                                        <input type="text" x-model="newValue" placeholder="Value"
                                                               class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400 font-mono">
                                                        <button @click="if(newKey) { step.body = step.body || {}; step.body[newKey] = newValue; newKey = ''; newValue = ''; }"
                                                                class="px-2 py-1 text-xs bg-ide-fg text-ide-bg rounded hover:opacity-90">Add</button>
                                                    </div>
                                                </div>

                                                <!-- JSON Mode -->
                                                <div x-show="step.bodyMode === 'json'" class="space-y-1">
                                                    <textarea x-model="step.bodyJson"
                                                              @blur="parseStepBodyJson(step)"
                                                              rows="6"
                                                              class="w-full px-2 py-1.5 text-xs font-mono bg-ide-bg text-green-400 border border-gray-700 rounded resize-y"
                                                              placeholder='{"key": "value"}'></textarea>
                                                    <p class="text-[10px] text-gray-400">Tip: Use @{{step1.varName}} for variables</p>
                                                </div>

                                                <!-- Form-Data Mode (multipart/form-data) -->
                                                <div x-show="step.bodyMode === 'formdata'" class="space-y-2" x-data="{ fdKey: '', fdValue: '', fdType: 'text' }">
                                                    <template x-for="(entry, idx) in (step.formDataEntries || [])" :key="idx">
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" x-model="entry.key" placeholder="Key"
                                                                   class="w-1/3 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg">
                                                            <template x-if="entry.type === 'file'">
                                                                <div class="flex-1 flex items-center gap-1">
                                                                    <input type="file" @change="entry.file = $event.target.files[0]; entry.value = $event.target.files[0]?.name || ''"
                                                                           class="flex-1 text-xs text-ide-fg file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:bg-ide-border file:text-ide-fg">
                                                                </div>
                                                            </template>
                                                            <template x-if="entry.type !== 'file'">
                                                                <input type="text" x-model="entry.value" placeholder="Value"
                                                                       class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg font-mono">
                                                            </template>
                                                            <select x-model="entry.type" class="px-1.5 py-1 text-[10px] bg-ide-surface border border-ide-border rounded text-ide-muted">
                                                                <option value="text">Text</option>
                                                                <option value="file">File</option>
                                                            </select>
                                                            <button @click="step.formDataEntries.splice(idx, 1)" class="p-1 text-gray-400 hover:text-red-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <div class="flex items-center gap-2 pt-1">
                                                        <input type="text" x-model="fdKey" placeholder="Key"
                                                               class="w-1/3 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400">
                                                        <input type="text" x-model="fdValue" placeholder="Value"
                                                               class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400 font-mono">
                                                        <select x-model="fdType" class="px-1.5 py-1 text-[10px] bg-ide-surface border border-ide-border rounded text-ide-muted">
                                                            <option value="text">Text</option>
                                                            <option value="file">File</option>
                                                        </select>
                                                        <button @click="if(fdKey) { if(!step.formDataEntries) step.formDataEntries = []; step.formDataEntries.push({key: fdKey, value: fdValue, type: fdType}); fdKey = ''; fdValue = ''; fdType = 'text'; }"
                                                                class="px-2 py-1 text-xs bg-ide-fg text-ide-bg rounded hover:opacity-90">Add</button>
                                                    </div>
                                                    <p class="text-[10px] text-gray-400">Sent as multipart/form-data. Supports file uploads.</p>
                                                </div>

                                                <!-- URL-Encoded Mode (application/x-www-form-urlencoded) -->
                                                <div x-show="step.bodyMode === 'urlencoded'" class="space-y-2" x-data="{ ueKey: '', ueValue: '' }">
                                                    <template x-for="(value, key) in (step.urlencodedBody || {})" :key="key">
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" :value="key" readonly
                                                                   class="w-1/3 px-2 py-1 text-xs font-medium bg-ide-border border border-ide-border rounded text-ide-fg">
                                                            <input type="text" x-model="step.urlencodedBody[key]"
                                                                   class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg font-mono">
                                                            <button @click="openVariablePicker(step, 'urlencodedBody', key, $event)"
                                                                    class="px-1.5 py-1 text-xs text-[var(--ide-info-text)] hover:opacity-80 hover:bg-[var(--ide-info-bg)] rounded">
                                                                @{{ }}
                                                            </button>
                                                            <button @click="delete step.urlencodedBody[key]" class="p-1 text-gray-400 hover:text-red-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <div class="flex items-center gap-2 pt-1">
                                                        <input type="text" x-model="ueKey" placeholder="Key"
                                                               class="w-1/3 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400">
                                                        <input type="text" x-model="ueValue" placeholder="Value"
                                                               class="flex-1 px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400 font-mono">
                                                        <button @click="if(ueKey) { if(!step.urlencodedBody) step.urlencodedBody = {}; step.urlencodedBody[ueKey] = ueValue; ueKey = ''; ueValue = ''; }"
                                                                class="px-2 py-1 text-xs bg-ide-fg text-ide-bg rounded hover:opacity-90">Add</button>
                                                    </div>
                                                    <p class="text-[10px] text-gray-400">Sent as application/x-www-form-urlencoded</p>
                                                </div>

                                                <!-- Raw Mode -->
                                                <div x-show="step.bodyMode === 'raw'" class="space-y-2">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <label class="text-xs text-ide-muted">Content-Type:</label>
                                                        <select x-model="step.rawContentType"
                                                                class="px-2 py-1 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg">
                                                            <option value="text/plain">text/plain</option>
                                                            <option value="text/xml">text/xml</option>
                                                            <option value="application/xml">application/xml</option>
                                                            <option value="text/html">text/html</option>
                                                            <option value="application/graphql">application/graphql</option>
                                                        </select>
                                                    </div>
                                                    <textarea x-model="step.rawBody"
                                                              rows="6"
                                                              class="w-full px-2 py-1.5 text-xs font-mono bg-ide-bg text-green-400 border border-gray-700 rounded resize-y"
                                                              placeholder="Enter raw body content..."></textarea>
                                                    <p class="text-[10px] text-gray-400">Tip: Use @{{step1.varName}} for variables</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Variables Section -->
                                        <div class="mt-3 pt-3 border-t border-ide-border" x-data="{ expanded: true, vName: '', vPath: '' }">
                                            <button @click="expanded = !expanded" class="text-xs text-gray-500 hover:text-ide-fg flex items-center gap-1.5 mb-2">
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span x-text="'Extract ' + Object.keys(step.extractVariables || {}).length + ' variables'"></span>
                                            </button>

                                            <div x-show="expanded" x-collapse class="space-y-2">
                                                <!-- Response fields picker -->
                                                <div x-data="{
                                                        showFields: false,
                                                        fieldSearch: '',
                                                        get responseFields() { return getResponseFieldsForEndpoint(step.endpoint, step.expectedStatus) },
                                                        get filteredResponseFields() {
                                                            if (!this.fieldSearch) return this.responseFields;
                                                            const q = this.fieldSearch.toLowerCase();
                                                            return this.responseFields.filter(f => f.name.toLowerCase().includes(q) || f.path.toLowerCase().includes(q));
                                                        }
                                                     }"
                                                     x-show="responseFields.length > 0" class="mt-2">
                                                    <button @click="showFields = !showFields" class="flex items-center gap-1.5 text-xs text-ide-muted hover:text-ide-fg transition-colors w-full">
                                                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': showFields }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        <span>Pick from response</span>
                                                        <template x-if="step.expectedStatus">
                                                            <span class="text-[10px] font-mono px-1 py-0.5 rounded"
                                                                  :class="step.expectedStatus >= 400 ? 'bg-amber-500/10 text-amber-600' : 'bg-green-500/10 text-green-600'"
                                                                  x-text="step.expectedStatus"></span>
                                                        </template>
                                                        <span class="ml-auto text-[10px] text-ide-muted" x-text="fieldSearch ? (filteredResponseFields.length + '/' + responseFields.length + ' fields') : (responseFields.length + ' fields')"></span>
                                                    </button>
                                                    <div x-show="showFields" x-collapse class="mt-1.5">
                                                        <template x-if="responseFields.length > 10">
                                                            <input type="text" x-model="fieldSearch" placeholder="Search fields..."
                                                                   class="w-full px-2 py-1 mb-1.5 text-xs bg-ide-surface border border-ide-border rounded text-ide-fg placeholder-gray-400 font-mono focus:ring-1 focus:ring-ide-primary">
                                                        </template>
                                                        <div class="flex flex-wrap gap-1 max-h-48 overflow-y-auto">
                                                            <template x-for="field in filteredResponseFields" :key="field.path">
                                                                <button @click="step.extractVariables = step.extractVariables || {}; if (step.extractVariables[field.name]) { delete step.extractVariables[field.name]; step.extractVariables = {...step.extractVariables}; } else { step.extractVariables[field.name] = field.path; } reExtractVariablesForStep(newFlow.steps.indexOf(step));"
                                                                        class="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-mono rounded-md border transition-all"
                                                                        :class="step.extractVariables?.[field.name]
                                                                            ? 'bg-green-500/10 border-green-500/30 text-green-700 dark:text-green-400'
                                                                            : 'bg-ide-surface border-ide-border text-ide-muted hover:text-ide-fg hover:border-ide-fg/30'">
                                                                    <span x-text="field.name"></span>
                                                                    <span class="text-[9px] opacity-50" x-text="field.type"></span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Add custom variable -->
                                                <div class="flex items-center gap-2 mt-2">
                                                    <input type="text" x-model="vName" placeholder="var name"
                                                           class="w-24 px-2 py-1 text-xs border border-ide-border rounded bg-ide-surface text-ide-fg placeholder-gray-400">
                                                    <span class="text-gray-400">=</span>
                                                    <input type="text" x-model="vPath" placeholder="data.id"
                                                           class="flex-1 px-2 py-1 text-xs border border-ide-border rounded bg-ide-surface text-ide-fg placeholder-gray-400 font-mono">
                                                    <button @click="if(vName && vPath) { step.extractVariables = step.extractVariables || {}; step.extractVariables[vName] = vPath; reExtractVariablesForStep(newFlow.steps.indexOf(step)); vName = ''; vPath = ''; }"
                                                            class="px-2 py-1 text-xs bg-ide-fg text-ide-bg rounded hover:opacity-90 transition-colors">Add</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Assertions Section -->
                                        <div class="mt-3 pt-3 border-t border-ide-border" x-data="{ expanded: (step.assertions || []).length > 0 }">
                                            <button @click="expanded = !expanded" class="text-xs text-gray-500 hover:text-ide-fg flex items-center gap-1.5 mb-2">
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span x-text="'Assertions (' + (step.assertions || []).length + ')'"></span>
                                            </button>

                                            <div x-show="expanded" x-collapse class="space-y-2"
                                                 x-data="{ get assertionFields() { return getResponseFieldsForEndpoint(step.endpoint, step.expectedStatus) } }">
                                                <!-- Existing assertions -->
                                                <template x-for="(assertion, aIdx) in step.assertions || []" :key="aIdx">
                                                    <div class="flex items-center gap-1.5 text-xs flex-wrap">
                                                        <select x-model="assertion.type" @change="if(assertion.type === 'status') { assertion.operator = 'equals'; assertion.expected = String(step.expectedStatus || 200); assertion.path = ''; } else if(assertion.type === 'responseTime') { assertion.operator = 'lessThan'; assertion.expected = '2000'; assertion.path = ''; } else { assertion.operator = 'exists'; assertion.path = ''; assertion.expected = ''; }"
                                                                class="px-1.5 py-1 bg-ide-surface border border-ide-border rounded text-ide-fg w-20">
                                                            <option value="status">Status</option>
                                                            <option value="field">Field</option>
                                                            <option value="responseTime">Time</option>
                                                        </select>
                                                        <template x-if="assertion.type === 'field'">
                                                            <select x-model="assertion.path"
                                                                    class="px-1.5 py-1 bg-ide-surface border border-ide-border rounded text-ide-fg font-mono max-w-[140px]">
                                                                <option value="" disabled>Select field...</option>
                                                                <template x-for="f in assertionFields" :key="'af-' + f.path">
                                                                    <option :value="f.path" x-text="f.path + ' (' + f.type + ')'"></option>
                                                                </template>
                                                                <option value="__custom__" x-show="assertion.path && !assertionFields.some(f => f.path === assertion.path)">Custom...</option>
                                                            </select>
                                                        </template>
                                                        <select x-model="assertion.operator" class="px-1.5 py-1 bg-ide-surface border border-ide-border rounded text-ide-fg">
                                                            <option value="equals">=</option>
                                                            <option value="notEquals">!=</option>
                                                            <option value="exists">exists</option>
                                                            <option value="notExists">!exists</option>
                                                            <option value="contains">contains</option>
                                                            <option value="greaterThan">&gt;</option>
                                                            <option value="lessThan">&lt;</option>
                                                            <option value="typeof">typeof</option>
                                                        </select>
                                                        <template x-if="assertion.operator !== 'exists' && assertion.operator !== 'notExists'">
                                                            <input type="text" x-model="assertion.expected" placeholder="expected"
                                                                   class="px-1.5 py-1 bg-ide-surface border border-ide-border rounded text-ide-fg w-20 font-mono">
                                                        </template>
                                                        <button @click="step.assertions.splice(aIdx, 1)" class="p-1 text-gray-400 hover:text-red-500">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                </template>

                                                <!-- Add assertion -->
                                                <button @click="step.assertions = step.assertions || []; step.assertions.push({ type: 'status', operator: 'equals', expected: String(step.expectedStatus || 200), path: '' })"
                                                        class="text-xs text-ide-primary hover:underline mt-1">+ Add assertion</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inline Result Block (Jupyter-style) -->
                                <template x-if="flowRunResults[index]">
                                    <div class="ml-12 border border-t-0 rounded-b-lg overflow-hidden" x-data="{ showInlineResponse: false }"
                                         :class="{
                                             'border-green-400/40 bg-green-50/50 dark:bg-green-900/10': flowRunResults[index]?.success,
                                             'border-amber-400/40 bg-amber-50/50 dark:bg-amber-900/10': flowRunResults[index]?.httpOk && !flowRunResults[index]?.allAssertionsPassed,
                                             'border-red-400/40 bg-red-50/50 dark:bg-red-900/10': !flowRunResults[index]?.httpOk
                                         }">
                                        <!-- Result Header -->
                                        <div class="px-3 py-2 flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <!-- Status badge -->
                                                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded font-mono"
                                                      :class="{
                                                          'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400': flowRunResults[index]?.success,
                                                          'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400': flowRunResults[index]?.httpOk && !flowRunResults[index]?.allAssertionsPassed,
                                                          'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400': !flowRunResults[index]?.httpOk
                                                      }"
                                                      x-text="flowRunResults[index]?.status || 'ERR'"></span>
                                                <!-- Duration -->
                                                <span x-show="flowRunResults[index]?.duration" class="text-[10px] font-mono text-ide-muted" x-text="flowRunResults[index]?.duration + 'ms'"></span>
                                                <!-- Pass/Fail -->
                                                <span class="text-[10px] font-bold"
                                                      :class="flowRunResults[index]?.success ? 'text-green-600' : 'text-red-600'"
                                                      x-text="flowRunResults[index]?.success ? 'PASS' : 'FAIL'"></span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <!-- Expand in dialog -->
                                                <button @click="openFlowResponseDialog(index)"
                                                        class="p-1 text-ide-muted hover:text-ide-primary rounded transition-colors" title="Expand response">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                                </button>
                                                <!-- Toggle response body -->
                                                <button @click="showInlineResponse = !showInlineResponse"
                                                        class="p-1 text-ide-muted hover:text-ide-fg rounded transition-colors" title="Toggle response">
                                                    <svg class="w-3 h-3 transition-transform" :class="showInlineResponse ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Extracted Variables (compact pills) -->
                                        <template x-if="flowRunResults[index]?.extractedVars && Object.keys(flowRunResults[index].extractedVars).length">
                                            <div class="px-3 pb-1.5 flex flex-wrap gap-1">
                                                <template x-for="(val, key) in flowRunResults[index].extractedVars" :key="key">
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[10px] bg-ide-primary/10 rounded font-mono">
                                                        <span class="text-ide-primary" x-text="key"></span>
                                                        <span class="text-gray-400">=</span>
                                                        <span class="text-ide-muted truncate max-w-[120px]" x-text="JSON.stringify(val).substring(0, 25)"></span>
                                                    </span>
                                                </template>
                                            </div>
                                        </template>
                                        <!-- Assertion Results -->
                                        <template x-if="flowRunResults[index]?.assertionResults && flowRunResults[index].assertionResults.length > 0">
                                            <div class="px-3 pb-1.5 space-y-0.5">
                                                <template x-for="(ar, arIdx) in flowRunResults[index].assertionResults" :key="arIdx">
                                                    <div class="text-[10px] flex items-center gap-1.5">
                                                        <span class="font-bold" :class="ar.passed ? 'text-green-600' : 'text-red-600'" x-text="ar.passed ? 'PASS' : 'FAIL'"></span>
                                                        <span class="text-ide-muted">
                                                            <span x-text="ar.type"></span><template x-if="ar.path"><span x-text="'(' + ar.path + ')'"></span></template>
                                                            <span x-text="ar.operator"></span>
                                                            <template x-if="ar.operator !== 'exists' && ar.operator !== 'notExists'"><span class="font-mono" x-text="ar.expected"></span></template>
                                                        </span>
                                                        <template x-if="!ar.passed">
                                                            <span class="text-red-500 truncate" x-text="'got: ' + JSON.stringify(ar.actual)"></span>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <!-- Collapsible Response Body -->
                                        <div x-show="showInlineResponse" x-collapse>
                                            <pre class="mx-3 mb-2 p-2 bg-ide-bg rounded text-xs overflow-auto font-mono max-h-40 border border-ide-border/50"><code x-html="syntaxHighlightJson(flowRunResults[index]?.data)"></code></pre>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Add Step Button -->
                        <div class="relative ml-12" x-data="{ searching: false, query: '' }">
                            <button @click="searching = !searching; query = ''; $nextTick(() => $refs.stepSearch?.focus())"
                                    class="w-full py-3 border border-ide-border bg-ide-bg rounded-lg hover:bg-ide-line-active transition-colors flex items-center justify-center gap-2 text-ide-muted hover:text-ide-fg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span class="text-sm font-medium">Add Step</span>
                            </button>

                            <!-- Inline Endpoint Search -->
                            <div x-show="searching" x-transition @click.away="searching = false"
                                 class="absolute left-0 right-0 mt-2 bg-ide-bg rounded-lg shadow-lg border border-ide-border overflow-hidden z-20">
                                <div class="p-2 border-b border-ide-border">
                                    <input type="text" x-model="query" placeholder="Search endpoints..." x-ref="stepSearch"
                                           @keydown.escape="searching = false"
                                           class="w-full px-3 py-2 text-sm border-0 bg-ide-bg rounded-md text-ide-fg focus:ring-0">
                                </div>
                                <div class="max-h-72 overflow-y-auto">
                                    <template x-for="ep in endpoints.filter(e => !query || e.path.toLowerCase().includes(query.toLowerCase()) || (e.summary || '').toLowerCase().includes(query.toLowerCase())).slice(0, 20)" :key="ep.path + ep.method">
                                        <button @click="addEndpointToFlow(ep); searching = false; query = ''"
                                                class="w-full px-3 py-2 text-left hover:bg-ide-line-active flex items-center gap-3 transition-colors">
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold rounded" :class="getMethodBadgeClass(ep.method)" x-text="ep.method.toUpperCase()"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-mono truncate text-ide-fg" x-text="ep.path"></div>
                                            </div>
                                        </button>
                                    </template>
                                    <div x-show="endpoints.filter(e => !query || e.path.toLowerCase().includes(query.toLowerCase())).length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">
                                        No endpoints found
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Variables (sticky, always visible when variables exist) -->
            <div x-show="Object.keys(flowVariables).length > 0" x-transition
                 class="w-64 bg-ide-bg border-l border-ide-border flex flex-col flex-shrink-0">
                <div x-data="{ varsExpanded: true, showVarsDialog: false }" class="flex flex-col h-full">
                    <div class="w-full px-4 py-3 flex items-center justify-between border-b border-ide-border flex-shrink-0">
                        <button @click="varsExpanded = !varsExpanded" class="flex items-center gap-1.5 hover:opacity-80 transition-opacity">
                            <h4 class="text-xs font-semibold text-ide-primary uppercase flex items-center gap-1.5">
                                <svg class="w-3 h-3 transition-transform" :class="varsExpanded ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                Variables
                                <span class="font-normal text-ide-primary/60" x-text="'(' + Object.values(flowVariables).reduce((sum, v) => sum + Object.keys(v).length, 0) + ')'"></span>
                            </h4>
                        </button>
                        <button @click="showVarsDialog = true" class="p-1 text-ide-muted hover:text-ide-fg rounded transition-colors" title="Expand variables">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        </button>
                    </div>
                    <div x-show="varsExpanded" x-collapse class="flex-1 overflow-y-auto px-4 py-3 space-y-1 text-xs">
                        <template x-for="(vars, stepKey) in flowVariables" :key="stepKey">
                            <div class="mb-2">
                                <div class="text-[10px] font-semibold text-ide-muted uppercase mb-1" x-text="stepKey"></div>
                                <template x-for="(val, varName) in vars" :key="varName">
                                    <div class="rounded bg-ide-surface/50 border border-ide-border/50 px-2 py-1.5 mb-1 cursor-pointer hover:border-ide-primary/30 transition-colors group"
                                         @click="navigator.clipboard.writeText('@{{' + stepKey + '.' + varName + '}}'); showToast('Copied @{{' + stepKey + '.' + varName + '}}')">
                                        <div class="flex items-center justify-between gap-1 mb-0.5">
                                            <code class="text-ide-primary text-[11px] font-semibold" x-text="varName"></code>
                                            <svg class="w-2.5 h-2.5 text-ide-muted opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div class="text-[10px] font-mono text-ide-muted truncate" :title="typeof val === 'object' ? JSON.stringify(val) : String(val)" x-text="typeof val === 'object' ? JSON.stringify(val) : String(val)"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Variables Expand Dialog -->
                    <div x-show="showVarsDialog" x-transition.opacity class="fixed inset-0 z-[200] flex items-center justify-center bg-black/50" @click.self="showVarsDialog = false">
                        <div class="bg-ide-bg border border-ide-border rounded-xl shadow-2xl w-[600px] max-h-[80vh] flex flex-col" @keydown.escape.window="showVarsDialog = false">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-ide-border flex-shrink-0">
                                <h3 class="text-sm font-semibold text-ide-fg flex items-center gap-2">
                                    <svg class="w-4 h-4 text-ide-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    Flow Variables
                                    <span class="text-xs font-normal text-ide-muted" x-text="'(' + Object.values(flowVariables).reduce((sum, v) => sum + Object.keys(v).length, 0) + ' total)'"></span>
                                </h3>
                                <button @click="showVarsDialog = false" class="p-1 text-gray-400 hover:text-ide-fg rounded transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
                                <template x-for="(vars, stepKey) in flowVariables" :key="'dlg-' + stepKey">
                                    <div>
                                        <div class="text-xs font-semibold text-ide-muted uppercase mb-2 flex items-center gap-2">
                                            <span x-text="stepKey"></span>
                                            <span class="text-[10px] font-normal" x-text="'(' + Object.keys(vars).length + ' vars)'"></span>
                                        </div>
                                        <div class="space-y-1.5">
                                            <template x-for="(val, varName) in vars" :key="'dlg-' + varName">
                                                <div class="bg-ide-surface border border-ide-border rounded-lg px-3 py-2">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <code class="text-ide-primary text-xs font-semibold" x-text="varName"></code>
                                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-ide-border text-ide-muted" x-text="typeof val === 'object' ? (Array.isArray(val) ? 'array' : 'object') : typeof val"></span>
                                                        <button @click="navigator.clipboard.writeText(typeof val === 'object' ? JSON.stringify(val) : String(val)); showToast('Copied to clipboard')"
                                                                class="ml-auto p-0.5 text-ide-muted hover:text-ide-fg rounded transition-colors" title="Copy value">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                        </button>
                                                        <button @click="navigator.clipboard.writeText('@{{' + stepKey + '.' + varName + '}}'); showToast('Copied variable reference')"
                                                                class="p-0.5 text-ide-muted hover:text-violet-400 rounded transition-colors" title="Copy variable reference">
                                                            <span class="text-[10px] font-mono">@{{}}</span>
                                                        </button>
                                                    </div>
                                                    <pre class="text-xs text-ide-fg font-mono whitespace-pre-wrap break-all bg-ide-bg rounded px-2 py-1.5 max-h-40 overflow-y-auto" x-text="typeof val === 'object' ? JSON.stringify(val, null, 2) : String(val)"></pre>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flow Step Documentation Dialog -->
    <div x-show="showFlowDocDialog && flowDocEndpoint"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50"
         @click.self="showFlowDocDialog = false"
         x-cloak>
        <div class="bg-ide-bg rounded-xl shadow-2xl w-[700px] max-h-[80vh] overflow-hidden flex flex-col"
             @click.stop>
            <!-- Header -->
            <div class="px-6 py-4 border-b border-ide-border flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="px-2 py-0.5 text-xs font-bold rounded flex-shrink-0" :class="getMethodBadgeClass(flowDocEndpoint?.method)" x-text="flowDocEndpoint?.method?.toUpperCase()"></span>
                        <span class="text-sm font-mono text-ide-fg truncate" x-text="flowDocEndpoint?.path"></span>
                    </div>
                    <button @click="showFlowDocDialog = false"
                            class="p-1.5 text-gray-400 hover:text-ide-fg hover:bg-ide-line-active rounded-lg transition-colors flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p x-show="flowDocEndpoint?.summary" class="text-sm text-ide-muted mt-1" x-text="flowDocEndpoint?.summary"></p>
            </div>

            <!-- Scrollable Body -->
            <div class="overflow-y-auto p-6 space-y-6">
                <!-- Query Parameters -->
                <template x-if="flowDocEndpoint && getQueryParameters(flowDocEndpoint).length > 0">
                    <div>
                        <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider mb-2 flex items-center gap-2"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Query Parameters</h4>
                        <table class="w-full text-code"><thead><tr class="text-left text-label text-ide-muted uppercase"><th class="pb-1.5 pr-3 font-medium">Name</th><th class="pb-1.5 pr-3 font-medium">Type</th><th class="pb-1.5 font-medium">Description</th></tr></thead>
                        <tbody class="divide-y divide-ide-border"><template x-for="param in getQueryParameters(flowDocEndpoint)" :key="param.name"><tr>
                            <td class="py-1.5 pr-3 font-mono text-[var(--ide-info-text)]"><span x-text="param.name"></span><template x-if="param.required"><span class="text-red-500">*</span></template></td>
                            <td class="py-1.5 pr-3"><span class="px-1.5 py-0.5 bg-ide-border text-ide-muted rounded text-label" x-text="param.schema?.type || 'string'"></span></td>
                            <td class="py-1.5 text-ide-muted"><span x-text="cleanDescription(param.description) || '-'"></span>
                                <template x-if="param.schema?.enum"><div class="mt-1 flex flex-wrap gap-1"><template x-for="opt in param.schema.enum" :key="opt"><span class="px-1 py-0.5 bg-ide-primary/10 text-ide-primary rounded text-label font-mono" x-text="opt"></span></template></div></template>
                            </td>
                        </tr></template></tbody></table>
                    </div>
                </template>

                <!-- Request Body -->
                <template x-if="flowDocEndpoint && hasRequestBody(flowDocEndpoint)">
                    <div>
                        <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider mb-2 flex items-center gap-2"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Request Body</h4>
                        <table class="w-full text-code"><thead><tr class="text-left text-label text-ide-muted uppercase"><th class="pb-1.5 pr-3 font-medium">Property</th><th class="pb-1.5 pr-3 font-medium">Type</th><th class="pb-1.5 font-medium">Description</th></tr></thead>
                        <tbody class="divide-y divide-ide-border"><template x-for="field in getRequestBodyFields(flowDocEndpoint)" :key="field.name"><tr>
                            <td class="py-1.5 pr-3 font-mono text-[var(--ide-info-text)]"><span x-text="field.name"></span><template x-if="field.required"><span class="text-red-500">*</span></template></td>
                            <td class="py-1.5 pr-3"><span class="px-1.5 py-0.5 bg-ide-border text-ide-muted rounded text-label" x-text="field.type"></span></td>
                            <td class="py-1.5 text-ide-muted"><div x-show="field.description" x-text="field.description"></div>
                                <template x-if="field.enum"><div class="flex flex-wrap gap-1"><template x-for="opt in field.enum" :key="opt"><span class="px-1 py-0.5 bg-ide-primary/10 text-ide-primary rounded text-label font-mono" x-text="opt"></span></template></div></template>
                            </td>
                        </tr></template></tbody></table>
                    </div>
                </template>

                <!-- Schema Documentation -->
                <template x-if="flowDocEndpoint">
                    <div x-data="{ schemaTab: 'request', flowDocReqExpanded: {}, flowDocResExpanded: {} }"
                         x-effect="if (flowDocEndpoint) { flowDocReqExpanded = {}; flowDocResExpanded = {}; if (flowDocEndpoint.method === 'get') { schemaTab = 'response'; } else { const hasReq = getPathParameters(flowDocEndpoint).length > 0 || getQueryParameters(flowDocEndpoint).length > 0 || hasRequestBody(flowDocEndpoint); schemaTab = hasReq ? 'request' : 'response'; } }">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-label font-semibold text-ide-fg uppercase tracking-wider">Schema Documentation</h4>
                            <div class="flex bg-ide-border rounded p-0.5">
                                <button @click="schemaTab = 'request'" class="px-2 py-0.5 text-label font-medium rounded transition-colors" :class="schemaTab === 'request' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Request</button>
                                <button @click="schemaTab = 'response'" class="px-2 py-0.5 text-label font-medium rounded transition-colors" :class="schemaTab === 'response' ? 'bg-ide-surface text-ide-fg shadow-sm' : 'text-ide-muted hover:text-ide-fg'">Response</button>
                            </div>
                        </div>
                        <!-- Request Schema -->
                        <div x-show="schemaTab === 'request'">
                            <template x-if="getPathParameters(flowDocEndpoint).length > 0 || hasRequestBody(flowDocEndpoint)">
                                <div class="border border-ide-border rounded overflow-hidden">
                                    <div class="grid grid-cols-[minmax(180px,2fr)_100px_60px_1fr] gap-2 px-3 py-1.5 bg-ide-surface text-label text-ide-muted uppercase font-medium border-b border-ide-border">
                                        <span>Property</span><span>Type</span><span>Status</span><span>Description</span>
                                    </div>
                                    <template x-if="getPathParameters(flowDocEndpoint).length > 0">
                                        <div>
                                            <div class="px-3 py-1 bg-ide-surface/50 text-[10px] text-ide-muted uppercase font-semibold tracking-wider border-b border-ide-border">Path Parameters</div>
                                            <div x-html="renderSchemaTree(getPathParameters(flowDocEndpoint).map(p => ({ name: p.name, type: p.schema?.type || 'string', required: true, nullable: false, description: p.description || '', nestedFields: [], enum: p.schema?.enum, example: p.schema?.example, constraints: '' })), flowDocReqExpanded, 'path', 0, 'flowDocReqExpanded')"></div>
                                        </div>
                                    </template>
                                    <template x-if="hasRequestBody(flowDocEndpoint)">
                                        <div>
                                            <div class="px-3 py-1 bg-ide-surface/50 text-[10px] text-ide-muted uppercase font-semibold tracking-wider border-b border-ide-border">Request Body</div>
                                            <div x-html="renderSchemaTree(getRequestBodyFields(flowDocEndpoint), flowDocReqExpanded, 'body', 0, 'flowDocReqExpanded')"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="getPathParameters(flowDocEndpoint).length === 0 && !hasRequestBody(flowDocEndpoint)"><p class="text-code text-ide-muted italic py-3">No request schema parameters.</p></template>
                        </div>
                        <!-- Response Schema -->
                        <div x-show="schemaTab === 'response'" x-data="{ selectedResponseCode: '200' }" x-effect="if (flowDocEndpoint?.responses) { const codes = Object.keys(flowDocEndpoint.responses); const validCode = codes.find(c => { const r = flowDocEndpoint.responses[c]; return r?.content?.['application/json']?.schema || r?.description; }) || codes[0]; if (validCode) selectedResponseCode = validCode; }">
                            <template x-if="flowDocEndpoint.responses && Object.keys(flowDocEndpoint.responses).length > 0">
                                <div>
                                    <div class="flex flex-wrap gap-1.5 mb-3"><template x-for="(response, statusCode) in flowDocEndpoint.responses" :key="statusCode"><button @click="selectedResponseCode = statusCode" class="px-2 py-1 text-label font-bold rounded transition-colors" :class="selectedResponseCode === statusCode ? 'ring-1 ring-offset-1 ring-primary-500 ' + getStatusBadgeClass(statusCode) : getStatusBadgeClass(statusCode) + ' opacity-60 hover:opacity-100'" x-text="statusCode"></button></template></div>
                                    <div class="mb-2 p-2 bg-ide-surface rounded text-code text-ide-fg" x-text="flowDocEndpoint.responses[selectedResponseCode]?.description || getStatusText(selectedResponseCode)"></div>
                                    <template x-if="getResponseSchemaFields(flowDocEndpoint, selectedResponseCode).length > 0">
                                        <div x-data="{ resNodes: {} }" x-init="$watch('selectedResponseCode', () => { resNodes = {}; initResExpand() }); initResExpand = () => { const fields = getResponseSchemaFields(flowDocEndpoint, selectedResponseCode); fields.forEach(f => { if (f.nestedFields.length > 0 && (f.name === 'data' || f.name === 'meta' || f.name === 'errors' || f.name === 'error' || f.name === 'message')) resNodes[f.name] = true; }); }; initResExpand();">
                                            <div class="border border-ide-border rounded overflow-hidden">
                                                <div class="grid grid-cols-[minmax(180px,2fr)_100px_60px_1fr] gap-2 px-3 py-1.5 bg-ide-surface text-label text-ide-muted uppercase font-medium border-b border-ide-border">
                                                    <span>Property</span><span>Type</span><span>Status</span><span>Description</span>
                                                </div>
                                                <div x-html="renderSchemaTree(getResponseSchemaFields(flowDocEndpoint, selectedResponseCode), resNodes, '', 0, 'resNodes')"></div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="getResponseSchemaFields(flowDocEndpoint, selectedResponseCode).length === 0"><p class="text-code text-ide-muted italic py-2">No schema defined for this response.</p></template>
                                </div>
                            </template>
                            <template x-if="!flowDocEndpoint.responses || Object.keys(flowDocEndpoint.responses).length === 0"><p class="text-code text-ide-muted italic py-3">No response schemas defined.</p></template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3 border-t border-ide-border flex-shrink-0 flex items-center justify-between">
                <button @click="if (flowDocEndpoint) { selectEndpointByPath(flowDocEndpoint.method, flowDocEndpoint.path); showFlowDocDialog = false; showFlowsPanel = false; }"
                        class="px-3 py-1.5 text-sm text-ide-primary hover:bg-ide-primary/10 rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Open in Explorer
                </button>
                <button @click="showFlowDocDialog = false"
                        class="px-4 py-1.5 text-sm font-medium text-ide-fg bg-ide-surface border border-ide-border rounded-lg hover:bg-ide-line-active transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Import from Saved Modal -->
    <div x-show="showingImportModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50"
         @click.self="showingImportModal = false"
         x-cloak>
        <div class="bg-ide-bg rounded-xl shadow-2xl w-[500px] max-h-[80vh] overflow-hidden"
             @click.stop>
            <!-- Header -->
            <div class="px-6 py-4 border-b border-ide-border">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-ide-fg">Import from Saved Request</h3>
                    <button @click="showingImportModal = false"
                            class="p-1.5 text-gray-400 hover:text-ide-fg hover:bg-ide-line-active rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-ide-muted mt-1">Import headers, body, and parameters from a saved request</p>
            </div>

            <!-- Filter -->
            <div class="px-6 py-3 border-b border-ide-border">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-medium text-ide-muted">Filter by endpoint</label>
                    <button @click="importFilterEndpoint = ''"
                            x-show="importFilterEndpoint !== ''"
                            class="text-xs text-blue-500 hover:text-blue-700">
                        Show all endpoints
                    </button>
                </div>
                <select x-model="importFilterEndpoint"
                        class="w-full px-3 py-2 text-sm bg-ide-bg border border-ide-border rounded-lg text-ide-fg">
                    <option value="">All endpoints</option>
                    <template x-for="ep in getUniqueSavedEndpoints()" :key="ep">
                        <option :value="ep" x-text="ep"></option>
                    </template>
                </select>
                <p x-show="importFilterEndpoint && getFilteredSavedForImport().length === 0" class="text-xs text-[var(--ide-warning-text)] mt-2">
                    No saved requests for this endpoint. Try "Show all endpoints" to see other saved requests.
                </p>
            </div>

            <!-- Saved Requests List -->
            <div class="overflow-y-auto max-h-[400px] p-4">
                <template x-if="getFilteredSavedForImport().length === 0">
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-ide-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        <p class="text-sm text-ide-muted">No saved requests found</p>
                        <p class="text-xs text-ide-muted mt-1">Save some requests first to import them here</p>
                    </div>
                </template>
                <div class="space-y-2">
                    <template x-for="saved in getFilteredSavedForImport()" :key="saved.id">
                        <button @click="importSavedToStep(saved)"
                                class="w-full text-left p-4 border border-ide-border rounded-lg hover:bg-ide-line-active hover:border-ide-primary/30 transition-all group">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-ide-fg" x-text="saved.name"></div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-1.5 py-0.5 text-[10px] font-bold rounded"
                                              :class="getMethodBadgeClass(saved.method)"
                                              x-text="saved.method?.toUpperCase()"></span>
                                        <span class="text-xs text-ide-muted font-mono truncate" x-text="saved.path"></span>
                                    </div>
                                    <!-- Show what will be imported -->
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <span x-show="saved.headers && Object.keys(saved.headers).length > 0"
                                              class="px-1.5 py-0.5 text-[10px] bg-ide-border text-ide-muted rounded">
                                            <span x-text="Object.keys(saved.headers).length"></span> headers
                                        </span>
                                        <span x-show="saved.body && Object.keys(saved.body).length > 0"
                                              class="px-1.5 py-0.5 text-[10px] bg-[var(--ide-info-bg)] text-[var(--ide-info-text)] rounded">
                                            <span x-text="Object.keys(saved.body).length"></span> body fields
                                        </span>
                                        <span x-show="saved.query_params?.values && Object.keys(saved.query_params.values).length > 0"
                                              class="px-1.5 py-0.5 text-[10px] bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)] rounded">
                                            <span x-text="Object.keys(saved.query_params?.values || {}).length"></span> params
                                        </span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-ide-muted group-hover:text-violet-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-ide-border bg-ide-bg">
                <button @click="showingImportModal = false"
                        class="w-full px-4 py-2 text-sm font-medium text-ide-fg bg-ide-surface border border-ide-border rounded-lg hover:bg-ide-line-active transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Variable Picker Dropdown -->
    <div x-show="variablePickerOpen"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed z-[70] bg-ide-bg border border-ide-border rounded-lg shadow-lg w-72 max-h-60 overflow-hidden"
         :style="{ top: variablePickerPosition.top + 'px', left: variablePickerPosition.left + 'px' }"
         @click.away="variablePickerOpen = false"
         x-cloak>
        <!-- Header -->
        <div class="px-3 py-2 border-b border-ide-border bg-ide-bg">
            <div class="text-xs font-medium text-ide-muted">Insert Variable</div>
            <div class="text-[10px] text-ide-muted">From previous steps</div>
        </div>

        <!-- Variables List -->
        <div class="overflow-y-auto max-h-48">
            <template x-if="variablePickerTarget && Object.keys(getAvailableVariablesForStep(variablePickerTarget.stepIndex)).length === 0">
                <div class="px-4 py-6 text-center">
                    <svg class="w-8 h-8 mx-auto text-ide-muted mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-xs text-ide-muted">No variables available yet</p>
                    <p class="text-[10px] text-ide-muted mt-1">Add extract variables to previous steps</p>
                </div>
            </template>
            <template x-if="variablePickerTarget">
                <template x-for="(vars, stepKey) in getAvailableVariablesForStep(variablePickerTarget.stepIndex)" :key="stepKey">
                    <div class="border-b border-ide-border last:border-b-0">
                        <div class="px-3 py-1.5 text-[10px] font-semibold text-ide-muted uppercase tracking-wider bg-ide-bg" x-text="stepKey"></div>
                        <template x-for="(val, varName) in vars" :key="varName">
                            <button @click="autocompleteMode ? insertVariableAutocomplete(stepKey, varName) : insertVariable(stepKey, varName)"
                                    class="w-full px-3 py-2 text-left hover:bg-ide-primary/5 transition-colors flex items-center justify-between group">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-sm font-medium text-ide-fg" x-text="varName"></span>
                                    <span class="text-[10px] text-ide-muted truncate max-w-[100px]" x-text="typeof val === 'string' && val.startsWith('(from') ? val : JSON.stringify(val).substring(0, 20)"></span>
                                </div>
                                <code class="text-[10px] text-ide-primary font-mono opacity-0 group-hover:opacity-100 transition-opacity" x-text="'@{{' + stepKey + '.' + varName + '}}'"></code>
                            </button>
                        </template>
                    </div>
                </template>
            </template>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="fixed bottom-4 right-4 z-50 toast-container">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                class="toast-item flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg"
                :class="{
                    'bg-green-500 text-white': toast.type === 'success',
                    'bg-red-500 text-white': toast.type === 'error',
                    'bg-blue-500 text-white': toast.type === 'info',
                    'toast-exit': toast.exiting
                }"
            >
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <span class="text-sm font-medium" x-text="toast.message"></span>
                <button @click="dismissToast(toast.id)" class="ml-2 text-white/80 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- OpenAPI Spec Data and Alpine.js Component -->
    <script>
        window.apiSpec = @json($spec);
        window.telescopeEnabled = @json($telescopeEnabled ?? false);

        // Initialize dark mode from localStorage on page load (before Alpine loads)
        (function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            }
        })();

        function ideExplorer() {
            return {
                // State
                spec: window.apiSpec || {},
                searchQuery: '',
                selectedEndpoint: null,
                activeTab: 'try-it',
                expandedTags: {},
                darkMode: localStorage.getItem('darkMode') === 'true',
                endpoints: [],
                endpointsByTag: {},
                get allEndpoints() { return this.endpoints; },

                // Request Builder State
                rawJsonMode: false,
                rawJsonBody: '{}',
                jsonParseError: null,
                requestState: {
                    pathParams: {},
                    queryParams: {},
                    queryParamsEnabled: {},
                    body: {},
                    bodyFieldsEnabled: {},
                    customHeaders: [{ id: 1, key: '', value: '', enabled: true }],
                    headerIdCounter: 1
                },

                // Response Mode State
                responseMode: 'preview',  // 'preview' or 'request'
                requestLoading: false,
                apiResponse: null,
                apiError: null,
                responseTruncated: false,
                showFullHighlighted: false,
                requestHistory: (() => { try { return JSON.parse(localStorage.getItem('apiura_history') || '[]'); } catch { return []; } })(),
                historyFilter: '',

                // Authentication State
                authToken: localStorage.getItem('apiura_token') || '',
                authMode: 'token',  // 'token' or 'login'
                tokenInput: '',
                loginEmail: '',
                loginPassword: '',
                loginError: '',
                loginLoading: false,

                // Environment Profiles State
                environments: (() => { try { return JSON.parse(localStorage.getItem('apiura_environments') || '[]'); } catch { return []; } })(),
                activeEnvironment: localStorage.getItem('apiura_active_env') || null,
                showEnvModal: false,
                newEnv: { name: '', baseUrl: '', token: '', headers: {}, color: 'green' },

                // UI State
                sidebarOpen: false,
                sidebarWidth: 256,
                isResizingSidebar: false,
                isLoading: true,
                isMac: navigator.platform.toUpperCase().indexOf('MAC') >= 0,

                // IDE-specific State
                activeActivity: sessionStorage.getItem('apiura_activity') || 'explorer',  // 'explorer', 'search', 'saved', 'flows', 'history'
                sidebarVisible: true,
                openTabs: (() => { try { return JSON.parse(localStorage.getItem('apiura_open_tabs') || '[]'); } catch { return []; } })(),
                activeTabId: localStorage.getItem('apiura_active_tab') || null,
                tabStates: {},
                editorTab: 'request',  // 'request', 'preview', 'docs'
                responseTab: 'response',  // 'response', 'headers', 'console'
                bottomPanelHeight: 280,
                isResizing: false,
                responseVisible: true,
                showCommandPalette: false,
                commandPaletteQuery: '',
                flowSearchQuery: '',
                showLoginForm: false,
                currentEnvironment: localStorage.getItem('apiura_active_env') || 'default',
                savedRequestFilter: '',

                // Modules State
                modules: [],
                loadingModules: false,
                moduleSearchQuery: '',
                editingModuleName: null,
                deleteModuleDialog: { show: false, moduleId: null, moduleName: '', itemCount: 0 },
                moduleCollapseState: (() => { try { return JSON.parse(localStorage.getItem('apiura_module_collapse') || '{}'); } catch { return {}; } })(),

                // Import Preview State
                showImportPreview: false,
                importPreviewItems: [],
                importPreviewModule: { id: 'new', name: '' },
                importPreviewFilter: 'all',
                importPreviewLoading: false,
                importPreviewDiffItem: null,

                // Keyboard Shortcuts
                showShortcuts: false,

                // Toast State (stacking)
                toasts: [],
                toastId: 0,

                // Import cURL State
                showImportCurl: false,
                curlInput: '',
                curlParseError: '',

                // Saved Requests State
                savedRequests: [],
                showSavedRequestsPanel: false,
                savingRequest: false,
                loadingSavedRequests: false,
                saveRequestName: '',
                saveRequestPriority: '',
                saveRequestTeam: '',
                showSaveModal: false,
                saveMode: 'new', // 'new' or 'update'


                // Telescope State
                telescopeEnabled: window.telescopeEnabled || false,
                showTelescope: false,
                telescopeEntries: [],
                telescopeLoading: false,
                telescopePage: 1,
                telescopeHasMore: false,
                telescopeFilter: '',
                telescopeMethodFilter: '',
                telescopeTotal: 0,

                // Comments State
                showCommentsModal: false,
                selectedSavedRequest: null,
                selectedSavedRequestComments: [],
                loadingComments: false,
                submittingComment: false,
                newComment: {
                    content: '',
                    author_name: '',
                    author_type: 'other',
                    status: 'info'
                },
                expandedEndpointSaved: {},

                // Query Param Helpers State
                commonQueryParams: window.apiSpec?.['x-common-query-params'] || {
                    pagination: [
                        { name: 'page', type: 'integer', description: 'Page number', default: 1 },
                        { name: 'per_page', type: 'integer', description: 'Items per page', default: 15 },
                    ],
                    sorting: [
                        { name: 'sort_by', type: 'string', description: 'Field to sort by' },
                        { name: 'sort_direction', type: 'string', description: 'Sort direction', default: 'asc', enum: ['asc', 'desc'] },
                    ],
                    date_range: [
                        { name: 'from', type: 'string', format: 'date', description: 'Start date (YYYY-MM-DD)' },
                        { name: 'to', type: 'string', format: 'date', description: 'End date (YYYY-MM-DD)' },
                    ],
                    search: [
                        { name: 'search', type: 'string', description: 'Search query' },
                    ],
                },

                // Settings State
                showSettings: false,
                customBaseUrl: localStorage.getItem('apiura_base_url') || '',

                // Schema Viewer State
                showSchemaViewer: false,
                selectedSchemaTable: null,

                // API Map State (Resource-Centric Visualization)
                showRelationshipGraph: false,
                apiMapView: 'overview', // 'overview' | 'resource'
                selectedResource: null, // Selected tag/resource
                resourceRelationships: [], // Auto-detected relationships
                resourceEndpoints: [], // Endpoints for selected resource
                selectedGraphNode: null, // Selected endpoint in resource view

                // Internal guards
                _loadSavedRequestGeneration: 0,
                _commentsSavedRequestBackup: undefined,

                // Flow Builder State
                flows: [],
                selectedFlowIds: [],
                loadingFlows: false,
                showFlowsPanel: false,
                isCreatingFlow: false,
                editingFlow: null,
                newFlow: {
                    name: '',
                    description: '',
                    steps: [],
                    defaultHeaders: {},  // Headers applied to all steps
                    continueOnError: false
                },
                runningFlow: false,
                runningSingleStep: -1,
                flowRunResults: [],
                flowResponseDialog: { show: false, step: null, name: '', status: null, duration: null, data: null, success: false, httpOk: false, method: '', url: '', headers: {}, body: null, bodyMode: '' },
                currentFlowStep: -1,
                flowVariables: {},  // Stores extracted variables: { step1: { token: 'abc', id: 123 }, step2: { ... } }
                flowRunError: null,
                flowTotalDuration: 0,

                flowDocEndpoint: null,
                showFlowDocDialog: false,

                // Flow Step Configuration State
                newDefaultHeaderKey: '',
                newDefaultHeaderValue: '',
                showingImportModal: false,
                importingStepIndex: -1,
                importFilterEndpoint: '',
                variablePickerOpen: false,
                variablePickerTarget: null,  // { stepIndex, field, subfield }
                variablePickerPosition: { top: 0, left: 0 },
                autocompleteMode: false,

                databaseTables: [],
                dbSchemaLoaded: false,

                // Load database schema from API (lazy-loaded when DB Schema panel opens)
                async loadDbSchema() {
                    if (this.dbSchemaLoaded) return;
                    try {
                        const response = await fetch('/apiura/db-schema', {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!response.ok) throw new Error('Failed to load schema');
                        const schema = await response.json();
                        this.databaseTables = Object.entries(schema).map(([tableName, tableData]) => {
                            const foreignKeyColumns = new Set();
                            (tableData.foreign_keys || []).forEach(fk => {
                                (fk.columns || []).forEach(col => foreignKeyColumns.add(col));
                            });
                            const primaryColumns = new Set();
                            (tableData.indexes || []).forEach(idx => {
                                if (idx.primary) (idx.columns || []).forEach(col => primaryColumns.add(col));
                            });
                            return {
                                name: tableName,
                                description: '',
                                columns: (tableData.columns || []).map(col => ({
                                    name: col.name,
                                    type: col.type_name || col.type || '-',
                                    description: '',
                                    primary: primaryColumns.has(col.name),
                                    nullable: col.nullable || false,
                                    foreign: foreignKeyColumns.has(col.name)
                                }))
                            };
                        });
                        if (this.databaseTables.length > 0 && !this.selectedSchemaTable) {
                            this.selectedSchemaTable = this.databaseTables[0].name;
                        }
                        this.dbSchemaLoaded = true;
                    } catch (e) {
                        this.showToast('Failed to load database schema', 'error');
                    }
                },

                // Panel Resizer Methods
                startSidebarResize(e) {
                    this.isResizingSidebar = true;
                    const startX = e.clientX;
                    const startWidth = this.sidebarWidth;
                    const onMouseMove = (ev) => {
                        const delta = ev.clientX - startX;
                        this.sidebarWidth = Math.min(480, Math.max(180, startWidth + delta));
                    };
                    const onMouseUp = () => {
                        this.isResizingSidebar = false;
                        document.removeEventListener('mousemove', onMouseMove);
                        document.removeEventListener('mouseup', onMouseUp);
                        document.body.style.cursor = '';
                        document.body.style.userSelect = '';
                    };
                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                    document.body.style.cursor = 'col-resize';
                    document.body.style.userSelect = 'none';
                },

                startResize(e) {
                    this.isResizing = true;
                    const startY = e.clientY;
                    const startHeight = this.bottomPanelHeight;
                    const onMouseMove = (ev) => {
                        const delta = startY - ev.clientY;
                        this.bottomPanelHeight = Math.min(600, Math.max(80, startHeight + delta));
                    };
                    const onMouseUp = () => {
                        this.isResizing = false;
                        document.removeEventListener('mousemove', onMouseMove);
                        document.removeEventListener('mouseup', onMouseUp);
                        document.body.style.cursor = '';
                        document.body.style.userSelect = '';
                    };
                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                    document.body.style.cursor = 'row-resize';
                    document.body.style.userSelect = 'none';
                },

                init() {
                    this.enrichResponseSchemas();
                    this.parseEndpoints();
                    // Expand all tags by default
                    Object.keys(this.endpointsByTag).forEach(tag => {
                        this.expandedTags[tag] = true;
                    });

                    // Restore from URL hash
                    this.restoreFromHash();

                    // Listen for browser back/forward navigation
                    window.addEventListener('popstate', () => this.restoreFromHash());

                    // Clear flow hash when closing the flow panel
                    this.$watch('showFlowsPanel', (value) => {
                        if (value) {
                            this.loadModules();
                        }
                        if (!value) {
                            if (this.activeActivity === 'flows') {
                                this.activeActivity = 'explorer';
                            }
                            if (window.location.hash.startsWith('#flow/')) {
                                history.replaceState(null, '', window.location.pathname);
                            }
                        }
                    });

                    // Persist active sidebar panel to sessionStorage
                    this.$watch('activeActivity', (value) => {
                        sessionStorage.setItem('apiura_activity', value);
                    });

                    // Persist open tabs to localStorage (debounced)
                    let _tabSaveTimer = null;
                    this.$watch('openTabs', (value) => {
                        clearTimeout(_tabSaveTimer);
                        _tabSaveTimer = setTimeout(() => {
                            localStorage.setItem('apiura_open_tabs', JSON.stringify(value));
                        }, 500);
                    });
                    this.$watch('activeTabId', (value) => {
                        if (value) {
                            localStorage.setItem('apiura_active_tab', value);
                        } else {
                            localStorage.removeItem('apiura_active_tab');
                        }
                    });

                    // Restore active tab on init if we have persisted tabs
                    if (this.openTabs.length > 0 && this.activeTabId && !window.location.hash) {
                        const tab = this.openTabs.find(t => t.id === this.activeTabId);
                        if (tab) {
                            const endpoint = this.endpoints.find(e => e.method === tab.method && e.path === tab.path);
                            if (endpoint) {
                                this.selectedEndpoint = endpoint;
                                this.restoreTabState(this.activeTabId);
                            }
                        }
                    }

                    // Restore flows panel if activeActivity was 'flows'
                    if (this.activeActivity === 'flows') {
                        this.showFlowsPanel = true;
                        this.loadFlows();
                        this.loadModules();
                    }

                    // Centralized escape handler - closes topmost modal only
                    window.addEventListener('keydown', (e) => {
                        // Command Palette (Ctrl+P or Cmd+P)
                        if ((e.metaKey || e.ctrlKey) && e.key === 'p') {
                            e.preventDefault();
                            this.showCommandPalette = !this.showCommandPalette;
                            this.commandPaletteQuery = '';
                            if (this.showCommandPalette) {
                                this.$nextTick(() => this.$refs.commandPaletteInput?.focus());
                            }
                            return;
                        }
                        // Toggle sidebar (Ctrl+B)
                        if ((e.metaKey || e.ctrlKey) && e.key === 'b') {
                            e.preventDefault();
                            this.sidebarVisible = !this.sidebarVisible;
                            return;
                        }
                        if (e.key !== 'Escape') return;
                        if (this.importPreviewDiffItem) { this.importPreviewDiffItem = null; return; }
                        if (this.showImportPreview) { this.closeImportPreview(); return; }
                        if (this.showCommandPalette) { this.showCommandPalette = false; return; }
                        if (this.showCommentsModal) { this.closeCommentsModal(); return; }
                        if (this.showFlowDocDialog) { this.showFlowDocDialog = false; return; }
                        if (this.showingImportModal) { this.showingImportModal = false; return; }
                        if (this.showSaveModal) { this.showSaveModal = false; return; }
                        if (this.showSettings) { this.showSettings = false; return; }
                        if (this.showSchemaViewer) { this.showSchemaViewer = false; return; }
                        if (this.showRelationshipGraph) { this.showRelationshipGraph = false; return; }
                        if (this.showFlowsPanel) { this.showFlowsPanel = false; this.isCreatingFlow = false; this.editingFlow = null; return; }
                        if (this.showShortcuts) { this.showShortcuts = false; return; }
                        if (this.showEnvModal) { this.showEnvModal = false; return; }
                        if (this.showTelescope) { this.showTelescope = false; return; }
                        if (this.showImportCurl) { this.showImportCurl = false; return; }
                        if (this.showSavedRequestsPanel) { this.showSavedRequestsPanel = false; return; }
                    });

                    // Setup keyboard shortcuts
                    this.setupKeyboardShortcuts();

                    // Hide loading after a brief delay
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 300);

                    // Load saved requests
                    this.loadSavedRequests();

                    // Watch dark mode changes
                    this.$watch('darkMode', val => {
                        localStorage.setItem('darkMode', val);
                        document.documentElement.classList.toggle('dark', val);
                        document.body.classList.toggle('dark', val);
                    });
                    // Watch auth token changes
                    this.$watch('authToken', val => {
                        if (val) {
                            localStorage.setItem('apiura_token', val);
                        } else {
                            localStorage.removeItem('apiura_token');
                        }
                    });

                    // Initialize environments
                    if (this.environments.length === 0) {
                        this.environments = [
                            { id: 'local', name: 'Local', baseUrl: '', token: '', headers: {}, color: 'green' },
                            { id: 'staging', name: 'Staging', baseUrl: '', token: '', headers: {}, color: 'yellow' },
                            { id: 'production', name: 'Production', baseUrl: '', token: '', headers: {}, color: 'red' }
                        ];
                    }

                    // Watch API Map modal
                    this.$watch('showRelationshipGraph', val => {
                        if (val) {
                            // Reset to overview and build relationships when opening
                            this.apiMapView = 'overview';
                            this.selectedResource = null;
                            this.selectedGraphNode = null;
                            this.resourceRelationships = this.buildRelationshipsFromSpec();
                        }
                    });
                },

                // Parse endpoints from OpenAPI spec
                // Fix response schemas where 'data' is incorrectly typed as string
                // by matching path segments to component schemas
                enrichResponseSchemas() {
                    if (!this.spec?.paths || !this.spec?.components?.schemas) return;

                    // Build map: plural-kebab-case -> schema name
                    // e.g. 'bank-accounts' -> 'BankAccount', 'assets' -> 'Asset'
                    const resourceToSchema = {};
                    for (const name of Object.keys(this.spec.components.schemas)) {
                        const kebab = name.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
                        let plural;
                        if (kebab.endsWith('y')) {
                            plural = kebab.slice(0, -1) + 'ies';
                        } else if (kebab.endsWith('s') || kebab.endsWith('x') || kebab.endsWith('ch') || kebab.endsWith('sh')) {
                            plural = kebab + 'es';
                        } else {
                            plural = kebab + 's';
                        }
                        resourceToSchema[plural] = name;
                    }
                    // Manual overrides for non-standard mappings
                    resourceToSchema['currencies'] = 'UserCurrency';

                    for (const [path, methods] of Object.entries(this.spec.paths)) {
                        for (const [method, details] of Object.entries(methods)) {
                            if (typeof details !== 'object' || !details.responses) continue;

                            for (const [code, response] of Object.entries(details.responses)) {
                                const schema = response?.content?.['application/json']?.schema;
                                if (!schema?.properties?.data || schema.properties.data.type !== 'string') continue;

                                // Extract resource segment from path (e.g. /v1/bank-accounts/{id} -> bank-accounts)
                                const segments = path.split('/').filter(s => s && !s.startsWith('{') && s !== 'v1');
                                // Only fix standard CRUD paths: /resource or /resource/{id}
                                // Skip sub-resource paths like /resource/{id}/value-history
                                if (segments.length > 1) continue;
                                const resourceSegment = segments[0];

                                if (!resourceSegment || !resourceToSchema[resourceSegment]) continue;
                                const schemaName = resourceToSchema[resourceSegment];
                                if (!this.spec.components.schemas[schemaName]) continue;

                                // Check if this is a list endpoint (no {id} in path) -> wrap in array
                                const hasIdParam = path.includes('{');
                                if (hasIdParam) {
                                    schema.properties.data = { '$ref': '#/components/schemas/' + schemaName };
                                } else {
                                    schema.properties.data = {
                                        type: 'array',
                                        items: { '$ref': '#/components/schemas/' + schemaName }
                                    };
                                }
                            }
                        }
                    }
                },

                parseEndpoints() {
                    const paths = this.spec.paths || {};
                    this.endpoints = [];
                    this.endpointsByTag = {};

                    Object.entries(paths).forEach(([path, methods]) => {
                        Object.entries(methods).forEach(([method, details]) => {
                            if (['get', 'post', 'put', 'patch', 'delete'].includes(method.toLowerCase())) {
                                const endpoint = {
                                    path,
                                    method: method.toUpperCase(),
                                    summary: details.summary || '',
                                    description: details.description || '',
                                    operationId: details.operationId || '',
                                    tags: details.tags || ['Untagged'],
                                    parameters: details.parameters || [],
                                    requestBody: details.requestBody || null,
                                    responses: details.responses || {}
                                };

                                this.endpoints.push(endpoint);

                                // Group by first tag
                                const tag = endpoint.tags[0] || 'Untagged';
                                if (!this.endpointsByTag[tag]) {
                                    this.endpointsByTag[tag] = [];
                                }
                                this.endpointsByTag[tag].push(endpoint);
                            }
                        });
                    });

                    // Sort tags alphabetically
                    const sortedTags = {};
                    Object.keys(this.endpointsByTag).sort().forEach(tag => {
                        sortedTags[tag] = this.endpointsByTag[tag];
                    });
                    this.endpointsByTag = sortedTags;
                },

                // Computed: filtered endpoints by tag
                get filteredEndpointsByTag() {
                    if (!this.searchQuery.trim()) {
                        return this.endpointsByTag;
                    }

                    const query = this.searchQuery.toLowerCase().trim();
                    const filtered = {};

                    Object.entries(this.endpointsByTag).forEach(([tag, endpoints]) => {
                        const matchingEndpoints = endpoints.filter(endpoint => {
                            return endpoint.path.toLowerCase().includes(query) ||
                                   endpoint.summary.toLowerCase().includes(query) ||
                                   endpoint.description.toLowerCase().includes(query) ||
                                   endpoint.operationId.toLowerCase().includes(query) ||
                                   endpoint.method.toLowerCase().includes(query);
                        });

                        if (matchingEndpoints.length > 0) {
                            filtered[tag] = matchingEndpoints;
                        }
                    });

                    return filtered;
                },

                // Computed: total endpoint count
                get endpointCount() {
                    return this.endpoints.length;
                },

                // Toggle tag expansion
                toggleTag(tag) {
                    this.expandedTags[tag] = !this.expandedTags[tag];
                },

                // Check if tag is expanded
                isTagExpanded(tag) {
                    return this.expandedTags[tag] === true;
                },

                // Select an endpoint
                selectEndpoint(endpoint, updateHash = true) {
                    // Save current tab state before switching
                    this.saveCurrentTabState();
                    this.selectedEndpoint = endpoint;
                    this.selectedSavedRequest = null;
                    // Open tab for this endpoint (IDE feature)
                    this.openEndpointTab(endpoint);
                    // Try to restore saved state for this tab, otherwise init fresh
                    const tabId = endpoint.method + ':' + endpoint.path;
                    if (!this.restoreTabState(tabId)) {
                        this.rawJsonMode = false;
                        this.jsonParseError = null;
                        this.responseMode = 'preview';
                        this.apiResponse = null;
                        this.apiError = null;
                        this.initRequestState();
                    }
                    // Close mobile sidebar
                    this.sidebarOpen = false;
                    // Update URL hash
                    if (updateHash && endpoint) {
                        this.updateHash(endpoint);
                    }
                },

                // === IDE Tab Management ===

                // Save current tab's state before switching away
                saveCurrentTabState() {
                    if (this.activeTabId) {
                        this.tabStates[this.activeTabId] = {
                            requestState: JSON.parse(JSON.stringify(this.requestState)),
                            rawJsonMode: this.rawJsonMode,
                            rawJsonBody: this.rawJsonBody,
                            jsonParseError: this.jsonParseError,
                            responseMode: this.responseMode,
                            apiResponse: this.apiResponse,
                            apiError: this.apiError,
                            responseTruncated: this.responseTruncated,
                            showFullHighlighted: this.showFullHighlighted,
                        };
                    }
                },

                // Restore a tab's state
                restoreTabState(tabId) {
                    const state = this.tabStates[tabId];
                    if (state) {
                        this.requestState = JSON.parse(JSON.stringify(state.requestState));
                        this.rawJsonMode = state.rawJsonMode;
                        this.rawJsonBody = state.rawJsonBody;
                        this.jsonParseError = state.jsonParseError;
                        this.responseMode = state.responseMode;
                        this.apiResponse = state.apiResponse;
                        this.apiError = state.apiError;
                        this.responseTruncated = state.responseTruncated || false;
                        this.showFullHighlighted = state.showFullHighlighted || false;
                        return true;
                    }
                    return false;
                },

                openEndpointTab(endpoint) {
                    if (!endpoint) return;
                    const tabId = endpoint.method + ':' + endpoint.path;
                    const existing = this.openTabs.find(t => t.id === tabId);
                    if (!existing) {
                        this.openTabs.push({
                            id: tabId,
                            method: endpoint.method,
                            path: endpoint.path,
                            summary: endpoint.summary || ''
                        });
                    }
                    this.activeTabId = tabId;
                },

                closeTab(tabId) {
                    const idx = this.openTabs.findIndex(t => t.id === tabId);
                    if (idx === -1) return;
                    this.openTabs.splice(idx, 1);
                    delete this.tabStates[tabId];
                    if (this.activeTabId === tabId) {
                        if (this.openTabs.length > 0) {
                            const newIdx = Math.min(idx, this.openTabs.length - 1);
                            this.switchToTab(this.openTabs[newIdx].id);
                        } else {
                            this.activeTabId = null;
                            this.selectedEndpoint = null;
                        }
                    }
                },

                switchToTab(tabId) {
                    const tab = this.openTabs.find(t => t.id === tabId);
                    if (!tab) return;
                    // Save current tab state before switching
                    this.saveCurrentTabState();
                    this.activeTabId = tabId;
                    // Find the matching endpoint and select it without opening a new tab
                    const endpoint = this.allEndpoints.find(e => e.method === tab.method && e.path === tab.path);
                    if (endpoint && (!this.selectedEndpoint || this.selectedEndpoint.method !== tab.method || this.selectedEndpoint.path !== tab.path)) {
                        this.selectedEndpoint = endpoint;
                        // Restore saved state or initialize fresh
                        if (!this.restoreTabState(tabId)) {
                            this.rawJsonMode = false;
                            this.jsonParseError = null;
                            this.responseMode = 'preview';
                            this.apiResponse = null;
                            this.apiError = null;
                            this.initRequestState();
                        }
                        this.updateHash(endpoint);
                    }
                },

                // Initialize request state when selecting an endpoint
                initRequestState() {
                    this.requestState = {
                        pathParams: {},
                        queryParams: {},
                        queryParamsEnabled: {},
                        body: {},
                        bodyFiles: {},
                        bodyFieldsEnabled: {},
                        customHeaders: [{ id: 1, key: '', value: '', enabled: true }],
                        headerIdCounter: 1,
                        customFields: []
                    };

                    if (!this.selectedEndpoint) return;

                    // Initialize path params
                    this.getPathParameters(this.selectedEndpoint).forEach(param => {
                        this.requestState.pathParams[param.name] = '';
                    });

                    // Initialize query params
                    this.getQueryParameters(this.selectedEndpoint).forEach(param => {
                        this.requestState.queryParams[param.name] = '';
                        this.requestState.queryParamsEnabled[param.name] = param.required || false;
                    });

                    // Initialize body fields
                    const fields = this.getRequestBodyFieldsForBuilder(this.selectedEndpoint);
                    fields.forEach(field => {
                        this.requestState.bodyFieldsEnabled[field.name] = field.required;

                        if (field.isArray && field.arrayItemIsFile) {
                            // Array of files — stored in bodyFiles as an array
                            this.requestState.bodyFiles[field.name] = [];
                        } else if (field.isFile) {
                            // Single file fields are stored in bodyFiles, not body
                            this.requestState.bodyFiles[field.name] = null;
                        } else if (field.isArray && field.nestedFields && field.nestedFields.length > 0) {
                            // Array of objects — start empty, user adds items
                            this.requestState.body[field.name] = [];
                        } else if (field.isArray && (!field.nestedFields || field.nestedFields.length === 0)) {
                            // Primitive array fields (dates, strings, numbers) init as empty arrays
                            this.requestState.body[field.name] = [];
                        } else if (field.type === 'object' && field.nestedFields && field.nestedFields.length > 0) {
                            this.requestState.body[field.name] = {};
                            field.nestedFields.forEach(nestedField => {
                                this.requestState.body[field.name][nestedField.name] = nestedField.type === 'boolean' ? false : '';
                                this.requestState.bodyFieldsEnabled[`${field.name}.${nestedField.name}`] = !nestedField.nullable;
                            });
                        } else {
                            this.requestState.body[field.name] = field.type === 'boolean' ? false : '';
                        }
                    });

                    this.syncToRawJson();
                },

                // Check if endpoint is selected
                isSelectedEndpoint(endpoint) {
                    return this.selectedEndpoint &&
                           this.selectedEndpoint.method === endpoint.method &&
                           this.selectedEndpoint.path === endpoint.path;
                },

                // Get method badge class
                getMethodBadgeClass(method) {
                    const classes = {
                        'GET': 'badge-get',
                        'POST': 'badge-post',
                        'PUT': 'badge-put',
                        'PATCH': 'badge-patch',
                        'DELETE': 'badge-delete'
                    };
                    return classes[method] || 'bg-ide-border text-ide-muted';
                },

                // Get status class for response codes
                getStatusClass(status) {
                    const code = parseInt(status);
                    if (code >= 200 && code < 300) {
                        return 'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)] border-b border-[var(--ide-success-text)]/20';
                    } else if (code >= 400 && code < 500) {
                        return 'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)] border-b border-[var(--ide-warning-text)]/20';
                    } else if (code >= 500) {
                        return 'bg-[var(--ide-error-bg)] text-[var(--ide-error-text)] border-b border-[var(--ide-error-text)]/20';
                    }
                    return 'bg-ide-bg text-ide-muted border-b border-ide-border';
                },

                // Get status text
                getStatusText(status) {
                    const texts = {
                        '200': 'OK',
                        '201': 'Created',
                        '204': 'No Content',
                        '400': 'Bad Request',
                        '401': 'Unauthorized',
                        '403': 'Forbidden',
                        '404': 'Not Found',
                        '422': 'Unprocessable Entity',
                        '429': 'Too Many Requests',
                        '500': 'Internal Server Error',
                        '502': 'Bad Gateway',
                        '503': 'Service Unavailable'
                    };
                    return texts[status] || '';
                },

                // Format JSON for display
                formatJson(obj) {
                    if (!obj) return '';
                    try {
                        return JSON.stringify(obj, null, 2);
                    } catch (e) {
                        return String(obj);
                    }
                },

                // Get the base URL (custom or from spec)
                getBaseUrl() {
                    if (this.customBaseUrl && this.customBaseUrl.trim()) {
                        return this.customBaseUrl.trim().replace(/\/$/, ''); // Remove trailing slash
                    }
                    const specUrl = this.spec.servers?.[0]?.url || '/api';
                    // Use relative path if the spec URL is on the same origin or a .test domain
                    // This avoids SSL cert issues with local development
                    try {
                        const parsed = new URL(specUrl);
                        if (parsed.origin === window.location.origin || parsed.hostname.endsWith('.test') || parsed.hostname.endsWith('.local')) {
                            return parsed.pathname.replace(/\/$/, '');
                        }
                    } catch (e) {
                        // specUrl is already relative, use as-is
                    }
                    return specUrl;
                },

                // Get the default base URL from spec (for display)
                getDefaultBaseUrl() {
                    return this.spec.servers?.[0]?.url || '/api';
                },

                // Save custom base URL to localStorage
                saveBaseUrl() {
                    localStorage.setItem('apiura_base_url', this.customBaseUrl);
                    this.showToast('Base URL saved');
                },

                // Get full API path including base URL
                getFullPath(path) {
                    return this.getBaseUrl() + (path || '');
                },

                // Check if endpoint requires authentication
                isAuthRequired(endpoint) {
                    if (!endpoint) return false;
                    // Check if 401 response exists
                    if (endpoint.responses && endpoint.responses['401']) {
                        return true;
                    }
                    // Check for security requirements
                    if (endpoint.security && endpoint.security.length > 0) {
                        return true;
                    }
                    return false;
                },

                // Get query parameters only (from OpenAPI spec)
                getQueryParameters(endpoint) {
                    if (!endpoint?.parameters) return [];
                    return endpoint.parameters.filter(p => p.in === 'query');
                },

                // Get ALL query parameters (spec + dynamically added)
                getAllQueryParameters(endpoint) {
                    const specParams = this.getQueryParameters(endpoint);
                    const specParamNames = new Set(specParams.map(p => p.name));

                    // Get dynamically added params from requestState
                    const dynamicParams = [];
                    for (const [name, value] of Object.entries(this.requestState.queryParams)) {
                        if (!specParamNames.has(name)) {
                            // Find the definition from common params
                            const commonDef = this.findCommonParamDefinition(name);
                            dynamicParams.push({
                                name: name,
                                in: 'query',
                                required: false,
                                isDynamic: true, // Mark as dynamically added
                                schema: commonDef?.schema || { type: commonDef?.type || 'string' },
                                description: commonDef?.description || '',
                                default: commonDef?.default
                            });
                        }
                    }

                    return [...specParams, ...dynamicParams];
                },

                // Find common param definition by name
                findCommonParamDefinition(name) {
                    for (const [category, params] of Object.entries(this.commonQueryParams)) {
                        const found = params.find(p => p.name === name);
                        if (found) {
                            return {
                                ...found,
                                category: category,
                                schema: found.enum
                                    ? { type: found.type || 'string', enum: found.enum }
                                    : { type: found.type || 'string', format: found.format }
                            };
                        }
                    }
                    return null;
                },

                // Check if endpoint is a list/collection endpoint (GET without trailing {param})
                isListEndpoint(endpoint) {
                    if (!endpoint) return false;
                    if (endpoint.method !== 'GET') return false;
                    // List endpoints don't end with a path parameter like {id}
                    return !endpoint.path.match(/\{[^}]+\}$/);
                },

                // Check if a quick-add category should be shown for this endpoint
                // Hidden if: not a GET request, not a list endpoint, or all params from that category already exist
                showQuickAddCategory(endpoint, category) {
                    if (!this.isListEndpoint(endpoint)) return false;
                    const categoryParams = this.commonQueryParams[category] || [];
                    if (categoryParams.length === 0) return false;
                    const existingNames = new Set(this.getAllQueryParameters(endpoint).map(p => p.name));
                    // Show if at least one param from this category is not yet added
                    return categoryParams.some(p => !existingNames.has(p.name));
                },

                // Remove a dynamic query param
                removeQueryParam(name) {
                    delete this.requestState.queryParams[name];
                    delete this.requestState.queryParamsEnabled[name];
                },

                // Clear a query param value
                clearQueryParamValue(name) {
                    this.requestState.queryParams[name] = '';
                },

                // Get type label for display
                getParamTypeLabel(param) {
                    const schema = param.schema || {};
                    if (schema.enum) return 'enum';
                    if (schema.format === 'date') return 'date';
                    if (schema.format === 'date-time') return 'datetime';
                    return schema.type || 'string';
                },

                // Get type color class
                getParamTypeColor(param) {
                    const type = this.getParamTypeLabel(param);
                    const colors = {
                        'integer': 'bg-[var(--ide-info-bg)] text-[var(--ide-info-text)]',
                        'number': 'bg-[var(--ide-info-bg)] text-[var(--ide-info-text)]',
                        'boolean': 'bg-ide-primary/10 text-ide-primary',
                        'enum': 'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]',
                        'date': 'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)]',
                        'datetime': 'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)]',
                        'string': 'bg-ide-border text-ide-muted',
                    };
                    return colors[type] || colors['string'];
                },

                // Get path parameters only
                getPathParameters(endpoint) {
                    if (!endpoint?.parameters) return [];
                    return endpoint.parameters.filter(p => p.in === 'path');
                },

                // Get request body schema from any content type (application/json, multipart/form-data, etc.)
                getRequestBodySchema(endpoint) {
                    const content = endpoint?.requestBody?.content;
                    if (!content) return null;
                    return content['application/json']?.schema
                        || content['multipart/form-data']?.schema
                        || content['application/x-www-form-urlencoded']?.schema
                        || null;
                },

                // Get the content type of the request body
                getRequestBodyContentType(endpoint) {
                    const content = endpoint?.requestBody?.content;
                    if (!content) return 'application/json';
                    if (content['multipart/form-data']) return 'multipart/form-data';
                    if (content['application/x-www-form-urlencoded']) return 'application/x-www-form-urlencoded';
                    return 'application/json';
                },

                // Check if endpoint has request body
                hasRequestBody(endpoint) {
                    return !!this.getRequestBodySchema(endpoint);
                },

                // Get request body fields
                getRequestBodyFields(endpoint) {
                    const schema = this.getRequestBodySchema(endpoint);
                    if (!schema) return [];
                    return this.extractSchemaFields(schema, schema.required || []);
                },

                // Extract fields from schema
                extractSchemaFields(schema, requiredFields = [], depth = 0) {
                    const fields = [];
                    if (!schema?.properties || depth > 8) return fields;

                    Object.entries(schema.properties).forEach(([name, prop]) => {
                        // Resolve $ref at property level
                        let resolvedProp = prop;
                        if (prop.$ref) {
                            const refPath = prop.$ref.replace('#/components/schemas/', '');
                            const refSchema = this.spec?.components?.schemas?.[refPath];
                            if (refSchema) {
                                resolvedProp = { ...refSchema, ...prop, $ref: undefined };
                            }
                        }

                        const field = {
                            name: name,
                            type: this.getFieldType(resolvedProp),
                            required: requiredFields.includes(name),
                            nullable: resolvedProp.nullable || (Array.isArray(resolvedProp.type) && resolvedProp.type.includes('null')) || false,
                            description: this.cleanDescription(resolvedProp.description),
                            enum: resolvedProp.enum,
                            example: resolvedProp.example,
                            constraints: this.getConstraints(resolvedProp),
                            format: resolvedProp.format || null,
                            minimum: resolvedProp.minimum,
                            maximum: resolvedProp.maximum,
                            nestedFields: [],
                            isFile: resolvedProp.format === 'binary' || resolvedProp.contentMediaType === 'application/octet-stream',
                            isArray: resolvedProp.type === 'array' || (Array.isArray(resolvedProp.type) && resolvedProp.type.includes('array')),
                            arrayItemType: resolvedProp.items?.type || 'string',
                            arrayItemFormat: resolvedProp.items?.format || null,
                            arrayItemIsFile: resolvedProp.items?.format === 'binary'
                        };

                        // Handle nested objects
                        if (resolvedProp.type === 'object' && resolvedProp.properties) {
                            field.nestedFields = this.extractSchemaFields(resolvedProp, resolvedProp.required || [], depth + 1);
                        }

                        // Handle arrays with object items (including $ref items)
                        if (resolvedProp.type === 'array' && resolvedProp.items) {
                            let itemSchema = resolvedProp.items;
                            if (itemSchema.$ref) {
                                const refPath = itemSchema.$ref.replace('#/components/schemas/', '');
                                const refSchema = this.spec?.components?.schemas?.[refPath];
                                if (refSchema) itemSchema = refSchema;
                            }
                            if (itemSchema.type === 'object' && itemSchema.properties) {
                                field.nestedFields = this.extractSchemaFields(itemSchema, itemSchema.required || [], depth + 1);
                            }
                        }

                        fields.push(field);
                    });

                    return fields;
                },

                // Get field type as string
                getFieldType(prop) {
                    if (Array.isArray(prop.type)) {
                        return prop.type.filter(t => t !== 'null').join(' | ');
                    }
                    if (prop.type === 'array' && prop.items) {
                        const itemType = prop.items.type || (prop.items.$ref ? prop.items.$ref.split('/').pop() : 'any');
                        return `${itemType}[]`;
                    }
                    if (prop.$ref) {
                        return prop.$ref.split('/').pop();
                    }
                    return prop.type || 'any';
                },

                // Clean description (remove "Options: ..." since we show enums separately)
                cleanDescription(description) {
                    if (!description) return '';
                    return description.replace(/Options:\s*[\w\s,\\]+/gi, '').trim();
                },

                // Get constraints string
                getConstraints(prop) {
                    const constraints = [];
                    if (prop.maxLength) constraints.push(`max length: ${prop.maxLength}`);
                    if (prop.minLength) constraints.push(`min length: ${prop.minLength}`);
                    if (prop.minimum !== undefined) constraints.push(`min: ${prop.minimum}`);
                    if (prop.maximum !== undefined) constraints.push(`max: ${prop.maximum}`);
                    if (prop.format) constraints.push(`format: ${prop.format}`);
                    return constraints.length > 0 ? constraints.join(', ') : null;
                },

                // Format example value
                formatExample(example) {
                    if (typeof example === 'object') {
                        return JSON.stringify(example);
                    }
                    return String(example);
                },

                // Collect all enums for summary section
                getAllEnums(endpoint) {
                    const enums = [];

                    // From query parameters
                    this.getQueryParameters(endpoint).forEach(param => {
                        if (param.schema?.enum) {
                            enums.push({
                                name: param.name,
                                location: 'query param',
                                values: param.schema.enum
                            });
                        }
                    });

                    // From request body
                    const addBodyEnums = (fields, prefix = '') => {
                        fields.forEach(field => {
                            if (field.enum && field.enum.length > 0) {
                                enums.push({
                                    name: prefix ? `${prefix}.${field.name}` : field.name,
                                    location: 'request body',
                                    values: field.enum
                                });
                            }
                            if (field.nestedFields && field.nestedFields.length > 0) {
                                addBodyEnums(field.nestedFields, field.name);
                            }
                        });
                    };
                    addBodyEnums(this.getRequestBodyFields(endpoint));

                    return enums;
                },

                // Get status badge class
                getStatusBadgeClass(statusCode) {
                    const code = parseInt(statusCode);
                    if (code >= 200 && code < 300) {
                        return 'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)]';
                    }
                    if (code >= 300 && code < 400) {
                        return 'bg-[var(--ide-info-bg)] text-[var(--ide-info-text)]';
                    }
                    if (code >= 400 && code < 500) {
                        return 'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)]';
                    }
                    if (code >= 500) {
                        return 'bg-[var(--ide-error-bg)] text-[var(--ide-error-text)]';
                    }
                    return 'bg-ide-border text-ide-fg';
                },

                // ============ REQUEST BUILDER METHODS ============

                // Get request body fields for builder (similar to getRequestBodyFields but returns raw structure)
                getRequestBodyFieldsForBuilder(endpoint) {
                    return this.getRequestBodyFields(endpoint);
                },

                // Toggle query parameter enabled state
                toggleQueryParam(name) {
                    this.requestState.queryParamsEnabled[name] = !this.requestState.queryParamsEnabled[name];
                },

                // Toggle body field enabled state
                toggleBodyField(fieldPath) {
                    this.requestState.bodyFieldsEnabled[fieldPath] = !this.requestState.bodyFieldsEnabled[fieldPath];
                    this.syncToRawJson();
                },

                // Custom headers management
                addCustomHeader() {
                    this.requestState.headerIdCounter++;
                    const newHeader = {
                        id: this.requestState.headerIdCounter,
                        key: '',
                        value: '',
                        enabled: true
                    };
                    this.requestState.customHeaders.push(newHeader);
                },

                removeCustomHeader(id) {
                    if (this.requestState.customHeaders.length > 1) {
                        const index = this.requestState.customHeaders.findIndex(h => h.id === id);
                        if (index !== -1) {
                            this.requestState.customHeaders.splice(index, 1);
                        }
                    }
                },

                getHeadersPreview() {
                    const headers = [
                        'Content-Type: application/json',
                        'Accept: application/json',
                    ];

                    if (this.authToken) {
                        headers.push(`Authorization: Bearer ${this.authToken.substring(0, 20)}...`);
                    } else {
                        headers.push('Authorization: Bearer {your-token}');
                    }

                    // Add enabled custom headers
                    for (const header of this.requestState.customHeaders) {
                        if (header.enabled && header.key.trim()) {
                            headers.push(`${header.key}: ${header.value}`);
                        }
                    }

                    return headers;
                },

                // Get headers preview with syntax highlighting (returns HTML)
                getHeadersPreviewHighlighted() {
                    const headers = this.getHeadersPreview();
                    return headers.map(header => {
                        const colonIndex = header.indexOf(':');
                        if (colonIndex > -1) {
                            const key = header.substring(0, colonIndex);
                            const value = header.substring(colonIndex + 1).trim();
                            return `<span class="header-key">${this.escapeHtml(key)}</span>: <span class="header-value">${this.escapeHtml(value)}</span>`;
                        }
                        return this.escapeHtml(header);
                    }).join('\n');
                },

                // Helper to escape HTML
                escapeHtml(text) {
                    return text
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                },

                getCustomHeadersObject() {
                    const headers = {};
                    for (const header of this.requestState.customHeaders) {
                        if (header.enabled && header.key.trim()) {
                            headers[header.key] = header.value;
                        }
                    }
                    return headers;
                },

                // Check if a field has dependencies (required_if, required_when, etc.)
                hasDependency(param) {
                    const desc = param.description || '';
                    return desc.toLowerCase().includes('required if') ||
                           desc.toLowerCase().includes('required when') ||
                           desc.toLowerCase().includes('only if') ||
                           desc.toLowerCase().includes('required_if');
                },

                // Get dependency tooltip text
                getDependencyTooltip(param) {
                    const desc = param.description || '';
                    const match = desc.match(/(required if|required when|only if|required_if)[^.]+/i);
                    return match ? match[0] : 'This field has dependencies on other fields';
                },

                // Get placeholder for path/query parameters
                getParamPlaceholder(param) {
                    if (param.schema?.example) return String(param.schema.example);
                    if (param.schema?.type === 'integer') return 'e.g., 1';
                    return `Enter ${param.name}`;
                },

                // Get placeholder for body fields
                getFieldPlaceholder(field) {
                    if (field.example !== undefined && field.example !== null) return String(field.example);
                    if (field.description) return field.description;
                    return '';
                },

                // Pre-fill form with example values from spec
                prefillWithExamples() {
                    // Prefill path params
                    this.getPathParameters(this.selectedEndpoint).forEach(param => {
                        if (param.schema?.example) {
                            this.requestState.pathParams[param.name] = String(param.schema.example);
                        } else if (param.schema?.type === 'integer') {
                            this.requestState.pathParams[param.name] = '1';
                        }
                    });

                    // Prefill query params
                    this.getQueryParameters(this.selectedEndpoint).forEach(param => {
                        if (param.schema?.example) {
                            let value = String(param.schema.example);
                            // Format dates for inputs
                            if (param.schema?.format === 'date-time' || param.schema?.format === 'date') {
                                value = this.formatDateForInput(value, param.schema.format);
                            }
                            this.requestState.queryParams[param.name] = value;
                            this.requestState.queryParamsEnabled[param.name] = true;
                        } else if (param.schema?.enum?.[0]) {
                            this.requestState.queryParams[param.name] = param.schema.enum[0];
                            this.requestState.queryParamsEnabled[param.name] = true;
                        } else if (param.schema?.format === 'date-time' || param.schema?.format === 'date' || this.isDateFieldByName(param.name)) {
                            // Generate date value for query params
                            this.requestState.queryParams[param.name] = this.generateDateExample({ ...param.schema, name: param.name });
                            this.requestState.queryParamsEnabled[param.name] = true;
                        } else {
                            // Fallback: generate default based on type/name
                            const defaultVal = this.getDefaultValueForType({ type: param.schema?.type || 'string', name: param.name, format: param.schema?.format });
                            this.requestState.queryParams[param.name] = String(defaultVal);
                            this.requestState.queryParamsEnabled[param.name] = true;
                        }
                    });

                    // Always use field-based approach to handle dates properly
                    const fields = this.getRequestBodyFieldsForBuilder(this.selectedEndpoint);
                    const bodyExample = this.getBodyExample(this.selectedEndpoint);
                    this.prefillFieldsWithExamples(fields, this.requestState.body, '', bodyExample);

                    this.syncToRawJson();
                    this.showToast('Examples filled');
                },

                // Helper to prefill fields recursively (handles nested objects and dates)
                prefillFieldsWithExamples(fields, targetObj, prefix, bodyExample = null) {
                    fields.forEach(field => {
                        const fieldPath = prefix ? `${prefix}.${field.name}` : field.name;

                        // Check if we have a value from bodyExample
                        const exampleValue = bodyExample ? bodyExample[field.name] : undefined;

                        if (field.type === 'object' && field.nestedFields && field.nestedFields.length > 0) {
                            // Handle nested objects
                            if (!targetObj[field.name]) {
                                targetObj[field.name] = {};
                            }
                            const nestedExample = (typeof exampleValue === 'object' && exampleValue !== null) ? exampleValue : null;
                            this.prefillFieldsWithExamples(field.nestedFields, targetObj[field.name], field.name, nestedExample);
                            this.requestState.bodyFieldsEnabled[field.name] = true;
                        } else if (exampleValue !== undefined && exampleValue !== null) {
                            // Use value from bodyExample, but format dates for datetime-local input
                            let value = exampleValue;
                            if (this.isDateField(field)) {
                                value = this.formatDateForInput(value, field.format);
                            }
                            targetObj[field.name] = value;
                            this.requestState.bodyFieldsEnabled[fieldPath] = true;
                        } else if (field.example !== undefined && field.example !== null) {
                            // Use example from field schema, but format dates for datetime-local input
                            let value = field.example;
                            if (this.isDateField(field)) {
                                value = this.formatDateForInput(value, field.format);
                            }
                            targetObj[field.name] = value;
                            this.requestState.bodyFieldsEnabled[fieldPath] = true;
                        } else if (field.enum?.[0]) {
                            targetObj[field.name] = field.enum[0];
                            this.requestState.bodyFieldsEnabled[fieldPath] = true;
                        } else if (this.isDateField(field)) {
                            // Generate date value respecting constraints
                            targetObj[field.name] = this.generateDateExample(field);
                            this.requestState.bodyFieldsEnabled[fieldPath] = true;
                        } else {
                            // Fallback: generate default based on type/name
                            if (field.isArray) {
                                if (field.nestedFields && field.nestedFields.length > 0) {
                                    const item = {};
                                    field.nestedFields.forEach(nf => {
                                        item[nf.name] = this.getDefaultValueForType(nf);
                                    });
                                    targetObj[field.name] = [item];
                                } else {
                                    targetObj[field.name] = [this.getDefaultValueForType({ ...field, type: field.items?.type || 'string', name: field.name })];
                                }
                            } else {
                                targetObj[field.name] = this.getDefaultValueForType(field);
                            }
                            this.requestState.bodyFieldsEnabled[fieldPath] = true;
                        }
                    });
                },

                // Check if field name suggests it's a date field
                isDateFieldByName(name) {
                    const nameLower = name.toLowerCase();
                    if (nameLower.endsWith('_at') || nameLower.endsWith('_date') || nameLower === 'date') return true;
                    if (nameLower.includes('date') && !nameLower.includes('update')) return true;
                    return false;
                },

                // Check if a query parameter is a date field
                isQueryParamDateField(param) {
                    if (param.schema?.format === 'date-time' || param.schema?.format === 'date') return true;
                    return this.isDateFieldByName(param.name);
                },

                // Get response schema fields for a specific status code
                getResponseSchemaFields(endpoint, statusCode) {
                    if (!endpoint?.responses?.[statusCode]) return [];

                    const response = endpoint.responses[statusCode];
                    const schema = response?.content?.['application/json']?.schema;

                    if (!schema) return [];

                    // Handle $ref
                    if (schema.$ref) {
                        const refPath = schema.$ref.replace('#/components/schemas/', '');
                        const refSchema = this.spec.components?.schemas?.[refPath];
                        if (refSchema) {
                            return this.extractSchemaFields(refSchema, refSchema.required || []);
                        }
                        return [];
                    }

                    return this.extractSchemaFields(schema, schema.required || []);
                },

                // Render a recursive schema tree as HTML for response documentation
                renderSchemaTree(fields, expandedNodes, prefix, depth = 0, varName = 'expandedNodes') {
                    if (!fields || fields.length === 0) return '';
                    const indent = depth * 20;
                    let html = '';

                    fields.forEach((field, idx) => {
                        const nodeKey = prefix ? `${prefix}.${field.name}` : field.name;
                        const hasChildren = field.nestedFields && field.nestedFields.length > 0;
                        const isExpanded = expandedNodes[nodeKey];
                        const isLast = idx === fields.length - 1;
                        const escapedDesc = (field.description || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

                        // Status badge — for responses, nullable fields are shown as optional
                        // since Scramble marks all keys as "required" (key always present) even when value can be null
                        const effectiveRequired = field.required && !field.nullable;
                        let statusBadge = '';
                        if (effectiveRequired) {
                            statusBadge = '<span class="px-1 py-0.5 text-[9px] font-semibold rounded" style="background:var(--ide-required-bg);color:var(--ide-required-text)">req</span>';
                        } else if (field.nullable) {
                            statusBadge = '<span class="px-1 py-0.5 text-[9px] font-medium rounded" style="background:var(--ide-nullable-bg);color:var(--ide-nullable-text)">nullable</span>';
                        } else {
                            statusBadge = '<span class="px-1 py-0.5 text-[9px] font-medium rounded" style="background:var(--ide-optional-bg);color:var(--ide-optional-text)">opt</span>';
                        }

                        // Type badge color
                        let typeStyle = 'background:var(--ide-border);color:var(--ide-muted)';
                        const ft = field.type?.toLowerCase() || '';
                        if (ft.includes('object') || ft.includes('[]')) typeStyle = 'background:var(--ide-type-object-bg);color:var(--ide-type-object-text)';
                        else if (ft === 'string') typeStyle = 'background:var(--ide-type-string-bg);color:var(--ide-type-string-text)';
                        else if (ft === 'integer' || ft === 'number') typeStyle = 'background:var(--ide-type-number-bg);color:var(--ide-type-number-text)';
                        else if (ft === 'boolean') typeStyle = 'background:var(--ide-type-boolean-bg);color:var(--ide-type-boolean-text)';

                        // Row
                        const borderClass = (isLast && !isExpanded) ? '' : 'border-b border-ide-border';
                        html += `<div class="grid grid-cols-[minmax(180px,2fr)_100px_60px_1fr] gap-2 px-3 py-1.5 text-code ${borderClass} hover:bg-ide-surface/50 transition-colors" style="padding-left: ${12 + indent}px">`;

                        // Property name column
                        html += '<div class="flex items-center gap-1 min-w-0">';
                        if (hasChildren) {
                            html += `<button onclick="let en = Alpine.$data(this.closest('[x-data]')).${varName}; en['${nodeKey.replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'] = !en['${nodeKey.replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'];" class="p-0.5 rounded hover:bg-ide-border transition-colors flex-shrink-0">`;
                            html += `<svg class="w-3 h-3 text-ide-muted transition-transform ${isExpanded ? 'rotate-90' : ''}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;
                            html += '</button>';
                        } else {
                            html += '<span class="w-4 flex-shrink-0"></span>';
                        }
                        html += `<span class="font-mono text-ide-primary truncate">${this.escapeHtml(field.name)}</span>`;
                        html += '</div>';

                        // Type column
                        html += `<div><span class="px-1.5 py-0.5 rounded text-[10px] font-medium whitespace-nowrap" style="${typeStyle}">${this.escapeHtml(field.type)}</span></div>`;

                        // Status column
                        html += `<div class="flex items-center gap-0.5">${statusBadge}</div>`;

                        // Description column
                        let descHtml = escapedDesc || '<span class="text-ide-muted opacity-50">-</span>';
                        if (field.enum && field.enum.length > 0) {
                            const enumStr = field.enum.map(e => `<code class="px-1 py-0.5 bg-ide-border rounded text-[10px]">${this.escapeHtml(String(e))}</code>`).join(' ');
                            descHtml += (escapedDesc ? ' ' : '') + enumStr;
                        }
                        if (field.constraints) {
                            descHtml += ` <span class="text-[10px] text-ide-muted">(${this.escapeHtml(field.constraints)})</span>`;
                        }
                        if (field.example !== undefined && field.example !== null) {
                            const exStr = typeof field.example === 'object' ? JSON.stringify(field.example) : String(field.example);
                            descHtml += ` <span class="text-[10px] text-ide-muted italic">e.g. ${exStr.replace(/</g, '&lt;').substring(0, 50)}</span>`;
                        }
                        html += `<div class="text-ide-muted truncate">${descHtml}</div>`;

                        html += '</div>';

                        // Render children if expanded
                        if (hasChildren && isExpanded) {
                            html += this.renderSchemaTree(field.nestedFields, expandedNodes, nodeKey, depth + 1, varName);
                        }
                    });

                    return html;
                },

                // Check if array items are date/datetime
                isArrayItemDate(field) {
                    if (field.arrayItemFormat === 'date-time' || field.arrayItemFormat === 'date') return true;
                    return false;
                },

                // Check if a field is a date/datetime field
                isDateField(field) {
                    if (field.format === 'date-time' || field.format === 'date') return true;
                    if (field.constraints && (field.constraints.includes('date-time') || field.constraints.includes('date'))) return true;
                    // Check by field name pattern
                    const nameLower = field.name.toLowerCase();
                    if (nameLower.endsWith('_at') || nameLower.endsWith('_date') || nameLower === 'date') return true;
                    if (nameLower.includes('date') && !nameLower.includes('update')) return true;
                    return false;
                },

                // Generate a date example respecting min/max constraints
                generateDateExample(field) {
                    const now = new Date();
                    let targetDate = now;

                    // Parse min/max constraints
                    const minDate = field.minimum ? new Date(field.minimum) : null;
                    const maxDate = field.maximum ? new Date(field.maximum) : null;

                    // Determine the appropriate date
                    if (minDate && maxDate) {
                        // Use middle point between min and max
                        targetDate = new Date((minDate.getTime() + maxDate.getTime()) / 2);
                    } else if (minDate) {
                        // Use min date + 1 day to be safely after minimum
                        targetDate = new Date(minDate.getTime() + 24 * 60 * 60 * 1000);
                    } else if (maxDate) {
                        // Use max date - 1 day to be safely before maximum
                        targetDate = new Date(maxDate.getTime() - 24 * 60 * 60 * 1000);
                    } else {
                        // Check field name for hints
                        const nameLower = field.name.toLowerCase();
                        if (nameLower.includes('start') || nameLower.includes('from') || nameLower.includes('begin')) {
                            // Start dates: use beginning of current month
                            targetDate = new Date(now.getFullYear(), now.getMonth(), 1);
                        } else if (nameLower.includes('end') || nameLower.includes('to') || nameLower.includes('until') || nameLower.includes('expir')) {
                            // End dates: use end of current month
                            targetDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                        } else if (nameLower.includes('birth') || nameLower.includes('dob')) {
                            // Birth date: use a reasonable past date
                            targetDate = new Date(1990, 0, 15);
                        }
                        // Otherwise use current date (default)
                    }

                    return this.formatDateForInput(targetDate, field.format);
                },

                // Format a date value for datetime-local or date input
                formatDateForInput(value, format) {
                    let date;
                    if (value instanceof Date) {
                        date = value;
                    } else if (typeof value === 'string') {
                        date = new Date(value);
                    } else {
                        date = new Date();
                    }

                    if (isNaN(date.getTime())) {
                        date = new Date(); // Fallback to now if invalid
                    }

                    if (format === 'date') {
                        // YYYY-MM-DD format for date input
                        return date.toISOString().split('T')[0];
                    } else {
                        // YYYY-MM-DDTHH:mm format for datetime-local input
                        return date.toISOString().slice(0, 16);
                    }
                },

                // Generate a sensible default value based on field type and name
                getDefaultValueForType(field) {
                    const type = field.type || 'string';
                    const name = (field.name || '').toLowerCase();
                    const format = field.format || '';

                    // Handle format-based defaults
                    if (format === 'email' || name.includes('email')) return 'user@example.com';
                    if (format === 'uuid') return '550e8400-e29b-41d4-a716-446655440000';
                    if (format === 'uri' || format === 'url' || name.includes('url') || name.includes('link')) return 'https://example.com';
                    if (format === 'date-time' || format === 'date') return this.generateDateExample(field);

                    // Handle name-based guesses for strings
                    if (type.includes('string')) {
                        if (name.includes('phone') || name.includes('mobile')) return '+1234567890';
                        if (name.includes('name') && name.includes('first')) return 'John';
                        if (name.includes('name') && name.includes('last')) return 'Doe';
                        if (name === 'name' || name.includes('username')) return 'example_name';
                        if (name.includes('password') || name.includes('secret')) return 'password123';
                        if (name.includes('description') || name.includes('note') || name.includes('comment')) return 'Sample text';
                        if (name.includes('color') || name.includes('colour')) return '#FF5733';
                        if (name.includes('address')) return '123 Main St';
                        if (name.includes('city')) return 'New York';
                        if (name.includes('country')) return 'US';
                        if (name.includes('zip') || name.includes('postal')) return '10001';
                        if (name.includes('currency')) return 'USD';
                        if (name.includes('icon') || name.includes('emoji')) return 'star';
                        if (name.includes('status')) return 'active';
                        if (name.includes('type') || name.includes('kind') || name.includes('category')) return 'default';
                        if (name.includes('slug')) return 'example-slug';
                        if (name.includes('code')) return 'ABC123';
                        if (name.includes('title')) return 'Sample Title';
                        if (name.includes('tag')) return 'tag1';
                        return 'string';
                    }

                    if (type.includes('integer') || type.includes('number')) {
                        if (name.includes('amount') || name.includes('price') || name.includes('total') || name.includes('balance')) return 100;
                        if (name.includes('quantity') || name.includes('count') || name.includes('limit') || name.includes('per_page')) return 10;
                        if (name.includes('page')) return 1;
                        if (name.includes('year')) return new Date().getFullYear();
                        if (name.includes('month')) return new Date().getMonth() + 1;
                        if (name.includes('day')) return new Date().getDate();
                        if (name.includes('age')) return 25;
                        if (name.includes('percent') || name.includes('rate')) return 50;
                        if (name.includes('id')) return 1;
                        if (name.includes('order') || name.includes('sort') || name.includes('position')) return 0;
                        return type.includes('number') ? 1.0 : 1;
                    }

                    if (type.includes('boolean')) return true;

                    return 'string';
                },

                // Get body example from spec
                getBodyExample(endpoint) {
                    if (!endpoint?.requestBody) return null;
                    const content = endpoint.requestBody.content;
                    return content?.['application/json']?.example
                        || content?.['multipart/form-data']?.example
                        || null;
                },

                // Sync form state to raw JSON
                syncToRawJson() {
                    const body = this.builtRequestBody;
                    this.rawJsonBody = JSON.stringify(body, null, 2);
                },

                // Sync raw JSON to form state
                syncFromRawJson() {
                    try {
                        const parsed = JSON.parse(this.rawJsonBody);
                        this.jsonParseError = null;

                        // Get known schema field names
                        const schemaFields = this.selectedEndpoint ? this.getRequestBodyFieldsForBuilder(this.selectedEndpoint).map(f => f.name) : [];

                        for (const [key, value] of Object.entries(parsed)) {
                            if (schemaFields.includes(key)) {
                                // Known schema field - update form state
                                if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
                                    if (!this.requestState.body[key]) {
                                        this.requestState.body[key] = {};
                                    }
                                    for (const [nestedKey, nestedValue] of Object.entries(value)) {
                                        this.requestState.body[key][nestedKey] = nestedValue;
                                        this.requestState.bodyFieldsEnabled[`${key}.${nestedKey}`] = true;
                                    }
                                    this.requestState.bodyFieldsEnabled[key] = true;
                                } else {
                                    this.requestState.body[key] = value;
                                    this.requestState.bodyFieldsEnabled[key] = true;
                                }
                            } else {
                                // Unknown field - add as custom field if not already present
                                if (!this.requestState.customFields) this.requestState.customFields = [];
                                const existing = this.requestState.customFields.find(f => f.name === key);
                                if (existing) {
                                    existing.value = typeof value === 'object' ? JSON.stringify(value) : String(value);
                                    existing.enabled = true;
                                } else {
                                    let type = 'string';
                                    let strValue = String(value);
                                    if (typeof value === 'boolean') { type = 'boolean'; strValue = String(value); }
                                    else if (typeof value === 'number') { type = Number.isInteger(value) ? 'integer' : 'number'; strValue = String(value); }
                                    else if (Array.isArray(value)) { type = 'array'; strValue = JSON.stringify(value); }
                                    else if (typeof value === 'object' && value !== null) { type = 'object'; strValue = JSON.stringify(value); }
                                    this.requestState.customFields.push({ id: Date.now() + Math.random(), name: key, value: strValue, type, enabled: true });
                                }
                            }
                        }
                    } catch (e) {
                        this.jsonParseError = 'Invalid JSON: ' + e.message;
                    }
                },

                // Build the complete URL with path and query params
                get builtUrl() {
                    if (!this.selectedEndpoint) return '';

                    let path = this.selectedEndpoint.path;

                    // Replace path parameters
                    for (const [name, value] of Object.entries(this.requestState.pathParams)) {
                        path = path.replace(`{${name}}`, encodeURIComponent(value || `{${name}}`));
                    }

                    // Build query string
                    const queryParts = [];
                    for (const [name, value] of Object.entries(this.requestState.queryParams)) {
                        if (this.requestState.queryParamsEnabled[name] && value !== '') {
                            queryParts.push(`${encodeURIComponent(name)}=${encodeURIComponent(value)}`);
                        }
                    }

                    const baseUrl = this.getBaseUrl();
                    const queryString = queryParts.length > 0 ? '?' + queryParts.join('&') : '';

                    return baseUrl + path + queryString;
                },

                // Build the request body from form state
                get builtRequestBody() {
                    if (!this.hasRequestBody(this.selectedEndpoint)) return null;

                    const body = {};

                    for (const [fieldName, value] of Object.entries(this.requestState.body)) {
                        if (!this.requestState.bodyFieldsEnabled[fieldName]) continue;

                        // Skip file fields — they go via FormData, not JSON
                        if (this.requestState.bodyFiles && this.requestState.bodyFiles[fieldName] !== undefined) continue;

                        if (Array.isArray(value)) {
                            // Array of objects: build each entry, filter empty values
                            if (value.length > 0 && typeof value[0] === 'object' && value[0] !== null && !Array.isArray(value[0])) {
                                const entries = value.map(entry => {
                                    const obj = {};
                                    for (const [k, v] of Object.entries(entry)) {
                                        if (v !== '' && v !== null && v !== undefined) {
                                            obj[k] = this.parseValue(v);
                                        }
                                    }
                                    return obj;
                                }).filter(obj => Object.keys(obj).length > 0);
                                if (entries.length > 0) {
                                    body[fieldName] = entries;
                                }
                            } else {
                                // Primitive array: filter out empty strings and pass through
                                const filtered = value.filter(v => v !== '' && v !== null && v !== undefined);
                                if (filtered.length > 0) {
                                    body[fieldName] = filtered;
                                }
                            }
                        } else if (typeof value === 'object' && value !== null) {
                            const nestedObj = {};
                            let hasEnabledFields = false;

                            for (const [nestedName, nestedValue] of Object.entries(value)) {
                                if (this.requestState.bodyFieldsEnabled[`${fieldName}.${nestedName}`]) {
                                    if (nestedValue !== '' && nestedValue !== null) {
                                        nestedObj[nestedName] = this.parseValue(nestedValue);
                                        hasEnabledFields = true;
                                    }
                                }
                            }

                            if (hasEnabledFields) {
                                body[fieldName] = nestedObj;
                            }
                        } else if (value !== '' && value !== null) {
                            body[fieldName] = this.parseValue(value);
                        }
                    }

                    // Add custom fields
                    if (this.requestState.customFields) {
                        this.requestState.customFields.forEach(field => {
                            if (field.enabled && field.name && field.type !== 'file') {
                                let value = field.value;
                                if (field.type === 'integer') value = parseInt(value) || 0;
                                else if (field.type === 'number') value = parseFloat(value) || 0;
                                else if (field.type === 'boolean') value = field.value === 'true';
                                else if (field.type === 'array' || field.type === 'object') {
                                    try { value = JSON.parse(value); } catch(e) { /* keep as string */ }
                                }
                                body[field.name] = value;
                            }
                        });
                    }

                    return body;
                },

                // Parse string value to appropriate type
                parseValue(value) {
                    if (typeof value === 'string') {
                        if (value === 'true') return true;
                        if (value === 'false') return false;
                        if (/^-?\d+$/.test(value)) return parseInt(value, 10);
                        if (/^-?\d+\.\d+$/.test(value)) return parseFloat(value);
                    }
                    return value;
                },

                // Copy request as JSON
                async copyAsJson() {
                    const token = this.authToken || '{your-token}';
                    const data = {
                        method: this.selectedEndpoint.method,
                        url: this.builtUrl,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    };

                    if (this.hasRequestBody(this.selectedEndpoint)) {
                        data.body = this.builtRequestBody;
                    }

                    await navigator.clipboard.writeText(JSON.stringify(data, null, 2));
                    this.showToast('Copied as JSON');
                },

                // Copy request as cURL command
                async copyCurlCommand() {
                    let curl = `curl -X ${this.selectedEndpoint.method} "${this.builtUrl}"`;
                    curl += ` \\\n  -H "Content-Type: application/json"`;
                    curl += ` \\\n  -H "Accept: application/json"`;
                    const token = this.authToken || '{your-token}';
                    curl += ` \\\n  -H "Authorization: Bearer ${token}"`;

                    // Add custom headers
                    for (const header of this.requestState.customHeaders) {
                        if (header.enabled && header.key.trim()) {
                            curl += ` \\\n  -H "${header.key}: ${header.value}"`;
                        }
                    }

                    if (this.hasRequestBody(this.selectedEndpoint) && this.builtRequestBody) {
                        const bodyStr = JSON.stringify(this.builtRequestBody);
                        curl += ` \\\n  -d '${bodyStr}'`;
                    }

                    await navigator.clipboard.writeText(curl);
                    this.showToast('Copied as cURL');
                },

                // Generate cURL for a flow step from dialog data
                generateFlowStepCurl(dialog) {
                    const method = (dialog.method || 'GET').toUpperCase();
                    const url = dialog.url || '';
                    let curl = `curl -X ${method} "${url}"`;

                    const headers = dialog.headers || {};
                    if (!headers['Content-Type'] && method !== 'GET' && method !== 'HEAD') {
                        curl += ` \\\n  -H "Content-Type: application/json"`;
                    }
                    if (!headers['Accept']) {
                        curl += ` \\\n  -H "Accept: application/json"`;
                    }
                    for (const [key, value] of Object.entries(headers)) {
                        curl += ` \\\n  -H "${key}: ${value}"`;
                    }
                    if (!headers['Authorization'] && this.authToken) {
                        curl += ` \\\n  -H "Authorization: Bearer ${this.authToken}"`;
                    }

                    if (dialog.body && method !== 'GET' && method !== 'HEAD') {
                        const bodyStr = typeof dialog.body === 'string' ? dialog.body : JSON.stringify(dialog.body);
                        curl += ` \\\n  -d '${bodyStr}'`;
                    }

                    return curl;
                },

                // Generate fetch code for a flow step from dialog data
                generateFlowStepFetch(dialog, lang) {
                    const method = (dialog.method || 'GET').toUpperCase();
                    const url = dialog.url || '';
                    const headers = { ...dialog.headers };
                    if (!headers['Accept']) headers['Accept'] = 'application/json';
                    if (!headers['Authorization'] && this.authToken) headers['Authorization'] = `Bearer ${this.authToken}`;
                    if (!headers['Content-Type'] && dialog.body && method !== 'GET' && method !== 'HEAD') {
                        headers['Content-Type'] = 'application/json';
                    }

                    const headersStr = Object.entries(headers).map(([k, v]) => `    "${k}": "${v}"`).join(',\n');
                    const hasBody = dialog.body && method !== 'GET' && method !== 'HEAD';
                    const bodyStr = hasBody
                        ? (typeof dialog.body === 'string' ? dialog.body : JSON.stringify(dialog.body, null, 2))
                        : null;

                    let code = '';
                    if (lang === 'ts') {
                        code += `const response: Response = await fetch("${url}", {\n`;
                    } else {
                        code += `const response = await fetch("${url}", {\n`;
                    }
                    code += `  method: "${method}",\n`;
                    code += `  headers: {\n${headersStr}\n  }`;
                    if (hasBody) {
                        code += `,\n  body: JSON.stringify(${bodyStr})`;
                    }
                    code += `\n});\n\n`;
                    if (lang === 'ts') {
                        code += `const data: Record<string, unknown> = await response.json();`;
                    } else {
                        code += `const data = await response.json();`;
                    }

                    return code;
                },

                // Build flowResponseDialog data from a step result at index
                openFlowResponseDialog(index) {
                    const result = this.flowRunResults[index];
                    if (!result) return;
                    const step = this.getCurrentFlow().steps[index];
                    if (!step) return;

                    // Build the URL with path params resolved
                    let url = this.getBaseUrl() + (step.endpoint?.path || '');
                    if (step.pathParams) {
                        for (const [key, value] of Object.entries(step.pathParams)) {
                            url = url.replace(`{${key}}`, encodeURIComponent(value));
                        }
                    }
                    // Add query params
                    const params = new URLSearchParams();
                    for (const [key, value] of Object.entries(step.params || {})) {
                        if (value !== '' && value !== null && value !== undefined) params.append(key, value);
                    }
                    if (params.toString()) url += '?' + params.toString();

                    // Build headers
                    const defaultHeaders = this.getCurrentFlow().defaultHeaders || {};
                    const headers = { 'Accept': 'application/json', ...defaultHeaders, ...(step.headers || {}) };
                    if (this.authToken && !headers['Authorization']) headers['Authorization'] = `Bearer ${this.authToken}`;

                    // Get body
                    const bodyMode = step.bodyMode || 'form';
                    let body = null;
                    if (!['GET', 'HEAD'].includes((step.endpoint?.method || '').toUpperCase())) {
                        if (bodyMode === 'json') {
                            try { body = JSON.parse(step.bodyJson || '{}'); } catch { body = step.body || {}; }
                        } else if (bodyMode === 'raw') {
                            body = step.rawBody || '';
                        } else {
                            body = step.body && Object.keys(step.body).length > 0 ? step.body : null;
                        }
                    }

                    this.flowResponseDialog = {
                        show: true, step: result.step, name: result.name,
                        status: result.status, duration: result.duration,
                        data: result.data, success: result.success, httpOk: result.httpOk,
                        method: (step.endpoint?.method || 'GET').toUpperCase(),
                        url, headers, body, bodyMode
                    };
                },

                // Show toast notification (stacking support)
                showToast(message, type = 'success') {
                    const id = ++this.toastId;
                    this.toasts.push({ id, message, type, exiting: false });

                    // Auto-dismiss after 3 seconds
                    setTimeout(() => {
                        this.dismissToast(id);
                    }, 3000);

                    // Limit to 5 toasts max
                    if (this.toasts.length > 5) {
                        const oldestId = this.toasts[0].id;
                        this.dismissToast(oldestId);
                    }
                },

                // Dismiss a specific toast
                dismissToast(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) {
                        toast.exiting = true;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 200);
                    }
                },

                // ============ URL HASH PERSISTENCE ============

                // Update URL hash when endpoint changes
                updateHash(endpoint) {
                    if (!endpoint) {
                        history.replaceState(null, '', window.location.pathname);
                        return;
                    }
                    const hash = `#${endpoint.path}/${endpoint.method.toLowerCase()}`;
                    // Use pushState so browser back/forward works
                    if (window.location.hash !== hash) {
                        history.pushState(null, '', hash);
                    }
                },

                // Restore endpoint from URL hash
                restoreFromHash() {
                    const hash = window.location.hash;
                    if (!hash || hash === '#') return;

                    const hashContent = hash.substring(1);

                    // Check for flow hash: #flow/{id}
                    const flowMatch = hashContent.match(/^flow\/(\d+)$/);
                    if (flowMatch) {
                        const flowId = parseInt(flowMatch[1]);
                        this.restoreFlowFromHash(flowId);
                        return;
                    }

                    // Parse hash: #/v1/transactions/post
                    const parts = hashContent.split('/');
                    if (parts.length < 2) return;

                    const method = parts.pop().toUpperCase();
                    // parts.join('/') already includes the leading slash since
                    // splitting '/v1/assets' produces ['', 'v1', 'assets']
                    const path = parts.join('/') || '/';

                    // Find matching endpoint
                    const endpoint = this.endpoints.find(e =>
                        e.path === path && e.method === method
                    );

                    if (endpoint) {
                        // Expand the tag containing this endpoint
                        const tag = endpoint.tags?.[0] || 'Untagged';
                        this.expandedTags[tag] = true;
                        // Select without updating hash again
                        this.selectEndpoint(endpoint, false);
                        // Restore saved state after selecting from hash
                        this.restoreEndpointState();
                    }
                },

                // Restore a flow from URL hash after loading flows
                async restoreFlowFromHash(flowId) {
                    if (!this.flows || this.flows.length === 0) {
                        await this.loadFlows();
                    }
                    const flow = this.flows.find(f => f.id === flowId);
                    if (flow) {
                        this.loadFlow(flow);
                    }
                },

                // ============ STATE PERSISTENCE ============

                // Generate storage key for an endpoint
                getEndpointStorageKey(endpoint) {
                    if (!endpoint) return null;
                    // Create a safe key: method_path (replace special chars)
                    const safePath = endpoint.path.replace(/[^a-zA-Z0-9]/g, '_');
                    return `apiura_state_${endpoint.method}_${safePath}`;
                },

                // Save current request state and response to localStorage
                saveEndpointState() {
                    if (!this.selectedEndpoint) return;

                    const key = this.getEndpointStorageKey(this.selectedEndpoint);
                    if (!key) return;

                    const state = {
                        requestState: this.requestState,
                        apiResponse: this.apiResponse,
                        apiError: this.apiError,
                        rawJsonMode: this.rawJsonMode,
                        rawJsonBody: this.rawJsonBody,
                        savedAt: new Date().toISOString()
                    };

                    try {
                        localStorage.setItem(key, JSON.stringify(state));
                    } catch (e) {
                        console.warn('Failed to save endpoint state:', e);
                    }
                },

                // Restore saved state for current endpoint
                restoreEndpointState() {
                    if (!this.selectedEndpoint) return false;

                    const key = this.getEndpointStorageKey(this.selectedEndpoint);
                    if (!key) return false;

                    try {
                        const saved = localStorage.getItem(key);
                        if (!saved) return false;

                        const state = JSON.parse(saved);

                        // Restore request state
                        if (state.requestState) {
                            // Merge with initialized state to handle new fields
                            this.requestState = {
                                ...this.requestState,
                                ...state.requestState
                            };
                        }

                        // Restore response
                        if (state.apiResponse) {
                            this.apiResponse = state.apiResponse;
                        }

                        // Restore error
                        if (state.apiError) {
                            this.apiError = state.apiError;
                        }

                        // Restore raw JSON mode
                        if (state.rawJsonMode !== undefined) {
                            this.rawJsonMode = state.rawJsonMode;
                        }

                        if (state.rawJsonBody) {
                            this.rawJsonBody = state.rawJsonBody;
                        }

                        // Sync raw JSON if in raw mode
                        if (!this.rawJsonMode) {
                            this.syncToRawJson();
                        }

                        return true;
                    } catch (e) {
                        console.warn('Failed to restore endpoint state:', e);
                        return false;
                    }
                },

                // Clear saved state for current endpoint
                clearEndpointState() {
                    if (!this.selectedEndpoint) return;

                    const key = this.getEndpointStorageKey(this.selectedEndpoint);
                    if (key) {
                        localStorage.removeItem(key);
                    }

                    // Reset to fresh state
                    this.initRequestState();
                    this.apiResponse = null;
                    this.apiError = null;
                    this.showToast('Request state cleared', 'info');
                },

                // Debounced save - saves after 500ms of no changes
                _saveTimeout: null,
                debouncedSaveState() {
                    if (this._saveTimeout) {
                        clearTimeout(this._saveTimeout);
                    }
                    this._saveTimeout = setTimeout(() => {
                        this.saveEndpointState();
                    }, 500);
                },

                // Call this on input changes to auto-save
                onInputChange() {
                    this.debouncedSaveState();
                },

                // ============ KEYBOARD SHORTCUTS ============



                // ============ ENVIRONMENT METHODS ============

                switchEnvironment(envId) {
                    this.activeEnvironment = envId;
                    localStorage.setItem('apiura_active_env', envId);
                    const env = this.environments.find(e => e.id === envId);
                    if (env) {
                        if (env.baseUrl) this.customBaseUrl = env.baseUrl;
                        if (env.token) this.authToken = env.token;
                    }
                },

                saveEnvironments() {
                    localStorage.setItem('apiura_environments', JSON.stringify(this.environments));
                },

                addEnvironment() {
                    const id = this.newEnv.name.toLowerCase().replace(/\s+/g, '-') + '-' + Date.now();
                    this.environments.push({ id, ...this.newEnv });
                    this.saveEnvironments();
                    this.newEnv = { name: '', baseUrl: '', token: '', headers: {}, color: 'green' };
                    this.showEnvModal = false;
                },

                updateEnvironment(envId, field, value) {
                    const env = this.environments.find(e => e.id === envId);
                    if (env) {
                        env[field] = value;
                        this.saveEnvironments();
                        if (this.activeEnvironment === envId) this.switchEnvironment(envId);
                    }
                },

                deleteEnvironment(envId) {
                    this.environments = this.environments.filter(e => e.id !== envId);
                    this.saveEnvironments();
                    if (this.activeEnvironment === envId) this.activeEnvironment = null;
                },

                getEnvColor(color) {
                    const colors = {
                        green: 'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)] border-[var(--ide-success-text)]/30',
                        yellow: 'bg-[var(--ide-warning-bg)] text-[var(--ide-warning-text)] border-[var(--ide-warning-text)]/30',
                        red: 'bg-[var(--ide-error-bg)] text-[var(--ide-error-text)] border-[var(--ide-error-text)]/30',
                        blue: 'bg-[var(--ide-info-bg)] text-[var(--ide-info-text)] border-[var(--ide-info-text)]/30',
                        purple: 'bg-ide-primary/10 text-ide-primary border-ide-primary/30',
                    };
                    return colors[color] || colors.green;
                },

                // ============ IMPORT CURL METHODS ============

                parseCurlCommand() {
                    this.curlParseError = '';
                    try {
                        const curl = this.curlInput.trim();
                        if (!curl.startsWith('curl')) {
                            this.curlParseError = 'Input must start with "curl"';
                            return;
                        }

                        let method = 'GET';
                        let url = '';
                        let headers = {};
                        let body = null;

                        const methodMatch = curl.match(/-X\s+['"]?(\w+)['"]?/);
                        if (methodMatch) method = methodMatch[1].toUpperCase();

                        const urlMatch = curl.match(/curl\s+(?:-[^\s]+\s+)*['"]?(https?:\/\/[^\s'"]+)['"]?/) || curl.match(/['"]?(https?:\/\/[^\s'"]+)['"]?/);
                        if (urlMatch) url = urlMatch[1];

                        const headerRegex = /-H\s+['"]([^'"]+)['"]/g;
                        let match;
                        while ((match = headerRegex.exec(curl)) !== null) {
                            const [key, ...valueParts] = match[1].split(':');
                            if (key && valueParts.length > 0) {
                                headers[key.trim()] = valueParts.join(':').trim();
                            }
                        }

                        const bodyMatch = curl.match(/(?:-d|--data|--data-raw|--data-binary)\s+['"](.+?)['"]\s/s) || curl.match(/(?:-d|--data|--data-raw|--data-binary)\s+['"](.+?)['"]$/s);
                        if (bodyMatch) {
                            body = bodyMatch[1];
                            if (!methodMatch) method = 'POST';
                        }

                        if (!url) {
                            this.curlParseError = 'Could not parse URL from cURL command';
                            return;
                        }

                        const urlObj = new URL(url);
                        const path = urlObj.pathname;

                        let matched = false;
                        if (this.spec?.paths) {
                            for (const [specPath, methods] of Object.entries(this.spec.paths)) {
                                const regex = new RegExp('^' + specPath.replace(/\{[^}]+\}/g, '[^/]+') + '$');
                                if (regex.test(path) && methods[method.toLowerCase()]) {
                                    for (const [tag, endpoints] of Object.entries(this.endpointsByTag || {})) {
                                        const ep = endpoints.find(e => e.method === method.toLowerCase() && e.path === specPath);
                                        if (ep) {
                                            this.selectEndpoint(ep);
                                            matched = true;
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                        }

                        if (Object.keys(headers).length > 0) {
                            if (headers['Authorization']) {
                                this.authToken = headers['Authorization'].replace('Bearer ', '');
                                delete headers['Authorization'];
                            }
                            this.requestState.customHeaders = Object.entries(headers).map(([key, value], idx) => ({ id: idx + 1, key, value, enabled: true }));
                        }

                        if (body) {
                            try {
                                this.requestState.body = JSON.parse(body);
                                this.rawJsonMode = false;
                            } catch (e) {
                                this.rawJsonBody = body;
                                this.rawJsonMode = true;
                            }
                        }

                        if (urlObj.search) {
                            const params = new URLSearchParams(urlObj.search);
                            const qp = {};
                            const qpe = {};
                            params.forEach((value, key) => {
                                qp[key] = value;
                                qpe[key] = true;
                            });
                            this.requestState.queryParams = qp;
                            this.requestState.queryParamsEnabled = qpe;
                        }

                        this.showImportCurl = false;
                        this.curlInput = '';
                    } catch (e) {
                        this.curlParseError = 'Failed to parse cURL: ' + e.message;
                    }
                },

                // ============ TELESCOPE METHODS ============

                formatHistoryTime(timestamp) {
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diff = now - date;
                    if (diff < 60000) return 'Just now';
                    if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
                    if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
                    return date.toLocaleDateString();
                },

                async loadTelescopeEntries(reset = false) {
                    if (this.telescopeLoading) return;
                    if (reset) {
                        this.telescopePage = 1;
                        this.telescopeEntries = [];
                    }
                    this.telescopeLoading = true;
                    try {
                        const params = new URLSearchParams({
                            page: this.telescopePage,
                            per_page: 30,
                        });
                        if (this.telescopeFilter) params.set('search', this.telescopeFilter);
                        if (this.telescopeMethodFilter) params.set('method', this.telescopeMethodFilter);

                        const resp = await fetch(`/apiura/telescope?${params}`);
                        if (!resp.ok) throw new Error('Failed to load telescope entries');
                        const data = await resp.json();
                        if (reset) {
                            this.telescopeEntries = data.entries;
                        } else {
                            this.telescopeEntries = [...this.telescopeEntries, ...data.entries];
                        }
                        this.telescopeHasMore = data.has_more;
                        this.telescopeTotal = data.total;
                    } catch (e) {
                        this.showToast('Failed to load Telescope entries', 'error');
                    } finally {
                        this.telescopeLoading = false;
                    }
                },

                loadMoreTelescope() {
                    this.telescopePage++;
                    this.loadTelescopeEntries(false);
                },

                telescopeSearch() {
                    this.loadTelescopeEntries(true);
                },

                setTelescopeMethodFilter(method) {
                    this.telescopeMethodFilter = method;
                    this.loadTelescopeEntries(true);
                },

                matchUriToEndpoint(method, uri) {
                    // Strip query string from URI
                    const path = uri.split('?')[0];
                    const normalizedMethod = method.toLowerCase();

                    if (!this.spec?.paths) return null;

                    for (const [specPath, methods] of Object.entries(this.spec.paths)) {
                        if (!methods[normalizedMethod]) continue;
                        const regex = new RegExp('^' + specPath.replace(/\{[^}]+\}/g, '[^/]+') + '$');
                        if (regex.test(path)) {
                            for (const [tag, endpoints] of Object.entries(this.endpointsByTag || {})) {
                                const ep = endpoints.find(e => e.method === normalizedMethod && e.path === specPath);
                                if (ep) return { endpoint: ep, specPath };
                            }
                        }
                    }
                    return null;
                },

                extractPathParams(specPath, actualUri) {
                    const uriPath = actualUri.split('?')[0];
                    const specParts = specPath.split('/');
                    const uriParts = uriPath.split('/');
                    const params = {};
                    specParts.forEach((part, i) => {
                        const match = part.match(/^\{(.+)\}$/);
                        if (match && uriParts[i]) {
                            params[match[1]] = decodeURIComponent(uriParts[i]);
                        }
                    });
                    return params;
                },

                async loadTelescopeEntry(uuid) {
                    try {
                        const resp = await fetch(`/apiura/telescope/${uuid}`);
                        if (!resp.ok) throw new Error('Failed to load telescope entry');
                        const data = await resp.json();

                        // Match to endpoint
                        const match = this.matchUriToEndpoint(data.method, data.uri);
                        if (match) {
                            this.selectEndpoint(match.endpoint);

                            // Wait for state to initialize
                            await this.$nextTick();

                            // Populate path params
                            const pathParams = this.extractPathParams(match.specPath, data.uri);
                            for (const [key, value] of Object.entries(pathParams)) {
                                if (this.requestState.pathParams.hasOwnProperty(key)) {
                                    this.requestState.pathParams[key] = value;
                                }
                            }

                            // Populate query params
                            if (data.query && typeof data.query === 'object') {
                                for (const [key, value] of Object.entries(data.query)) {
                                    this.requestState.queryParams[key] = typeof value === 'object' ? JSON.stringify(value) : String(value);
                                    this.requestState.queryParamsEnabled[key] = true;
                                }
                            }

                            // Populate body
                            if (data.payload && typeof data.payload === 'object' && Object.keys(data.payload).length > 0) {
                                for (const [key, value] of Object.entries(data.payload)) {
                                    if (this.requestState.body.hasOwnProperty(key)) {
                                        this.requestState.body[key] = value;
                                        this.requestState.bodyFieldsEnabled[key] = true;
                                    }
                                }
                                this.syncToRawJson();
                            }

                            // Populate custom headers from telescope data
                            if (data.request_headers && typeof data.request_headers === 'object') {
                                const skipHeaders = ['host', 'connection', 'accept', 'user-agent', 'accept-encoding', 'accept-language', 'cookie', 'content-type', 'content-length', 'authorization', 'x-csrf-token', 'x-requested-with', 'x-xsrf-token'];
                                const customHeaders = [];
                                let idCounter = 1;
                                for (const [key, values] of Object.entries(data.request_headers)) {
                                    if (!skipHeaders.includes(key.toLowerCase())) {
                                        const val = Array.isArray(values) ? values.join(', ') : String(values);
                                        customHeaders.push({ id: idCounter++, key, value: val, enabled: true });
                                    }
                                }
                                if (customHeaders.length > 0) {
                                    customHeaders.push({ id: idCounter++, key: '', value: '', enabled: true });
                                    this.requestState.customHeaders = customHeaders;
                                    this.requestState.headerIdCounter = idCounter;
                                }
                            }
                        } else {
                            this.showToast(`Could not match endpoint: ${data.method} ${data.uri}`, 'info');
                        }

                        // Set response data from telescope
                        let responseBody = data.response_body;
                        if (typeof responseBody === 'string') {
                            try { responseBody = JSON.parse(responseBody); } catch {}
                        }

                        // Build response headers object
                        const respHeaders = {};
                        if (data.response_headers && typeof data.response_headers === 'object') {
                            for (const [key, values] of Object.entries(data.response_headers)) {
                                respHeaders[key] = Array.isArray(values) ? values.join(', ') : String(values);
                            }
                        }

                        this.apiResponse = {
                            status: data.response_status || data.status,
                            statusText: this.getStatusText(data.response_status || data.status),
                            headers: respHeaders,
                            data: responseBody,
                            time: Math.round(data.duration),
                        };
                        this.apiError = null;
                        this.responseMode = 'request';

                        this.showTelescope = false;
                        this.showToast('Loaded from Telescope', 'success');
                    } catch (e) {
                        this.showToast('Failed to load Telescope entry detail', 'error');
                    }
                },

                getStatusText(status) {
                    const texts = { 200: 'OK', 201: 'Created', 204: 'No Content', 301: 'Moved Permanently', 302: 'Found', 304: 'Not Modified', 400: 'Bad Request', 401: 'Unauthorized', 403: 'Forbidden', 404: 'Not Found', 405: 'Method Not Allowed', 422: 'Unprocessable Entity', 429: 'Too Many Requests', 500: 'Internal Server Error', 502: 'Bad Gateway', 503: 'Service Unavailable' };
                    return texts[status] || '';
                },

                // ============ DESIGN TAB HELPERS ============

                getDesignPathParams() {
                    if (!this.selectedEndpoint) return [];
                    const endpoint = this.getEndpointSpec();
                    if (!endpoint) return [];
                    return (endpoint.parameters || []).filter(p => p.in === 'path');
                },

                getDesignQueryParams() {
                    if (!this.selectedEndpoint) return [];
                    const endpoint = this.getEndpointSpec();
                    if (!endpoint) return [];
                    return (endpoint.parameters || []).filter(p => p.in === 'query');
                },

                getDesignRequestBody() {
                    if (!this.selectedEndpoint) return null;
                    const endpoint = this.getEndpointSpec();
                    if (!endpoint || !endpoint.requestBody) return null;
                    const bodySchema = this.getRequestBodySchema(endpoint);
                    if (!bodySchema) return null;

                    const schema = this.resolveSchema(bodySchema);
                    const fields = [];
                    const example = {};

                    if (schema.properties) {
                        for (const [name, prop] of Object.entries(schema.properties)) {
                            const resolved = this.resolveSchema(prop);
                            fields.push({
                                name,
                                type: resolved.type || 'any',
                                format: resolved.format,
                                required: (schema.required || []).includes(name),
                                description: resolved.description || '',
                                validation: this.getValidationString(resolved),
                                enum: resolved.enum
                            });
                            example[name] = this.getExampleValue(name, resolved);
                        }
                    }

                    return { fields, example };
                },

                getDesignResponses() {
                    if (!this.selectedEndpoint) return [];
                    const endpoint = this.getEndpointSpec();
                    if (!endpoint || !endpoint.responses) return [];

                    return Object.entries(endpoint.responses).map(([status, resp]) => {
                        const content = resp.content?.['application/json'];
                        let example = null;
                        if (content?.schema) {
                            const schema = this.resolveSchema(content.schema);
                            example = content.example || this.schemaToExample(schema);
                        }
                        return {
                            status,
                            description: resp.description || '',
                            example: example || { message: 'No example available' },
                            open: status.startsWith('2')
                        };
                    });
                },

                resolveSchema(schema) {
                    if (!schema) return {};
                    if (schema.$ref) {
                        const refPath = schema.$ref.replace('#/components/schemas/', '');
                        return this.spec?.components?.schemas?.[refPath] || {};
                    }
                    if (schema.allOf) {
                        return schema.allOf.reduce((acc, s) => ({...acc, ...this.resolveSchema(s)}), {});
                    }
                    return schema;
                },

                getValidationString(prop) {
                    const rules = [];
                    if (prop.minLength) rules.push('min:' + prop.minLength);
                    if (prop.maxLength) rules.push('max:' + prop.maxLength);
                    if (prop.minimum !== undefined) rules.push('>= ' + prop.minimum);
                    if (prop.maximum !== undefined) rules.push('<= ' + prop.maximum);
                    if (prop.pattern) rules.push('pattern: ' + prop.pattern);
                    if (prop.format) rules.push(prop.format);
                    return rules.join(', ');
                },

                getExampleValue(name, prop) {
                    if (prop.example !== undefined) return prop.example;
                    if (prop.enum?.length > 0) return prop.enum[0];
                    if (prop.default !== undefined) return prop.default;
                    const n = name.toLowerCase();
                    if (n.includes('email')) return 'user@example.com';
                    if (n.includes('name')) return 'John Doe';
                    if (n.includes('date') || n.includes('_at')) return '2026-01-15T00:00:00Z';
                    if (n.includes('amount') || n.includes('price') || n.includes('balance')) return 1000.00;
                    if (n.includes('id')) return 1;
                    if (n.includes('url') || n.includes('link')) return 'https://example.com';
                    if (n.includes('phone')) return '+1234567890';
                    if (n.includes('description') || n.includes('note')) return 'Sample description';
                    if (prop.type === 'integer') return 1;
                    if (prop.type === 'number') return 10.5;
                    if (prop.type === 'boolean') return true;
                    if (prop.type === 'array') return [];
                    if (prop.type === 'object') return {};
                    return 'string_value';
                },

                schemaToExample(schema) {
                    if (!schema) return null;
                    if (schema.example) return schema.example;
                    if (schema.type === 'object' && schema.properties) {
                        const obj = {};
                        for (const [k, v] of Object.entries(schema.properties)) {
                            const resolved = this.resolveSchema(v);
                            obj[k] = this.getExampleValue(k, resolved);
                        }
                        return obj;
                    }
                    if (schema.type === 'array' && schema.items) {
                        const item = this.resolveSchema(schema.items);
                        return [this.schemaToExample(item) || 'item'];
                    }
                    return null;
                },

                generateDesignTypeScript() {
                    if (!this.selectedEndpoint) return '// Select an endpoint';
                    const endpoint = this.getEndpointSpec();
                    if (!endpoint) return '// No spec available';

                    const pathParts = this.selectedEndpoint.path.split('/').filter(Boolean);
                    const resourceName = pathParts[pathParts.length - 1]?.replace(/[{}]/g, '') || 'Resource';
                    const typeName = resourceName.charAt(0).toUpperCase() + resourceName.slice(1).replace(/s$/, '');

                    let ts = '';

                    if (['post', 'put', 'patch'].includes(this.selectedEndpoint.method)) {
                        const body = this.getDesignRequestBody();
                        if (body) {
                            ts += 'interface ' + typeName + 'Request {\n';
                            body.fields.forEach(f => {
                                const tsType = this.mapToTsType(f.type, f.format, f.enum);
                                ts += '  ' + f.name + (f.required ? '' : '?') + ': ' + tsType + ';\n';
                            });
                            ts += '}\n\n';
                        }
                    }

                    const responses = this.getDesignResponses();
                    const successResp = responses.find(r => r.status.startsWith('2'));
                    if (successResp?.example) {
                        ts += 'interface ' + typeName + 'Response {\n';
                        ts += this.objectToTsInterface(successResp.example, '  ');
                        ts += '}\n';
                    }

                    return ts || '// No type information available';
                },

                mapToTsType(type, format, enumValues) {
                    if (enumValues?.length > 0) return enumValues.map(v => "'" + v + "'").join(' | ');
                    if (format === 'date-time' || format === 'date') return 'string';
                    const map = { string: 'string', integer: 'number', number: 'number', boolean: 'boolean', array: 'any[]', object: 'Record<string, any>' };
                    return map[type] || 'any';
                },

                objectToTsInterface(obj, indent) {
                    indent = indent || '';
                    if (!obj || typeof obj !== 'object') return '';
                    let result = '';
                    for (const [key, value] of Object.entries(obj)) {
                        if (value === null) result += indent + key + ': any | null;\n';
                        else if (Array.isArray(value)) {
                            if (value.length > 0 && typeof value[0] === 'object') {
                                result += indent + key + ': {\n';
                                result += this.objectToTsInterface(value[0], indent + '  ');
                                result += indent + '}[];\n';
                            } else {
                                result += indent + key + ': ' + (typeof value[0] === 'number' ? 'number' : 'string') + '[];\n';
                            }
                        }
                        else if (typeof value === 'object') {
                            result += indent + key + ': {\n';
                            result += this.objectToTsInterface(value, indent + '  ');
                            result += indent + '};\n';
                        }
                        else result += indent + key + ': ' + (typeof value) + ';\n';
                    }
                    return result;
                },

                getEndpointSpec() {
                    if (!this.selectedEndpoint || !this.spec?.paths) return null;
                    const pathSpec = this.spec.paths[this.selectedEndpoint.path] || this.spec.paths['/' + this.selectedEndpoint.path];
                    if (!pathSpec) return null;
                    return pathSpec[this.selectedEndpoint.method];
                },

                getEndpointDescription() {
                    const endpoint = this.getEndpointSpec();
                    return endpoint?.description || endpoint?.summary || 'No description available';
                },

                // ============ VISUAL TAB HELPERS ============

                getRelatedEndpoints() {
                    if (!this.selectedEndpoint || !this.spec?.paths) return [];
                    
                    // Determine the current resource's tag
                    const currentTag = this.getEndpointSpec()?.tags?.[0];
                    // Also derive from path as fallback
                    const pathParts = this.selectedEndpoint.path.split('/').filter(p => p && !p.startsWith('{'));
                    const resourceSegment = pathParts[pathParts.length - 1] || '';
                    
                    // Use resourceRelationships (same data as API map) for resource-level relationships
                    const related = [];
                    const seen = new Set();
                    
                    for (const rel of (this.resourceRelationships || [])) {
                        // Check if this relationship involves our resource (by tag or path segment)
                        const isFrom = rel.from === currentTag || this.tagMatchesResource(rel.from, resourceSegment);
                        const isTo = rel.to === currentTag || this.tagMatchesResource(rel.to, resourceSegment);
                        
                        if (isFrom && rel.to) {
                            const key = 'uses-' + rel.to + '-' + rel.field;
                            if (!seen.has(key)) {
                                seen.add(key);
                                // Find a representative GET endpoint for the target resource
                                const targetEndpoints = this.endpointsByTag[rel.to] || [];
                                const listEndpoint = targetEndpoints.find(ep => ep.method === 'get' && !ep.path.includes('{'));
                                const anyEndpoint = targetEndpoints[0];
                                const ep = listEndpoint || anyEndpoint;
                                
                                related.push({
                                    method: ep ? ep.method.toUpperCase() : 'GET',
                                    path: ep ? ep.path : '',
                                    summary: `This resource references ${rel.to}`,
                                    field: rel.field,
                                    type: rel.type === 'polymorphic' ? 'polymorphic' : 'uses',
                                    direction: 'out',
                                    resourceName: rel.to
                                });
                            }
                        }
                        
                        if (isTo && rel.from) {
                            const key = 'provides-' + rel.from + '-' + rel.field;
                            if (!seen.has(key)) {
                                seen.add(key);
                                const sourceEndpoints = this.endpointsByTag[rel.from] || [];
                                const listEndpoint = sourceEndpoints.find(ep => ep.method === 'get' && !ep.path.includes('{'));
                                const anyEndpoint = sourceEndpoints[0];
                                const ep = listEndpoint || anyEndpoint;
                                
                                related.push({
                                    method: ep ? ep.method.toUpperCase() : 'GET',
                                    path: ep ? ep.path : '',
                                    summary: `${rel.from} references this resource`,
                                    field: rel.field,
                                    type: rel.type === 'polymorphic' ? 'polymorphic' : 'provides',
                                    direction: 'in',
                                    resourceName: rel.from
                                });
                            }
                        }
                    }
                    
                    return related;
                },
                
                // Helper: check if a tag name matches a resource path segment
                tagMatchesResource(tag, resourceSegment) {
                    if (!tag || !resourceSegment) return false;
                    const normalized = tag.toLowerCase().replace(/[^a-z0-9]/g, '');
                    const segNormalized = resourceSegment.toLowerCase().replace(/[-_]/g, '');
                    return normalized === segNormalized || normalized + 's' === segNormalized || normalized === segNormalized + 's';
                },

                selectEndpointByPath(method, path) {
                    const entries = Object.entries(this.endpointsByTag || {});
                    for (const [tag, endpoints] of entries) {
                        const ep = endpoints.find(e => e.method === method.toLowerCase() && e.path === path);
                        if (ep) {
                            this.selectEndpoint(ep);
                            return;
                        }
                    }
                },

                getVisualDataFlow() {
                    if (!this.selectedEndpoint) return [];
                    const method = this.selectedEndpoint.method.toUpperCase();
                    const pathParts = this.selectedEndpoint.path.split('/').filter(Boolean);
                    const resource = pathParts[pathParts.length - 1]?.replace(/[{}]/g, '') || 'resource';
                    const hasId = this.selectedEndpoint.path.includes('{');

                    const flows = {
                        'GET': hasId ? [
                            { type: 'input', title: 'Receive Request', description: 'Client requests ' + resource + ' by ID' },
                            { type: 'process', title: 'Authenticate & Authorize', description: 'Verify bearer token and check permissions' },
                            { type: 'process', title: 'Query Database', description: 'SELECT from ' + resource + ' table with relationships' },
                            { type: 'output', title: 'Return Response', description: 'Return ' + resource + ' data with 200 status' }
                        ] : [
                            { type: 'input', title: 'Receive Request', description: 'Client requests ' + resource + ' listing' },
                            { type: 'process', title: 'Authenticate & Authorize', description: 'Verify bearer token and check permissions' },
                            { type: 'process', title: 'Apply Filters & Pagination', description: 'Process query parameters (search, sort, page)' },
                            { type: 'process', title: 'Query Database', description: 'SELECT from ' + resource + ' table with filters applied' },
                            { type: 'output', title: 'Return Paginated Response', description: 'Return ' + resource + ' list with pagination metadata' }
                        ],
                        'POST': [
                            { type: 'input', title: 'Receive Request', description: 'Client sends new ' + resource + ' data' },
                            { type: 'process', title: 'Authenticate & Authorize', description: 'Verify bearer token and check permissions' },
                            { type: 'process', title: 'Validate Input', description: 'Validate all fields against rules (required, types, constraints)' },
                            { type: 'process', title: 'Create Record', description: 'INSERT into ' + resource + ' table' },
                            { type: 'side-effect', title: 'Side Effects', description: 'Trigger events, update related records, send notifications' },
                            { type: 'output', title: 'Return Created', description: 'Return new ' + resource + ' with 201 status' }
                        ],
                        'PUT': [
                            { type: 'input', title: 'Receive Request', description: 'Client sends updated ' + resource + ' data' },
                            { type: 'process', title: 'Authenticate & Authorize', description: 'Verify bearer token and check ownership' },
                            { type: 'process', title: 'Find Record', description: 'Find ' + resource + ' by ID or return 404' },
                            { type: 'process', title: 'Validate Input', description: 'Validate updated fields against rules' },
                            { type: 'process', title: 'Update Record', description: 'UPDATE ' + resource + ' table' },
                            { type: 'side-effect', title: 'Side Effects', description: 'Trigger events, recalculate derived data' },
                            { type: 'output', title: 'Return Updated', description: 'Return updated ' + resource + ' with 200 status' }
                        ],
                        'PATCH': [
                            { type: 'input', title: 'Receive Request', description: 'Client sends partial ' + resource + ' update' },
                            { type: 'process', title: 'Authenticate & Authorize', description: 'Verify bearer token and check ownership' },
                            { type: 'process', title: 'Find Record', description: 'Find ' + resource + ' by ID or return 404' },
                            { type: 'process', title: 'Validate Input', description: 'Validate only provided fields' },
                            { type: 'process', title: 'Partial Update', description: 'UPDATE only changed fields in ' + resource + ' table' },
                            { type: 'output', title: 'Return Updated', description: 'Return updated ' + resource + ' with 200 status' }
                        ],
                        'DELETE': [
                            { type: 'input', title: 'Receive Request', description: 'Client requests to delete ' + resource },
                            { type: 'process', title: 'Authenticate & Authorize', description: 'Verify bearer token and check ownership' },
                            { type: 'process', title: 'Find Record', description: 'Find ' + resource + ' by ID or return 404' },
                            { type: 'side-effect', title: 'Cascade Effects', description: 'Delete related records (comments, attachments, etc.)' },
                            { type: 'process', title: 'Delete Record', description: 'DELETE from ' + resource + ' table' },
                            { type: 'output', title: 'Return Confirmation', description: 'Return 204 No Content' }
                        ]
                    };

                    return flows[method] || flows['GET'];
                },

                getAffectedTables() {
                    if (!this.selectedEndpoint) return [];
                    const method = this.selectedEndpoint.method.toUpperCase();
                    const pathParts = this.selectedEndpoint.path.replace(/^\//, '').split('/');

                    // Derive table name from the last non-parameter path segment
                    let resourceName = '';
                    for (let i = pathParts.length - 1; i >= 0; i--) {
                        if (!pathParts[i].startsWith('{')) {
                            resourceName = pathParts[i];
                            break;
                        }
                    }

                    // Convert URL segment to likely table name (kebab-case to snake_case, keep plural)
                    const mainTable = resourceName.replace(/-/g, '_');

                    if (!mainTable) return [];

                    const tables = [];
                    if (['GET'].includes(method)) {
                        tables.push({ name: mainTable, operation: 'read' });
                    } else if (['POST'].includes(method)) {
                        tables.push({ name: mainTable, operation: 'write' });
                    } else if (['PUT', 'PATCH'].includes(method)) {
                        tables.push({ name: mainTable, operation: 'read' });
                        tables.push({ name: mainTable, operation: 'write' });
                    } else if (['DELETE'].includes(method)) {
                        tables.push({ name: mainTable, operation: 'delete' });
                    }

                    return tables;
                },

                getApiDependencies() {
                    if (!this.selectedEndpoint || !this.spec?.paths) return { requires: [], providesTo: [] };
                    const method = this.selectedEndpoint.method.toUpperCase();
                    const path = this.selectedEndpoint.path;
                    const requires = [];
                    const providesTo = [];

                    // Find _id fields in request body to determine what this endpoint requires
                    if (['POST', 'PUT', 'PATCH'].includes(method) && this.hasRequestBody(this.selectedEndpoint)) {
                        const fields = this.getRequestBodyFields(this.selectedEndpoint);
                        fields.forEach(field => {
                            if (field.name.endsWith('_id') || field.name.endsWith('_type')) {
                                // Derive the resource name from the field (e.g. wallet_id -> wallets)
                                const resourceName = field.name.replace(/_id$/, '').replace(/_type$/, '').replace(/_/g, '-');
                                const plural = resourceName + 's';
                                
                                // Search spec paths for matching endpoints
                                for (const [specPath, methods] of Object.entries(this.spec.paths)) {
                                    const pathSegments = specPath.split('/').filter(Boolean);
                                    const lastSegment = pathSegments[pathSegments.length - 1];
                                    const secondLast = pathSegments.length > 1 ? pathSegments[pathSegments.length - 2] : '';
                                    
                                    // Match: /api/v1/{resources} (list) or /api/v1/{resource-name}
                                    if ((lastSegment === plural || lastSegment === resourceName || 
                                         secondLast === plural || secondLast === resourceName ||
                                         lastSegment === plural.replace(/-/g, '_') || 
                                         secondLast === plural.replace(/-/g, '_')) && methods.get) {
                                        // Prefer the list endpoint (no path params at end)
                                        if (!lastSegment.startsWith('{')) {
                                            requires.push({
                                                field: field.name,
                                                method: 'GET',
                                                endpoint: specPath,
                                                description: `Get ${resourceName.replace(/-/g, ' ')} IDs`
                                            });
                                            break;
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Also check path parameters (e.g. /wallets/{wallet_id}/transactions)
                    const pathParams = path.match(/\{([^}]+)\}/g) || [];
                    pathParams.forEach(param => {
                        const paramName = param.replace(/[{}]/g, '');
                        if (paramName === 'id') return; // Skip generic {id}
                        
                        const resourceName = paramName.replace(/_id$/, '').replace(/_/g, '-');
                        const plural = resourceName + 's';
                        
                        for (const [specPath, methods] of Object.entries(this.spec.paths)) {
                            const pathSegments = specPath.split('/').filter(Boolean);
                            const lastSegment = pathSegments[pathSegments.length - 1];
                            
                            if ((lastSegment === plural || lastSegment === resourceName) && methods.get) {
                                const alreadyAdded = requires.some(r => r.field === paramName);
                                if (!alreadyAdded) {
                                    requires.push({
                                        field: paramName,
                                        method: 'GET',
                                        endpoint: specPath,
                                        description: `Get ${resourceName.replace(/-/g, ' ')} IDs`
                                    });
                                }
                                break;
                            }
                        }
                    });

                    // Find endpoints that reference this endpoint's resource (provides data to)
                    // Extract this endpoint's resource name from path
                    const thisPathParts = path.replace(/^\//, '').split('/').filter(p => !p.startsWith('{'));
                    const thisResource = thisPathParts[thisPathParts.length - 1];
                    if (thisResource) {
                        const singularResource = thisResource.replace(/s$/, '').replace(/-/g, '_');
                        const idField = singularResource + '_id';
                        
                        // Search all endpoints for ones that use this resource's ID
                        for (const [specPath, methods] of Object.entries(this.spec.paths)) {
                            if (specPath === path) continue; // Skip self
                            
                            for (const [m, spec] of Object.entries(methods || {})) {
                                if (!['post', 'put', 'patch'].includes(m) || !spec || typeof spec !== 'object') continue;
                                
                                // Check request body schema for our _id field
                                const bodySchema = this.getRequestBodySchema(spec);
                                if (bodySchema) {
                                    const resolved = bodySchema.$ref 
                                        ? (this.spec.components?.schemas?.[bodySchema.$ref.replace('#/components/schemas/', '')] || {})
                                        : bodySchema;
                                    
                                    if (resolved.properties && resolved.properties[idField]) {
                                        providesTo.push({
                                            field: idField,
                                            method: m.toUpperCase(),
                                            endpoint: specPath,
                                            description: `Uses ${idField}`
                                        });
                                    }
                                }
                                
                                // Check path params
                                if (specPath.includes(`{${idField}}`)) {
                                    const alreadyAdded = providesTo.some(p => p.endpoint === specPath && p.field === idField);
                                    if (!alreadyAdded) {
                                        providesTo.push({
                                            field: idField,
                                            method: m.toUpperCase(),
                                            endpoint: specPath,
                                            description: `Uses ${idField} in path`
                                        });
                                    }
                                }
                            }
                        }
                    }

                    return { requires, providesTo };
                },

                getImpactAnalysis() {
                    if (!this.selectedEndpoint) return [];
                    const method = this.selectedEndpoint.method.toUpperCase();
                    const related = this.getRelatedEndpoints();
                    const pathParts = this.selectedEndpoint.path.split('/').filter(Boolean);
                    const resource = pathParts[pathParts.length - 1]?.replace(/[{}]/g, '') || 'resource';

                    const impacts = [];

                    // Test Coverage - the most actionable section
                    const testItems = [];
                    if (method === 'GET') {
                        testItems.push('Valid response structure matches schema');
                        testItems.push('Authentication required (401 without token)');
                        if (!this.selectedEndpoint.path.includes('{')) {
                            testItems.push('Pagination returns correct page size');
                            testItems.push('Filters return matching results only');
                            testItems.push('Empty result set returns valid structure');
                        } else {
                            testItems.push('Returns 404 for non-existent ID');
                            testItems.push('Returns correct resource by ID');
                        }
                    } else if (method === 'POST') {
                        testItems.push('Creates resource with valid data (201)');
                        testItems.push('Returns 422 with missing required fields');
                        testItems.push('Returns 422 with invalid field types');
                        testItems.push('Returns 401 without authentication');
                        testItems.push('Duplicate entry handling (if applicable)');
                    } else if (method === 'PUT' || method === 'PATCH') {
                        testItems.push('Updates resource with valid data (200)');
                        testItems.push('Returns 404 for non-existent ID');
                        testItems.push('Returns 422 with invalid data');
                        testItems.push('Returns 403 for unauthorized update');
                        testItems.push('Partial update only changes specified fields');
                    } else if (method === 'DELETE') {
                        testItems.push('Deletes resource successfully (204)');
                        testItems.push('Returns 404 for non-existent ID');
                        testItems.push('Returns 403 for unauthorized delete');
                        testItems.push('Cascade deletes related records');
                    }

                    impacts.push({
                        icon: '\uD83E\uDDEA',
                        area: 'Test Coverage',
                        description: 'Recommended test scenarios for this endpoint:',
                        items: testItems,
                        hasFlowAction: true
                    });

                    // Cache/Data Invalidation - useful for all mutating endpoints
                    if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                        impacts.push({
                            icon: '\uD83D\uDD04',
                            area: 'Data Invalidation',
                            description: 'After this operation, cached data should be refreshed:',
                            items: [`GET ${this.selectedEndpoint.path.replace(/\/\{[^}]+\}$/, '')}`, ...related.filter(ep => ep.method === 'GET').map(ep => `${ep.method} ${ep.path}`).slice(0, 3)]
                        });
                    }

                    // Related Endpoints
                    if (related.length > 1) {
                        impacts.push({
                            icon: '\uD83D\uDD17',
                            area: 'Related Endpoints',
                            description: 'Other endpoints in this resource group:',
                            items: related.filter(ep => !(ep.method === this.selectedEndpoint.method.toUpperCase() && ep.path === this.selectedEndpoint.path)).map(ep => `${ep.method} ${ep.path}`)
                        });
                    }

                    return impacts;
                },

                setupKeyboardShortcuts() {
                    document.addEventListener('keydown', (e) => {
                        // Ctrl/Cmd + Enter: Send request
                        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                            e.preventDefault();
                            if (this.selectedEndpoint) this.sendRequest();
                        }
                        // Ctrl/Cmd + S: Save request
                        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                            e.preventDefault();
                            if (this.selectedEndpoint) this.openSaveModal();
                        }
                        // Ctrl/Cmd + K: Focus search in explorer
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.activeActivity = 'explorer';
                            this.sidebarVisible = true;
                            this.$nextTick(() => this.$refs.searchInput?.focus());
                        }
                        // Escape is handled by centralized handler in init()
                        // ? key: Show keyboard shortcuts (when not in an input)
                        if (e.key === '?' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName)) {
                            e.preventDefault();
                            this.showShortcuts = !this.showShortcuts;
                        }
                        // Number keys 1-3 for tabs (when not in an input)
                        if (['1','2','3'].includes(e.key) && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName) && !e.ctrlKey && !e.metaKey) {
                            const tabs = ['try-it', 'visual', 'all'];
                            this.activeTab = tabs[parseInt(e.key) - 1];
                        }
                    });
                },

                // ============ AUTHENTICATION METHODS ============

                // Set bearer token directly
                setToken(token) {
                    this.authToken = token.trim();
                    this.tokenInput = '';
                    this.showToast('Token saved');
                },

                // Perform login to get token
                async performLogin() {
                    if (!this.loginEmail || !this.loginPassword) return;

                    const baseUrl = this.getBaseUrl();

                    // Find the actual login path from the OpenAPI spec
                    let loginPath = '/login';
                    if (this.spec?.paths) {
                        for (const [p, methods] of Object.entries(this.spec.paths)) {
                            if (p.includes('login') && methods.post) {
                                loginPath = p;
                                break;
                            }
                        }
                    }

                    const loginUrl = baseUrl + loginPath;

                    // Security: warn when sending credentials over non-HTTPS
                    if (loginUrl.startsWith('http://')) {
                        try {
                            const host = new URL(loginUrl).hostname;
                            const isLocal = ['localhost', '127.0.0.1'].includes(host) || host.endsWith('.test') || host.endsWith('.local');
                            if (!isLocal && !confirm('Warning: You are about to send credentials over an unencrypted HTTP connection. Continue?')) {
                                return;
                            }
                        } catch (e) { /* relative URL, safe */ }
                    }

                    this.loginLoading = true;
                    this.loginError = '';

                    try {
                        const response = await fetch(loginUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                email: this.loginEmail,
                                password: this.loginPassword
                            })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Login failed');
                        }

                        // Try to extract token from various response formats
                        const token = data.token || data.access_token || data.data?.token || data.data?.access_token;

                        if (token) {
                            this.authToken = token;
                            this.loginEmail = '';
                            this.loginPassword = '';
                            this.showToast('Login successful');
                        } else {
                            throw new Error('Token not found in response');
                        }
                    } catch (error) {
                        this.loginError = error.message || 'Login failed. Please try again.';
                    } finally {
                        this.loginLoading = false;
                    }
                },

                // Logout and clear token
                logout() {
                    this.authToken = '';
                    this.showToast('Logged out');
                },

                // ============ API REQUEST METHODS ============

                // Send actual API request
                async sendRequest() {
                    if (!this.selectedEndpoint || this.requestLoading) return;

                    this.requestLoading = true;
                    this.apiError = null;
                    this.apiResponse = null;

                    const startTime = performance.now();
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 30000);

                    try {
                        // Build the full URL
                        const url = this.builtUrl;

                        // Check if we have file fields (schema-defined: single or array, or custom)
                        const hasFileFields = Object.values(this.requestState.bodyFiles || {}).some(f => Array.isArray(f) ? f.length > 0 : !!f)
                            || this.requestState.customFields?.some(f => f.enabled && f.name && f.type === 'file' && f.value);

                        // Build headers
                        const headers = {
                            'Accept': 'application/json'
                        };
                        if (!hasFileFields) {
                            headers['Content-Type'] = 'application/json';
                        }

                        // Add auth header if token exists
                        if (this.authToken) {
                            headers['Authorization'] = 'Bearer ' + this.authToken;
                        }

                        // Add custom headers
                        const customHeaders = this.getCustomHeadersObject();
                        Object.assign(headers, customHeaders);

                        // Build fetch options
                        const options = {
                            method: this.selectedEndpoint.method,
                            headers: headers
                        };

                        // Add body for non-GET requests
                        if (['POST', 'PUT', 'PATCH'].includes(this.selectedEndpoint.method) && this.hasRequestBody(this.selectedEndpoint)) {
                            if (hasFileFields) {
                                // Use FormData for file uploads
                                const formData = new FormData();
                                const body = this.builtRequestBody || {};
                                for (const [key, value] of Object.entries(body)) {
                                    if (Array.isArray(value)) {
                                        value.forEach(item => {
                                            if (typeof item === 'object' && item !== null) {
                                                // Array of objects: append each key as fieldname[idx][key]
                                                const idx = value.indexOf(item);
                                                for (const [k, v] of Object.entries(item)) {
                                                    formData.append(`${key}[${idx}][${k}]`, String(v));
                                                }
                                            } else {
                                                formData.append(key + '[]', String(item));
                                            }
                                        });
                                    } else if (typeof value === 'object' && value !== null) {
                                        formData.append(key, JSON.stringify(value));
                                    } else {
                                        formData.append(key, String(value));
                                    }
                                }
                                // Add schema-defined file fields (single and array)
                                if (this.requestState.bodyFiles) {
                                    for (const [key, fileOrFiles] of Object.entries(this.requestState.bodyFiles)) {
                                        if (!this.requestState.bodyFieldsEnabled[key]) continue;
                                        if (Array.isArray(fileOrFiles)) {
                                            fileOrFiles.forEach(f => formData.append(key + '[]', f));
                                        } else if (fileOrFiles) {
                                            formData.append(key, fileOrFiles);
                                        }
                                    }
                                }
                                // Add custom file fields
                                if (this.requestState.customFields) {
                                    this.requestState.customFields.forEach(field => {
                                        if (field.enabled && field.name && field.type === 'file' && field.value) {
                                            formData.append(field.name, field.value);
                                        }
                                    });
                                }
                                options.body = formData;
                            } else {
                                options.body = JSON.stringify(this.builtRequestBody);
                            }
                        }

                        // Make the request
                        options.signal = controller.signal;
                        const response = await fetch(url, options);
                        clearTimeout(timeoutId);
                        const endTime = performance.now();

                        // Parse response headers
                        const responseHeaders = {};
                        response.headers.forEach((value, key) => {
                            responseHeaders[key] = value;
                        });

                        // Parse response body
                        let responseData;
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            responseData = await response.json();
                        } else {
                            responseData = await response.text();
                        }

                        this.apiResponse = {
                            status: response.status,
                            statusText: response.statusText,
                            headers: responseHeaders,
                            data: responseData,
                            time: Math.round(endTime - startTime)
                        };

                        // Check response size for syntax highlighting guard
                        const responseStr = typeof responseData === 'string' ? responseData : JSON.stringify(responseData);
                        this.responseTruncated = responseStr.length > 512000; // 500KB
                        this.showFullHighlighted = false;

                        // Switch to response view and scroll to it
                        this.responseMode = 'request';
                        this.$nextTick(() => {
                            const el = document.getElementById('response-section');
                            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        });

                        // Add to request history
                        this.addToHistory(this.selectedEndpoint, this.apiResponse);

                        // Show success/info toast based on status
                        if (response.ok) {
                            this.showToast(`Request completed (${response.status})`, 'success');
                        } else {
                            this.showToast(`Request returned ${response.status}`, 'info');
                        }

                    } catch (error) {
                        clearTimeout(timeoutId);
                        this.apiError = error.name === 'AbortError'
                            ? 'Request timed out after 30 seconds.'
                            : (error.message || 'Request failed. Please check your connection and try again.');
                        this.responseMode = 'request';
                        this.showToast(error.name === 'AbortError' ? 'Request timed out' : 'Request failed', 'error');
                        this.$nextTick(() => {
                            const el = document.getElementById('response-section');
                            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        });
                    } finally {
                        this.requestLoading = false;
                        // Save state after request completes (success or error)
                        this.saveEndpointState();
                    }
                },

                // Copy response body to clipboard
                async copyResponseBody() {
                    if (!this.apiResponse?.data) return;

                    const text = typeof this.apiResponse.data === 'object'
                        ? JSON.stringify(this.apiResponse.data, null, 2)
                        : String(this.apiResponse.data);

                    await navigator.clipboard.writeText(text);
                    this.showToast('Response copied');
                },

                // ============ REQUEST HISTORY METHODS ============

                addToHistory(endpoint, response) {
                    this.requestHistory.unshift({
                        method: endpoint.method,
                        path: endpoint.path,
                        status: response?.status || 0,
                        duration: response?.time || 0,
                        timestamp: new Date().toISOString()
                    });
                    if (this.requestHistory.length > 100) {
                        this.requestHistory = this.requestHistory.slice(0, 100);
                    }
                    try {
                        localStorage.setItem('apiura_history', JSON.stringify(this.requestHistory));
                    } catch { /* localStorage full */ }
                },

                clearHistory() {
                    this.requestHistory = [];
                    localStorage.removeItem('apiura_history');
                    this.showToast('History cleared');
                },

                getFilteredHistory() {
                    if (!this.historyFilter) return this.requestHistory;
                    const q = this.historyFilter.toLowerCase();
                    return this.requestHistory.filter(h =>
                        h.path.toLowerCase().includes(q) || h.method.toLowerCase().includes(q)
                    );
                },

                formatHistoryTime(timestamp) {
                    const d = new Date(timestamp);
                    const now = new Date();
                    const diff = now - d;
                    if (diff < 60000) return 'just now';
                    if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
                    if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
                    return d.toLocaleDateString();
                },

                // ============ SAVED REQUESTS METHODS ============

                // Load all saved requests
                async loadSavedRequests() {
                    this.loadingSavedRequests = true;
                    try {
                        const response = await fetch('/apiura/saved-requests');
                        if (response.ok) {
                            const json = await response.json();
                            this.savedRequests = json.data || json;
                        } else {
                            this.showToast('Failed to load saved requests', 'error');
                        }
                    } catch (error) {
                        console.error('Failed to load saved requests:', error);
                        this.showToast('Failed to load saved requests', 'error');
                    } finally {
                        this.loadingSavedRequests = false;
                    }
                },

                // Open save modal
                openSaveModal() {
                    if (this.selectedSavedRequest) {
                        this.saveMode = 'update';
                        this.saveRequestName = this.selectedSavedRequest.name || '';
                        this.saveRequestPriority = this.selectedSavedRequest.priority || '';
                        this.saveRequestTeam = this.selectedSavedRequest.team || '';
                    } else {
                        this.saveMode = 'new';
                        this.saveRequestName = '';
                        this.saveRequestPriority = '';
                        this.saveRequestTeam = '';
                    }
                    this.showSaveModal = true;
                },

                // Save current request
                async saveRequest() {
                    if (!this.selectedEndpoint) return;

                    this.savingRequest = true;
                    try {
                        const data = {
                            name: this.saveRequestName || `${this.selectedEndpoint.method} ${this.selectedEndpoint.path}`,
                            priority: this.saveRequestPriority || null,
                            team: this.saveRequestTeam || null,
                            method: this.selectedEndpoint.method,
                            path: this.selectedEndpoint.path,
                            path_params: this.requestState.pathParams,
                            query_params: {
                                values: this.requestState.queryParams,
                                enabled: this.requestState.queryParamsEnabled
                            },
                            headers: this.getCustomHeadersObject(),
                            body: this.builtRequestBody,
                            response_status: this.apiResponse?.status || null,
                            response_headers: this.apiResponse?.headers || null,
                            response_body: this.apiResponse?.data ? JSON.stringify(this.apiResponse.data) : null
                        };

                        const response = await fetch('/apiura/saved-requests', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify(data)
                        });

                        if (response.ok) {
                            const json = await response.json().catch(() => ({}));
                            this.selectedSavedRequest = json.data || json;
                            await this.loadSavedRequests();
                            this.showSaveModal = false;
                            this.showToast('Request saved');
                        } else {
                            const errorData = await response.json().catch(() => ({}));
                            console.error('Save failed:', response.status, errorData);
                            throw new Error(errorData.message || `Server error: ${response.status}`);
                        }
                    } catch (error) {
                        console.error('Save request error:', error);
                        this.showToast(error.message || 'Failed to save request', 'error');
                    } finally {
                        this.savingRequest = false;
                    }
                },

                // Update an existing saved request
                async updateSavedRequest() {
                    if (!this.selectedEndpoint || !this.selectedSavedRequest?.id) return;

                    this.savingRequest = true;
                    try {
                        const data = {
                            name: this.saveRequestName || `${this.selectedEndpoint.method} ${this.selectedEndpoint.path}`,
                            priority: this.saveRequestPriority || null,
                            team: this.saveRequestTeam || null,
                            method: this.selectedEndpoint.method,
                            path: this.selectedEndpoint.path,
                            path_params: this.requestState.pathParams,
                            query_params: {
                                values: this.requestState.queryParams,
                                enabled: this.requestState.queryParamsEnabled
                            },
                            headers: this.getCustomHeadersObject(),
                            body: this.builtRequestBody,
                            response_status: this.apiResponse?.status || null,
                            response_headers: this.apiResponse?.headers || null,
                            response_body: this.apiResponse?.data ? JSON.stringify(this.apiResponse.data) : null
                        };

                        const response = await fetch(`/apiura/saved-requests/${this.selectedSavedRequest.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify(data)
                        });

                        if (response.ok) {
                            const json = await response.json().catch(() => ({}));
                            this.selectedSavedRequest = json.data || json;
                            await this.loadSavedRequests();
                            this.showSaveModal = false;
                            this.showToast('Request updated');
                        } else {
                            const errorData = await response.json().catch(() => ({}));
                            console.error('Update failed:', response.status, errorData);
                            throw new Error(errorData.message || `Server error: ${response.status}`);
                        }
                    } catch (error) {
                        console.error('Update request error:', error);
                        this.showToast(error.message || 'Failed to update request', 'error');
                    } finally {
                        this.savingRequest = false;
                    }
                },

                // Sort saved requests by priority (highest first), then newest first
                sortSavedRequests(requests) {
                    const priorityOrder = { critical: 4, high: 3, medium: 2, low: 1 };
                    return [...requests].sort((a, b) => {
                        const pa = priorityOrder[a.priority] || 0;
                        const pb = priorityOrder[b.priority] || 0;
                        if (pb !== pa) return pb - pa;
                        return (b.id || 0) - (a.id || 0);
                    });
                },

                // Get saved requests filtered by current endpoint
                getFilteredSavedRequests() {
                    if (!this.selectedEndpoint) return [];
                    const filtered = this.savedRequests.filter(saved =>
                        saved.path === this.selectedEndpoint.path &&
                        saved.method === this.selectedEndpoint.method
                    );
                    return this.sortSavedRequests(filtered);
                },

                // Load a saved request
                async loadSavedRequest(saved) {
                    // Find the endpoint in our list
                    const endpoint = this.endpoints.find(e =>
                        e.path === saved.path && e.method === saved.method
                    );

                    if (!endpoint) {
                        this.showToast('Endpoint not found in spec', 'error');
                        return;
                    }

                    // Guard against race conditions with a generation counter
                    const generation = ++this._loadSavedRequestGeneration;

                    // Fetch full details (list doesn't include response_body/response_headers)
                    let fullSaved = saved;
                    if (saved.id && saved.response_status) {
                        try {
                            const res = await fetch(`/apiura/saved-requests/${saved.id}`);
                            if (this._loadSavedRequestGeneration !== generation) return; // User navigated away
                            if (res.ok) {
                                const json = await res.json();
                                fullSaved = json.data || json;
                            }
                        } catch (e) {
                            if (this._loadSavedRequestGeneration !== generation) return;
                            console.error('Failed to fetch full saved request:', e);
                        }
                    }

                    // Abort if user has clicked something else during the fetch
                    if (this._loadSavedRequestGeneration !== generation) return;

                    // Select the endpoint first (this clears selectedSavedRequest)
                    this.selectEndpoint(endpoint);

                    // Restore request state
                    this.$nextTick(() => {
                        // Final check after $nextTick
                        if (this._loadSavedRequestGeneration !== generation) return;
                        // Track which saved request is loaded (set after selectEndpoint so it doesn't get cleared)
                        this.selectedSavedRequest = fullSaved;

                        // Restore path params
                        if (fullSaved.path_params) {
                            this.requestState.pathParams = { ...fullSaved.path_params };
                        }

                        // Restore query params
                        if (fullSaved.query_params) {
                            this.requestState.queryParams = { ...(fullSaved.query_params.values || {}) };
                            this.requestState.queryParamsEnabled = { ...(fullSaved.query_params.enabled || {}) };
                        }

                        // Restore body
                        if (fullSaved.body) {
                            const fields = this.getRequestBodyFieldsForBuilder(endpoint);
                            fields.forEach(field => {
                                if (fullSaved.body[field.name] !== undefined) {
                                    this.requestState.body[field.name] = fullSaved.body[field.name];
                                    this.requestState.bodyFieldsEnabled[field.name] = true;

                                    // Handle nested fields
                                    if (typeof fullSaved.body[field.name] === 'object' && fullSaved.body[field.name] !== null) {
                                        Object.keys(fullSaved.body[field.name]).forEach(nestedKey => {
                                            this.requestState.bodyFieldsEnabled[`${field.name}.${nestedKey}`] = true;
                                        });
                                    }
                                }
                            });
                            this.syncToRawJson();
                        }

                        // Restore response if available
                        if (fullSaved.response_status) {
                            this.responseMode = 'request';
                            this.apiResponse = {
                                status: fullSaved.response_status,
                                headers: fullSaved.response_headers || {},
                                data: fullSaved.response_body ? (() => { try { return JSON.parse(fullSaved.response_body); } catch { return fullSaved.response_body; } })() : null,
                                time: 0
                            };
                        }

                        this.showSavedRequestsPanel = false;
                        this.showToast('Request loaded');
                    });
                },

                // Delete a saved request
                async deleteSavedRequest(id, event) {
                    event.stopPropagation();
                    if (!confirm('Delete this saved request?')) return;

                    try {
                        const response = await fetch(`/apiura/saved-requests/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            }
                        });

                        if (response.ok) {
                            if (this.selectedSavedRequest?.id === id) {
                                this.selectedSavedRequest = null;
                            }
                            await this.loadSavedRequests();
                            this.showToast('Request deleted');
                        } else {
                            throw new Error('Failed to delete');
                        }
                    } catch (error) {
                        this.showToast('Failed to delete request', 'error');
                    }
                },

                // ============ COMMENTS METHODS ============

                // Load comments for a saved request
                async loadComments(savedRequestId) {
                    this.loadingComments = true;
                    try {
                        const response = await fetch(`/apiura/saved-requests/${savedRequestId}/comments`);
                        if (response.ok) {
                            const json = await response.json();
                            this.selectedSavedRequestComments = json.data || json;
                        }
                    } catch (error) {
                        console.error('Failed to load comments:', error);
                        this.showToast('Failed to load comments', 'error');
                    } finally {
                        this.loadingComments = false;
                    }
                },

                // Submit a new comment
                async submitComment(savedRequestId) {
                    if (!this.newComment.content.trim()) {
                        this.showToast('Comment content is required', 'error');
                        return;
                    }
                    if (!this.newComment.author_name.trim()) {
                        this.showToast('Author name is required', 'error');
                        return;
                    }

                    this.submittingComment = true;
                    try {
                        const response = await fetch(`/apiura/saved-requests/${savedRequestId}/comments`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify({
                                content: this.newComment.content,
                                author_name: this.newComment.author_name,
                                author_type: this.newComment.author_type,
                                status: this.newComment.status
                            })
                        });

                        if (response.ok) {
                            const json = await response.json();
                            const comment = json.data || json;
                            this.selectedSavedRequestComments.push(comment);
                            this.newComment.content = '';
                            this.newComment.status = 'info';
                            // Refresh saved requests to update sidebar indicators
                            await this.loadSavedRequests();
                            this.showToast('Comment added');
                        } else {
                            const error = await response.json();
                            throw new Error(error.message || 'Failed to add comment');
                        }
                    } catch (error) {
                        this.showToast(error.message || 'Failed to add comment', 'error');
                    } finally {
                        this.submittingComment = false;
                    }
                },

                // Delete a comment
                async deleteComment(savedRequestId, commentId) {
                    if (!confirm('Delete this comment?')) return;

                    try {
                        const response = await fetch(`/apiura/saved-requests/${savedRequestId}/comments/${commentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            }
                        });

                        if (response.ok) {
                            this.selectedSavedRequestComments = this.selectedSavedRequestComments.filter(c => c.id !== commentId);
                            // Refresh saved requests to update sidebar indicators
                            await this.loadSavedRequests();
                            this.showToast('Comment deleted');
                        } else {
                            throw new Error('Failed to delete');
                        }
                    } catch (error) {
                        this.showToast('Failed to delete comment', 'error');
                    }
                },

                // Get color class for author type
                getAuthorTypeColor(type) {
                    const colors = {
                        'backend': 'bg-ide-primary/20 text-ide-primary',
                        'frontend': 'bg-[var(--ide-info-bg)] text-[var(--ide-info-text)]',
                        'qa': 'bg-[var(--ide-success-bg)] text-[var(--ide-success-text)]',
                        'other': 'bg-ide-border text-ide-fg'
                    };
                    return colors[type] || colors['other'];
                },

                // View comments for a saved request
                viewSavedRequestComments(saved) {
                    this._commentsSavedRequestBackup = this.selectedSavedRequest;
                    this.selectedSavedRequest = saved;
                    this.showCommentsModal = true;
                    this.loadComments(saved.id);
                },

                // Close comments modal
                closeCommentsModal() {
                    this.showCommentsModal = false;
                    this.selectedSavedRequestComments = [];
                    if (this._commentsSavedRequestBackup !== undefined) {
                        this.selectedSavedRequest = this._commentsSavedRequestBackup;
                        this._commentsSavedRequestBackup = undefined;
                    }
                },

                // Get saved request info for an endpoint (sidebar indicators)
                getEndpointSavedInfo(method, path) {
                    const requests = this.savedRequests.filter(s => s.method === method && s.path === path);
                    if (requests.length === 0) return null;
                    const totalComments = requests.reduce((sum, r) => sum + (r.comments_count || 0), 0);
                    const statusPriority = { critical: 3, warning: 2, info: 1, resolved: 0 };
                    let highestStatus = null;
                    let highestPriority = -1;
                    requests.forEach(r => {
                        if (r.highest_comment_status) {
                            const p = statusPriority[r.highest_comment_status] || 0;
                            if (p > highestPriority) {
                                highestPriority = p;
                                highestStatus = r.highest_comment_status;
                            }
                        }
                    });
                    const sorted = this.sortSavedRequests(requests);
                    return {
                        count: sorted.length,
                        commentsCount: totalComments,
                        highestStatus,
                        requests: sorted.slice(0, 5),
                        hasMore: sorted.length > 5
                    };
                },

                // Get dot color class for a comment status
                getStatusDotColor(status) {
                    const colors = { critical: 'bg-red-500', warning: 'bg-yellow-500', info: 'bg-blue-500', resolved: 'bg-gray-400' };
                    return colors[status] || '';
                },

                // Get badge color class for a comment status
                getStatusColor(status) {
                    const colors = { critical: 'bg-red-500 text-white', warning: 'bg-yellow-500 text-white', info: 'bg-blue-500 text-white', resolved: 'bg-gray-400 text-white' };
                    return colors[status] || 'bg-gray-400 text-white';
                },

                // Get text color class for a comment status (used on comment icons/buttons)
                getStatusTextColor(status) {
                    const colors = { critical: 'text-red-500', warning: 'text-yellow-500', info: 'text-blue-500', resolved: 'text-gray-400' };
                    return colors[status] || 'text-ide-muted';
                },

                // Toggle endpoint saved requests preview in sidebar
                toggleEndpointSaved(method, path) {
                    const key = method + ':' + path;
                    this.expandedEndpointSaved[key] = !this.expandedEndpointSaved[key];
                },

                // Check if endpoint saved requests preview is expanded
                isEndpointSavedExpanded(method, path) {
                    return !!this.expandedEndpointSaved[method + ':' + path];
                },

                // Update a comment's status via PUT
                async updateCommentStatus(savedRequestId, commentId, newStatus) {
                    try {
                        const response = await fetch(`/apiura/saved-requests/${savedRequestId}/comments/${commentId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify({ status: newStatus })
                        });
                        if (response.ok) {
                            const json = await response.json();
                            const updated = json.data || json;
                            // Update in local comments list
                            const idx = this.selectedSavedRequestComments.findIndex(c => c.id === commentId);
                            if (idx !== -1) this.selectedSavedRequestComments[idx].status = updated.status;
                            // Refresh saved requests to update sidebar indicators
                            await this.loadSavedRequests();
                            this.showToast('Comment status updated');
                        } else {
                            throw new Error('Failed to update status');
                        }
                    } catch (error) {
                        this.showToast('Failed to update comment status', 'error');
                    }
                },

                // ============ QUERY PARAM HELPERS METHODS ============

                // Add a custom query parameter with editable key
                addCustomQueryParam() {
                    let i = 1;
                    while (this.requestState.queryParams.hasOwnProperty(`custom_${i}`)) i++;
                    const name = `custom_${i}`;
                    this.requestState.queryParams[name] = '';
                    this.requestState.queryParamsEnabled[name] = true;
                },

                // Rename a dynamic query parameter key
                renameQueryParam(oldName, newName) {
                    newName = newName.trim();
                    if (!newName || newName === oldName) return;
                    if (this.requestState.queryParams.hasOwnProperty(newName)) {
                        this.showToast(`Parameter "${newName}" already exists`, 'error');
                        return;
                    }
                    const value = this.requestState.queryParams[oldName];
                    const enabled = this.requestState.queryParamsEnabled[oldName];
                    delete this.requestState.queryParams[oldName];
                    delete this.requestState.queryParamsEnabled[oldName];
                    this.requestState.queryParams[newName] = value;
                    this.requestState.queryParamsEnabled[newName] = enabled;
                },

                // Add pagination params
                addPaginationParams() {
                    const params = this.commonQueryParams.pagination || [];
                    params.forEach(p => {
                        if (!this.requestState.queryParams.hasOwnProperty(p.name)) {
                            this.requestState.queryParams[p.name] = p.default !== undefined ? String(p.default) : '';
                        }
                        this.requestState.queryParamsEnabled[p.name] = true;
                    });
                    this.showToast('Added pagination params');
                },

                // Add sorting params
                addSortParams() {
                    const params = this.commonQueryParams.sorting || [];
                    params.forEach(p => {
                        if (!this.requestState.queryParams.hasOwnProperty(p.name)) {
                            this.requestState.queryParams[p.name] = p.default !== undefined ? String(p.default) : '';
                        }
                        this.requestState.queryParamsEnabled[p.name] = true;
                    });
                    this.showToast('Added sorting params');
                },

                // Add date range params
                addDateRangeParams() {
                    const params = this.commonQueryParams.date_range || [];
                    params.forEach(p => {
                        if (!this.requestState.queryParams.hasOwnProperty(p.name)) {
                            this.requestState.queryParams[p.name] = '';
                        }
                        this.requestState.queryParamsEnabled[p.name] = true;
                    });
                    this.showToast('Added date range params');
                },

                // Add search param
                addSearchParam() {
                    const params = this.commonQueryParams.search || [];
                    params.forEach(p => {
                        if (!this.requestState.queryParams.hasOwnProperty(p.name)) {
                            this.requestState.queryParams[p.name] = '';
                        }
                        this.requestState.queryParamsEnabled[p.name] = true;
                    });
                    this.showToast('Added search param');
                },

                // ============ FLOW BUILDER METHODS ============

                // Run the entire flow
                async runFlow() {
                    const flow = this.newFlow;
                    if (!flow.steps || flow.steps.length === 0) return;

                    this.runningFlow = true;
                    this.flowRunResults = new Array(flow.steps.length).fill(null);
                    this.flowVariables = {};
                    this.flowRunError = null;
                    this.flowTotalDuration = 0;
                    this.currentFlowStep = 0;

                    const flowStartTime = performance.now();
                    const continueOnError = this.newFlow.continueOnError || false;

                    for (let i = 0; i < flow.steps.length; i++) {
                        const step = flow.steps[i];
                        this.currentFlowStep = i;

                        try {
                            const processedStep = this.substituteFlowVariables(step);
                            const result = await this.executeFlowStep(processedStep);
                            const stepKey = `step${i + 1}`;
                            this.flowVariables[stepKey] = this.extractVariablesFromResponse(
                                result.data,
                                step.extractVariables || {}
                            );

                            const assertions = step.assertions || [];
                            const assertionResults = this.evaluateAssertions(result, assertions, result.duration);
                            const allAssertionsPassed = assertionResults.length === 0 || assertionResults.every(a => a.passed);

                            const hasExpectedStatus = step.expectedStatus !== null && step.expectedStatus !== undefined && step.expectedStatus !== '';
                            const statusMatches = hasExpectedStatus
                                ? result.status === Number(step.expectedStatus)
                                : (result.status >= 200 && result.status < 300);

                            this.flowRunResults.splice(i, 1, {
                                step: i + 1,
                                name: step.name,
                                status: result.status,
                                success: statusMatches && allAssertionsPassed,
                                httpOk: statusMatches,
                                expectedStatus: hasExpectedStatus ? Number(step.expectedStatus) : null,
                                assertionResults,
                                allAssertionsPassed,
                                data: result.data,
                                duration: result.duration,
                                extractedVars: this.flowVariables[stepKey]
                            });

                            if (!continueOnError) {
                                if (!statusMatches) {
                                    this.flowRunError = hasExpectedStatus
                                        ? `Step ${i + 1}: expected status ${step.expectedStatus}, got ${result.status}`
                                        : `Step ${i + 1} failed with status ${result.status}`;
                                    break;
                                }
                                if (!allAssertionsPassed) {
                                    const failCount = assertionResults.filter(a => !a.passed).length;
                                    this.flowRunError = `Step ${i + 1}: ${failCount} assertion(s) failed`;
                                    break;
                                }
                            }
                        } catch (e) {
                            this.flowRunResults.splice(i, 1, {
                                step: i + 1,
                                name: step.name,
                                status: 0,
                                success: false,
                                httpOk: false,
                                assertionResults: [],
                                allAssertionsPassed: true,
                                duration: 0,
                                error: e.message
                            });
                            this.flowRunError = `Step ${i + 1} error: ${e.message}`;
                            if (!continueOnError) break;
                        }
                    }

                    this.flowTotalDuration = Math.round(performance.now() - flowStartTime);
                    this.runningFlow = false;
                    this.currentFlowStep = -1;
                },

                // ---- Step execution (Jupyter-style) ----

                async executeStepAtIndex(i) {
                    const step = this.newFlow.steps[i];
                    this.runningFlow = true;
                    try {
                        const processedStep = this.substituteFlowVariables(step);
                        const result = await this.executeFlowStep(processedStep);
                        const stepKey = `step${i + 1}`;
                        this.flowVariables[stepKey] = this.extractVariablesFromResponse(result.data, step.extractVariables || {});
                        const assertions = step.assertions || [];
                        const assertionResults = this.evaluateAssertions(result, assertions, result.duration);
                        const allAssertionsPassed = assertionResults.length === 0 || assertionResults.every(a => a.passed);
                        const hasExpectedStatus = step.expectedStatus !== null && step.expectedStatus !== undefined && step.expectedStatus !== '';
                        const statusMatches = hasExpectedStatus
                            ? result.status === Number(step.expectedStatus)
                            : (result.status >= 200 && result.status < 300);
                        const resultObj = {
                            step: i + 1, name: step.name, status: result.status,
                            success: statusMatches && allAssertionsPassed, httpOk: statusMatches,
                            expectedStatus: hasExpectedStatus ? Number(step.expectedStatus) : null,
                            assertionResults, allAssertionsPassed,
                            data: result.data, duration: result.duration,
                            extractedVars: this.flowVariables[stepKey]
                        };
                        // Replace existing result at index or set it (splice for reactivity)
                        // Ensure array is long enough
                        while (this.flowRunResults.length <= i) this.flowRunResults.push(null);
                        this.flowRunResults.splice(i, 1, resultObj);
                        this.runningFlow = false;
                        if (!statusMatches) {
                            this.flowRunError = hasExpectedStatus
                                ? `Step ${i + 1}: expected ${step.expectedStatus}, got ${result.status}`
                                : `Step ${i + 1} failed with status ${result.status}`;
                        } else if (!allAssertionsPassed) {
                            this.flowRunError = `Step ${i + 1}: ${assertionResults.filter(a => !a.passed).length} assertion(s) failed`;
                        }
                        return { success: statusMatches && allAssertionsPassed };
                    } catch (e) {
                        while (this.flowRunResults.length <= i) this.flowRunResults.push(null);
                        this.flowRunResults.splice(i, 1, {
                            step: i + 1, name: step.name, status: 0, success: false,
                            httpOk: false, assertionResults: [], allAssertionsPassed: true, duration: 0, error: e.message
                        });
                        this.flowRunError = `Step ${i + 1} error: ${e.message}`;
                        this.runningFlow = false;
                        return { success: false };
                    }
                },

                async runSingleStep(index) {
                    this.runningSingleStep = index;
                    this.flowRunError = null;
                    delete this.flowVariables[`step${index + 1}`];
                    await this.executeStepAtIndex(index);
                    this.flowTotalDuration = this.flowRunResults.filter(r => r).reduce((t, r) => t + (r.duration || 0), 0);
                    this.runningSingleStep = -1;
                },

                // Save a flow step as a standalone saved request, resolving variables to actual values
                async saveStepAsRequest(step, stepIndex) {
                    const resolveVar = (val) => {
                        if (typeof val !== 'string') return val;
                        return val.replace(/@?\{\{(step\d+)\.(\w+)\}\}/g, (match, stepKey, varName) => {
                            return this.flowVariables[stepKey]?.[varName] ?? '';
                        });
                    };
                    const resolveObj = (obj) => {
                        if (!obj || typeof obj !== 'object') return obj;
                        const result = {};
                        for (const [k, v] of Object.entries(obj)) {
                            result[k] = typeof v === 'string' ? resolveVar(v) : v;
                        }
                        return result;
                    };
                    const data = {
                        name: step.name || `${step.endpoint.method.toUpperCase()} ${step.endpoint.path}`,
                        method: step.endpoint.method.toUpperCase(),
                        path: step.endpoint.path,
                        path_params: resolveObj(step.pathParams || {}),
                        query_params: resolveObj(step.params || {}),
                        headers: resolveObj(step.headers || {}),
                        body: resolveObj(step.body || {})
                    };
                    try {
                        const response = await fetch('/apiura/saved-requests', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify(data)
                        });
                        if (response.ok) {
                            this.showToast('Step saved as request!');
                        } else {
                            const err = await response.json();
                            this.showToast(err.message || 'Failed to save', 'error');
                        }
                    } catch (e) {
                        this.showToast('Failed to save: ' + e.message, 'error');
                    }
                },

                // Substitute flow variables in step data
                substituteFlowVariables(step) {
                    const substitute = (obj) => {
                        if (typeof obj === 'string') {
                            // Replace step variable placeholders with actual values
                            return obj.replace(/@?\{\{(step\d+)\.(\w+)\}\}/g, (match, stepKey, varName) => {
                                return this.flowVariables[stepKey]?.[varName] ?? match;
                            });
                        }
                        if (Array.isArray(obj)) {
                            return obj.map(substitute);
                        }
                        if (obj && typeof obj === 'object') {
                            const result = {};
                            for (const [key, value] of Object.entries(obj)) {
                                result[key] = substitute(value);
                            }
                            return result;
                        }
                        return obj;
                    };

                    return {
                        ...step,
                        endpoint: substitute(step.endpoint),
                        pathParams: substitute(step.pathParams || {}),
                        params: substitute(step.params || {}),
                        headers: substitute(step.headers || {}),
                        body: substitute(step.body)
                    };
                },

                // Substitute flow variables in a single string
                substituteFlowVariableString(str) {
                    if (typeof str !== 'string') return str;
                    return str.replace(/@?\{\{(step\d+)\.(\w+)\}\}/g, (match, stepKey, varName) => {
                        return this.flowVariables[stepKey]?.[varName] ?? match;
                    });
                },

                // Execute a single flow step
                async executeFlowStep(step) {
                    const baseUrl = this.getBaseUrl();
                    let url = baseUrl + step.endpoint.path;

                    // Replace path parameters
                    if (step.pathParams) {
                        for (const [key, value] of Object.entries(step.pathParams)) {
                            url = url.replace(`{${key}}`, encodeURIComponent(value));
                        }
                    }

                    // Add query params
                    const params = new URLSearchParams();
                    for (const [key, value] of Object.entries(step.params || {})) {
                        if (value !== '' && value !== null && value !== undefined) {
                            params.append(key, value);
                        }
                    }
                    if (params.toString()) {
                        url += '?' + params.toString();
                    }

                    // Build headers - merge default headers with step-specific headers
                    // Step headers override default headers
                    const defaultHeaders = this.substituteFlowVariables({ headers: this.getCurrentFlow().defaultHeaders || {} }).headers;
                    const headers = {
                        'Accept': 'application/json',
                        ...defaultHeaders,
                        ...step.headers
                    };

                    // Add auth token if we have one
                    if (this.authToken && !headers['Authorization']) {
                        headers['Authorization'] = `Bearer ${this.authToken}`;
                    }

                    const bodyMode = step.bodyMode || 'form';

                    // Build fetch options
                    const options = {
                        method: step.endpoint.method.toUpperCase(),
                        headers
                    };

                    // Add body for non-GET requests
                    if (!['GET', 'HEAD'].includes(options.method)) {
                        if (bodyMode === 'formdata') {
                            const formData = new FormData();
                            for (const entry of (step.formDataEntries || [])) {
                                if (entry.key) {
                                    if (entry.type === 'file' && entry.file) {
                                        formData.append(entry.key, entry.file);
                                    } else {
                                        formData.append(entry.key, this.substituteFlowVariableString(entry.value || ''));
                                    }
                                }
                            }
                            options.body = formData;
                            // Don't set Content-Type for FormData — browser sets it with boundary
                            delete options.headers['Content-Type'];
                        } else if (bodyMode === 'urlencoded') {
                            const params = new URLSearchParams();
                            for (const [key, value] of Object.entries(step.urlencodedBody || {})) {
                                if (key) params.append(key, this.substituteFlowVariableString(value || ''));
                            }
                            options.body = params.toString();
                            options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
                        } else if (bodyMode === 'raw') {
                            options.body = this.substituteFlowVariableString(step.rawBody || '');
                            options.headers['Content-Type'] = step.rawContentType || 'text/plain';
                        } else if (bodyMode === 'json') {
                            // JSON mode: use bodyJson (string) or fall back to body object
                            let jsonBody = step.bodyJson;
                            if (!jsonBody || jsonBody.trim() === '') {
                                jsonBody = JSON.stringify(step.body || {});
                            }
                            options.body = this.substituteFlowVariableString(jsonBody);
                            options.headers['Content-Type'] = 'application/json';
                        } else {
                            // Form mode (default): send body as JSON
                            if (step.body && Object.keys(step.body).length > 0) {
                                // Parse any JSON string values that represent objects/arrays
                                const parsedBody = {};
                                for (const [key, value] of Object.entries(step.body)) {
                                    if (typeof value === 'string' && value.trim()) {
                                        try {
                                            const parsed = JSON.parse(value.trim());
                                            if (typeof parsed === 'object' && parsed !== null) {
                                                parsedBody[key] = parsed;
                                                continue;
                                            }
                                        } catch (e) { /* not JSON, use as-is */ }
                                    }
                                    parsedBody[key] = value;
                                }
                                options.body = JSON.stringify(parsedBody);
                                options.headers['Content-Type'] = 'application/json';
                            }
                        }
                    }

                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 30000);
                    options.signal = controller.signal;

                    const startTime = performance.now();
                    const response = await fetch(url, options);
                    clearTimeout(timeoutId);
                    const text = await response.text();
                    const endTime = performance.now();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        data = text;
                    }

                    return {
                        status: response.status,
                        data,
                        duration: Math.round(endTime - startTime)
                    };
                },

                // Extract variables from response data
                extractVariablesFromResponse(data, extractConfig) {
                    const extracted = {};

                    for (const [varName, jsonPath] of Object.entries(extractConfig)) {
                        if (!jsonPath) continue;

                        // Navigate the JSON path (e.g., "data.user.id" or "data.items[0].name")
                        const value = this.getValueByPath(data, jsonPath);
                        if (value !== undefined) {
                            extracted[varName] = value;
                        }
                    }

                    return extracted;
                },

                // Re-extract variables for a step that has already run (when user adds/removes extract variables after execution)
                reExtractVariablesForStep(stepIndex) {
                    const result = this.flowRunResults[stepIndex];
                    if (!result?.data) return;
                    const step = this.newFlow.steps[stepIndex];
                    if (!step) return;
                    const stepKey = `step${stepIndex + 1}`;
                    this.flowVariables[stepKey] = this.extractVariablesFromResponse(result.data, step.extractVariables || {});
                    // Also update the extractedVars in the result display
                    if (this.flowRunResults[stepIndex]) {
                        this.flowRunResults[stepIndex] = { ...this.flowRunResults[stepIndex], extractedVars: this.flowVariables[stepKey] };
                    }
                },

                // Get value from object by dot-notation path
                getValueByPath(obj, path) {
                    if (!path || !obj) return undefined;

                    // Handle paths like "data.user.id" or "data.items[0].name"
                    const parts = path.replace(/\[(\d+)\]/g, '.$1').split('.');
                    let current = obj;

                    for (const part of parts) {
                        if (current === null || current === undefined) return undefined;
                        current = current[part];
                    }

                    return current;
                },

                // Evaluate assertions against a flow step result
                evaluateAssertions(result, assertions, duration) {
                    if (!assertions || assertions.length === 0) return [];

                    return assertions.map(assertion => {
                        let actual, passed = false;

                        switch (assertion.type) {
                            case 'status':
                                actual = result.status;
                                passed = this.compareValues(actual, assertion.operator, assertion.expected);
                                break;
                            case 'field':
                                actual = this.getValueByPath(result.data, assertion.path);
                                passed = this.compareValues(actual, assertion.operator, assertion.expected);
                                break;
                            case 'responseTime':
                                actual = duration;
                                passed = this.compareValues(actual, assertion.operator, assertion.expected);
                                break;
                            default:
                                actual = undefined;
                                passed = false;
                        }

                        return { ...assertion, passed, actual };
                    });
                },

                // Compare values for assertions
                compareValues(actual, operator, expected) {
                    // Parse expected to number if comparing numerically
                    const numExpected = Number(expected);
                    const numActual = Number(actual);

                    switch (operator) {
                        case 'equals':
                            // Try numeric comparison first, then string
                            if (!isNaN(numExpected) && !isNaN(numActual)) return numActual === numExpected;
                            return String(actual) === String(expected);
                        case 'notEquals':
                            if (!isNaN(numExpected) && !isNaN(numActual)) return numActual !== numExpected;
                            return String(actual) !== String(expected);
                        case 'exists':
                            return actual !== undefined && actual !== null;
                        case 'notExists':
                            return actual === undefined || actual === null;
                        case 'contains':
                            return String(actual).includes(String(expected));
                        case 'greaterThan':
                            return numActual > numExpected;
                        case 'lessThan':
                            return numActual < numExpected;
                        case 'typeof':
                            if (expected === 'array') return Array.isArray(actual);
                            return typeof actual === expected;
                        default:
                            return false;
                    }
                },

                // Create a new flow (clears state for flow builder)
                createNewFlow() {
                    this.editingFlow = null;
                    this.isCreatingFlow = true;
                    this.newFlow = {
                        name: '',
                        description: '',
                        steps: [],
                        defaultHeaders: {},
                        continueOnError: false
                    };
                    this.flowRunResults = [];
                    this.flowRunError = null;
                    this.flowTotalDuration = 0;
                    this.flowVariables = {};
                    this.showFlowsPanel = true;
                },

                // Load a flow for editing (sets editingFlow and resets results)
                loadFlow(flow) {
                    this.editingFlow = JSON.parse(JSON.stringify(flow)); // Deep copy
                    this.isCreatingFlow = false;
                    this.newFlow = {
                        name: flow.name || '',
                        description: flow.description || '',
                        steps: JSON.parse(JSON.stringify(flow.steps || [])),
                        defaultHeaders: JSON.parse(JSON.stringify(flow.defaultHeaders || {})),
                        continueOnError: flow.continueOnError || false
                    };
                    this.flowRunResults = [];
                    this.flowRunError = null;
                    this.flowTotalDuration = 0;
                    this.flowVariables = {};
                    this.showFlowsPanel = true;
                    // Persist flow in URL hash
                    if (flow.id) {
                        history.replaceState(null, '', `#flow/${flow.id}`);
                    }
                },

                // Add endpoint as a step to the current flow
                addEndpointToFlow(endpoint) {
                    const step = {
                        id: Date.now(),
                        name: endpoint.summary || `${endpoint.method} ${endpoint.path}`,
                        endpoint: {
                            method: endpoint.method,
                            path: endpoint.path
                        },
                        pathParams: {},
                        params: {},
                        headers: {},
                        body: {},
                        bodyJson: '{}',
                        bodyMode: 'form',
                        formDataEntries: [],
                        urlencodedBody: {},
                        rawBody: '',
                        rawContentType: 'text/plain',
                        extractVariables: {},
                        assertions: [],
                        expectedResult: '',
                        expectedStatus: null
                    };

                    // Initialize path params
                    this.getPathParameters(endpoint).forEach(param => {
                        step.pathParams[param.name] = '';
                    });

                    this.newFlow.steps.push(step);
                    this.showToast('Step added to flow');
                },

                createTestFlowFromEndpoint() {
                    if (!this.selectedEndpoint) return;
                    const method = this.selectedEndpoint.method.toUpperCase();
                    const path = this.selectedEndpoint.path;
                    const resource = path.split('/').filter(Boolean).pop()?.replace(/[{}]/g, '') || 'resource';
                    const deps = this.getApiDependencies();
                    
                    const steps = [];
                    let stepNum = 0;
                    let authHeader = {};

                    // Auth: if already logged in, use the live token directly (no login step needed)
                    if (this.authToken) {
                        authHeader = { 'Authorization': `Bearer ${this.authToken}` };
                    } else {
                        // Step: Login (only if no token is present)
                        let loginPath = '';
                        if (this.spec?.paths) {
                            for (const [p, methods] of Object.entries(this.spec.paths)) {
                                if (p.includes('login') && methods.post) {
                                    loginPath = p;
                                    break;
                                }
                            }
                        }
                        if (loginPath) {
                            stepNum++;
                            const loginStepName = `step${stepNum}`;
                            steps.push({
                                order: stepNum,
                                name: 'Login',
                                endpoint: { method: 'POST', path: loginPath },
                                params: {},
                                headers: {},
                                body: { email: 'test@example.com', password: 'password' },
                                extractVariables: { token: 'data.token' },
                                expectedStatus: null
                            });
                            authHeader = { 'Authorization': `Bearer @{{${loginStepName}.token}}` };
                        }
                    }
                    
                    // Steps: Fetch dependencies (GET endpoints for _id fields)
                    const depVariableMap = {}; // Maps field_name -> step variable reference
                    
                    if (deps.requires.length > 0) {
                        // Deduplicate by endpoint
                        const uniqueDeps = [];
                        const seenEndpoints = new Set();
                        deps.requires.forEach(dep => {
                            if (!seenEndpoints.has(dep.endpoint)) {
                                seenEndpoints.add(dep.endpoint);
                                uniqueDeps.push(dep);
                            }
                        });
                        
                        uniqueDeps.forEach(dep => {
                            stepNum++;
                            const depStepName = `step${stepNum}`;
                            const depResource = dep.endpoint.split('/').filter(Boolean).pop()?.replace(/[{}]/g, '') || 'resource';
                            
                            steps.push({
                                order: stepNum,
                                name: `Get ${depResource}`,
                                endpoint: { method: 'GET', path: dep.endpoint },
                                params: {},
                                headers: authHeader,
                                body: {},
                                extractVariables: { [`${depResource}_id`]: 'data.0.id', [`first_${depResource}`]: 'data.0' },
                                expectedStatus: null
                            });
                            
                            // Map all _id fields that come from this endpoint
                            deps.requires.filter(d => d.endpoint === dep.endpoint).forEach(d => {
                                depVariableMap[d.field] = `@{{${depStepName}.${depResource}_id}}`;
                            });
                        });
                    }
                    
                    // Step: For PUT/PATCH - GET current resource first and extract overlapping fields
                    let getStepName = null;
                    const getExtractVars = {};
                    if (['PUT', 'PATCH'].includes(method) && path.includes('{')) {
                        stepNum++;
                        getStepName = `step${stepNum}`;

                        // Find fields that exist in both GET response and PUT/PATCH body
                        const bodyFields = this.getRequestBodyFields(this.selectedEndpoint).map(f => f.name);
                        const getEndpoint = this.endpoints.find(ep => ep.path === path && ep.method === 'GET');
                        if (getEndpoint && bodyFields.length > 0) {
                            const responseSchema = getEndpoint.responses?.['200']?.content?.['application/json']?.schema;
                            if (responseSchema) {
                                // Resolve the data property schema
                                let dataSchema = responseSchema?.properties?.data;
                                if (dataSchema?.$ref) {
                                    const refPath = dataSchema.$ref.replace('#/components/schemas/', '');
                                    dataSchema = this.spec?.components?.schemas?.[refPath] || dataSchema;
                                }
                                const responseFields = Object.keys(dataSchema?.properties || {});
                                // Extract fields that appear in both response and request body
                                bodyFields.forEach(fieldName => {
                                    if (responseFields.includes(fieldName)) {
                                        getExtractVars[fieldName] = `data.${fieldName}`;
                                    }
                                });
                            }
                        }

                        steps.push({
                            order: stepNum,
                            name: `Get current ${resource}`,
                            endpoint: { method: 'GET', path: path },
                            params: {},
                            headers: authHeader,
                            body: {},
                            extractVariables: getExtractVars,
                            expectedStatus: null
                        });
                    }

                    // Step: The actual request being tested
                    stepNum++;
                    const mainStepName = `step${stepNum}`;
                    let testBody = {};

                    if (['POST', 'PUT', 'PATCH'].includes(method) && this.hasRequestBody(this.selectedEndpoint)) {
                        // Generate a complete body from the schema using mock data generator
                        const bodySchema = this.getRequestBodySchema(this.selectedEndpoint);
                        if (bodySchema) {
                            const mockBody = this.generateMockFromSchema(bodySchema);
                            if (mockBody && typeof mockBody === 'object') {
                                testBody = { ...mockBody };
                            }
                        }

                        // For PUT/PATCH: override body fields with values from the GET step
                        if (getStepName) {
                            Object.keys(getExtractVars).forEach(fieldName => {
                                testBody[fieldName] = `@{{${getStepName}.${fieldName}}}`;
                            });
                        }

                        // Override _id fields with dependency variable references
                        Object.keys(depVariableMap).forEach(fieldName => {
                            testBody[fieldName] = depVariableMap[fieldName];
                        });
                    }
                    
                    steps.push({
                        order: stepNum,
                        name: `${method} ${resource}`,
                        endpoint: { method, path },
                        params: {},
                        headers: authHeader,
                        body: testBody,
                        extractVariables: method === 'POST' ? { created_id: 'data.id' } : {},
                        expectedStatus: null
                    });
                    
                    // Step: Verify (GET after mutation)
                    if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                        const getEndpoints = this.getRelatedEndpoints().filter(ep => ep.method === 'GET');
                        const detailGet = getEndpoints.find(ep => ep.path.includes('{'));
                        if (detailGet) {
                            stepNum++;
                            steps.push({
                                order: stepNum,
                                name: `Verify ${method === 'DELETE' ? 'deletion' : 'changes'}`,
                                endpoint: { method: 'GET', path: detailGet.path },
                                params: {},
                                headers: authHeader,
                                body: {},
                                extractVariables: {},
                                expectedStatus: null
                            });
                        }
                    }
                    
                    // Open the flows panel with this new flow
                    this.newFlow = {
                        name: `Test: ${method} ${path}`,
                        description: `Auto-generated test flow for ${method} ${path}` + (deps.requires.length > 0 ? `\nDependencies: ${deps.requires.map(d => d.field).join(', ')}` : ''),
                        steps: steps,
                        defaultHeaders: authHeader
                    };
                    this.isCreatingFlow = true;
                    this.showFlowsPanel = true;
                    this.showToast('Test flow created! Review and run it.');
                },

                // Get object-type variables/data from previous flow steps for applying to object fields
                getObjectVariablesForStep(stepIndex) {
                    const results = [];
                    // Iterate from most recent previous step backward
                    for (let i = stepIndex - 1; i >= 0; i--) {
                        const stepKey = `step${i + 1}`;
                        // Check flowVariables for this step
                        const vars = this.flowVariables[stepKey];
                        if (vars) {
                            for (const [varName, value] of Object.entries(vars)) {
                                if (value && typeof value === 'object' && !Array.isArray(value)) {
                                    results.push({ stepKey, varName, value, source: 'variable' });
                                }
                            }
                        }
                        // Check raw response data
                        const result = this.flowRunResults[i];
                        if (result?.data) {
                            const responseData = result.data?.data || result.data;
                            if (responseData && typeof responseData === 'object') {
                                // If the top-level data is an object (not array), offer it
                                if (!Array.isArray(responseData)) {
                                    results.push({ stepKey, varName: 'data', value: responseData, source: 'response' });
                                    // Also scan for nested object fields
                                    for (const [key, val] of Object.entries(responseData)) {
                                        if (val && typeof val === 'object' && !Array.isArray(val)) {
                                            results.push({ stepKey, varName: `data.${key}`, value: val, source: 'response' });
                                        }
                                    }
                                } else if (responseData.length > 0 && typeof responseData[0] === 'object') {
                                    results.push({ stepKey, varName: 'data[0]', value: responseData[0], source: 'response' });
                                }
                            }
                        }
                    }
                    return results;
                },

                // Get the current flow being worked on (always returns newFlow since it holds the working data)
                getCurrentFlow() {
                    return this.newFlow;
                },

                // Get available status codes from an endpoint's OpenAPI responses
                getStatusCodesForEndpoint(endpointInfo) {
                    if (!endpointInfo?.path || !endpointInfo?.method) return [];
                    const pathItem = this.spec?.paths?.[endpointInfo.path];
                    const operation = pathItem?.[endpointInfo.method.toLowerCase()];
                    if (!operation?.responses) return [];
                    return Object.keys(operation.responses)
                        .filter(code => /^\d{3}$/.test(code))
                        .sort()
                        .map(code => ({
                            code: Number(code),
                            description: operation.responses[code]?.description || ''
                        }));
                },

                // Get extractable fields from an endpoint's response schema for a specific status code
                getResponseFieldsForEndpoint(endpointInfo, statusCode) {
                    if (!endpointInfo?.path || !endpointInfo?.method) return [];

                    // Find the endpoint in the spec
                    const endpoint = this.endpoints.find(ep =>
                        ep.path === endpointInfo.path && ep.method.toLowerCase() === endpointInfo.method.toLowerCase()
                    );
                    if (!endpoint) return [];

                    const fields = [];

                    // Get response schema from OpenAPI spec
                    const pathItem = this.spec?.paths?.[endpoint.path];
                    const operation = pathItem?.[endpoint.method.toLowerCase()];
                    const responses = operation?.responses;

                    // Look at the specific status code, or fall back to 200/201
                    let response;
                    if (statusCode) {
                        response = responses?.[String(statusCode)];
                    }
                    if (!response) {
                        response = responses?.['200'] || responses?.['201'];
                    }
                    const schema = response?.content?.['application/json']?.schema;

                    if (schema) {
                        this.extractFieldsFromSchema(schema, '', fields, 0);
                    }

                    // Filter out wrapper fields like 'data', 'success', 'message' - we want the actual data fields
                    const wrapperFields = ['data', 'success', 'message', 'meta', 'links'];
                    const filteredFields = fields.filter(f => {
                        // Keep fields that are inside 'data' (like data.id, data.name)
                        // Remove top-level wrapper fields
                        if (wrapperFields.includes(f.path)) return false; // Remove 'data' itself
                        return true;
                    });

                    // Prioritize fields with 'id' or 'token' in name
                    filteredFields.sort((a, b) => {
                        const aHasId = a.name.includes('id') || a.name === 'token';
                        const bHasId = b.name.includes('id') || b.name === 'token';
                        if (aHasId && !bHasId) return -1;
                        if (bHasId && !aHasId) return 1;
                        return 0;
                    });

                    return filteredFields;
                },

                // Recursively extract fields from a JSON schema
                extractFieldsFromSchema(schema, basePath, fields, depth) {
                    if (depth > 3) return; // Limit recursion depth

                    // Resolve $ref first
                    let resolvedSchema = schema;
                    if (schema.$ref) {
                        const refName = schema.$ref.split('/').pop();
                        resolvedSchema = this.spec?.components?.schemas?.[refName];
                        if (!resolvedSchema) return;
                    }

                    // Handle object with properties
                    if (resolvedSchema.properties) {
                        Object.keys(resolvedSchema.properties).forEach(propName => {
                            const prop = resolvedSchema.properties[propName];
                            const path = basePath ? `${basePath}.${propName}` : propName;

                            // Resolve nested $ref
                            let propSchema = prop;
                            if (prop.$ref) {
                                const refName = prop.$ref.split('/').pop();
                                propSchema = this.spec?.components?.schemas?.[refName] || prop;
                            }

                            // Check if this is a nested object or array - if so, recurse into it
                            const isNestedObject = propSchema.type === 'object' || propSchema.properties;
                            const isArray = propSchema.type === 'array';

                            if (propName === 'data' && (isNestedObject || isArray)) {
                                // Special handling for 'data' wrapper - go inside it
                                if (isArray && propSchema.items) {
                                    // Array of items - extract from items schema
                                    this.extractFieldsFromSchema(propSchema.items, 'data.0', fields, depth + 1);
                                } else {
                                    this.extractFieldsFromSchema(propSchema, 'data', fields, depth + 1);
                                }
                            } else if (isNestedObject && depth < 2) {
                                // Add the field itself
                                fields.push({ name: propName, path: path, type: 'object' });
                                // Also recurse into nested objects
                                this.extractFieldsFromSchema(propSchema, path, fields, depth + 1);
                            } else if (isArray) {
                                // For arrays, show path to first item's fields
                                fields.push({ name: propName, path: path, type: 'array' });
                                if (propSchema.items && depth < 2) {
                                    this.extractFieldsFromSchema(propSchema.items, `${path}.0`, fields, depth + 1);
                                }
                            } else {
                                // Leaf field (string, number, boolean, etc.)
                                fields.push({
                                    name: propName,
                                    path: path,
                                    type: propSchema.type || 'string'
                                });
                            }
                        });
                    }

                    // Handle allOf (common in OpenAPI)
                    if (resolvedSchema.allOf) {
                        resolvedSchema.allOf.forEach(subSchema => {
                            this.extractFieldsFromSchema(subSchema, basePath, fields, depth);
                        });
                    }
                },

                // ============ FLOW STEP CONFIGURATION HELPERS ============

                // Get body fields for an endpoint (for form-based body editing in flow steps)
                getBodyFieldsForEndpoint(endpointInfo) {
                    if (!endpointInfo?.path || !endpointInfo?.method) return [];

                    const endpoint = this.endpoints.find(ep =>
                        ep.path === endpointInfo.path &&
                        ep.method.toLowerCase() === endpointInfo.method.toLowerCase()
                    );
                    if (!endpoint) return [];

                    const pathItem = this.spec?.paths?.[endpoint.path];
                    const operation = pathItem?.[endpoint.method.toLowerCase()];
                    const requestBody = operation?.requestBody;

                    if (!requestBody) return [];

                    const schema = this.getRequestBodySchema(operation);
                    if (!schema) return [];

                    const fields = [];
                    const resolvedSchema = this.resolveSchemaRef(schema);

                    if (resolvedSchema?.properties) {
                        const required = resolvedSchema.required || [];
                        for (const [name, prop] of Object.entries(resolvedSchema.properties)) {
                            const resolvedProp = this.resolveSchemaRef(prop);
                            fields.push({
                                name,
                                type: resolvedProp.type || 'string',
                                required: required.includes(name),
                                example: resolvedProp.example,
                                enum: resolvedProp.enum,
                                description: resolvedProp.description,
                                format: resolvedProp.format
                            });
                        }
                    }

                    return fields;
                },

                // Resolve $ref in schema
                resolveSchemaRef(schema) {
                    if (!schema) return null;
                    if (schema.$ref) {
                        const refName = schema.$ref.split('/').pop();
                        return this.spec?.components?.schemas?.[refName] || schema;
                    }
                    return schema;
                },

                // Get query parameters for an endpoint (for flow step configuration)
                getQueryParamsForEndpoint(endpointInfo) {
                    if (!endpointInfo?.path || !endpointInfo?.method) return [];

                    const endpoint = this.endpoints.find(ep =>
                        ep.path === endpointInfo.path &&
                        ep.method.toLowerCase() === endpointInfo.method.toLowerCase()
                    );
                    if (!endpoint) return [];

                    const pathItem = this.spec?.paths?.[endpoint.path];
                    const operation = pathItem?.[endpoint.method.toLowerCase()];

                    return (operation?.parameters || [])
                        .filter(p => p.in === 'query')
                        .map(p => ({
                            name: p.name,
                            type: p.schema?.type || 'string',
                            required: p.required || false,
                            description: p.description,
                            example: p.example || p.schema?.example
                        }));
                },

                // Add default header to flow
                addDefaultHeader() {
                    if (!this.newDefaultHeaderKey.trim()) return;
                    this.newFlow.defaultHeaders = this.newFlow.defaultHeaders || {};
                    this.newFlow.defaultHeaders[this.newDefaultHeaderKey.trim()] = this.newDefaultHeaderValue;
                    this.newDefaultHeaderKey = '';
                    this.newDefaultHeaderValue = '';
                },

                // Remove default header from flow
                removeDefaultHeader(key) {
                    delete this.newFlow.defaultHeaders[key];
                },

                // Add header to a step
                addStepHeader(step, key, value) {
                    step.headers = step.headers || {};
                    step.headers[key] = value;
                },

                // Remove header from a step
                removeStepHeader(step, key) {
                    delete step.headers[key];
                },

                // Add query param to a step
                addStepParam(step, key, value) {
                    step.params = step.params || {};
                    step.params[key] = value;
                },

                // Remove query param from a step
                removeStepParam(step, key) {
                    delete step.params[key];
                },

                // Parse JSON body for a step
                parseStepBodyJson(step) {
                    try {
                        step.body = JSON.parse(step.bodyJson || '{}');
                        return true;
                    } catch (e) {
                        return false;
                    }
                },

                // Sync body to JSON for a step
                syncStepBodyToJson(step) {
                    step.bodyJson = JSON.stringify(step.body || {}, null, 2);
                },

                // Resolve a flow step to its full endpoint object from the spec
                getFullEndpointForStep(step) {
                    if (!step?.endpoint) return null;
                    return this.endpoints.find(e =>
                        e.method.toUpperCase() === step.endpoint.method.toUpperCase() && e.path === step.endpoint.path
                    ) || null;
                },

                // Show import modal for a step
                showImportModal(stepIndex) {
                    this.importingStepIndex = stepIndex;
                    // Default filter to the step's endpoint
                    const step = this.newFlow.steps[stepIndex];
                    if (step?.endpoint) {
                        this.importFilterEndpoint = `${step.endpoint.method?.toUpperCase()} ${step.endpoint.path}`;
                    } else {
                        this.importFilterEndpoint = '';
                    }
                    this.showingImportModal = true;
                    // Ensure saved requests are loaded
                    if (this.savedRequests.length === 0) {
                        this.loadSavedRequests();
                    }
                },

                // Get unique endpoints from saved requests for filtering
                getUniqueSavedEndpoints() {
                    const endpoints = new Set();
                    this.savedRequests.forEach(saved => {
                        endpoints.add(`${saved.method?.toUpperCase()} ${saved.path}`);
                    });
                    return Array.from(endpoints).sort();
                },

                // Get filtered saved requests for import modal
                getFilteredSavedForImport() {
                    let filtered;
                    if (!this.importFilterEndpoint) {
                        filtered = this.savedRequests;
                    } else {
                        filtered = this.savedRequests.filter(saved => {
                            const endpoint = `${saved.method?.toUpperCase()} ${saved.path}`;
                            return endpoint === this.importFilterEndpoint;
                        });
                    }
                    return this.sortSavedRequests(filtered);
                },

                // Import saved request configuration to a step
                importSavedToStep(saved) {
                    if (this.importingStepIndex < 0 || this.importingStepIndex >= this.newFlow.steps.length) {
                        this.showingImportModal = false;
                        return;
                    }

                    const step = this.newFlow.steps[this.importingStepIndex];

                    // Import headers
                    if (saved.headers && Object.keys(saved.headers).length) {
                        step.headers = { ...step.headers, ...saved.headers };
                    }

                    // Import query params
                    if (saved.query_params?.values) {
                        step.params = { ...step.params, ...saved.query_params.values };
                    }

                    // Import body
                    if (saved.body && Object.keys(saved.body).length) {
                        step.body = { ...step.body, ...saved.body };
                        step.bodyJson = JSON.stringify(step.body, null, 2);
                    }

                    // Import path params
                    if (saved.path_params && Object.keys(saved.path_params).length) {
                        step.pathParams = { ...step.pathParams, ...saved.path_params };
                    }

                    this.showingImportModal = false;
                    this.showToast('Configuration imported from saved request');
                },

                // Get available variables for a step (from previous steps)
                getAvailableVariablesForStep(stepIndex) {
                    const available = {};
                    for (let i = 0; i < stepIndex; i++) {
                        const step = this.newFlow.steps[i];
                        const stepKey = `step${i + 1}`;
                        if (step.extractVariables && Object.keys(step.extractVariables).length) {
                            available[stepKey] = {};
                            for (const varName of Object.keys(step.extractVariables)) {
                                // Show extracted value if flow has been run, otherwise show path
                                available[stepKey][varName] = this.flowVariables[stepKey]?.[varName]
                                    ?? `(from ${step.extractVariables[varName]})`;
                            }
                        }
                    }
                    return available;
                },

                // Open variable picker for a field
                openVariablePicker(step, field, subfield, event) {
                    const stepIndex = this.newFlow.steps.indexOf(step);
                    if (stepIndex < 0) return;

                    this.variablePickerTarget = { stepIndex, field, subfield };
                    this.variablePickerOpen = true;
                    this.autocompleteMode = false;

                    // Position near the button
                    if (event) {
                        const rect = event.target.getBoundingClientRect();
                        this.variablePickerPosition = {
                            top: rect.bottom + window.scrollY + 4,
                            left: rect.left + window.scrollX
                        };
                    }
                },

                // Insert variable into a field
                insertVariable(stepKey, varName) {
                    if (!this.variablePickerTarget) return;

                    const { stepIndex, field, subfield } = this.variablePickerTarget;
                    const step = this.newFlow.steps[stepIndex];
                    const varString = `@{{${stepKey}.${varName}}}`;

                    if (field === 'body' && subfield) {
                        step.body = step.body || {};
                        step.body[subfield] = varString;
                    } else if (field === 'headers' && subfield) {
                        step.headers = step.headers || {};
                        step.headers[subfield] = varString;
                    } else if (field === 'params' && subfield) {
                        step.params = step.params || {};
                        step.params[subfield] = varString;
                    } else if (field === 'pathParams' && subfield) {
                        step.pathParams = step.pathParams || {};
                        step.pathParams[subfield] = varString;
                    }

                    this.variablePickerOpen = false;
                    this.variablePickerTarget = null;
                },

                // Handle variable autocomplete while typing
                handleVariableAutocomplete(event, step, field, subfield) {
                    const value = event.target.value;
                    const cursorPos = event.target.selectionStart;

                    // Check if user just typed @{{ or {{
                    const textBeforeCursor = value.substring(0, cursorPos);
                    const match = textBeforeCursor.match(/@?\{\{(\w*)$/);

                    if (match) {
                        const stepIndex = this.newFlow.steps.indexOf(step);
                        if (stepIndex < 0) return;

                        this.variablePickerTarget = {
                            stepIndex,
                            field,
                            subfield,
                            inputEl: event.target,
                            partialMatch: match[1],
                            matchStart: cursorPos - match[0].length
                        };
                        this.autocompleteMode = true;
                        this.variablePickerOpen = true;

                        // Position near the input
                        const rect = event.target.getBoundingClientRect();
                        this.variablePickerPosition = {
                            top: rect.bottom + window.scrollY + 4,
                            left: rect.left + window.scrollX
                        };
                    } else {
                        this.variablePickerOpen = false;
                    }
                },

                // Insert variable in autocomplete mode (replaces partial text)
                insertVariableAutocomplete(stepKey, varName) {
                    if (!this.variablePickerTarget || !this.autocompleteMode) {
                        this.insertVariable(stepKey, varName);
                        return;
                    }

                    const { stepIndex, field, subfield, inputEl, matchStart } = this.variablePickerTarget;
                    const step = this.newFlow.steps[stepIndex];
                    const varString = `@{{${stepKey}.${varName}}}`;

                    // Get current value and replace the partial match
                    let currentValue = '';
                    if (field === 'body' && subfield) {
                        currentValue = step.body?.[subfield] || '';
                    } else if (field === 'headers' && subfield) {
                        currentValue = step.headers?.[subfield] || '';
                    } else if (field === 'params' && subfield) {
                        currentValue = step.params?.[subfield] || '';
                    } else if (field === 'pathParams' && subfield) {
                        currentValue = step.pathParams?.[subfield] || '';
                    }

                    const cursorPos = inputEl?.selectionStart || currentValue.length;
                    const newValue = currentValue.substring(0, matchStart) + varString + currentValue.substring(cursorPos);

                    // Update the appropriate field
                    if (field === 'body' && subfield) {
                        step.body = step.body || {};
                        step.body[subfield] = newValue;
                    } else if (field === 'headers' && subfield) {
                        step.headers = step.headers || {};
                        step.headers[subfield] = newValue;
                    } else if (field === 'params' && subfield) {
                        step.params = step.params || {};
                        step.params[subfield] = newValue;
                    } else if (field === 'pathParams' && subfield) {
                        step.pathParams = step.pathParams || {};
                        step.pathParams[subfield] = newValue;
                    }

                    this.variablePickerOpen = false;
                    this.variablePickerTarget = null;
                    this.autocompleteMode = false;
                },

                // Check if a method requires body
                methodRequiresBody(method) {
                    return ['POST', 'PUT', 'PATCH'].includes(method?.toUpperCase());
                },

                // Get response data from previously executed flow steps
                getPreviousStepResponseData(stepIndex) {
                    const results = [];
                    for (let i = stepIndex - 1; i >= 0; i--) {
                        const result = this.flowRunResults[i];
                        if (result?.data) {
                            const stepKey = `step${i + 1}`;
                            const stepName = this.newFlow.steps[i]?.name || stepKey;
                            // Unwrap Laravel's data.data wrapper
                            const unwrapped = result.data?.data !== undefined ? result.data.data : result.data;
                            results.push({ stepIndex: i, stepKey, stepName, data: unwrapped, rawData: result.data });
                        }
                    }
                    return results;
                },

                // Find a matching value for a field name in previous step responses
                // Returns { value, source, stepIndex, stepKey, jsonPath } for auto-extraction
                findValueInPreviousResponses(fieldName, previousResponses) {
                    for (const prev of previousResponses) {
                        const data = prev.data;
                        if (!data || typeof data !== 'object') continue;

                        // For arrays, use first item
                        const isArray = Array.isArray(data);
                        const obj = isArray ? data[0] : data;
                        if (!obj || typeof obj !== 'object') continue;

                        // 1. Direct key match in unwrapped data
                        if (obj[fieldName] !== undefined && obj[fieldName] !== null) {
                            const jsonPath = isArray ? `data.data.0.${fieldName}` : (prev.rawData?.data !== undefined ? `data.data.${fieldName}` : `data.${fieldName}`);
                            return { value: obj[fieldName], source: `${prev.stepKey} (${prev.stepName})`, stepIndex: prev.stepIndex, stepKey: prev.stepKey, jsonPath };
                        }

                        // 2. Check top-level rawData too
                        if (prev.rawData && typeof prev.rawData === 'object' && prev.rawData[fieldName] !== undefined) {
                            return { value: prev.rawData[fieldName], source: `${prev.stepKey} (${prev.stepName})`, stepIndex: prev.stepIndex, stepKey: prev.stepKey, jsonPath: `data.${fieldName}` };
                        }

                        // 3. _id suffix match: if field is "wallet_id", look for "id" in a step whose path contains "wallet"
                        if (fieldName.endsWith('_id')) {
                            const resource = fieldName.slice(0, -3); // "wallet_id" -> "wallet"
                            const stepPath = this.newFlow.steps[prev.stepIndex]?.endpoint?.path || '';
                            if (stepPath.toLowerCase().includes(resource.toLowerCase())) {
                                if (obj.id !== undefined && obj.id !== null) {
                                    const jsonPath = isArray ? `data.data.0.id` : (prev.rawData?.data !== undefined ? `data.data.id` : `data.id`);
                                    return { value: obj.id, source: `${prev.stepKey} (${prev.stepName}).id`, stepIndex: prev.stepIndex, stepKey: prev.stepKey, jsonPath };
                                }
                            }
                        }
                    }
                    return null;
                },

                // Prefill a flow step with example values, preferring data from previous steps
                prefillStepWithExamples(step) {
                    if (!step?.endpoint) return;

                    // Find the full endpoint definition
                    const endpoint = this.endpoints.find(ep =>
                        ep.path === step.endpoint.path &&
                        ep.method.toLowerCase() === step.endpoint.method.toLowerCase()
                    );
                    if (!endpoint) {
                        this.showToast('Endpoint not found in spec', 'error');
                        return;
                    }

                    const pathItem = this.spec?.paths?.[endpoint.path];
                    const operation = pathItem?.[endpoint.method.toLowerCase()];

                    // Gather previous step data for smart fill
                    const stepIndex = this.newFlow.steps.indexOf(step);
                    const previousResponses = stepIndex > 0 ? this.getPreviousStepResponseData(stepIndex) : [];

                    // Track auto-filled fields: { fieldName: { source, type: 'variable'|'discovered' } }
                    const autoFilledFields = {};
                    let discoveredCount = 0;
                    let variableCount = 0;

                    // Helper: try to find value from flowVariables first, then raw responses
                    // Also auto-extracts discovered values as variables on the source step
                    // Returns { value, stepKey, varName, source, type } where stepKey+varName form the variable ref
                    const findSmartValue = (fieldName) => {
                        // Priority 1: flowVariables (explicitly extracted by user) - check all steps, last first
                        for (let i = stepIndex - 1; i >= 0; i--) {
                            const sKey = `step${i + 1}`;
                            const vars = this.flowVariables[sKey];
                            if (vars && vars[fieldName] !== undefined && vars[fieldName] !== null) {
                                return { value: vars[fieldName], stepKey: sKey, varName: fieldName, source: sKey, type: 'variable' };
                            }
                        }
                        // Priority 2: Raw response data from all previous steps (most recent first)
                        if (previousResponses.length > 0) {
                            const found = this.findValueInPreviousResponses(fieldName, previousResponses);
                            if (found) {
                                // Auto-extract: add to source step's extractVariables and flowVariables
                                const sourceStep = this.newFlow.steps[found.stepIndex];
                                if (sourceStep) {
                                    sourceStep.extractVariables = sourceStep.extractVariables || {};
                                    if (!sourceStep.extractVariables[fieldName]) {
                                        sourceStep.extractVariables[fieldName] = found.jsonPath;
                                    }
                                }
                                this.flowVariables[found.stepKey] = this.flowVariables[found.stepKey] || {};
                                this.flowVariables[found.stepKey][fieldName] = found.value;
                                // Update extractedVars in the result display
                                if (this.flowRunResults[found.stepIndex]) {
                                    this.flowRunResults[found.stepIndex] = { ...this.flowRunResults[found.stepIndex], extractedVars: this.flowVariables[found.stepKey] };
                                }
                                return { value: found.value, stepKey: found.stepKey, varName: fieldName, source: found.source, type: 'discovered' };
                            }
                        }
                        return null;
                    };

                    // Build a variable reference string like @{{step1.wallet_id}}
                    const varRef = (smart) => `@{{${smart.stepKey}.${smart.varName}}}`;

                    // Prefill path params
                    if (step.pathParams) {
                        const pathParams = (operation?.parameters || []).filter(p => p.in === 'path');
                        pathParams.forEach(param => {
                            const smart = findSmartValue(param.name);
                            if (smart) {
                                step.pathParams[param.name] = varRef(smart);
                                autoFilledFields[param.name] = { source: smart.source, type: smart.type, ref: varRef(smart), section: 'pathParams' };
                                if (smart.type === 'discovered') discoveredCount++; else variableCount++;
                            } else if (param.schema?.example !== undefined) {
                                step.pathParams[param.name] = String(param.schema.example);
                            } else if (param.schema?.type === 'integer') {
                                step.pathParams[param.name] = '1';
                            } else {
                                step.pathParams[param.name] = param.name;
                            }
                        });
                    }

                    // Prefill query params
                    const queryParams = (operation?.parameters || []).filter(p => p.in === 'query');
                    step.params = step.params || {};
                    queryParams.forEach(param => {
                        const smart = findSmartValue(param.name);
                        if (smart) {
                            step.params[param.name] = varRef(smart);
                            autoFilledFields[param.name] = { source: smart.source, type: smart.type, ref: varRef(smart), section: 'params' };
                            if (smart.type === 'discovered') discoveredCount++; else variableCount++;
                        } else if (param.schema?.example !== undefined) {
                            step.params[param.name] = String(param.schema.example);
                        } else if (param.schema?.enum?.[0]) {
                            step.params[param.name] = param.schema.enum[0];
                        } else if (param.required) {
                            step.params[param.name] = '';
                        }
                    });

                    // Prefill body
                    if (this.methodRequiresBody(step.endpoint.method)) {
                        const schema = this.getRequestBodySchema(operation);
                        if (schema) {
                            const mockBody = this.generateMockFromSchema(schema);
                            if (mockBody && typeof mockBody === 'object') {
                                const smartBody = {};
                                for (const [key, mockValue] of Object.entries(mockBody)) {
                                    const smart = findSmartValue(key);
                                    if (smart) {
                                        // For object values, keep the raw JSON (variable refs don't work inside JSON)
                                        if (typeof smart.value === 'object') {
                                            smartBody[key] = JSON.stringify(smart.value, null, 2);
                                        } else {
                                            smartBody[key] = varRef(smart);
                                        }
                                        autoFilledFields[key] = { source: smart.source, type: smart.type, ref: varRef(smart), section: 'body' };
                                        if (smart.type === 'discovered') discoveredCount++; else variableCount++;
                                    } else {
                                        smartBody[key] = mockValue;
                                    }
                                }
                                step.body = { ...step.body, ...smartBody };
                                step.bodyJson = JSON.stringify(step.body, null, 2);
                            }
                        }
                    }

                    // Store auto-filled info on step for visual indicators
                    step._autoFilledFields = autoFilledFields;
                    // Clear the indicators after 15 seconds
                    setTimeout(() => { step._autoFilledFields = {}; }, 15000);

                    // Build descriptive toast
                    const total = discoveredCount + variableCount;
                    if (total > 0) {
                        let msg = `Filled ${total} field${total > 1 ? 's' : ''} from previous steps`;
                        if (discoveredCount > 0) {
                            msg += ` (${discoveredCount} newly extracted)`;
                        }
                        this.showToast(msg, 'success');
                    } else {
                        this.showToast('Examples filled from spec');
                    }
                },

                // ============ EXPORT METHODS ============

                // Export to Postman Collection v2.1
                exportToPostman() {
                    const baseUrl = this.getBaseUrl();

                    const collection = {
                        info: {
                            name: this.spec.info?.title || 'API Collection',
                            description: this.spec.info?.description || '',
                            schema: 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
                        },
                        item: []
                    };

                    // Group by tag
                    const folders = {};
                    this.endpoints.forEach(ep => {
                        const tag = ep.tags[0] || 'Other';
                        if (!folders[tag]) {
                            folders[tag] = { name: tag, item: [] };
                        }

                        // Build URL with path variables
                        const urlPath = ep.path.replace(/\{([^}]+)\}/g, ':$1');

                        // Build headers
                        const headers = [
                            { key: 'Content-Type', value: 'application/json' },
                            { key: 'Accept', value: 'application/json' },
                            { key: 'Authorization', value: 'Bearer @{{token}}' }
                        ];

                        // Build request body if applicable
                        let body = undefined;
                        const epSchema = this.getRequestBodySchema(ep);
                        if (epSchema) {
                            const mockBody = this.generateMockFromSchema(epSchema);
                            body = {
                                mode: 'raw',
                                raw: JSON.stringify(mockBody, null, 2),
                                options: {
                                    raw: { language: 'json' }
                                }
                            };
                        }

                        // Build query parameters
                        const queryParams = (ep.parameters || [])
                            .filter(p => p.in === 'query')
                            .map(p => ({
                                key: p.name,
                                value: '',
                                description: p.description || '',
                                disabled: !p.required
                            }));

                        folders[tag].item.push({
                            name: ep.summary || `${ep.method.toUpperCase()} ${ep.path}`,
                            request: {
                                method: ep.method.toUpperCase(),
                                header: headers,
                                url: {
                                    raw: baseUrl + urlPath + (queryParams.length > 0 ? '?' + queryParams.map(q => `${q.key}=`).join('&') : ''),
                                    host: [baseUrl.replace(/https?:\/\//, '')],
                                    path: urlPath.split('/').filter(Boolean),
                                    query: queryParams
                                },
                                body: body,
                                description: ep.description || ''
                            }
                        });
                    });

                    collection.item = Object.values(folders);

                    this.downloadJson(collection, 'postman-collection.json');
                    this.showToast('Postman collection downloaded');
                },

                // Export to Markdown documentation
                exportToMarkdown() {
                    let md = `# ${this.spec.info?.title || 'API Documentation'}\n\n`;
                    md += `**Version:** ${this.spec.info?.version || '1.0.0'}\n\n`;
                    md += `**Base URL:** \`${this.getBaseUrl()}\`\n\n`;
                    if (this.spec.info?.description) {
                        md += `${this.spec.info.description}\n\n`;
                    }
                    md += '---\n\n';
                    md += '## Table of Contents\n\n';

                    // Generate TOC
                    Object.keys(this.endpointsByTag).forEach(tag => {
                        md += `- [${tag}](#${tag.toLowerCase().replace(/\s+/g, '-')})\n`;
                    });
                    md += '\n---\n\n';

                    // Group by tag
                    Object.entries(this.endpointsByTag).forEach(([tag, endpoints]) => {
                        md += `## ${tag}\n\n`;

                        endpoints.forEach(ep => {
                            const methodBadge = ep.method.toUpperCase();
                            md += `### ${methodBadge} ${ep.path}\n\n`;
                            if (ep.summary) md += `**${ep.summary}**\n\n`;
                            if (ep.description) md += `${ep.description}\n\n`;

                            // Authentication
                            if (ep.security && ep.security.length > 0) {
                                md += '**Authentication:** Required (Bearer Token)\n\n';
                            }

                            // Parameters
                            const params = ep.parameters || [];
                            const pathParams = params.filter(p => p.in === 'path');
                            const queryParams = params.filter(p => p.in === 'query');

                            if (pathParams.length > 0) {
                                md += '**Path Parameters:**\n\n';
                                md += '| Name | Type | Required | Description |\n';
                                md += '|------|------|----------|-------------|\n';
                                pathParams.forEach(p => {
                                    md += `| \`${p.name}\` | ${p.schema?.type || 'string'} | ${p.required ? 'Yes' : 'No'} | ${p.description || '-'} |\n`;
                                });
                                md += '\n';
                            }

                            if (queryParams.length > 0) {
                                md += '**Query Parameters:**\n\n';
                                md += '| Name | Type | Required | Description |\n';
                                md += '|------|------|----------|-------------|\n';
                                queryParams.forEach(p => {
                                    md += `| \`${p.name}\` | ${p.schema?.type || 'string'} | ${p.required ? 'Yes' : 'No'} | ${p.description || '-'} |\n`;
                                });
                                md += '\n';
                            }

                            // Request body
                            const mdBodySchema = this.getRequestBodySchema(ep);
                            if (mdBodySchema) {
                                md += '**Request Body:**\n\n```json\n';
                                const body = this.generateMockFromSchema(mdBodySchema);
                                md += JSON.stringify(body, null, 2);
                                md += '\n```\n\n';
                            }

                            // Responses
                            if (ep.responses) {
                                md += '**Responses:**\n\n';
                                Object.entries(ep.responses).forEach(([code, resp]) => {
                                    const statusText = this.getStatusText(code);
                                    md += `- **${code} ${statusText}**: ${resp.description || ''}\n`;
                                });
                                md += '\n';
                            }

                            // Example cURL
                            md += '**Example Request:**\n\n```bash\n';
                            md += this.generateCurlForEndpoint(ep);
                            md += '\n```\n\n';

                            md += '---\n\n';
                        });
                    });

                    this.downloadFile(md, 'api-documentation.md', 'text/markdown');
                    this.showToast('Markdown documentation downloaded');
                },

                // Generate cURL command for an endpoint
                generateCurlForEndpoint(ep) {
                    const baseUrl = this.getBaseUrl();
                    let path = ep.path;

                    // Replace path parameters with example values
                    const pathParams = (ep.parameters || []).filter(p => p.in === 'path');
                    pathParams.forEach(p => {
                        const exampleValue = p.schema?.example || p.example || `{${p.name}}`;
                        path = path.replace(`{${p.name}}`, exampleValue);
                    });

                    let curl = `curl -X ${ep.method.toUpperCase()} "${baseUrl}${path}"`;
                    curl += ` \\\n  -H "Content-Type: application/json"`;
                    curl += ` \\\n  -H "Accept: application/json"`;
                    curl += ` \\\n  -H "Authorization: Bearer YOUR_TOKEN"`;

                    const curlBodySchema = this.getRequestBodySchema(ep);
                    if (curlBodySchema) {
                        const body = this.generateMockFromSchema(curlBodySchema);
                        curl += ` \\\n  -d '${JSON.stringify(body)}'`;
                    }

                    return curl;
                },

                // Download OpenAPI JSON (with examples)
                exportOpenApiJson() {
                    this.downloadJson(this.spec, 'openapi-with-examples.json');
                    this.showToast('OpenAPI + Examples downloaded');
                },

                // Download OpenAPI JSON with examples stripped
                exportOpenApiClean() {
                    const stripped = this.deepStripExamples(JSON.parse(JSON.stringify(this.spec)));
                    this.downloadJson(stripped, 'openapi-clean.json');
                    this.showToast('OpenAPI Clean downloaded');
                },

                // Export flow as JSON
                exportFlowAsJson() {
                    const flow = this.editingFlow || this.newFlow;
                    const exported = this.buildFlowExport(flow);
                    this.downloadJson(exported, (flow.name || 'flow').replace(/[^a-zA-Z0-9_-]/g, '_') + '.json');
                    this.showToast('Flow exported as JSON');
                },

                // Export flow as YAML
                exportFlowAsYaml() {
                    const flow = this.editingFlow || this.newFlow;
                    const exported = this.buildFlowExport(flow);
                    const yaml = jsyaml.dump(exported, { lineWidth: 120, noRefs: true });
                    this.downloadFile(yaml, (flow.name || 'flow').replace(/[^a-zA-Z0-9_-]/g, '_') + '.yaml', 'text/yaml');
                    this.showToast('Flow exported as YAML');
                },

                // Build portable flow export object
                buildFlowExport(flow) {
                    return {
                        name: flow.name || 'Untitled Flow',
                        description: flow.description || '',
                        continueOnError: flow.continueOnError || false,
                        defaultHeaders: flow.defaultHeaders || {},
                        steps: (flow.steps || []).map(s => ({
                            name: s.name,
                            endpoint: s.endpoint,
                            pathParams: s.pathParams || {},
                            params: s.params || {},
                            headers: s.headers || {},
                            body: s.body || {},
                            expectedStatus: s.expectedStatus || null,
                            extractVariables: s.extractVariables || {},
                            assertions: (s.assertions || []).map(a => ({
                                type: a.type,
                                ...(a.path ? { path: a.path } : {}),
                                operator: a.operator,
                                ...(a.expected !== undefined ? { expected: a.expected } : {})
                            }))
                        }))
                    };
                },

                // Import flow from file — routes through Import Preview modal
                importFlow() {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = '.json,.yaml,.yml';
                    input.onchange = async (e) => {
                        const file = e.target.files[0];
                        if (!file) return;
                        const text = await file.text();
                        try {
                            let data;
                            if (file.name.endsWith('.yaml') || file.name.endsWith('.yml')) {
                                data = jsyaml.load(text);
                            } else {
                                data = JSON.parse(text);
                            }
                            this.loadFlowFromImport(data, file.name.replace(/\.(json|yaml|yml)$/i, ''));
                        } catch (err) {
                            this.showToast('Failed to parse file: ' + err.message, 'error');
                        }
                    };
                    input.click();
                },

                // Load imported flow data — routes through Import Preview modal
                loadFlowFromImport(data, suggestedName = '') {
                    let items = [];
                    if (Array.isArray(data)) {
                        items = data.filter(d => d && Array.isArray(d.steps));
                    } else if (data && Array.isArray(data.steps)) {
                        items = [data];
                    }

                    if (items.length === 0) {
                        this.showToast('No valid flows found in file', 'error');
                        return;
                    }

                    this.openImportPreview(items, 'flow', suggestedName);
                },

                // Build internal flow object from imported data
                buildFlowFromImportData(data) {
                    return {
                        name: data.name || 'Imported Flow',
                        description: data.description || '',
                        steps: (data.steps || []).map((s, i) => ({
                            id: Date.now() + i,
                            name: s.name || `Step ${i + 1}`,
                            endpoint: s.endpoint || { method: 'GET', path: '/' },
                            pathParams: s.pathParams || {},
                            params: Array.isArray(s.params) ? {} : (s.params || {}),
                            headers: s.headers || {},
                            body: s.body || {},
                            bodyJson: JSON.stringify(s.body || {}, null, 2),
                            bodyMode: 'form',
                            extractVariables: s.extractVariables || {},
                            assertions: (s.assertions || []).map(a => this.normalizeImportedAssertion(a)),
                            expectedStatus: s.expectedStatus ?? null,
                            expectedResult: s.expectedResult || ''
                        })),
                        defaultHeaders: data.defaultHeaders || {},
                        continueOnError: data.continueOnError || false
                    };
                },

                // Normalize assertion formats from various sources into our internal format
                normalizeImportedAssertion(a) {
                    // Already in our format
                    if (a.type === 'status' || a.type === 'field' || a.type === 'responseTime') {
                        return {
                            type: a.type,
                            path: a.path || '',
                            operator: a.operator || 'equals',
                            expected: a.expected !== undefined ? String(a.expected) : ''
                        };
                    }
                    // Handle jsonPath format (e.g. from Postman-style or user-authored files)
                    if (a.type === 'jsonPath' || a.path) {
                        const conditionMap = { exists: 'exists', notExists: 'notExists', equals: 'equals', contains: 'contains', greaterThan: 'greaterThan', lessThan: 'lessThan' };
                        return {
                            type: 'field',
                            path: a.path || '',
                            operator: conditionMap[a.condition] || a.condition || 'exists',
                            expected: a.value !== undefined ? String(a.value) : (a.expected !== undefined ? String(a.expected) : '')
                        };
                    }
                    // Fallback
                    return { type: a.type || 'field', path: a.path || '', operator: a.operator || 'exists', expected: a.expected || '' };
                },

                // Import multiple flows by saving each one
                async importMultipleFlows(flowsData) {
                    const validFlows = flowsData
                        .filter(data => data && Array.isArray(data.steps))
                        .map(data => this.buildFlowFromImportData(data));

                    if (validFlows.length === 0) {
                        this.showToast('No valid flows found in file', 'error');
                        return;
                    }

                    try {
                        const response = await fetch('/apiura/flows/bulk', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({ flows: validFlows })
                        });

                        if (response.ok) {
                            const result = await response.json();
                            await this.loadFlows();
                            this.activeActivity = 'flows';
                            this.showFlowsPanel = true;
                            this.showToast(`${result.count} flow(s) imported`);
                        } else {
                            const err = await response.json().catch(() => ({}));
                            this.showToast(err.message || 'Failed to import flows', 'error');
                        }
                    } catch (e) {
                        console.error('Failed to import flows:', e);
                        this.showToast('Failed to import flows: ' + e.message, 'error');
                    }
                },

                // =============== MODULE METHODS ===============

                async loadModules() {
                    this.loadingModules = true;
                    try {
                        const response = await fetch('/apiura/modules?with_items=true');
                        if (response.ok) {
                            const result = await response.json();
                            this.modules = result.data || [];
                        }
                    } catch (e) {
                        console.error('Failed to load modules:', e);
                    } finally {
                        this.loadingModules = false;
                    }
                },

                async createModule(parentId = null) {
                    const name = prompt('Module name:');
                    if (!name || !name.trim()) return;
                    try {
                        const response = await fetch('/apiura/modules', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({ name: name.trim(), parent_id: parentId })
                        });
                        if (response.ok) {
                            await this.loadModules();
                            this.showToast(`Module "${name.trim()}" created`);
                        } else {
                            const err = await response.json().catch(() => ({}));
                            this.showToast(err.message || 'Failed to create module', 'error');
                        }
                    } catch (e) {
                        this.showToast('Failed to create module: ' + e.message, 'error');
                    }
                },

                async renameModule(moduleId, newName) {
                    if (!newName || !newName.trim()) return;
                    const mod = this.findModuleById(moduleId);
                    if (mod && mod.name === newName.trim()) return;
                    try {
                        const response = await fetch(`/apiura/modules/${moduleId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({ name: newName.trim() })
                        });
                        if (response.ok) {
                            await this.loadModules();
                        } else {
                            const err = await response.json().catch(() => ({}));
                            this.showToast(err.message || 'Failed to rename module', 'error');
                        }
                    } catch (e) {
                        this.showToast('Failed to rename module: ' + e.message, 'error');
                    }
                },

                promptDeleteModule(moduleId) {
                    const mod = this.findModuleById(moduleId);
                    if (!mod) return;
                    const itemCount = this.getModuleDeepItemCount(mod);
                    this.deleteModuleDialog = {
                        show: true,
                        moduleId,
                        moduleName: mod.name || 'Untitled',
                        itemCount
                    };
                },

                getModuleDeepItemCount(mod) {
                    let count = (mod.saved_flows || []).length + (mod.saved_requests || []).length;
                    for (const child of (mod.children || [])) {
                        count += this.getModuleDeepItemCount(child);
                    }
                    return count;
                },

                async deleteModule(moduleId, deleteItems = false) {
                    this.deleteModuleDialog = { show: false, moduleId: null, moduleName: '', itemCount: 0 };
                    try {
                        const url = deleteItems
                            ? `/apiura/modules/${moduleId}?delete_items=true`
                            : `/apiura/modules/${moduleId}`;
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            }
                        });
                        if (response.ok) {
                            await Promise.all([this.loadModules(), this.loadFlows()]);
                            this.showToast(deleteItems ? 'Module and all contents deleted' : 'Module deleted, items moved to unorganized');
                        } else {
                            this.showToast('Failed to delete module', 'error');
                        }
                    } catch (e) {
                        this.showToast('Failed to delete module: ' + e.message, 'error');
                    }
                },

                findModuleById(id) {
                    for (const mod of this.modules) {
                        if (mod.id === id) return mod;
                        for (const child of (mod.children || [])) {
                            if (child.id === id) return child;
                            for (const gc of (child.children || [])) {
                                if (gc.id === id) return gc;
                            }
                        }
                    }
                    return null;
                },

                getFilteredModules() {
                    if (!this.moduleSearchQuery) return this.modules;
                    const q = this.moduleSearchQuery.toLowerCase();
                    return this.modules.filter(m =>
                        m.name.toLowerCase().includes(q) ||
                        (m.children || []).some(c => c.name.toLowerCase().includes(q))
                    );
                },

                getModuleItemCount(mod) {
                    let count = (mod.saved_flows_count || (mod.saved_flows || []).length) + (mod.saved_requests_count || (mod.saved_requests || []).length);
                    for (const child of (mod.children || [])) {
                        count += (child.saved_flows_count || (child.saved_flows || []).length) + (child.saved_requests_count || (child.saved_requests || []).length);
                    }
                    return count;
                },

                getUnorganizedFlows() {
                    return this.flows.filter(f => !f.module_id);
                },

                // Collapse/expand
                toggleModuleCollapse(moduleId) {
                    this.moduleCollapseState[moduleId] = !this.moduleCollapseState[moduleId];
                    this.persistCollapseState();
                },

                isModuleCollapsed(moduleId) {
                    return !!this.moduleCollapseState[moduleId];
                },

                collapseAllModules() {
                    const collapse = {};
                    for (const mod of this.modules) {
                        collapse[mod.id] = true;
                        for (const child of (mod.children || [])) {
                            collapse[child.id] = true;
                        }
                    }
                    this.moduleCollapseState = collapse;
                    this.persistCollapseState();
                },

                expandAllModules() {
                    this.moduleCollapseState = {};
                    this.persistCollapseState();
                },

                persistCollapseState() {
                    try { localStorage.setItem('apiura_module_collapse', JSON.stringify(this.moduleCollapseState)); } catch {}
                },

                // Move items to module
                async moveItemToModule(type, itemId, targetModuleId) {
                    const payload = { target_module_id: targetModuleId };
                    if (type === 'request') payload.request_ids = [itemId];
                    else payload.flow_ids = [itemId];

                    try {
                        const response = await fetch('/apiura/modules/move-items', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify(payload)
                        });
                        if (response.ok) {
                            await Promise.all([this.loadModules(), this.loadFlows()]);
                            const modName = targetModuleId ? this.findModuleById(targetModuleId)?.name || 'module' : 'Unorganized';
                            this.showToast(`Moved to ${modName}`);
                        }
                    } catch (e) {
                        this.showToast('Failed to move item: ' + e.message, 'error');
                    }
                },

                // =============== IMPORT PREVIEW METHODS ===============

                openImportPreview(items, type, suggestedName = '') {
                    this.importPreviewItems = items.map((item, i) => ({
                        id: 'import-' + Date.now() + '-' + i,
                        name: item.name || (type === 'flow' ? 'Untitled Flow' : (item.method + ' ' + item.path)),
                        type: type,
                        data: item,
                        status: 'new',
                        confidence: 0,
                        matchedExisting: null,
                        selected: true,
                        importMode: 'import'
                    }));

                    // Check if a module with the suggested name already exists (root + children)
                    let matchingModule = null;
                    if (suggestedName) {
                        const lowerName = suggestedName.toLowerCase();
                        for (const mod of this.modules) {
                            if (mod.name.toLowerCase() === lowerName) { matchingModule = mod; break; }
                            for (const child of (mod.children || [])) {
                                if (child.name.toLowerCase() === lowerName) { matchingModule = child; break; }
                            }
                            if (matchingModule) break;
                        }
                    }

                    if (matchingModule) {
                        this.importPreviewModule = { id: matchingModule.id, name: matchingModule.name };
                    } else {
                        this.importPreviewModule = { id: 'new', name: suggestedName || '' };
                    }

                    this.importPreviewFilter = 'all';
                    this.importPreviewDiffItem = null;
                    this.showImportPreview = true;

                    // Auto-run duplicate detection if matched to existing module
                    if (matchingModule) {
                        this.$nextTick(() => this.runDuplicateDetection());
                    }
                },

                closeImportPreview() {
                    this.showImportPreview = false;
                    this.importPreviewItems = [];
                    this.importPreviewDiffItem = null;
                },

                async runDuplicateDetection() {
                    if (this.importPreviewModule.id === 'new') return;
                    this.importPreviewLoading = true;
                    try {
                        const flows = this.importPreviewItems.filter(i => i.type === 'flow').map(i => i.data);
                        const requests = this.importPreviewItems.filter(i => i.type === 'request').map(i => i.data);

                        const payload = { module_id: this.importPreviewModule.id };
                        if (flows.length > 0) payload.flows = flows;
                        if (requests.length > 0) payload.requests = requests;

                        const response = await fetch('/apiura/modules/import-preview', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify(payload)
                        });

                        if (response.ok) {
                            const result = await response.json();
                            let flowIdx = 0, reqIdx = 0;
                            this.importPreviewItems = this.importPreviewItems.map(item => {
                                let dupInfo;
                                if (item.type === 'flow' && result.flows && result.flows[flowIdx]) {
                                    dupInfo = result.flows[flowIdx].duplicate;
                                    flowIdx++;
                                } else if (item.type === 'request' && result.requests && result.requests[reqIdx]) {
                                    dupInfo = result.requests[reqIdx].duplicate;
                                    reqIdx++;
                                }
                                if (dupInfo) {
                                    item.confidence = dupInfo.confidence;
                                    item.matchedExisting = dupInfo.matched_item;
                                    if (dupInfo.is_duplicate) {
                                        item.status = 'duplicate';
                                        item.selected = false;
                                        item.importMode = 'skip';
                                    } else if (dupInfo.confidence >= 50) {
                                        item.status = 'possible';
                                        item.selected = true;
                                        item.importMode = 'import';
                                    } else {
                                        item.status = 'new';
                                        item.selected = true;
                                        item.importMode = 'import';
                                    }
                                }
                                return item;
                            });
                        }
                    } catch (e) {
                        console.error('Duplicate detection failed:', e);
                    } finally {
                        this.importPreviewLoading = false;
                    }
                },

                getFilteredImportPreviewItems() {
                    if (this.importPreviewFilter === 'all') return this.importPreviewItems;
                    if (this.importPreviewFilter === 'new') return this.importPreviewItems.filter(i => i.status === 'new');
                    if (this.importPreviewFilter === 'duplicates') return this.importPreviewItems.filter(i => i.status === 'duplicate' || i.status === 'possible');
                    return this.importPreviewItems;
                },

                toggleSelectAllImportItems() {
                    const filtered = this.getFilteredImportPreviewItems();
                    const allSelected = filtered.every(i => i.selected);
                    filtered.forEach(i => {
                        i.selected = !allSelected;
                        if (i.selected && i.importMode === 'skip') i.importMode = 'import';
                        if (!i.selected) i.importMode = 'skip';
                    });
                },

                getImportSummary() {
                    const items = this.importPreviewItems;
                    return {
                        newCount: items.filter(i => i.selected && i.importMode === 'import').length,
                        overwriteCount: items.filter(i => i.selected && i.importMode === 'overwrite').length,
                        copyCount: items.filter(i => i.selected && i.importMode === 'copy').length,
                        skipCount: items.filter(i => !i.selected || i.importMode === 'skip').length,
                        totalSelected: items.filter(i => i.selected && i.importMode !== 'skip').length
                    };
                },

                async executeImport() {
                    const selected = this.importPreviewItems.filter(i => i.selected && i.importMode !== 'skip');
                    if (selected.length === 0) {
                        this.showToast('No items selected for import', 'error');
                        return;
                    }

                    this.importPreviewLoading = true;
                    try {
                        let moduleId = this.importPreviewModule.id;

                        // Create new module if needed
                        if (moduleId === 'new') {
                            const moduleName = this.importPreviewModule.name.trim() || 'Imported Module';
                            const createResp = await fetch('/apiura/modules', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                },
                                body: JSON.stringify({ name: moduleName })
                            });
                            if (!createResp.ok) {
                                const err = await createResp.json().catch(() => ({}));
                                // Check for duplicate name error
                                if (err.errors?.name) {
                                    let existingMod = null;
                                    const lowerName = moduleName.toLowerCase();
                                    for (const m of this.modules) {
                                        if (m.name.toLowerCase() === lowerName) { existingMod = m; break; }
                                        for (const c of (m.children || [])) {
                                            if (c.name.toLowerCase() === lowerName) { existingMod = c; break; }
                                        }
                                        if (existingMod) break;
                                    }
                                    if (existingMod) {
                                        this.showToast(`A module named "${moduleName}" already exists. Select it from the dropdown to import into it.`, 'error');
                                        this.importPreviewModule = { id: existingMod.id, name: existingMod.name };
                                        this.$nextTick(() => this.runDuplicateDetection());
                                    } else {
                                        this.showToast(err.errors.name[0] || 'A module with this name already exists.', 'error');
                                    }
                                } else {
                                    this.showToast(err.message || 'Failed to create module', 'error');
                                }
                                return;
                            }
                            const created = await createResp.json();
                            moduleId = created.data.id;
                        }

                        // Build import items
                        const importItems = selected.map(item => ({
                            type: item.type,
                            action: item.importMode,
                            data: item.data,
                            overwrite_id: item.importMode === 'overwrite' && item.matchedExisting ? item.matchedExisting.id : null
                        }));

                        const response = await fetch('/apiura/modules/import-execute', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({ module_id: moduleId, items: importItems })
                        });

                        if (response.ok) {
                            const result = await response.json();
                            this.showToast(result.message);
                            await this.loadModules();
                            await this.loadFlows();
                            this.closeImportPreview();
                        } else {
                            const err = await response.json().catch(() => ({}));
                            this.showToast(err.message || 'Import failed', 'error');
                        }
                    } catch (e) {
                        this.showToast('Import failed: ' + e.message, 'error');
                    } finally {
                        this.importPreviewLoading = false;
                    }
                },

                showImportDiff(item) {
                    this.importPreviewDiffItem = item;
                },

                // Recursively remove all 'example' keys from an object
                deepStripExamples(obj) {
                    if (Array.isArray(obj)) {
                        return obj.map(item => typeof item === 'object' && item !== null ? this.deepStripExamples(item) : item);
                    }
                    if (typeof obj === 'object' && obj !== null) {
                        const result = {};
                        for (const [key, value] of Object.entries(obj)) {
                            if (key === 'example') continue;
                            result[key] = typeof value === 'object' && value !== null ? this.deepStripExamples(value) : value;
                        }
                        return result;
                    }
                    return obj;
                },

                // Copy all endpoints as cURL commands
                async copyAllEndpointsAsCurl() {
                    let curlCommands = [];

                    Object.entries(this.endpointsByTag).forEach(([tag, endpoints]) => {
                        curlCommands.push(`# ========== ${tag} ==========\n`);
                        endpoints.forEach(ep => {
                            curlCommands.push(`# ${ep.summary || ep.path}`);
                            curlCommands.push(this.generateCurlForEndpoint(ep));
                            curlCommands.push('');
                        });
                    });

                    await navigator.clipboard.writeText(curlCommands.join('\n'));
                    this.showToast(`Copied ${this.endpoints.length} cURL commands to clipboard`);
                },

                // ============ CODE SNIPPET EXPORT ============

                // Copy current request as JavaScript fetch
                async copyAsJavaScriptFetch() {
                    const code = this.generateFetchCode('javascript');
                    await navigator.clipboard.writeText(code);
                    this.showToast('JavaScript fetch code copied to clipboard');
                },

                // Copy current request as TypeScript fetch
                async copyAsTypeScriptFetch() {
                    const code = this.generateFetchCode('typescript');
                    await navigator.clipboard.writeText(code);
                    this.showToast('TypeScript fetch code copied to clipboard');
                },

                // Generate fetch code for current request
                generateFetchCode(language = 'javascript') {
                    const endpoint = this.selectedEndpoint;
                    const method = endpoint.method.toUpperCase();
                    const baseUrl = this.getBaseUrl();

                    // Build URL with path params
                    let path = endpoint.path;
                    const pathParams = (endpoint.parameters || []).filter(p => p.in === 'path');
                    pathParams.forEach(param => {
                        const value = this.requestState.pathParams[param.name] || `{${param.name}}`;
                        path = path.replace(`{${param.name}}`, value);
                    });

                    // Add query params
                    const queryParams = (endpoint.parameters || []).filter(p => p.in === 'query');
                    const enabledQueryParams = queryParams.filter(param => {
                        const isEnabled = this.requestState.queryParamsEnabled[param.name] !== false;
                        const hasValue = this.requestState.queryParams[param.name];
                        return isEnabled && hasValue;
                    });

                    let queryString = '';
                    if (enabledQueryParams.length > 0) {
                        const params = enabledQueryParams.map(param => {
                            const value = this.requestState.queryParams[param.name];
                            return `${encodeURIComponent(param.name)}=${encodeURIComponent(value)}`;
                        });
                        queryString = '?' + params.join('&');
                    }

                    const fullUrl = baseUrl + path + queryString;

                    // Build headers
                    const headers = {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    };

                    // Add auth header if token is set
                    if (this.authToken) {
                        headers['Authorization'] = `Bearer ${this.authToken}`;
                    } else {
                        headers['Authorization'] = 'Bearer YOUR_TOKEN_HERE';
                    }

                    // Add custom headers
                    this.requestState.customHeaders.forEach(header => {
                        if (header.enabled && header.key && header.value) {
                            headers[header.key] = header.value;
                        }
                    });

                    // Build body
                    let body = null;
                    if (this.methodRequiresBody(endpoint.method)) {
                        if (this.rawJsonMode && this.rawJsonBody) {
                            try {
                                body = JSON.parse(this.rawJsonBody);
                            } catch (e) {
                                body = this.rawJsonBody;
                            }
                        } else {
                            body = this.builtRequestBody;
                        }
                    }

                    // Generate the code
                    let code = '';
                    const summary = endpoint.summary || `${method} ${endpoint.path}`;
                    const description = endpoint.description || '';

                    if (language === 'typescript') {
                        code = this.generateTypeScriptFetch(fullUrl, method, headers, body, summary, description, endpoint);
                    } else {
                        code = this.generateJavaScriptFetch(fullUrl, method, headers, body, summary, description);
                    }

                    return code;
                },

                // Generate JavaScript fetch code
                generateJavaScriptFetch(url, method, headers, body, summary, description) {
                    let code = `// ${summary}\n`;
                    if (description) {
                        code += `// ${description}\n`;
                    }
                    code += '\n';

                    // Build fetch options
                    const options = {
                        method: method
                    };

                    code += `const response = await fetch('${url}', {\n`;
                    code += `  method: '${method}',\n`;
                    code += `  headers: {\n`;

                    const headerEntries = Object.entries(headers);
                    headerEntries.forEach(([key, value], index) => {
                        const comma = index < headerEntries.length - 1 ? ',' : '';
                        code += `    '${key}': '${value}'${comma}\n`;
                    });

                    code += `  }`;

                    if (body) {
                        code += `,\n  body: JSON.stringify(${JSON.stringify(body, null, 4).split('\n').map((line, i) => i === 0 ? line : '  ' + line).join('\n')})`;
                    }

                    code += '\n});\n\n';
                    code += 'const data = await response.json();\n';
                    code += 'console.log(data);\n';

                    return code;
                },

                // Generate TypeScript fetch code
                generateTypeScriptFetch(url, method, headers, body, summary, description, endpoint) {
                    let code = `// ${summary}\n`;
                    if (description) {
                        code += `// ${description}\n`;
                    }
                    code += '\n';

                    // Generate response interface from schema if available
                    const responseSchema = endpoint.responses?.['200']?.content?.['application/json']?.schema;
                    if (responseSchema) {
                        code += this.generateTypeScriptInterface(responseSchema, 'ApiResponse');
                        code += '\n';
                    } else {
                        code += `interface ApiResponse {\n`;
                        code += `  success: boolean;\n`;
                        code += `  data: unknown;\n`;
                        code += `  message?: string;\n`;
                        code += `}\n\n`;
                    }

                    code += `const response = await fetch('${url}', {\n`;
                    code += `  method: '${method}',\n`;
                    code += `  headers: {\n`;

                    const headerEntries = Object.entries(headers);
                    headerEntries.forEach(([key, value], index) => {
                        const comma = index < headerEntries.length - 1 ? ',' : '';
                        code += `    '${key}': '${value}'${comma}\n`;
                    });

                    code += `  }`;

                    if (body) {
                        code += `,\n  body: JSON.stringify(${JSON.stringify(body, null, 4).split('\n').map((line, i) => i === 0 ? line : '  ' + line).join('\n')})`;
                    }

                    code += '\n});\n\n';
                    code += 'const data: ApiResponse = await response.json();\n';
                    code += 'console.log(data);\n';

                    return code;
                },

                // Generate TypeScript interface from JSON schema
                generateTypeScriptInterface(schema, name = 'Response', depth = 0) {
                    if (depth > 3) return `interface ${name} { [key: string]: unknown; }\n`;

                    let code = `interface ${name} {\n`;

                    if (schema.properties) {
                        const required = schema.required || [];
                        Object.entries(schema.properties).forEach(([propName, propSchema]) => {
                            const isRequired = required.includes(propName);
                            const tsType = this.schemaToTypeScriptType(propSchema);
                            code += `  ${propName}${isRequired ? '' : '?'}: ${tsType};\n`;
                        });
                    } else if (schema.type === 'array' && schema.items) {
                        code = `type ${name} = ${this.schemaToTypeScriptType(schema)};\n`;
                        return code;
                    } else {
                        code += `  [key: string]: unknown;\n`;
                    }

                    code += `}\n`;
                    return code;
                },

                // Convert JSON Schema type to TypeScript type
                schemaToTypeScriptType(schema) {
                    if (!schema) return 'unknown';

                    if (schema.$ref) {
                        // Extract ref name
                        const refName = schema.$ref.split('/').pop();
                        return refName;
                    }

                    switch (schema.type) {
                        case 'string':
                            if (schema.enum) {
                                return schema.enum.map(v => `'${v}'`).join(' | ');
                            }
                            return 'string';
                        case 'integer':
                        case 'number':
                            return 'number';
                        case 'boolean':
                            return 'boolean';
                        case 'array':
                            const itemType = this.schemaToTypeScriptType(schema.items);
                            return `${itemType}[]`;
                        case 'object':
                            if (schema.properties) {
                                const props = Object.entries(schema.properties).map(([key, val]) => {
                                    return `${key}: ${this.schemaToTypeScriptType(val)}`;
                                });
                                return `{ ${props.join('; ')} }`;
                            }
                            return 'Record<string, unknown>';
                        default:
                            return 'unknown';
                    }
                },

                // Helper: Download JSON file
                downloadJson(obj, filename) {
                    this.downloadFile(JSON.stringify(obj, null, 2), filename, 'application/json');
                },

                // Helper: Download file
                downloadFile(content, filename, type) {
                    const blob = new Blob([content], { type });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                },

                // Format date for display
                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                // Format snake_case to Title Case (e.g., "user_currencies" → "User Currencies")
                formatTableName(name) {
                    return name
                        .split('_')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                },

                // ==========================================
                // API Map Helper Methods (Resource-Centric)
                // ==========================================

                // Build relationships by analyzing *_id and *_type fields in request bodies
                buildRelationshipsFromSpec() {
                    const relationships = [];
                    const tags = Object.keys(this.endpointsByTag);

                    for (const endpoint of this.endpoints) {
                        // Only analyze POST/PUT/PATCH endpoints that have request bodies
                        if (!['POST', 'PUT', 'PATCH'].includes(endpoint.method)) continue;
                        if (!this.hasRequestBody(endpoint)) continue;

                        const bodyFields = this.getRequestBodyFields(endpoint);
                        const sourceTag = endpoint.tags[0] || 'Untagged';

                        for (const field of bodyFields) {
                            // Detect *_id fields -> find corresponding resource
                            if (field.name.endsWith('_id')) {
                                const resourceName = field.name.replace('_id', '');
                                const targetTag = this.findTagByResourceName(resourceName, tags);
                                if (targetTag && targetTag !== sourceTag) {
                                    // Check if relationship already exists
                                    const exists = relationships.some(r =>
                                        r.from === sourceTag && r.to === targetTag && r.field === field.name
                                    );
                                    if (!exists) {
                                        relationships.push({
                                            from: sourceTag,
                                            to: targetTag,
                                            field: field.name,
                                            type: 'references'
                                        });
                                    }
                                }
                            }

                            // Detect polymorphic: *_type paired with *_id
                            if (field.name.endsWith('_type')) {
                                const baseField = field.name.replace('_type', '');
                                const idField = bodyFields.find(f => f.name === baseField + '_id');
                                if (idField) {
                                    // Get potential types from enum if available
                                    const polymorphicTypes = field.enum || [];

                                    // Try to find tags for each polymorphic type
                                    for (const polyType of polymorphicTypes) {
                                        // Extract model name from full class path (e.g., "App\\Models\\CashAccount" -> "CashAccount")
                                        const modelName = polyType.includes('\\')
                                            ? polyType.split('\\').pop()
                                            : polyType;
                                        const targetTag = this.findTagByResourceName(modelName, tags);
                                        if (targetTag && targetTag !== sourceTag) {
                                            const exists = relationships.some(r =>
                                                r.from === sourceTag && r.to === targetTag && r.field === field.name
                                            );
                                            if (!exists) {
                                                relationships.push({
                                                    from: sourceTag,
                                                    to: targetTag,
                                                    field: field.name,
                                                    type: 'polymorphic'
                                                });
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    return relationships;
                },

                // Find a tag by resource name (handles various naming conventions)
                findTagByResourceName(name, tags) {
                    if (!name || !tags) return null;

                    // Normalize the resource name
                    const normalizedInput = name.toLowerCase().replace(/[-_]/g, '');

                    // Try various matching strategies
                    for (const tag of tags) {
                        const normalizedTag = tag.toLowerCase().replace(/[-_]/g, '');

                        // Direct match
                        if (normalizedTag === normalizedInput) return tag;

                        // Singular/plural matching
                        if (normalizedTag === normalizedInput + 's') return tag;
                        if (normalizedTag + 's' === normalizedInput) return tag;

                        // Handle compound names like 'cash_account' -> 'CashAccount' or 'Cash Account'
                        const camelCase = name.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join('');
                        if (tag === camelCase) return tag;

                        // Handle plural compound names
                        const camelCasePlural = camelCase + 's';
                        if (tag === camelCasePlural) return tag;
                    }

                    return null;
                },

                // Select a resource and switch to resource view
                selectResourceForMap(tag) {
                    this.selectedResource = tag;
                    this.resourceEndpoints = this.endpoints.filter(ep => ep.tags?.includes(tag));
                    this.apiMapView = 'resource';
                    this.selectedGraphNode = null;
                },

                // Get endpoint count for a tag
                getEndpointCountForTag(tag) {
                    return this.endpoints.filter(ep => ep.tags?.includes(tag)).length;
                },

                // Get unique methods used in a tag
                getMethodsForTag(tag) {
                    const methods = new Set();
                    this.endpoints
                        .filter(ep => ep.tags?.includes(tag))
                        .forEach(ep => methods.add(ep.method));
                    return Array.from(methods).sort((a, b) => {
                        const order = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
                        return order.indexOf(a) - order.indexOf(b);
                    });
                },



                // Get method text color class (for endpoint list in resource view)
                getMethodColor(method) {
                    const colors = {
                        'GET': 'text-[#61affe]',
                        'POST': 'text-[#49cc90]',
                        'PUT': 'text-[#fca130]',
                        'PATCH': 'text-[#50e3c2]',
                        'DELETE': 'text-[#f93e3e]'
                    };
                    return colors[method] || 'text-ide-muted';
                },

                // Get connection count for a tag
                getConnectionCountForTag(tag) {
                    return this.resourceRelationships.filter(r => r.from === tag || r.to === tag).length;
                },

                // Get connections for the currently selected resource
                getResourceConnections() {
                    if (!this.selectedResource) return [];

                    const connections = [];
                    for (const rel of (this.resourceRelationships || [])) {
                        if (rel.from === this.selectedResource && rel.to) {
                            // This resource references another (outgoing)
                            connections.push({
                                tag: rel.to,
                                field: rel.field,
                                direction: 'out',
                                type: rel.type
                            });
                        }
                        if (rel.to === this.selectedResource) {
                            // Another resource references this one (incoming)
                            connections.push({
                                tag: rel.from,
                                field: rel.field,
                                direction: 'in',
                                type: rel.type
                            });
                        }
                    }

                    // Dedupe by tag, keeping track of all fields
                    const seen = new Map();
                    for (const conn of connections) {
                        const key = conn.tag + '-' + conn.direction;
                        if (!seen.has(key)) {
                            seen.set(key, conn);
                        }
                    }

                    return Array.from(seen.values());
                },

                // Calculate position for connected resource nodes in a circle
                getConnectionPosition(index, total) {
                    const radius = 160; // px from center
                    const angle = (index / total) * 2 * Math.PI - Math.PI / 2; // Start from top
                    const x = Math.cos(angle) * radius;
                    const y = Math.sin(angle) * radius;
                    return `transform: translate(${x}px, ${y}px); left: 50%; top: 50%; margin-left: -56px; margin-top: -56px;`;
                },

                // Get line end coordinates for SVG connection lines
                getConnectionLineEnd(index, total) {
                    const radius = 100; // Shorter than node position so line ends before node
                    const angle = (index / total) * 2 * Math.PI - Math.PI / 2;
                    const x = 50 + (Math.cos(angle) * radius / 4) + '%';
                    const y = 50 + (Math.sin(angle) * radius / 4) + '%';
                    return { x, y };
                },

                // Get related resources for an endpoint (fields ending with _id)
                getEndpointRelatedResources(endpoint) {
                    if (!endpoint || !this.hasRequestBody(endpoint)) return [];

                    const fields = this.getRequestBodyFields(endpoint);
                    const related = [];
                    const tags = Object.keys(this.endpointsByTag);

                    for (const field of fields) {
                        if (field.name.endsWith('_id')) {
                            const resourceName = field.name.replace('_id', '');
                            const targetTag = this.findTagByResourceName(resourceName, tags);
                            related.push({
                                field: field.name,
                                resource: resourceName,
                                targetTag: targetTag
                            });
                        }
                    }

                    return related;
                },

                // Navigate to endpoint from graph node
                navigateToEndpointFromGraph(nodeData) {
                    const endpoint = this.endpoints.find(ep =>
                        ep.method === nodeData.method && ep.path === nodeData.path
                    );
                    if (endpoint) {
                        this.selectEndpoint(endpoint);
                        this.showRelationshipGraph = false;
                    }
                },

                // Get mock response from endpoint spec (defaults to 200/201)
                getMockResponse(endpoint) {
                    return this.getMockResponseForStatus(endpoint, '200') || this.getMockResponseForStatus(endpoint, '201');
                },

                // Get mock response for a specific status code
                getMockResponseForStatus(endpoint, statusCode) {
                    if (!endpoint?.responses?.[statusCode]) return null;

                    const response = endpoint.responses[statusCode];

                    // Check for example first
                    if (response?.content?.['application/json']?.example) {
                        return response.content['application/json'].example;
                    }

                    // Check for schema and generate mock
                    if (response?.content?.['application/json']?.schema) {
                        const schema = response.content['application/json'].schema;

                        // Handle $ref
                        if (schema.$ref) {
                            const refPath = schema.$ref.replace('#/components/schemas/', '');
                            const refSchema = this.spec.components?.schemas?.[refPath];
                            if (refSchema) {
                                return this.generateMockFromSchema(refSchema);
                            }
                        }

                        return this.generateMockFromSchema(schema);
                    }

                    // Generate default mock based on status code
                    return this.getDefaultMockForStatus(statusCode);
                },

                // Get default mock response for common status codes
                getDefaultMockForStatus(statusCode) {
                    const code = parseInt(statusCode);

                    if (code === 200) {
                        return { success: true, data: {}, message: "Operation successful" };
                    }
                    if (code === 201) {
                        return { success: true, data: { id: 1 }, message: "Resource created successfully" };
                    }
                    if (code === 204) {
                        return null; // No content
                    }
                    if (code === 400) {
                        return { success: false, message: "Bad Request", errors: { field: ["The field is invalid."] } };
                    }
                    if (code === 401) {
                        return { success: false, message: "Unauthenticated." };
                    }
                    if (code === 403) {
                        return { success: false, message: "Forbidden. You do not have permission to access this resource." };
                    }
                    if (code === 404) {
                        return { success: false, message: "Resource not found." };
                    }
                    if (code === 422) {
                        return { success: false, message: "Validation failed.", errors: { field: ["The field is required."] } };
                    }
                    if (code === 429) {
                        return { success: false, message: "Too many requests. Please try again later." };
                    }
                    if (code >= 500) {
                        return { success: false, message: "Internal server error." };
                    }

                    return { message: "Response" };
                },

                // Generate mock data from schema
                generateMockFromSchema(schema, depth = 0) {
                    if (depth > 5) return null; // Prevent infinite recursion

                    if (!schema) return null;

                    // Handle $ref - resolve the reference
                    if (schema.$ref) {
                        const refPath = schema.$ref.replace('#/components/schemas/', '');
                        const refSchema = this.spec.components?.schemas?.[refPath];
                        if (refSchema) {
                            return this.generateMockFromSchema(refSchema, depth + 1);
                        }
                        // Fallback: generate a basic object with the ref name
                        return { id: 1, name: refPath };
                    }

                    // Handle allOf - merge all schemas
                    if (schema.allOf) {
                        let result = {};
                        schema.allOf.forEach(subSchema => {
                            const subResult = this.generateMockFromSchema(subSchema, depth + 1);
                            if (typeof subResult === 'object' && subResult !== null && !Array.isArray(subResult)) {
                                result = { ...result, ...subResult };
                            }
                        });
                        return result;
                    }

                    // Handle oneOf/anyOf - use first option
                    if (schema.oneOf || schema.anyOf) {
                        const options = schema.oneOf || schema.anyOf;
                        return this.generateMockFromSchema(options[0], depth + 1);
                    }

                    if (schema.example !== undefined) {
                        return schema.example;
                    }

                    if (schema.type === 'object') {
                        const obj = {};
                        if (schema.properties) {
                            Object.entries(schema.properties).forEach(([key, prop]) => {
                                obj[key] = this.generateMockFromSchema(prop, depth + 1);
                            });
                        }
                        return obj;
                    }

                    if (schema.type === 'array') {
                        if (schema.items) {
                            const item = this.generateMockFromSchema(schema.items, depth + 1);
                            return item !== null ? [item] : [];
                        }
                        return [];
                    }

                    if (schema.enum && schema.enum.length > 0) {
                        return schema.enum[0];
                    }

                    // Default values by type
                    switch (schema.type) {
                        case 'string':
                            if (schema.format === 'date-time') return '2024-01-15T10:30:00Z';
                            if (schema.format === 'date') return '2024-01-15';
                            if (schema.format === 'email') return 'user@example.com';
                            if (schema.format === 'uuid') return '550e8400-e29b-41d4-a716-446655440000';
                            if (schema.format === 'uri') return 'https://example.com';
                            return 'string';
                        case 'integer':
                            return schema.minimum !== undefined ? schema.minimum : 1;
                        case 'number':
                            return schema.minimum !== undefined ? schema.minimum : 1.0;
                        case 'boolean':
                            return true;
                        default:
                            return null;
                    }
                },

                // Format JSON for pretty display
                formatJsonPretty(data) {
                    if (data === null || data === undefined) return '';
                    if (typeof data === 'string') return data;
                    try {
                        return JSON.stringify(data, null, 2);
                    } catch (e) {
                        return String(data);
                    }
                },

                // Format JSON with syntax highlighting (returns HTML)
                syntaxHighlightJson(data) {
                    if (data === null || data === undefined) return '<span class="text-gray-500">null</span>';

                    let json;
                    if (typeof data === 'string') {
                        json = data;
                    } else {
                        try {
                            json = JSON.stringify(data, null, 2);
                        } catch (e) {
                            return String(data);
                        }
                    }

                    // Escape HTML first
                    let escaped = json
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');

                    // Keys (property names) - cyan/sky color
                    escaped = escaped.replace(
                        /"([^"]+)"(?=\s*:)/g,
                        '<span class="json-key">"$1"</span>'
                    );

                    // String values - green color
                    escaped = escaped.replace(
                        /:\s*"([^"]*)"(?=[,\n\r\}]|$)/g,
                        ': <span class="json-string">"$1"</span>'
                    );

                    // Numbers - yellow/orange color
                    escaped = escaped.replace(
                        /:\s*(-?\d+\.?\d*)(?=[,\n\r\}\]]|$)/g,
                        ': <span class="json-number">$1</span>'
                    );

                    // Booleans - purple color
                    escaped = escaped.replace(
                        /:\s*(true|false)(?=[,\n\r\}\]]|$)/g,
                        ': <span class="json-boolean">$1</span>'
                    );

                    // Null - red color
                    escaped = escaped.replace(
                        /:\s*(null)(?=[,\n\r\}\]]|$)/g,
                        ': <span class="json-null">$1</span>'
                    );

                    return escaped;
                },

                // ============ FLOW BUILDER METHODS ============

                // Load all flows from API
                async loadFlows() {
                    this.loadingFlows = true;
                    try {
                        const response = await fetch('/apiura/flows');
                        if (response.ok) {
                            const json = await response.json();
                            this.flows = json.data || json;
                        } else {
                            console.error('Failed to load flows:', response.status);
                            this.flows = [];
                            this.showToast('Failed to load flows', 'error');
                        }
                    } catch (e) {
                        console.error('Failed to load flows:', e);
                        this.flows = [];
                        this.showToast('Failed to load flows', 'error');
                    }
                    this.loadingFlows = false;
                },

                // Save current flow (create or update)
                async saveFlow() {
                    if (!this.newFlow.steps || this.newFlow.steps.length === 0) {
                        this.showToast('Add at least one step before saving', 'error');
                        return;
                    }
                    const isNew = !this.editingFlow?.id;
                    const url = isNew ? '/apiura/flows' : `/apiura/flows/${this.editingFlow.id}`;
                    const method = isNew ? 'POST' : 'PUT';

                    const flowData = {
                        name: this.newFlow.name || 'Untitled Flow',
                        description: this.newFlow.description || '',
                        steps: this.newFlow.steps || [],
                        defaultHeaders: this.newFlow.defaultHeaders || {},
                        continueOnError: this.newFlow.continueOnError || false
                    };

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        if (!csrfToken) {
                            this.showToast('Security token missing. Please refresh the page.', 'error');
                            return;
                        }

                        const response = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(flowData)
                        });

                        const responseData = await response.json();

                        if (response.ok) {
                            const saved = responseData.data || responseData;
                            await this.loadFlows();
                            await this.loadModules();
                            // Update editingFlow with saved data (keeps the id for future saves)
                            this.editingFlow = saved;
                            this.isCreatingFlow = false;
                            // Keep newFlow as-is so the UI retains all working data
                            if (saved.id) {
                                history.replaceState(null, '', `#flow/${saved.id}`);
                            }
                            this.showToast(isNew ? 'Flow created!' : 'Flow updated!');
                        } else {
                            console.error('Save failed:', responseData);
                            this.showToast(responseData.message || 'Failed to save flow', 'error');
                        }
                    } catch (e) {
                        console.error('Failed to save flow:', e);
                        this.showToast('Failed to save flow: ' + e.message, 'error');
                    }
                },

                async saveFlowAsNew() {
                    if (!this.newFlow.steps || this.newFlow.steps.length === 0) {
                        this.showToast('Add at least one step before saving', 'error');
                        return;
                    }
                    const flowData = {
                        name: (this.newFlow.name || 'Untitled Flow') + ' (Copy)',
                        description: this.newFlow.description || '',
                        steps: this.newFlow.steps || [],
                        defaultHeaders: this.newFlow.defaultHeaders || {},
                        continueOnError: this.newFlow.continueOnError || false
                    };

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        if (!csrfToken) {
                            this.showToast('Security token missing. Please refresh the page.', 'error');
                            return;
                        }

                        const response = await fetch('/apiura/flows', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(flowData)
                        });

                        const responseData = await response.json();

                        if (response.ok) {
                            const saved = responseData.data || responseData;
                            await this.loadFlows();
                            await this.loadModules();
                            // Switch to the new copy
                            this.editingFlow = saved;
                            this.isCreatingFlow = false;
                            this.newFlow.name = saved.name;
                            if (saved.id) {
                                history.replaceState(null, '', `#flow/${saved.id}`);
                            }
                            this.showToast('Saved as new flow!');
                        } else {
                            this.showToast(responseData.message || 'Failed to save flow', 'error');
                        }
                    } catch (e) {
                        this.showToast('Failed to save flow: ' + e.message, 'error');
                    }
                },

                // Delete a flow
                async deleteFlow(flowId) {
                    if (!confirm('Delete this flow?')) return;

                    try {
                        const response = await fetch(`/apiura/flows/${flowId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            }
                        });

                        if (response.ok) {
                            await Promise.all([this.loadFlows(), this.loadModules()]);
                            if (this.editingFlow?.id === flowId) {
                                this.editingFlow = null;
                                this.isCreatingFlow = false;
                                this.newFlow = { name: '', description: '', steps: [], defaultHeaders: {}, continueOnError: false };
                            }
                            this.showToast('Flow deleted');
                        } else {
                            this.showToast('Failed to delete flow', 'error');
                        }
                    } catch (e) {
                        console.error('Failed to delete flow:', e);
                        this.showToast('Failed to delete flow', 'error');
                    }
                },

                // Toggle selection of a flow for bulk operations
                toggleFlowSelection(flowId) {
                    const idx = this.selectedFlowIds.indexOf(flowId);
                    if (idx === -1) {
                        this.selectedFlowIds.push(flowId);
                    } else {
                        this.selectedFlowIds.splice(idx, 1);
                    }
                },

                // Export selected flows as JSON or YAML
                exportSelectedFlows(format) {
                    const selected = this.flows.filter(f => this.selectedFlowIds.includes(f.id));
                    if (selected.length === 0) {
                        this.showToast('No flows selected', 'error');
                        return;
                    }
                    const exported = selected.map(f => this.buildFlowExport(f));
                    const filename = selected.length === 1
                        ? (selected[0].name || 'flow').replace(/[^a-zA-Z0-9_-]/g, '_')
                        : `flows-${selected.length}`;
                    if (format === 'yaml') {
                        const yaml = jsyaml.dump(exported, { lineWidth: 120, noRefs: true });
                        this.downloadFile(yaml, filename + '.yaml', 'text/yaml');
                        this.showToast(`${selected.length} flow(s) exported as YAML`);
                    } else {
                        this.downloadJson(exported, filename + '.json');
                        this.showToast(`${selected.length} flow(s) exported as JSON`);
                    }
                    this.selectedFlowIds = [];
                },

                // Delete selected flows in bulk
                async deleteSelectedFlows() {
                    const count = this.selectedFlowIds.length;
                    if (count === 0) return;
                    if (!confirm(`Delete ${count} flow(s)?`)) return;

                    try {
                        const response = await fetch('/apiura/flows/bulk-delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({ ids: this.selectedFlowIds })
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (this.selectedFlowIds.includes(this.editingFlow?.id)) {
                                this.editingFlow = null;
                                this.isCreatingFlow = false;
                                this.newFlow = { name: '', description: '', steps: [], defaultHeaders: {}, continueOnError: false };
                            }
                            this.selectedFlowIds = [];
                            await Promise.all([this.loadFlows(), this.loadModules()]);
                            this.showToast(`${result.count} flow(s) deleted`);
                        } else {
                            this.showToast('Failed to delete flows', 'error');
                        }
                    } catch (e) {
                        console.error('Failed to delete flows:', e);
                        this.showToast('Failed to delete flows', 'error');
                    }
                },

                // Add a step to the current flow
                addStepToFlow(endpoint) {
                    const step = {
                        order: this.newFlow.steps.length + 1,
                        name: endpoint.summary || `${endpoint.method.toUpperCase()} ${endpoint.path}`,
                        endpoint: {
                            method: endpoint.method,
                            path: endpoint.path
                        },
                        params: {},
                        headers: {},
                        body: null,
                        extractVariables: {}
                    };
                    this.newFlow.steps.push(step);
                },

                // Remove a step from the current flow
                removeStepFromFlow(index) {
                    this.newFlow.steps.splice(index, 1);
                    this.flowRunResults.splice(index, 1);
                    // Reorder remaining steps and update result step numbers
                    this.newFlow.steps.forEach((s, i) => s.order = i + 1);
                    this.flowRunResults.forEach((r, i) => { if (r) r.step = i + 1; });
                },

                // Move a step up in the order
                moveStepUp(index) {
                    if (index === 0) return;
                    const steps = this.newFlow.steps;
                    [steps[index - 1], steps[index]] = [steps[index], steps[index - 1]];
                    steps.forEach((s, i) => s.order = i + 1);
                    // Swap results too
                    if (this.flowRunResults.length > index) {
                        [this.flowRunResults[index - 1], this.flowRunResults[index]] = [this.flowRunResults[index], this.flowRunResults[index - 1]];
                        this.flowRunResults.forEach((r, i) => { if (r) r.step = i + 1; });
                    }
                },

                // Move a step down in the order
                moveStepDown(index) {
                    if (index >= this.newFlow.steps.length - 1) return;
                    const steps = this.newFlow.steps;
                    [steps[index], steps[index + 1]] = [steps[index + 1], steps[index]];
                    steps.forEach((s, i) => s.order = i + 1);
                    // Swap results too
                    if (this.flowRunResults.length > index + 1) {
                        [this.flowRunResults[index], this.flowRunResults[index + 1]] = [this.flowRunResults[index + 1], this.flowRunResults[index]];
                        this.flowRunResults.forEach((r, i) => { if (r) r.step = i + 1; });
                    }
                },

                // Update variable extraction name (for renaming variables)
                updateExtractVariableName(step, oldName, newName) {
                    if (oldName === newName || !newName) return;
                    const value = step.extractVariables[oldName];
                    delete step.extractVariables[oldName];
                    step.extractVariables[newName] = value;
                }
            };
        }

        // Response Preview Component

    </script>

    <!-- Toast Notification Stack -->
    <div class="fixed bottom-4 right-4 z-50 toast-container" aria-live="polite">
        <template x-for="(t, index) in toasts" :key="t.id">
            <div
                class="toast-item px-4 py-3 bg-ide-fg text-ide-bg rounded-lg shadow-lg flex items-center gap-2 min-w-[200px] max-w-sm"
                :class="{ 'toast-exit': t.exiting }"
            >
                <!-- Success Icon -->
                <template x-if="t.type === 'success'">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
                <!-- Info Icon -->
                <template x-if="t.type === 'info'">
                    <svg class="w-5 h-5 flex-shrink-0 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <!-- Error Icon -->
                <template x-if="t.type === 'error'">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <span class="text-sm" x-text="t.message"></span>
                <button
                    @click="dismissToast(t.id)"
                    class="ml-auto p-1 hover:bg-white/10 rounded transition-colors"
                    aria-label="Dismiss"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>


    <!-- Environment Management Modal -->
    <div x-show="showEnvModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showEnvModal = false" x-cloak>
        <div class="bg-ide-bg rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-ide-border flex items-center justify-between">
                <h2 class="text-lg font-semibold text-ide-fg">Manage Environments</h2>
                <button @click="showEnvModal = false" class="text-gray-400 hover:text-ide-fg text-xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <template x-for="env in environments" :key="env.id">
                    <div class="border border-ide-border rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full" :class="{
                                    'bg-green-500': env.color === 'green',
                                    'bg-yellow-500': env.color === 'yellow',
                                    'bg-red-500': env.color === 'red',
                                    'bg-blue-500': env.color === 'blue',
                                    'bg-purple-500': env.color === 'purple',
                                }"></span>
                                <input type="text" :value="env.name" @change="updateEnvironment(env.id, 'name', $event.target.value)"
                                    class="font-medium text-ide-fg bg-transparent border-none focus:ring-1 focus:ring-blue-500 rounded px-1 -ml-1">
                            </div>
                            <div class="flex items-center gap-2">
                                <select @change="updateEnvironment(env.id, 'color', $event.target.value)" :value="env.color"
                                    class="text-xs border border-ide-border rounded px-2 py-1 bg-ide-surface text-ide-fg">
                                    <option value="green">Green</option>
                                    <option value="yellow">Yellow</option>
                                    <option value="red">Red</option>
                                    <option value="blue">Blue</option>
                                    <option value="purple">Purple</option>
                                </select>
                                <button @click="deleteEnvironment(env.id)" class="text-red-500 hover:text-red-600 text-sm">Delete</button>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-ide-muted">Base URL</label>
                            <input type="text" :value="env.baseUrl" @change="updateEnvironment(env.id, 'baseUrl', $event.target.value)"
                                placeholder="https://api.example.com/api/v1"
                                class="w-full mt-1 px-3 py-1.5 border border-ide-border rounded-lg bg-ide-surface text-sm text-ide-fg">
                        </div>
                        <div>
                            <label class="text-xs text-ide-muted">Auth Token</label>
                            <input type="password" :value="env.token" @change="updateEnvironment(env.id, 'token', $event.target.value)"
                                placeholder="Bearer token"
                                class="w-full mt-1 px-3 py-1.5 border border-ide-border rounded-lg bg-ide-surface text-sm text-ide-fg">
                        </div>
                    </div>
                </template>
                <button @click="addEnvironment()" class="w-full py-2 border-2 border-dashed border-ide-border rounded-lg text-ide-muted hover:border-blue-400 hover:text-blue-500 transition-colors text-sm">
                    + Add Environment
                </button>
            </div>
        </div>
    </div>

    <!-- Flow Response Dialog -->
    <div x-show="flowResponseDialog.show" class="fixed inset-0 z-[70] flex items-center justify-center bg-black/50" @click.self="flowResponseDialog.show = false"
         @keydown.escape.window="if(flowResponseDialog.show) { flowResponseDialog.show = false; $event.stopPropagation(); }" x-cloak>
        <div class="bg-ide-bg rounded-xl shadow-2xl w-[700px] max-h-[85vh] flex flex-col border border-ide-border mx-4" @click.stop>
            <!-- Header -->
            <div class="px-5 py-3 border-b border-ide-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <h3 class="text-sm font-semibold text-ide-fg truncate" x-text="'Step ' + flowResponseDialog.step + ': ' + flowResponseDialog.name"></h3>
                    <span class="text-xs font-mono px-2 py-0.5 rounded flex-shrink-0"
                          :class="flowResponseDialog.success ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'"
                          x-text="flowResponseDialog.status"></span>
                    <span x-show="flowResponseDialog.duration" class="text-xs font-mono text-ide-muted flex-shrink-0" x-text="flowResponseDialog.duration + 'ms'"></span>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <!-- Copy dropdown menu -->
                    <div class="relative" x-data="{ copyMenu: false }">
                        <button @click="copyMenu = !copyMenu"
                                class="px-2 py-1 text-xs text-ide-muted hover:text-ide-fg hover:bg-ide-line-active rounded-md transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="copyMenu" @click.outside="copyMenu = false" x-transition
                             class="absolute right-0 top-full mt-1 w-48 bg-ide-bg border border-ide-border rounded-lg shadow-xl z-50 py-1" x-cloak>
                            <button @click="navigator.clipboard.writeText(JSON.stringify(flowResponseDialog.data, null, 2)); showToast('Copied response'); copyMenu = false"
                                    class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Copy Response
                            </button>
                            <button @click="navigator.clipboard.writeText(generateFlowStepCurl(flowResponseDialog)); showToast('Copied as cURL'); copyMenu = false"
                                    class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-ide-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Copy as cURL
                            </button>
                            <button @click="navigator.clipboard.writeText(generateFlowStepFetch(flowResponseDialog, 'js')); showToast('Copied as JS fetch'); copyMenu = false"
                                    class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                Copy as JavaScript
                            </button>
                            <button @click="navigator.clipboard.writeText(generateFlowStepFetch(flowResponseDialog, 'ts')); showToast('Copied as TS fetch'); copyMenu = false"
                                    class="w-full px-3 py-1.5 text-left text-xs text-ide-fg hover:bg-ide-line-active flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                Copy as TypeScript
                            </button>
                        </div>
                    </div>
                    <button @click="flowResponseDialog.show = false"
                            class="p-1.5 text-ide-muted hover:text-ide-fg hover:bg-ide-line-active rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <!-- Body -->
            <div class="flex-1 overflow-auto p-4">
                <pre class="p-4 bg-ide-surface rounded-lg text-xs font-mono overflow-auto leading-relaxed"><code x-html="syntaxHighlightJson(flowResponseDialog.data)"></code></pre>
            </div>
        </div>
    </div>

    <!-- Delete Module Confirmation Dialog -->
    <div x-show="deleteModuleDialog.show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="deleteModuleDialog.show = false" x-cloak
         @keydown.escape.window="deleteModuleDialog.show = false">
        <div class="bg-ide-bg rounded-2xl shadow-xl w-full max-w-md mx-4 border border-ide-border" @click.stop>
            <div class="px-6 py-4 border-b border-ide-border">
                <h3 class="text-sm font-semibold text-ide-fg">Delete Module</h3>
            </div>
            <div class="px-6 py-5 space-y-4">
                <p class="text-xs text-ide-fg">
                    Are you sure you want to delete "<span class="font-semibold" x-text="deleteModuleDialog.moduleName"></span>"?
                </p>
                <template x-if="deleteModuleDialog.itemCount > 0">
                    <div class="space-y-2.5">
                        <p class="text-xs text-ide-muted">
                            This module contains <span class="font-semibold text-ide-fg" x-text="deleteModuleDialog.itemCount"></span> item(s). What would you like to do with them?
                        </p>
                        <button @click="deleteModule(deleteModuleDialog.moduleId, false)"
                                class="w-full flex items-center gap-3 p-3 rounded-lg border border-ide-border hover:border-blue-400/50 hover:bg-blue-500/5 transition-all text-left group">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-ide-fg">Move to Unorganized</div>
                                <div class="text-[10px] text-ide-muted mt-0.5">Delete the module but keep all items accessible</div>
                            </div>
                        </button>
                        <button @click="deleteModule(deleteModuleDialog.moduleId, true)"
                                class="w-full flex items-center gap-3 p-3 rounded-lg border border-ide-border hover:border-red-400/50 hover:bg-red-500/5 transition-all text-left group">
                            <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-red-500">Delete Everything</div>
                                <div class="text-[10px] text-ide-muted mt-0.5">Delete the module and all <span x-text="deleteModuleDialog.itemCount"></span> item(s) inside</div>
                            </div>
                        </button>
                    </div>
                </template>
                <template x-if="deleteModuleDialog.itemCount === 0">
                    <p class="text-xs text-ide-muted">This module is empty and will be permanently deleted.</p>
                </template>
            </div>
            <div class="px-6 py-3 border-t border-ide-border flex justify-end gap-2">
                <button @click="deleteModuleDialog.show = false"
                        class="px-3 py-1.5 text-xs text-ide-muted hover:text-ide-fg transition-colors">Cancel</button>
                <template x-if="deleteModuleDialog.itemCount === 0">
                    <button @click="deleteModule(deleteModuleDialog.moduleId, false)"
                            class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">Delete</button>
                </template>
            </div>
        </div>
    </div>

    <!-- Import cURL Modal -->
    <div x-show="showImportCurl" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showImportCurl = false" x-cloak>
        <div class="bg-ide-bg rounded-2xl shadow-xl w-full max-w-lg mx-4">
            <div class="px-6 py-4 border-b border-ide-border flex items-center justify-between">
                <h2 class="text-lg font-semibold text-ide-fg">Import from cURL</h2>
                <button @click="showImportCurl = false" class="text-gray-400 hover:text-ide-fg text-xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-sm text-ide-muted">Paste a cURL command to auto-fill the request builder.</p>
                <textarea x-model="curlInput" rows="8" placeholder="curl -X POST https://api.example.com/v1/endpoint ..."
                    class="w-full px-4 py-3 border border-ide-border rounded-lg bg-ide-bg text-sm font-mono text-ide-fg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                <template x-if="curlParseError">
                    <p class="text-sm text-red-500" x-text="curlParseError"></p>
                </template>
                <div class="flex justify-end gap-3">
                    <button @click="showImportCurl = false" class="px-4 py-2 text-sm text-ide-muted hover:bg-ide-line-active rounded-lg">Cancel</button>
                    <button @click="parseCurlCommand()" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts Modal -->
    <div x-show="showShortcuts" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showShortcuts = false" x-cloak>
        <div class="bg-ide-bg rounded-2xl shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-ide-border flex items-center justify-between">
                <h2 class="text-lg font-semibold text-ide-fg">Keyboard Shortcuts</h2>
                <button @click="showShortcuts = false" class="text-gray-400 hover:text-ide-fg text-xl">&times;</button>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-center justify-between py-2"><span class="text-sm text-ide-muted">Send Request</span><div class="flex gap-1"><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">Ctrl</kbd><span class="text-gray-400">+</span><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">Enter</kbd></div></div>
                <div class="flex items-center justify-between py-2"><span class="text-sm text-ide-muted">Save Request</span><div class="flex gap-1"><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">Ctrl</kbd><span class="text-gray-400">+</span><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">S</kbd></div></div>
                <div class="flex items-center justify-between py-2"><span class="text-sm text-ide-muted">Search Endpoints</span><div class="flex gap-1"><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">Ctrl</kbd><span class="text-gray-400">+</span><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">K</kbd></div></div>
                <div class="flex items-center justify-between py-2"><span class="text-sm text-ide-muted">Close Modal / Panel</span><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">Esc</kbd></div>
                <div class="flex items-center justify-between py-2"><span class="text-sm text-ide-muted">Switch Tabs (1-3)</span><div class="flex gap-1"><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">1</kbd><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">2</kbd><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">3</kbd></div></div>
                <div class="flex items-center justify-between py-2"><span class="text-sm text-ide-muted">Show Shortcuts</span><kbd class="px-2 py-1 bg-ide-border rounded text-xs font-mono text-ide-fg">?</kbd></div>
            </div>
            <div class="px-6 py-3 border-t border-ide-border bg-ide-bg rounded-b-2xl">
                <p class="text-xs text-ide-muted text-center">Press <kbd class="px-1 bg-ide-border rounded text-xs">?</kbd> anytime to toggle this help</p>
            </div>
        </div>
    </div>

    <!-- Import Preview Modal -->
    <div x-show="showImportPreview" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" @click.self="closeImportPreview()" x-cloak>
        <div class="bg-ide-bg rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[85vh] flex flex-col border border-ide-border">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-ide-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-ide-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <h2 class="text-lg font-semibold text-ide-fg">Import Preview</h2>
                    <span class="text-sm text-ide-muted" x-text="importPreviewItems.length + ' item(s)'"></span>
                </div>
                <button @click="closeImportPreview()" class="text-gray-400 hover:text-ide-fg text-xl">&times;</button>
            </div>

            <!-- Module Selection -->
            <div class="px-6 py-3 border-b border-ide-border flex items-center gap-3 flex-shrink-0">
                <label class="text-xs text-ide-muted flex-shrink-0">Import to:</label>
                <select x-model="importPreviewModule.id" @change="if(importPreviewModule.id !== 'new') runDuplicateDetection()"
                    class="flex-1 px-2 py-1.5 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg focus:outline-none focus:ring-1 focus:ring-ide-primary">
                    <option value="new">Create New Module</option>
                    <template x-for="mod in modules" :key="mod.id">
                        <optgroup :label="mod.name">
                            <option :value="mod.id" x-text="mod.name"></option>
                            <template x-for="child in (mod.children || [])" :key="child.id">
                                <option :value="child.id" x-text="'  ' + child.name"></option>
                            </template>
                        </optgroup>
                    </template>
                </select>
                <input x-show="importPreviewModule.id === 'new'" x-model="importPreviewModule.name" type="text" placeholder="Module name..."
                    class="flex-1 px-2 py-1.5 text-xs border border-ide-border rounded bg-ide-bg text-ide-fg placeholder-ide-muted focus:outline-none focus:ring-1 focus:ring-ide-primary">
            </div>

            <!-- Filter Bar -->
            <div class="px-6 py-2 border-b border-ide-border flex items-center justify-between flex-shrink-0">
                <div class="flex gap-1">
                    <button @click="importPreviewFilter = 'all'" class="px-2 py-1 text-xs rounded transition-colors"
                        :class="importPreviewFilter === 'all' ? 'bg-ide-primary text-white' : 'text-ide-muted hover:text-ide-fg hover:bg-ide-line-active'"
                        x-text="'All (' + importPreviewItems.length + ')'"></button>
                    <button @click="importPreviewFilter = 'new'" class="px-2 py-1 text-xs rounded transition-colors"
                        :class="importPreviewFilter === 'new' ? 'bg-green-600 text-white' : 'text-ide-muted hover:text-ide-fg hover:bg-ide-line-active'"
                        x-text="'New (' + importPreviewItems.filter(i => i.status === 'new').length + ')'"></button>
                    <button @click="importPreviewFilter = 'duplicates'" class="px-2 py-1 text-xs rounded transition-colors"
                        :class="importPreviewFilter === 'duplicates' ? 'bg-red-600 text-white' : 'text-ide-muted hover:text-ide-fg hover:bg-ide-line-active'"
                        x-text="'Duplicates (' + importPreviewItems.filter(i => i.status === 'duplicate' || i.status === 'possible').length + ')'"></button>
                </div>
                <button @click="toggleSelectAllImportItems()" class="text-xs text-ide-primary hover:underline">
                    <span x-text="getFilteredImportPreviewItems().every(i => i.selected) ? 'Deselect All' : 'Select All'"></span>
                </button>
            </div>

            <!-- Loading overlay -->
            <div x-show="importPreviewLoading" class="px-6 py-3 text-center text-xs text-ide-muted flex items-center justify-center gap-2 flex-shrink-0">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Checking for duplicates...
            </div>

            <!-- Item List -->
            <div class="flex-1 overflow-y-auto px-6 py-2">
                <template x-for="item in getFilteredImportPreviewItems()" :key="item.id">
                    <div class="flex items-center gap-3 py-2 border-b border-ide-border/50 last:border-0">
                        <!-- Checkbox -->
                        <input type="checkbox" x-model="item.selected" @change="if(!item.selected) item.importMode = 'skip'; else if(item.importMode === 'skip') item.importMode = 'import'"
                            class="w-3.5 h-3.5 rounded border-ide-border text-ide-primary focus:ring-ide-primary flex-shrink-0">
                        <!-- Item info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-ide-fg truncate" x-text="item.name"></span>
                                <!-- Status badge -->
                                <span x-show="item.status === 'new'" class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-green-500/20 text-green-400">New</span>
                                <span x-show="item.status === 'duplicate'" class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-red-500/20 text-red-400" x-text="'Duplicate (' + item.confidence + '%)'"></span>
                                <span x-show="item.status === 'possible'" class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-yellow-500/20 text-yellow-400" x-text="'Possible (' + item.confidence + '%)'"></span>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <template x-if="item.type === 'flow'">
                                    <span class="text-[10px] text-ide-muted" x-text="(item.data.steps || []).length + ' steps'"></span>
                                </template>
                                <template x-if="item.type === 'request'">
                                    <span class="text-[10px] text-ide-muted" x-text="(item.data.method || 'GET') + ' ' + (item.data.path || '/')"></span>
                                </template>
                                <span x-show="item.matchedExisting" class="text-[10px] text-ide-muted">
                                    Matches: <span class="text-ide-fg" x-text="item.matchedExisting?.name"></span>
                                </span>
                            </div>
                        </div>
                        <!-- Actions -->
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button x-show="item.matchedExisting" @click="showImportDiff(item)" class="px-2 py-1 text-[10px] text-ide-primary hover:bg-ide-primary/10 rounded" title="Compare">
                                Diff
                            </button>
                            <template x-if="item.status === 'duplicate' || item.status === 'possible'">
                                <div class="flex gap-1">
                                    <button @click="item.selected = true; item.importMode = 'copy'" class="px-2 py-1 text-[10px] rounded transition-colors"
                                        :class="item.importMode === 'copy' ? 'bg-blue-600 text-white' : 'text-ide-muted hover:text-ide-fg hover:bg-ide-line-active'">Copy</button>
                                    <button @click="item.selected = true; item.importMode = 'overwrite'" class="px-2 py-1 text-[10px] rounded transition-colors"
                                        :class="item.importMode === 'overwrite' ? 'bg-orange-600 text-white' : 'text-ide-muted hover:text-ide-fg hover:bg-ide-line-active'">Overwrite</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                <!-- Empty filtered state -->
                <div x-show="getFilteredImportPreviewItems().length === 0" class="py-8 text-center text-xs text-ide-muted">
                    No items match the current filter.
                </div>
            </div>

            <!-- Summary Footer -->
            <div class="px-6 py-3 border-t border-ide-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> <span x-text="getImportSummary().newCount"></span> new</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-500"></span> <span x-text="getImportSummary().overwriteCount"></span> overwrite</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> <span x-text="getImportSummary().copyCount"></span> copy</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-500"></span> <span x-text="getImportSummary().skipCount"></span> skip</span>
                </div>
                <div class="flex gap-2">
                    <button @click="closeImportPreview()" class="px-4 py-1.5 text-xs text-ide-muted hover:text-ide-fg rounded border border-ide-border hover:bg-ide-line-active">Cancel</button>
                    <button @click="executeImport()" :disabled="importPreviewLoading || getImportSummary().totalSelected === 0"
                        class="px-4 py-1.5 text-xs text-white bg-ide-primary rounded hover:bg-ide-primary/80 disabled:opacity-50 disabled:cursor-not-allowed"
                        x-text="importPreviewLoading ? 'Importing...' : 'Import ' + getImportSummary().totalSelected + ' item(s)'"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Diff Modal (Side-by-Side) -->
    <div x-show="importPreviewDiffItem" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60" @click.self="importPreviewDiffItem = null" x-cloak>
        <div class="bg-ide-bg rounded-2xl shadow-2xl w-full max-w-5xl mx-4 max-h-[85vh] flex flex-col border border-ide-border">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-ide-border flex items-center justify-between flex-shrink-0">
                <h2 class="text-lg font-semibold text-ide-fg">Compare Items</h2>
                <button @click="importPreviewDiffItem = null" class="text-gray-400 hover:text-ide-fg text-xl">&times;</button>
            </div>
            <!-- Content -->
            <div class="flex-1 overflow-hidden flex" x-show="importPreviewDiffItem">
                <!-- Existing (left) -->
                <div class="flex-1 border-r border-ide-border flex flex-col overflow-hidden">
                    <div class="px-4 py-2 border-b border-ide-border bg-ide-surface/50 text-xs font-semibold text-ide-muted">
                        Existing Item
                        <span class="font-normal ml-1" x-text="importPreviewDiffItem?.matchedExisting?.name || ''"></span>
                    </div>
                    <div class="flex-1 overflow-auto p-4">
                        <pre class="text-[11px] text-ide-fg font-mono whitespace-pre-wrap break-all" x-text="importPreviewDiffItem?.matchedExisting ? JSON.stringify(importPreviewDiffItem.matchedExisting, null, 2) : 'No existing item to compare'"></pre>
                    </div>
                </div>
                <!-- Incoming (right) -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <div class="px-4 py-2 border-b border-ide-border bg-ide-surface/50 text-xs font-semibold text-ide-muted">
                        Incoming Item
                        <span class="font-normal ml-1" x-text="importPreviewDiffItem?.name || ''"></span>
                    </div>
                    <div class="flex-1 overflow-auto p-4">
                        <pre class="text-[11px] text-ide-fg font-mono whitespace-pre-wrap break-all" x-text="importPreviewDiffItem?.data ? JSON.stringify(importPreviewDiffItem.data, null, 2) : ''"></pre>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="px-6 py-3 border-t border-ide-border flex justify-end flex-shrink-0">
                <button @click="importPreviewDiffItem = null" class="px-4 py-1.5 text-xs text-ide-muted hover:text-ide-fg rounded border border-ide-border hover:bg-ide-line-active">Close</button>
            </div>
        </div>
    </div>

    <!-- Telescope Panel -->
    <template x-if="telescopeEnabled">
        <div x-show="showTelescope" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="fixed right-0 top-0 h-full w-96 bg-ide-bg border-l border-ide-border shadow-xl z-40 flex flex-col" x-cloak>
            <!-- Header -->
            <div class="px-4 py-3 border-b border-ide-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <h3 class="font-semibold text-ide-fg">Telescope</h3>
                    <span x-show="telescopeTotal > 0" class="text-xs text-ide-muted" x-text="telescopeTotal + ' entries'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="loadTelescopeEntries(true)" class="text-xs text-purple-500 hover:text-purple-600" :disabled="telescopeLoading">Refresh</button>
                    <button @click="showTelescope = false" class="text-gray-400 hover:text-ide-fg text-xl">&times;</button>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="px-4 py-2 border-b border-ide-border">
                <input type="text" x-model="telescopeFilter" @keydown.enter="telescopeSearch()" @input.debounce.500ms="telescopeSearch()" placeholder="Search by path, status..." class="w-full px-3 py-1.5 border border-ide-border rounded-lg bg-ide-bg text-sm text-ide-fg">
            </div>

            <!-- Method Filter Buttons -->
            <div class="px-4 py-2 flex gap-1.5 flex-wrap border-b border-ide-border">
                <button @click="setTelescopeMethodFilter('')"
                    :class="telescopeMethodFilter === '' ? 'bg-ide-fg text-ide-bg' : 'bg-ide-border text-ide-muted hover:bg-ide-line-active'"
                    class="px-2.5 py-1 rounded text-xs font-medium transition-colors">ALL</button>
                <template x-for="m in ['GET', 'POST', 'PUT', 'PATCH', 'DELETE']" :key="m">
                    <button @click="setTelescopeMethodFilter(m)"
                        :class="telescopeMethodFilter === m ? getMethodBadgeClass(m) + ' ring-2 ring-offset-1 ring-offset-ide-bg' : 'bg-ide-border text-ide-muted hover:bg-ide-line-active'"
                        class="px-2.5 py-1 rounded text-xs font-bold transition-colors" x-text="m"></button>
                </template>
            </div>

            <!-- Entries List -->
            <div class="flex-1 overflow-y-auto">
                <!-- Loading Spinner -->
                <template x-if="telescopeLoading && telescopeEntries.length === 0">
                    <div class="flex items-center justify-center py-12">
                        <svg class="animate-spin w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </template>

                <!-- Entry rows -->
                <template x-for="entry in telescopeEntries" :key="entry.uuid">
                    <div @click="loadTelescopeEntry(entry.uuid)" class="px-4 py-3 hover:bg-ide-primary/5 cursor-pointer border-b border-ide-border transition-colors">
                        <div class="flex items-center gap-2">
                            <span :class="getMethodBadgeClass(entry.method)" class="px-1.5 py-0.5 rounded text-xs font-bold" x-text="entry.method"></span>
                            <span class="text-xs font-mono text-ide-muted truncate flex-1" x-text="entry.uri.split('?')[0]"></span>
                        </div>
                        <div class="flex items-center gap-3 mt-1 text-xs text-ide-muted">
                            <span :class="{
                                'text-[var(--ide-success-text)]': entry.status >= 200 && entry.status < 300,
                                'text-[var(--ide-warning-text)]': entry.status >= 300 && entry.status < 400,
                                'text-[var(--ide-error-text)]': entry.status >= 400
                            }" x-text="entry.status"></span>
                            <span x-text="entry.duration ? Math.round(entry.duration) + 'ms' : '-'"></span>
                            <span class="ml-auto" x-text="formatHistoryTime(entry.timestamp)"></span>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <template x-if="!telescopeLoading && telescopeEntries.length === 0">
                    <div class="px-4 py-8 text-center text-ide-muted text-sm">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <p>No telescope entries found.</p>
                        <p class="mt-1 text-xs">Make API requests to see them here.</p>
                    </div>
                </template>

                <!-- Load More -->
                <template x-if="telescopeHasMore">
                    <div class="px-4 py-3 text-center">
                        <button @click="loadMoreTelescope()" :disabled="telescopeLoading"
                            class="px-4 py-1.5 text-sm text-ide-primary hover:bg-ide-primary/5 rounded-lg border border-ide-primary/20 transition-colors disabled:opacity-50">
                            <span x-show="!telescopeLoading">Load More</span>
                            <span x-show="telescopeLoading">Loading...</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <!-- Loading Skeleton (shown during initial spec parse) -->
    <div
        x-show="isLoading"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="fixed inset-0 z-50 bg-ide-bg flex items-center justify-center"
    >
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-xl skeleton"></div>
            <div class="w-32 h-4 mx-auto mb-2 rounded skeleton"></div>
            <div class="w-24 h-3 mx-auto rounded skeleton"></div>
        </div>
    </div>
</body>
</html>
