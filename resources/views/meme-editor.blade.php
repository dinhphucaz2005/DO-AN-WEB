@extends('layouts.app')

@section('title', 'Meme Creator - Tạo Meme Online Miễn Phí')

@section('content')
<div class="meme-editor">
    <!-- Header with shortcuts hint -->
    <div class="editor-header">
        <div>
            <h1>🎨 Meme Creator</h1>
            <p>Tạo meme hài hước từ ảnh của bạn! Upload ảnh, thêm text và emoji để tạo ra những meme viral.</p>
        </div>
        <button id="toggleShortcuts" class="btn btn-info">⌨️ Phím tắt</button>
    </div>

    <!-- Shortcuts Panel (hidden by default) -->
    <div id="shortcutsPanel" class="shortcuts-panel" style="display:none;">
        <h3>⌨️ Phím tắt</h3>
        <div class="shortcuts-grid">
            <div class="shortcut-item"><kbd>Ctrl+Z</kbd> <span>Hoàn tác (Undo)</span></div>
            <div class="shortcut-item"><kbd>Ctrl+Y</kbd> hoặc <kbd>Ctrl+Shift+Z</kbd> <span>Làm lại (Redo)</span></div>
            <div class="shortcut-item"><kbd>Delete</kbd> / <kbd>Backspace</kbd> <span>Xóa đối tượng</span></div>
            <div class="shortcut-item"><kbd>Ctrl+Wheel</kbd> <span>Phóng to/thu nhỏ</span></div>
            <div class="shortcut-item"><kbd>+</kbd> / <kbd>-</kbd> <span>Zoom</span></div>
            <div class="shortcut-item"><kbd>←</kbd> / <kbd>→</kbd> <span>Xoay</span></div>
            <div class="shortcut-item"><kbd>↑</kbd> / <kbd>↓</kbd> <span>Kích thước</span></div>
        </div>
    </div>

    <div class="editor-container">
        <!-- Upload Section -->
        <div class="upload-section">
            <div class="upload-area" id="uploadArea">
                <div class="upload-placeholder">
                    <i class="upload-icon">📁</i>
                    <p>Kéo thả ảnh vào đây hoặc click để chọn</p>
                    <input type="file" id="imageInput" accept="image/*" hidden>
                </div>
            </div>
        </div>
        
        <!-- Editor Section -->
        <div class="editor-section">
            <div class="canvas-container">
                <canvas id="memeCanvas" width="800" height="600"></canvas>
            </div>
            
            <!-- Controls -->
            <div class="controls">
                <!-- Text Controls -->
                <div class="control-group">
                    <h3>✏️ Text</h3>
                    <div class="text-controls">
                        <button id="addText" class="btn btn-secondary">➕ Add Text</button>
                        <div class="text-options">
                            <label>Font Size: <input type="range" id="fontSize" min="10" max="120" value="40"></label>
                            <label>Màu: <input type="color" id="textColor" value="#ffffff"></label>
                            <label>Stroke: <input type="color" id="strokeColor" value="#000000"></label>
                        </div>
                    </div>
                </div>
                
                <!-- Icon Catalog -->
                <div class="control-group">
                    <h3>😀 Icons & Stickers</h3>
                    <div class="icon-catalog" id="iconCatalog">
                        <div class="icon-item" data-icon="😂">😂</div>
                        <div class="icon-item" data-icon="😭">😭</div>
                        <div class="icon-item" data-icon="🤔">🤔</div>
                        <div class="icon-item" data-icon="😎">😎</div>
                        <div class="icon-item" data-icon="🔥">🔥</div>
                        <div class="icon-item" data-icon="💀">💀</div>
                        <div class="icon-item" data-icon="👌">👌</div>
                        <div class="icon-item" data-icon="🤯">🤯</div>
                        <div class="icon-item" data-icon="😍">😍</div>
                        <div class="icon-item" data-icon="🤮">🤮</div>
                        <div class="icon-item" data-icon="💯">💯</div>
                        <div class="icon-item" data-icon="⚡">⚡</div>
                        <div class="icon-item" data-icon="🌟">🌟</div>
                        <div class="icon-item" data-icon="💥">💥</div>
                        <div class="icon-item" data-icon="👍">👍</div>
                        <div class="icon-item" data-icon="👎">👎</div>
                    </div>

                    <!-- User sticker upload / drop area -->
                    <h4 style="margin-top:12px; margin-bottom:8px;">🖼️ Your Stickers</h4>
                    <div class="user-sticker-drop" id="userStickerDrop">
                        <p>Kéo thả nhiều ảnh vào đây để thêm vào catalog (PNG/JPG). Hoặc click để chọn.</p>
                        <input type="file" id="userStickerInput" accept="image/*" multiple hidden>
                    </div>
                    <div class="icon-catalog" id="userStickerCatalog">
                        <!-- user sticker thumbnails will be appended here -->
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="control-group">
                    <h3>💾 Actions</h3>
                    @auth
                    <div class="save-controls" style="margin-bottom: 15px;">
                        <input type="text" id="memeTitle" placeholder="Meme Title..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 8px;">
                        <button id="saveMeme" class="btn btn-primary" style="width: 100%;">💾 Save Meme</button>
                    </div>
                    @endauth
                    <div class="action-buttons">
                        <!-- Undo/Redo -->
                        <div style="display:flex; gap:8px; margin-bottom:8px;">
                            <button id="undoBtn" class="btn btn-secondary" style="flex:1;" title="Undo (Ctrl+Z)" disabled>↶ Undo</button>
                            <button id="redoBtn" class="btn btn-secondary" style="flex:1;" title="Redo (Ctrl+Y)" disabled>↷ Redo</button>
                        </div>

                        <button id="clearCanvas" class="btn btn-secondary">🗑️ Clear</button>
                        <button id="downloadMeme" class="btn btn-primary">💾 Download</button>
                        <button id="resetEditor" class="btn btn-secondary">🔄 Reset</button>

                        <!-- Zoom controls -->
                        <div style="display:flex; flex-wrap: wrap; gap:8px; align-items:center; margin-top:8px;">
                            <button id="zoomOut" class="btn btn-secondary" style="flex:1;" title="Zoom Out (-)">➖</button>
                            <div id="zoomIndicator" style="min-width:56px; text-align:center; font-weight:600;">100%</div>
                            <button id="zoomIn" class="btn btn-secondary" style="flex:1;" title="Zoom In (+)">➕</button>
                        </div>
                        <button id="resetZoom" class="btn btn-secondary" style="width:100%; margin-top:4px;">⤢ Reset Zoom</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.meme-editor {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header */
.editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 20px;
}

