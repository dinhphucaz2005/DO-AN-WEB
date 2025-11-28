@extends('layouts.app')

@section('title', 'Meme Creator - Tạo Meme Online Miễn Phí')

@section('content')
<div class="meme-editor">
    <!-- Header -->
    <div class="rainbow-box">
            <div class="rainbow-inner">
            <h1>✏️ Create Meme</h1>
            <p>Tạo meme hài hước từ ảnh của bạn! Upload ảnh, thêm text và emoji để tạo ra những meme viral.</p>
            @auth
            <div style="margin-top:12px;">
                <input type="text" id="memeTitle" placeholder="Tiêu đề meme..." style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;">
            </div>
            @endauth
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
                        <textarea id="memeDescription" placeholder="Mô tả (tùy chọn)..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 8px; resize: vertical; min-height: 60px;"></textarea>
                        <button id="saveMeme" class="btn btn-primary" style="width: 100%;">💾 Lưu Meme</button>
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
    display: none !important;
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

.meme-editor {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
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

/* Ensure control panels sit above canvas overlays and receive pointer events */
.controls, .control-group, .action-buttons {
    position: relative;
    z-index: 99999 !important;
    pointer-events: auto;
}

/* If there are floating UI elements, keep buttons interactive */
.action-buttons .btn {
    pointer-events: auto;
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

        // Text controls (guarded)
        const addTextBtn = document.getElementById('addText');
        const textColorEl = document.getElementById('textColor');
        const strokeColorEl = document.getElementById('strokeColor');
        const fontSizeEl = document.getElementById('fontSize');
        if (addTextBtn) addTextBtn.addEventListener('click', () => this.addText());
        if (textColorEl) textColorEl.addEventListener('input', (e) => this.updateActiveObject({ fill: e.target.value }));
        if (strokeColorEl) strokeColorEl.addEventListener('input', (e) => this.updateActiveObject({ stroke: e.target.value }));
        if (fontSizeEl) fontSizeEl.addEventListener('input', (e) => this.updateActiveObject({ fontSize: parseInt(e.target.value, 10) }));

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
        const clearBtn = document.getElementById('clearCanvas');
        const downloadBtn = document.getElementById('downloadMeme');
        const resetEditorBtn = document.getElementById('resetEditor');
        const saveBtnEl = document.getElementById('saveMeme');
        if (clearBtn) { clearBtn.style.pointerEvents = 'auto'; clearBtn.addEventListener('click', () => this.clearCanvas()); }
        if (downloadBtn) { downloadBtn.style.pointerEvents = 'auto'; downloadBtn.addEventListener('click', () => this.downloadMeme()); }
        if (resetEditorBtn) { resetEditorBtn.style.pointerEvents = 'auto'; resetEditorBtn.addEventListener('click', () => this.resetEditor()); }
        if (saveBtnEl) { saveBtnEl.style.pointerEvents = 'auto'; saveBtnEl.addEventListener('click', () => this.saveMeme()); }

        // Undo/Redo buttons (guarded)
        const undoBtnEl = document.getElementById('undoBtn');
        const redoBtnEl = document.getElementById('redoBtn');
        if (undoBtnEl) { undoBtnEl.style.pointerEvents = 'auto'; undoBtnEl.addEventListener('click', () => this.undo()); }
        if (redoBtnEl) { redoBtnEl.style.pointerEvents = 'auto'; redoBtnEl.addEventListener('click', () => this.redo()); }

        // Toggle shortcuts panel (guarded)
        const toggleShortcutsBtn = document.getElementById('toggleShortcuts');
        if (toggleShortcutsBtn) {
            toggleShortcutsBtn.addEventListener('click', () => {
                const panel = document.getElementById('shortcutsPanel');
                if (panel) panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
            });
        }

        // Zoom controls (ensure pointer events enabled)
        const zoomInEl = document.getElementById('zoomIn');
        const zoomOutEl = document.getElementById('zoomOut');
        const resetZoomEl = document.getElementById('resetZoom');
        if (zoomInEl) { zoomInEl.style.pointerEvents = 'auto'; zoomInEl.addEventListener('click', () => this.zoomIn()); }
        if (zoomOutEl) { zoomOutEl.style.pointerEvents = 'auto'; zoomOutEl.addEventListener('click', () => this.zoomOut()); }
        if (resetZoomEl) { resetZoomEl.style.pointerEvents = 'auto'; resetZoomEl.addEventListener('click', () => this.resetZoom()); }

        // Fallback delegated listener: if some overlay prevents direct clicks, capture by delegation
        document.addEventListener('click', (e) => {
            const btn = e.target.closest && e.target.closest('button');
            if (!btn) return;
            const id = btn.id;
            if (!id) return;
            try {
                if (id === 'zoomIn') { this.zoomIn(); }
                else if (id === 'zoomOut') { this.zoomOut(); }
                else if (id === 'resetZoom') { this.resetZoom(); }
                else if (id === 'undoBtn') { this.undo(); }
                else if (id === 'redoBtn') { this.redo(); }
                else if (id === 'clearCanvas') { this.clearCanvas(); }
                else if (id === 'downloadMeme') { this.downloadMeme(); }
            } catch (err) { /* ignore */ }
        }, { capture: true });

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

    async saveMeme() {
        const title = document.getElementById('memeTitle').value;
        if (!title || title.trim() === '') {
            alert('⚠️ Vui lòng nhập tiêu đề cho meme của bạn!');
            return;
        }

        const description = document.getElementById('memeDescription')?.value || '';

        // Show loading state
        const saveBtn = document.getElementById('saveMeme');
        const originalText = saveBtn.textContent;
        saveBtn.disabled = true;
        saveBtn.textContent = '⏳ Đang lưu...';

        try {
            // Ensure all image elements are loaded and canvas is fully rendered before export
            await this.ensureAllImagesLoaded();

            // Export canvas to data URL while preserving user's zoom/viewport
            const imageDataUrl = await this.exportCanvasToDataURL(2);

            // Convert dataURL to Blob
            function dataURLtoBlob(dataurl) {
                var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1], bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                while(n--){
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new Blob([u8arr], {type:mime});
            }
            const imageBlob = dataURLtoBlob(imageDataUrl);

            // Get canvas JSON for metadata
            const canvasData = this.canvas.toJSON();

            // Debug: log basic info about canvas and exported image
            try {
                console.log('Saving meme:', { title: title.trim(), canvasObjects: canvasData.objects?.length || 0 });
                console.log('Exported image data length:', imageDataUrl.length);
                console.log('Exported image prefix:', imageDataUrl.slice(0, 64));
            } catch (e) { console.warn('Debug log failed', e); }

            // CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            // Send to server using FormData for image upload
            const formData = new FormData();
            formData.append('title', title.trim());
            formData.append('type', 'meme');
            formData.append('image_file', imageBlob, 'meme.png');
            formData.append('canvas_json', JSON.stringify(canvasData));
            formData.append('description', description.trim());
            formData.append('settings', JSON.stringify({
                width: this.canvas.width,
                height: this.canvas.height,
                objects: canvasData.objects?.length || 0,
                saved_at: new Date().toISOString()
            }));

            fetch('{{ route("memes.saveImage") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Server error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message + '\n\nĐang chuyển đến gallery...');
                    window.location.href = '{{ route("memes.index") }}';
                } else {
                    throw new Error(data.message || 'Failed to save');
                }
            })
            .catch(error => {
                console.error('Error saving meme:', error);
                alert('❌ Lỗi khi lưu meme: ' + error.message);
                saveBtn.disabled = false;
                saveBtn.textContent = originalText;
            });
        } catch (error) {
            console.error('Error preparing meme:', error);
            alert('❌ Lỗi khi chuẩn bị meme: ' + error.message);
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
        }
    }

    // Wait for all image objects on the canvas to finish loading (or until timeout)
    ensureAllImagesLoaded(timeout = 3000) {
        return new Promise((resolve) => {
            const images = this.canvas.getObjects().filter(o => o.type === 'image');
            // Also include canvas.backgroundImage if present
            if (this.canvas.backgroundImage) {
                images.push(this.canvas.backgroundImage);
            }
            if (!images || images.length === 0) return resolve();

            let remaining = images.length;
            const finish = () => {
                remaining--;
                if (remaining <= 0) resolve();
            };

            images.forEach(img => {
                try {
                    const el = (img.getElement && img.getElement()) || img._element || img.element || null;
                    if (!el) return finish();
                    if (el.complete && el.naturalWidth && el.naturalWidth > 0) {
                        return finish();
                    }
                    // Add listeners to detect when image finishes loading
                    const onLoad = () => { el.removeEventListener('load', onLoad); el.removeEventListener('error', onErr); finish(); };
                    const onErr = () => { el.removeEventListener('load', onLoad); el.removeEventListener('error', onErr); finish(); };
                    el.addEventListener('load', onLoad);
                    el.addEventListener('error', onErr);
                } catch (e) {
                    // If any error, count it as finished to avoid blocking
                    finish();
                }
            });

            // Safety timeout
            setTimeout(resolve, timeout);
        });
    }

    // Export canvas to data URL safely by temporarily resetting zoom/viewport and restoring it
    exportCanvasToDataURL(multiplier = 1) {
        return new Promise((resolve) => {
            try {
                // Save current transform/zoom
                const prevZoom = this.canvas.getZoom();
                const prevViewport = this.canvas.viewportTransform ? this.canvas.viewportTransform.slice() : [1,0,0,1,0,0];

                // Reset zoom/viewport so export captures full canvas as seen
                this.canvas.setZoom(1);
                this.canvas.viewportTransform = [1,0,0,1,0,0];
                this.canvas.discardActiveObject();
                this.canvas.requestRenderAll();

                // Use toDataURL with explicit width/height to avoid viewport issues
                const options = {
                    format: 'png',
                    quality: 1,
                    multiplier: multiplier,
                    left: 0,
                    top: 0,
                    width: this.canvas.width,
                    height: this.canvas.height,
                };

                // Small timeout to ensure render completed
                setTimeout(() => {
                    try {
                        const dataUrl = this.canvas.toDataURL(options);

                        // Restore zoom/viewport
                        this.canvas.setZoom(prevZoom);
                        this.canvas.viewportTransform = prevViewport;
                        this.canvas.requestRenderAll();

                        resolve(dataUrl);
                    } catch (err) {
                        // Restore even on error
                        this.canvas.setZoom(prevZoom);
                        this.canvas.viewportTransform = prevViewport;
                        this.canvas.requestRenderAll();
                        resolve(null);
                    }
                }, 50);
            } catch (e) {
                try { this.canvas.requestRenderAll(); } catch(_){}
                resolve(null);
            }
        });
    }

    updateZoomIndicator() {
        const zoomIndicator = document.getElementById('zoomIndicator');
        if (zoomIndicator) {
            const target = this.getTargetImage ? this.getTargetImage() : null;
            if (target) {
                const scale = (target.scaleX || 1) * 100;
                zoomIndicator.textContent = `${Math.round(scale)}%`;
            } else {
                zoomIndicator.textContent = `${Math.round((this.canvas.getZoom() || 1) * 100)}%`;
            }
        }
    }

    // Choose which image object to act on: active, background, or first image
    getTargetImage() {
        const active = this.canvas.getActiveObject();
        if (active && active.type === 'image') return active;
        if (this.currentBackgroundImage) return this.currentBackgroundImage;
        return this.canvas.getObjects().find(o => o.type === 'image') || null;
    }

    zoomIn() {
        const target = this.getTargetImage();
        if (target) {
            try {
                const center = target.getCenterPoint();
                target.set({ originX: 'center', originY: 'center' });
                const prev = target.scaleX || 1;
                const next = Math.min(prev * 1.1, 10);
                target.scale(next);
                target.setPositionByOrigin(center, 'center', 'center');
                target.setCoords();
                this.canvas.requestRenderAll();
                this.saveHistory();
            } catch (e) { console.warn('zoomIn image error', e); }
        } else {
            let zoom = this.canvas.getZoom() || 1;
            zoom = zoom * 1.1; // Increase zoom by 10%
            if (zoom > 20) zoom = 20;
            this.canvas.setZoom(zoom);
            this.canvas.requestRenderAll();
        }
        this.updateZoomIndicator();
    }

    zoomOut() {
        const target = this.getTargetImage();
        if (target) {
            try {
                const center = target.getCenterPoint();
                target.set({ originX: 'center', originY: 'center' });
                const prev = target.scaleX || 1;
                const next = Math.max(prev / 1.1, 0.05);
                target.scale(next);
                target.setPositionByOrigin(center, 'center', 'center');
                target.setCoords();
                this.canvas.requestRenderAll();
                this.saveHistory();
            } catch (e) { console.warn('zoomOut image error', e); }
        } else {
            let zoom = this.canvas.getZoom() || 1;
            zoom = zoom / 1.1; // Decrease zoom by 10%
            if (zoom < 0.01) zoom = 0.01;
            this.canvas.setZoom(zoom);
            this.canvas.requestRenderAll();
        }
        this.updateZoomIndicator();
    }

    resetZoom() {
        const target = this.getTargetImage();
        if (target) {
            try {
                const center = target.getCenterPoint();
                target.set({ originX: 'center', originY: 'center' });
                if (typeof target.originalScale !== 'undefined') {
                    target.scale(target.originalScale);
                } else {
                    target.scale(1);
                }
                target.setPositionByOrigin(center, 'center', 'center');
                target.setCoords();
                this.canvas.requestRenderAll();
                this.saveHistory();
            } catch (e) { console.warn('resetZoom image error', e); }
        } else {
            this.canvas.setZoom(1);
            this.canvas.viewportTransform = [1, 0, 0, 1, 0, 0]; // Reset pan
            this.canvas.requestRenderAll();
        }
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
            // Tạo image element từ data URL
            const imgSrc = e.target.result;
            const htmlImg = new Image();
            htmlImg.onload = () => {
                // Xóa hoàn toàn canvas và tất cả objects cũ
                this.canvas.clear();
                this.canvas.backgroundColor = 'white';
                
                // Tính toán scale để ảnh vừa canvas và giữ tỷ lệ
                const maxWidth = this.canvas.width;
                const maxHeight = this.canvas.height;
                let width = htmlImg.width;
                let height = htmlImg.height;
                // Compute scale to fit the canvas while preserving aspect ratio
                const scale = Math.min(maxWidth / htmlImg.width, maxHeight / htmlImg.height, 1);

                // center coords
                const centerX = this.canvas.width / 2;
                const centerY = this.canvas.height / 2;

                // Create fabric image and scale to fit the canvas area
                fabric.Image.fromURL(imgSrc, (img) => {
                    // Set center origin so scaling keeps it centered
                    img.set({
                        left: centerX,
                        top: centerY,
                        selectable: true,
                        evented: true,
                        originX: 'center',
                        originY: 'center',
                        lockMovementX: true,
                        lockMovementY: true,
                        lockRotation: true,
                        hasControls: true
                    });

                    // Apply computed scale (use scale rather than scaleToWidth to respect both dimensions)
                    try { img.scale(scale); } catch(e) { /* fallback */ img.scaleToWidth(Math.round(htmlImg.width * scale)); }

                    // Add to canvas and ensure it's centered
                    this.canvas.add(img);
                    try { this.canvas.centerObject(img); } catch (e) { }
                    this.canvas.sendToBack(img);
                    this.canvas.renderAll();
                    this.updateCanvasMessage(true);

                    // Save reference and original scale for reset
                    this.currentBackgroundImage = img;
                    try { img.originalScale = img.scaleX || 1; } catch(e) {}

                    // Reset input value so subsequent uploads behave predictably
                    try { if (this.imageInput) this.imageInput.value = ''; } catch(e){}
                    console.log('Loaded image into canvas (fit):', { src: imgSrc, scale: scale, canvasObjects: this.canvas.getObjects().length });
                }, { crossOrigin: 'Anonymous' });
            };
            htmlImg.onerror = () => {
                alert('❌ Lỗi khi tải ảnh!');
            };
            htmlImg.src = imgSrc;
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
                }, { crossOrigin: 'Anonymous' });
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

    async downloadMeme() {
        // Ensure all images are loaded before export
        await this.ensureAllImagesLoaded();
        // Ensure canvas is fully rendered before export
        this.canvas.renderAll();

        // Export canvas as image PNG (full content including background and text)
        const imageDataUrl = this.canvas.toDataURL({
            format: 'png',
            quality: 1,
            multiplier: 2 // High quality export
        });

        const link = document.createElement('a');
        link.download = `meme-${Date.now()}.png`;
        link.href = imageDataUrl;
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


<script>
// Tự động load template nếu có from_template=1 và localStorage chứa ảnh template
document.addEventListener('DOMContentLoaded', function() {
    function getQueryParam(name) {
        const url = new URL(window.location.href);
        return url.searchParams.get(name);
    }
    if (getQueryParam('from_template') === '1') {
        var imgData = localStorage.getItem('selected_template_image');
        if (imgData) {
            var trySet = function() {
                if (window.memeEditor && window.memeEditor.canvas) {
                    window.memeEditor.clearCanvas();
                    fabric.Image.fromURL(imgData, function(img) {
                        img.set({
                            left: window.memeEditor.canvas.width/2,
                            top: window.memeEditor.canvas.height/2,
                            originX: 'center',
                            originY: 'center',
                            selectable: true
                        });
                        window.memeEditor.canvas.add(img);
                        window.memeEditor.canvas.renderAll();
                    }, { crossOrigin: 'Anonymous' });
                } else {
                    setTimeout(trySet, 200);
                }
            };
            trySet();
            // Xóa sau khi dùng để tránh lặp lại
            setTimeout(function(){
                localStorage.removeItem('selected_template_image');
                localStorage.removeItem('selected_template_title');
            }, 2000);
        }
    }
});
</script>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
@endpush

