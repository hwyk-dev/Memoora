<script>
function drawingTool(existingDataUrl) {
    return {
        tool: 'pen',
        penColor: '#1e293b',
        brushSize: 3,
        isDrawing: false,
        isEmpty: !existingDataUrl,
        history: [],
        ctx: null,
        canvas: null,
        _ready: false,

        init() {
            this.canvas = document.getElementById('drawing-canvas');
            if (!this.canvas) return;
            this.ctx = this.canvas.getContext('2d');
            window.canvasHasContent = !!existingDataUrl;

            // Defer setup to let x-show/Alpine finish laying out the DOM.
            // Then fall back to ResizeObserver in case the canvas tab is
            // still hidden (user starts on the text tab).
            this.$nextTick(() => this._trySetup());
        },

        _trySetup() {
            if (this._ready) return;

            if (this.canvas.offsetWidth > 0) {
                this._doSetup();
            } else {
                // Canvas is inside a hidden tab — wait until it gets a size.
                const obs = new ResizeObserver(() => {
                    if (this.canvas.offsetWidth > 0) {
                        obs.disconnect();
                        this._doSetup();
                    }
                });
                obs.observe(this.canvas);
            }
        },

        _doSetup() {
            if (this._ready) return;
            this._ready = true;

            this._resize();
            window.addEventListener('resize', () => this._resize());

            if (existingDataUrl) {
                this._loadImage(existingDataUrl, () => {
                    this.isEmpty = false;
                    window.canvasHasContent = true;
                    this._snapshot();
                });
            }
        },

        // Load a data-URL onto the canvas using CSS-pixel dimensions.
        _loadImage(dataUrl, onDone) {
            const img = new Image();
            img.onload = () => {
                const w = this.canvas.offsetWidth;
                const h = this.canvas.offsetHeight;
                this.ctx.drawImage(img, 0, 0, w, h);
                if (onDone) onDone();
            };
            img.src = dataUrl;
        },

        _resize() {
            const w = this.canvas.offsetWidth  || 700;
            const h = this.canvas.offsetHeight || 340;

            // Capture current pixels before wiping the canvas.
            const snap = this._ready && !this.isEmpty ? this.canvas.toDataURL() : null;

            this.canvas.width  = w * window.devicePixelRatio;
            this.canvas.height = h * window.devicePixelRatio;
            this.ctx.setTransform(window.devicePixelRatio, 0, 0, window.devicePixelRatio, 0, 0);

            if (snap) {
                this._loadImage(snap);
            }
        },

        // ── drawing ──────────────────────────────────────────────────────

        getPos(e) {
            const rect = this.canvas.getBoundingClientRect();
            const src  = e.touches ? e.touches[0] : e;
            return {
                x: (src.clientX - rect.left) * (this.canvas.offsetWidth  / rect.width),
                y: (src.clientY - rect.top)  * (this.canvas.offsetHeight / rect.height),
            };
        },

        startDraw(e) {
            this._snapshot();
            this.isDrawing = true;
            const { x, y } = this.getPos(e);
            this.ctx.beginPath();
            this.ctx.moveTo(x, y);
        },

        draw(e) {
            if (!this.isDrawing) return;
            const { x, y } = this.getPos(e);
            this.ctx.lineTo(x, y);
            this.ctx.lineWidth  = this.brushSize;
            this.ctx.lineCap    = 'round';
            this.ctx.lineJoin   = 'round';

            if (this.tool === 'eraser') {
                this.ctx.globalCompositeOperation = 'destination-out';
                this.ctx.strokeStyle = 'rgba(0,0,0,1)';
            } else {
                this.ctx.globalCompositeOperation = 'source-over';
                this.ctx.strokeStyle = this.penColor;
            }
            this.ctx.stroke();
            this.isEmpty = false;
            window.canvasHasContent = true;
        },

        stopDraw() {
            this.isDrawing = false;
            this.ctx.beginPath();
            this.ctx.globalCompositeOperation = 'source-over';
        },

        // ── history ───────────────────────────────────────────────────────

        _snapshot() {
            this.history.push(this.canvas.toDataURL());
            if (this.history.length > 30) this.history.shift();
        },

        undo() {
            if (!this.history.length) return;
            const prev = this.history.pop();
            const w = this.canvas.offsetWidth;
            const h = this.canvas.offsetHeight;
            const img = new Image();
            img.onload = () => {
                this.ctx.clearRect(0, 0, w, h);
                this.ctx.drawImage(img, 0, 0, w, h);
                this.isEmpty    = this.history.length === 0;
                window.canvasHasContent = !this.isEmpty;
            };
            img.src = prev;
        },

        clearCanvas() {
            this._snapshot();
            const w = this.canvas.offsetWidth;
            const h = this.canvas.offsetHeight;
            this.ctx.clearRect(0, 0, w, h);
            this.isEmpty = true;
            window.canvasHasContent = false;
        },
    };
}
</script>
