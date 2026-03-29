{{-- Drawing canvas UI — included in create & edit --}}
<div class="border border-slate-200 dark:border-zinc-700 rounded-xl overflow-hidden" x-data="drawingTool({{ json_encode($existingDrawing ?? null) }})">

    {{-- Drawing toolbar --}}
    <div class="flex flex-wrap items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700">

        {{-- Tool: Pen / Eraser --}}
        <div class="flex items-center gap-1">
            <button type="button" @click="tool = 'pen'"
                    :class="tool === 'pen' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-zinc-700'"
                    class="p-1.5 rounded-lg transition-colors" title="Pen">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </button>
            <button type="button" @click="tool = 'eraser'"
                    :class="tool === 'eraser' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-zinc-700'"
                    class="p-1.5 rounded-lg transition-colors" title="Eraser">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>

        <div class="w-px h-5 bg-slate-300 dark:bg-zinc-600"></div>

        {{-- Brush size --}}
        <div class="flex items-center gap-1">
            <span class="text-xs text-slate-500 dark:text-slate-400 mr-1">Size</span>
            <button type="button" @click="brushSize = 2" :class="brushSize === 2 ? 'ring-2 ring-indigo-500' : ''"
                    class="w-5 h-5 rounded-full bg-slate-400 dark:bg-slate-500 flex items-center justify-center" title="Small">
                <span class="w-1 h-1 rounded-full bg-white"></span>
            </button>
            <button type="button" @click="brushSize = 5" :class="brushSize === 5 ? 'ring-2 ring-indigo-500' : ''"
                    class="w-5 h-5 rounded-full bg-slate-400 dark:bg-slate-500 flex items-center justify-center" title="Medium">
                <span class="w-2 h-2 rounded-full bg-white"></span>
            </button>
            <button type="button" @click="brushSize = 12" :class="brushSize === 12 ? 'ring-2 ring-indigo-500' : ''"
                    class="w-5 h-5 rounded-full bg-slate-400 dark:bg-slate-500 flex items-center justify-center" title="Large">
                <span class="w-3 h-3 rounded-full bg-white"></span>
            </button>
        </div>

        <div class="w-px h-5 bg-slate-300 dark:bg-zinc-600"></div>

        {{-- Color swatches --}}
        <div class="flex items-center gap-1">
            @foreach (['#1e293b','#ef4444','#f97316','#eab308','#22c55e','#3b82f6','#8b5cf6','#ec4899','#ffffff'] as $hex)
                <button type="button" @click="penColor = '{{ $hex }}'"
                        :class="penColor === '{{ $hex }}' ? 'ring-2 ring-offset-1 ring-indigo-500 dark:ring-offset-zinc-800' : ''"
                        class="w-5 h-5 rounded-full border border-slate-300 dark:border-zinc-600 transition-all"
                        style="background-color: {{ $hex }}" title="{{ $hex }}"></button>
            @endforeach
            {{-- Custom color --}}
            <label class="relative w-5 h-5 rounded-full overflow-hidden border border-slate-300 dark:border-zinc-600 cursor-pointer" title="Custom color">
                <input type="color" x-model="penColor" class="absolute inset-0 opacity-0 w-8 h-8 -m-1 cursor-pointer">
                <span class="block w-full h-full" :style="'background: conic-gradient(red, yellow, lime, aqua, blue, magenta, red)'"></span>
            </label>
        </div>

        <div class="flex-1"></div>

        {{-- Undo / Clear --}}
        <button type="button" @click="undo()"
                class="p-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors" title="Undo">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
        </button>
        <button type="button" @click="clearCanvas()"
                class="p-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Clear">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Canvas --}}
    <div class="bg-white dark:bg-zinc-900 relative">
        <canvas id="drawing-canvas"
                class="w-full"
                style="height: 340px;"
                @mousedown="startDraw($event)"
                @mousemove="draw($event)"
                @mouseup="stopDraw()"
                @mouseleave="stopDraw()"
                @touchstart.prevent="startDraw($event)"
                @touchmove.prevent="draw($event)"
                @touchend="stopDraw()">
        </canvas>
        <p x-show="isEmpty" class="absolute inset-0 flex items-center justify-center text-sm text-slate-400 dark:text-slate-600 pointer-events-none select-none">
            Click and drag to draw…
        </p>
    </div>
</div>
