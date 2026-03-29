<x-app-layout>
    <x-slot name="title">New Note</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('notes.index') }}" class="hover:text-slate-900 dark:hover:text-slate-100 transition-colors">Notes</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-900 dark:text-slate-100 font-medium">New Note</span>
        </div>
    </x-slot>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
        <style>
            .ql-toolbar { border-radius: 0.75rem 0.75rem 0 0 !important; border-color: rgb(203 213 225) !important; background: #f8fafc; }
            .dark .ql-toolbar { border-color: rgb(63 63 70) !important; background: #27272a; }
            .ql-container { border-radius: 0 0 0.75rem 0.75rem !important; border-color: rgb(203 213 225) !important; font-size: 0.9375rem; min-height: 280px; }
            .dark .ql-container { border-color: rgb(63 63 70) !important; background: #18181b; color: #f1f5f9; }
            .ql-editor { min-height: 280px; }
            .ql-toolbar .ql-stroke { stroke: #64748b; }
            .ql-toolbar .ql-fill { fill: #64748b; }
            .ql-toolbar .ql-picker { color: #64748b; }
            .dark .ql-toolbar .ql-stroke { stroke: #94a3b8; }
            .dark .ql-toolbar .ql-fill { fill: #94a3b8; }
            .dark .ql-toolbar .ql-picker { color: #94a3b8; }
            .dark .ql-toolbar .ql-picker-options { background: #27272a; border-color: #3f3f46; }
            .dark .ql-toolbar button:hover .ql-stroke, .dark .ql-toolbar button.ql-active .ql-stroke { stroke: #818cf8; }
            .dark .ql-toolbar button:hover .ql-fill, .dark .ql-toolbar button.ql-active .ql-fill { fill: #818cf8; }
            .dark .ql-toolbar button:hover, .dark .ql-toolbar button.ql-active { color: #818cf8; }
            /* Canvas drawing area */
            #drawing-canvas { cursor: crosshair; touch-action: none; display: block; }
        </style>
    @endpush

    <div class="max-w-3xl" x-data="noteEditor()">
        <div class="card">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Create a new note</h2>

            <form method="POST" action="{{ route('notes.store') }}" class="space-y-5" @submit="syncFields">
                @csrf

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}"
                           required autofocus maxlength="255" placeholder="Note title…"
                           class="form-input @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tab switcher: Text / Draw --}}
                <div>
                    <div class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-zinc-800 rounded-xl w-fit mb-4">
                        <button type="button" @click="activeTab = 'text'"
                                :class="activeTab === 'text' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                                class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Text
                        </button>
                        <button type="button" @click="activeTab = 'draw'"
                                :class="activeTab === 'draw' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                                class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Draw
                        </button>
                    </div>

                    {{-- Text editor (Quill) --}}
                    <div x-show="activeTab === 'text'" style="display:none">
                        <div id="quill-editor"></div>
                        <input type="hidden" name="content" id="content-input" value="{{ old('content') }}">
                        @error('content')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Drawing canvas --}}
                    <div x-show="activeTab === 'draw'" style="display:none">
                        @include('notes._canvas')
                    </div>
                </div>

                <input type="hidden" name="drawing" id="drawing-input">

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('notes.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Save note</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
        <script>
            function noteEditor() {
                return {
                    activeTab: 'text',
                    _quillReady: false,

                    init() {
                        // Watch for tab switches — init Quill the first time text tab is shown.
                        this.$watch('activeTab', (tab) => {
                            if (tab === 'text') this._initQuill();
                        });
                        // Starting on text tab — init after Alpine settles.
                        this.$nextTick(() => this._initQuill());
                    },

                    _initQuill() {
                        if (this._quillReady) return;
                        this._quillReady = true;
                        window.quillInstance = new Quill('#quill-editor', {
                            theme: 'snow',
                            placeholder: 'Write your note here…',
                            modules: {
                                toolbar: [
                                    [{ header: [1, 2, 3, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ color: [] }, { background: [] }],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    [{ align: [] }],
                                    ['blockquote', 'code-block'],
                                    ['link'],
                                    ['clean']
                                ]
                            }
                        });
                        // Restore old content on validation error
                        const old = document.getElementById('content-input').value;
                        if (old) window.quillInstance.clipboard.dangerouslyPasteHTML(old);
                    },

                    syncFields() {
                        if (window.quillInstance) {
                            const html = window.quillInstance.getSemanticHTML();
                            document.getElementById('content-input').value = html === '<p><br></p>' ? '' : html;
                        }
                        const canvas = document.getElementById('drawing-canvas');
                        if (canvas && window.canvasHasContent) {
                            document.getElementById('drawing-input').value = canvas.toDataURL('image/png');
                        }
                    }
                }
            }
        </script>
        @include('notes._canvas_script')
    @endpush
</x-app-layout>