.editor-header h1 {
    text-align: left;
    color: #333;
    margin: 0 0 10px 0;
}

.editor-header p {
    text-align: left;
    color: #666;
    margin: 0;
}

/* Shortcuts Panel */
.shortcuts-panel {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.shortcuts-panel h3 {
    margin: 0 0 15px 0;
    color: #333;
}

.shortcuts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 12px;
}

.shortcut-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.shortcut-item kbd {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 4px 8px;
    font-family: monospace;
    font-size: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    min-width: 80px;
    text-align: center;
}

.shortcut-item span {
    color: #666;
}

.meme-editor h1 {
    text-align: center;
    color: #333;
    margin-bottom: 10px;
}

.meme-editor > p {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}

.editor-container {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 20px;
    min-height: 600px;
}

/* Upload Section */
.upload-section {
    display: flex;
    flex-direction: column;
}

.upload-area {
    border: 3px dashed #ddd;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    background: #f9f9f9;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.upload-area:hover {
    border-color: #ff6b35;
    background: #fff5f0;
}

.upload-area.dragover {
    border-color: #ff6b35;
    background: #fff5f0;
    transform: scale(1.02);
}

.upload-placeholder {
    color: #666;
}

.upload-icon {
    font-size: 48px;
    display: block;
    margin-bottom: 15px;
}

/* Editor Section */
.editor-section {
    display: flex;
    flex-direction: column;
}

.canvas-container {
    background: #f0f0f0;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
}

#memeCanvas {
    max-width: 100%;
    height: auto;
    border: 2px solid #ddd;
    border-radius: 8px;
    background: white;
    cursor: crosshair;
}

/* Controls */
.controls {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.control-group {
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 15px;
}

.control-group h3 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1rem;
}

.text-controls {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.text-controls input[type="text"] {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.text-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 10px;
}

.text-options label {
    display: flex;
    flex-direction: column;
    font-size: 12px;
    color: #666;
}

.text-options input {
    margin-top: 5px;
}

/* Icon Catalog */
.icon-catalog {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}

.icon-item {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    border: 2px solid transparent;
    border-radius: 6px;
    cursor: pointer;
    font-size: 20px;
    transition: all 0.2s ease;
}

.icon-item:hover {
    background: #e0e0e0;
    border-color: #ff6b35;
    transform: scale(1.1);
}

/* User sticker drop area */
.user-sticker-drop {
    border: 2px dashed #007bff;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background: #f0f8ff;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-bottom: 10px;
}

.user-sticker-drop.dragover {
    background: #e0f7ff;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:active {
    transform: translateY(1px);
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

.btn-primary {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #e55a2b 0%, #e0861b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255,107,53,0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover:not(:disabled) {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108,117,125,0.3);
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23,162,184,0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .editor-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .controls {
        grid-template-columns: 1fr;
    }
    
    .text-options {
        grid-template-columns: 1fr;
    }
    
    .icon-catalog {
        grid-template-columns: repeat(6, 1fr);
    }
}
</style>

<script>
class MemeEditor {
    constructor() {
        this.canvas = new fabric.Canvas('memeCanvas', {
            width: 800,
            height: 600,
            backgroundColor: '#f0f0f0',
            preserveObjectStacking: true,
        });
        this.uploadArea = document.getElementById('uploadArea');
        this.imageInput = document.getElementById('imageInput');
        this.userStickerDrop = document.getElementById('userStickerDrop');
        this.userStickerInput = document.getElementById('userStickerInput');

        // History for undo/redo
        this.history = [];
        this.historyStep = -1;
        this.isHistoryAction = false;

        this.initializeEvents();
        this.initializeHistoryTracking();
        this.updateCanvasMessage();
        this.updateZoomIndicator();
        this.updateHistoryButtons();
    }

    initializeEvents() {
        // Upload events
        this.uploadArea.addEventListener('click', () => this.imageInput.click());
        this.imageInput.addEventListener('change', (e) => this.handleImageUpload(e));
        // Drag and drop for upload area
        this.uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); this.uploadArea.classList.add('dragover'); });
        this.uploadArea.addEventListener('dragleave', () => this.uploadArea.classList.remove('dragover'));
        this.uploadArea.addEventListener('drop', (e) => { e.preventDefault(); this.uploadArea.classList.remove('dragover'); this.handleImageDrop(e); });

        // Text controls
        document.getElementById('addText').addEventListener('click', () => this.addText());
        document.getElementById('textColor').addEventListener('input', (e) => this.updateActiveObject({ fill: e.target.value }));
        document.getElementById('strokeColor').addEventListener('input', (e) => this.updateActiveObject({ stroke: e.target.value }));
        document.getElementById('fontSize').addEventListener('input', (e) => this.updateActiveObject({ fontSize: parseInt(e.target.value, 10) }));

        // Icon/Sticker catalog
        document.querySelectorAll('.icon-item').forEach(item => {
            item.addEventListener('click', () => {
                const icon = item.dataset.icon;
                if (icon) this.addEmoji(icon);
            });
        });
        
        // User sticker drop/input
        this.userStickerDrop.addEventListener('click', () => this.userStickerInput.click());
        this.userStickerDrop.addEventListener('dragover', (e) => { e.preventDefault(); this.userStickerDrop.classList.add('dragover'); });
        this.userStickerDrop.addEventListener('dragleave', () => this.userStickerDrop.classList.remove('dragover'));
        this.userStickerDrop.addEventListener('drop', (e) => { e.preventDefault(); this.userStickerDrop.classList.remove('dragover'); this.handleUserStickersDrop(e.dataTransfer.files); });
        this.userStickerInput.addEventListener('change', (e) => this.handleUserStickersDrop(e.target.files));

        // Action Buttons
        document.getElementById('clearCanvas').addEventListener('click', () => this.clearCanvas());
        document.getElementById('downloadMeme').addEventListener('click', () => this.downloadMeme());
        document.getElementById('resetEditor').addEventListener('click', () => this.resetEditor());
        if (document.getElementById('saveMeme')) {
            document.getElementById('saveMeme').addEventListener('click', () => this.saveMeme());
        }

        // Undo/Redo buttons
        document.getElementById('undoBtn').addEventListener('click', () => this.undo());
        document.getElementById('redoBtn').addEventListener('click', () => this.redo());

        // Toggle shortcuts panel
        document.getElementById('toggleShortcuts').addEventListener('click', () => {
            const panel = document.getElementById('shortcutsPanel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        });

        // Zoom controls
        document.getElementById('zoomIn').addEventListener('click', () => this.zoomIn());
        document.getElementById('zoomOut').addEventListener('click', () => this.zoomOut());
        document.getElementById('resetZoom').addEventListener('click', () => this.resetZoom());

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Undo: Ctrl+Z
            if (e.ctrlKey && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                this.undo();
                return;
            }

            // Redo: Ctrl+Y or Ctrl+Shift+Z
            if ((e.ctrlKey && e.key === 'y') || (e.ctrlKey && e.shiftKey && e.key === 'Z')) {
                e.preventDefault();
                this.redo();
                return;
            }

            // Delete selected object
            if (e.key === 'Delete' || e.key === 'Backspace') {
                const activeObject = this.canvas.getActiveObject();
                if (activeObject && !e.target.matches('input, textarea')) {
                    e.preventDefault();
                    this.canvas.remove(activeObject);
                    this.canvas.requestRenderAll();
                }
                return;
            }

            // Zoom shortcuts
            if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                this.zoomIn();
                return;
            }
            if (e.key === '-' || e.key === '_') {
                e.preventDefault();
                this.zoomOut();
                return;
            }
        });

        // Canvas events for updating controls
        this.canvas.on('selection:created', (e) => this.updateControls(e.target));
        this.canvas.on('selection:updated', (e) => this.updateControls(e.target));
        this.canvas.on('selection:cleared', () => this.updateControls(null));

        // Canvas events for zoom/pan
        this.canvas.on('mouse:wheel', (opt) => {
            const delta = opt.e.deltaY;
            let zoom = this.canvas.getZoom();
            zoom *= 0.999 ** delta;
            if (zoom > 20) zoom = 20;
            if (zoom < 0.01) zoom = 0.01;
            this.canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
            this.updateZoomIndicator();
            opt.e.preventDefault();
            opt.e.stopPropagation();
        });
    }

    initializeHistoryTracking() {
        // Track canvas changes for undo/redo
        this.canvas.on('object:added', () => this.saveHistory());
        this.canvas.on('object:modified', () => this.saveHistory());
        this.canvas.on('object:removed', () => this.saveHistory());

        // Save initial state
        this.saveHistory();
    }

    saveHistory() {
        if (this.isHistoryAction) return;

        // Remove any states after current step (when user makes new action after undo)
        this.history = this.history.slice(0, this.historyStep + 1);

        // Save current canvas state
        const state = JSON.stringify(this.canvas.toJSON());
        this.history.push(state);
        this.historyStep++;

        // Limit history to 50 steps to prevent memory issues
        if (this.history.length > 50) {
            this.history.shift();
            this.historyStep--;
        }

        this.updateHistoryButtons();
    }

    undo() {
        if (this.historyStep > 0) {
            this.historyStep--;
            this.loadHistoryState(this.history[this.historyStep]);
        }
    }

    redo() {
        if (this.historyStep < this.history.length - 1) {
            this.historyStep++;
            this.loadHistoryState(this.history[this.historyStep]);
        }
    }

    loadHistoryState(state) {
        this.isHistoryAction = true;
        this.canvas.loadFromJSON(state, () => {
            this.canvas.requestRenderAll();
            this.isHistoryAction = false;
            this.updateHistoryButtons();
        });
    }

    updateHistoryButtons() {
        const undoBtn = document.getElementById('undoBtn');
        const redoBtn = document.getElementById('redoBtn');

        if (undoBtn) {
            undoBtn.disabled = this.historyStep <= 0;
        }
        if (redoBtn) {
            redoBtn.disabled = this.historyStep >= this.history.length - 1;
        }
    }

    saveMeme() {
        const title = document.getElementById('memeTitle').value;
        if (!title) {
            alert('Please enter a title for your meme.');
            return;
        }

        const data = JSON.stringify(this.canvas.toJSON());
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("memes.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                title: title,
                data: data,
            }),
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '{{ route("memes.index") }}';
            } else {
                alert('Failed to save meme. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error saving meme:', error);
            alert('An error occurred while saving the meme.');
        });
    }

    updateZoomIndicator() {
        const zoomIndicator = document.getElementById('zoomIndicator');
        if (zoomIndicator) {
            zoomIndicator.textContent = `${Math.round(this.canvas.getZoom() * 100)}%`;
        }
    }

    zoomIn() {
        let zoom = this.canvas.getZoom();
        zoom = zoom * 1.1; // Increase zoom by 10%
        if (zoom > 20) zoom = 20;
        this.canvas.setZoom(zoom);
        this.updateZoomIndicator();
    }

    zoomOut() {
        let zoom = this.canvas.getZoom();
        zoom = zoom / 1.1; // Decrease zoom by 10%
        if (zoom < 0.01) zoom = 0.01;
        this.canvas.setZoom(zoom);
        this.updateZoomIndicator();
    }

    resetZoom() {
        this.canvas.setZoom(1);
        this.canvas.viewportTransform = [1, 0, 0, 1, 0, 0]; // Reset pan
        this.canvas.requestRenderAll();
        this.updateZoomIndicator();
    }

    handleImageUpload(event) {
        const file = event.target.files[0];
        if (file) this.loadImage(file);
    }
    
    handleImageDrop(event) {
        const files = event.dataTransfer.files;
        if (files.length > 0) {
            this.loadImage(files[0]);
        }
    }

    loadImage(file) {
        if (!file.type.startsWith('image/')) { alert('Vui lòng chọn file ảnh hợp lệ!'); return; }
        const reader = new FileReader();
        reader.onload = (e) => {
            fabric.Image.fromURL(e.target.result, (img) => {
                this.canvas.setBackgroundImage(img, this.canvas.renderAll.bind(this.canvas), {
                    scaleX: this.canvas.width / img.width,
                    scaleY: this.canvas.height / img.height
                });
                this.updateCanvasMessage(true);
            });
        };
        reader.readAsDataURL(file);
    }

    addText() {
        const text = new fabric.IText('Sample Text', {
            left: 100,
            top: 100,
            fontFamily: 'Impact',
            fontSize: 40,
            fill: document.getElementById('textColor').value,
            stroke: document.getElementById('strokeColor').value,
            strokeWidth: 2,
        });
        this.canvas.add(text);
        this.canvas.setActiveObject(text);
    }

    addEmoji(emoji) {
        const text = new fabric.Text(emoji, {
            left: 150,
            top: 150,
            fontSize: 60,
        });
        this.canvas.add(text);
        this.canvas.setActiveObject(text);
    }
    
    handleUserStickersDrop(fileList) {
        Array.from(fileList).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                fabric.Image.fromURL(e.target.result, (img) => {
                    img.scaleToWidth(150);
                    img.set({ left: 100, top: 100 });
                    this.canvas.add(img);
                    this.canvas.setActiveObject(img);
                });
            };
            reader.readAsDataURL(file);
        });
    }

    updateActiveObject(options) {
        const activeObject = this.canvas.getActiveObject();
        if (activeObject) {
            activeObject.set(options);
            this.canvas.requestRenderAll();
        }
    }

    updateControls(target) {
        if (target && (target.type === 'i-text' || target.type === 'text')) {
            document.getElementById('textColor').value = target.fill;
            document.getElementById('strokeColor').value = target.stroke;
            document.getElementById('fontSize').value = target.fontSize;
        }
    }

    updateCanvasMessage(hasImage = false) {
        this.canvas.getObjects('text').forEach(o => {
            if (o.text === 'Tải ảnh lên để bắt đầu tạo meme') {
                this.canvas.remove(o);
            }
        });

        if (!hasImage && this.canvas.getObjects().length === 0 && !this.canvas.backgroundImage) {
            const message = new fabric.Text('Tải ảnh lên để bắt đầu tạo meme', {
                left: this.canvas.width / 2,
                top: this.canvas.height / 2,
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                fontFamily: 'Arial',
                fontSize: 20,
                fill: '#999',
                selectable: false,
                evented: false,
            });
            this.canvas.add(message);
        }
    }

    clearCanvas() {
        this.canvas.remove(...this.canvas.getObjects());
        this.canvas.setBackgroundImage(null, this.canvas.renderAll.bind(this.canvas));
        this.canvas.backgroundColor = '#f0f0f0';
        this.updateCanvasMessage();
        this.canvas.renderAll();
    }

    downloadMeme() {
        const link = document.createElement('a');
        link.download = `meme-${Date.now()}.png`;
        link.href = this.canvas.toDataURL({ format: 'png' });
        link.click();
    }

    resetEditor() {
        this.clearCanvas();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // We need to ensure Fabric.js is loaded before we initialize the editor
    // A simple check or a more robust loader can be used. For now, a timeout is a simple way.
    setTimeout(() => {
        if (typeof fabric !== 'undefined') {
            window.memeEditor = new MemeEditor();
        } else {
            console.error('Fabric.js has not loaded. Please check the library path.');
            alert('Error: Could not load the editor library. Please refresh the page.');
        }
    }, 500); // Wait 500ms for the CDN script to load.
});
</script>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
@endpush

