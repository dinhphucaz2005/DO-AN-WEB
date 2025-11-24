@extends('layouts.app')

@section('title', 'GIF Creator - Tạo GIF Online Miễn Phí')

@section('content')
<div class="gif-creator">
    <!-- Header -->
    <div class="editor-header">
        <div>
            <h1>🎬 GIF Creator</h1>
            <p>Tạo GIF động từ nhiều ảnh một cách dễ dàng! Chỉ cần 3 bước đơn giản.</p>
        </div>
    </div>

    <!-- Steps indicator -->
    <div class="steps-container">
        <div class="step active" id="step1">
            <div class="step-number">1</div>
            <div class="step-title">Upload ảnh</div>
        </div>
        <div class="step" id="step2">
            <div class="step-number">2</div>
            <div class="step-title">Tùy chỉnh</div>
        </div>
        <div class="step" id="step3">
            <div class="step-number">3</div>
            <div class="step-title">Tạo & Tải về</div>
        </div>
    </div>

    <div class="gif-editor-container">
        <!-- Step 1: Upload Section -->
        <div class="section-card" id="uploadSection">
            <h2>📁 Bước 1: Chọn ảnh của bạn</h2>
            <div class="upload-area" id="gifUploadArea">
                <div class="upload-placeholder">
                    <i class="upload-icon">🖼️</i>
                    <h3>Kéo thả ảnh vào đây</h3>
                    <p>hoặc</p>
                    <button class="btn btn-primary">📂 Chọn ảnh từ máy</button>
                    <p style="font-size: 0.9em; color: #666; margin-top: 15px;">Cần 2-20 ảnh để tạo GIF</p>
                    <input type="file" id="gifImageInput" accept="image/*" multiple hidden>
                </div>
            </div>

            <!-- Frames Preview -->
            <div class="frames-section" id="framesSection" style="display:none;">
                <div class="frames-header">
                    <h3>🖼️ Các frame của bạn (<span id="frameCount">0</span> ảnh)</h3>
                    <button class="btn-small btn-primary" onclick="document.getElementById('gifImageInput').click()">+ Thêm ảnh</button>
                </div>
                <div class="frames-container" id="framesContainer"></div>
                <p style="color: #666; font-size: 0.9em; margin-top: 10px;">💡 Kéo thả để sắp xếp lại thứ tự</p>
            </div>
        </div>

        <!-- Step 2: Settings Section -->
        <div class="section-card" id="settingsSection" style="display:none;">
            <h2>⚙️ Bước 2: Tùy chỉnh GIF</h2>

            <div class="settings-grid">
                <div class="setting-item">
                    <label>📏 Kích thước</label>
                    <div class="size-presets">
                        <button class="preset-btn" onclick="setSize(400, 400)">400×400</button>
                        <button class="preset-btn active" onclick="setSize(500, 500)">500×500</button>
                        <button class="preset-btn" onclick="setSize(600, 600)">600×600</button>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <input type="number" id="gifWidth" value="500" min="100" max="1000" step="50" style="width: 100px;">
                        <span>×</span>
                        <input type="number" id="gifHeight" value="500" min="100" max="1000" step="50" style="width: 100px;">
                        <span>px</span>
                    </div>
                </div>

                <div class="setting-item">
                    <label>⚡ Tốc độ chuyển frame</label>
                    <input type="range" id="frameDelay" min="100" max="2000" value="500" step="100" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9em; color: #666;">
                        <span>Nhanh</span>
                        <span id="frameDelayValue" style="font-weight: bold; color: #333;">500ms</span>
                        <span>Chậm</span>
                    </div>
                </div>

                <div class="setting-item">
                    <label>🎨 Chất lượng</label>
                    <select id="gifQuality" style="width: 100%; padding: 10px;">
                        <option value="1">Tốt nhất (chậm hơn)</option>
                        <option value="10" selected>Tốt</option>
                        <option value="20">Trung bình</option>
                    </select>
                </div>

                <div class="setting-item">
                    <label>
                        <input type="checkbox" id="loopGif" checked>
                        🔄 Lặp vô hạn
                    </label>
                </div>
            </div>

            <!-- Text Overlay -->
            <div class="text-overlay-section">
                <h3>✏️ Thêm text (tùy chọn)</h3>
                <input type="text" id="gifText" placeholder="Nhập text muốn hiển thị trên GIF..." class="text-input">

                <div class="text-options" id="textOptions" style="display:none;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label>Kích thước: <span id="gifTextSizeValue">40px</span></label>
                            <input type="range" id="gifTextSize" min="20" max="100" value="40" step="5" style="width: 100%;">
                        </div>
                        <div>
                            <label>Màu text</label>
                            <input type="color" id="gifTextColor" value="#ffffff" style="width: 100%; height: 40px;">
                        </div>
                        <div>
                            <label>Vị trí</label>
                            <select id="gifTextPosition" style="width: 100%; padding: 8px;">
                                <option value="top">Trên</option>
                                <option value="middle">Giữa</option>
                                <option value="bottom" selected>Dưới</option>
                            </select>
                        </div>
                        <div>
                            <label>Màu viền</label>
                            <input type="color" id="gifTextStroke" value="#000000" style="width: 100%; height: 40px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Preview & Generate Section -->
        <div class="section-card" id="previewSection" style="display:none;">
            <h2>👁️ Bước 3: Xem trước & Tạo GIF</h2>

            <div class="preview-container">
                <div class="preview-canvas-wrapper">
                    <canvas id="gifPreviewCanvas" width="500" height="500"></canvas>
                    <div id="gifResult" style="display:none; text-align: center;">
                        <img id="gifResultImage" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);" />
                    </div>
                </div>

                <div class="preview-actions">
                    <button id="previewBtn" class="btn btn-primary btn-large">▶️ Xem trước</button>
                    <button id="createGifBtn" class="btn btn-success btn-large">✨ Tạo GIF</button>
                    <button id="downloadBtn" class="btn btn-secondary btn-large" style="display:none;">⬇️ Tải về GIF</button>
                    @auth
                    <button id="saveGifBtn" class="btn btn-success btn-large" style="display:none;">💾 Lưu GIF</button>
                    @endauth
                </div>

                <div id="gifProgress" style="display:none; margin-top: 20px;">
                    <div class="progress-bar">
                        <div class="progress-fill" id="gifProgressFill"></div>
                    </div>
                    <p id="gifProgressText" style="text-align: center; color: #666; margin-top: 10px;">Đang tạo GIF...</p>
                </div>
            </div>
        </div>

        <!-- Navigation buttons -->
        <div class="navigation-buttons">
            <button id="prevBtn" class="btn btn-secondary" style="display:none;">⬅️ Quay lại</button>
            <button id="nextBtn" class="btn btn-primary" style="display:none;">Tiếp theo ➡️</button>
            <button id="resetBtn" class="btn btn-danger">🔄 Làm lại từ đầu</button>
        </div>
    </div>
</div>

<style>
.gif-creator {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.editor-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 3px solid #e0e0e0;
}

.editor-header h1 {
    font-size: 2.5rem;
    margin: 0 0 10px 0;
    color: #333;
}

.editor-header p {
    margin: 0;
    color: #666;
    font-size: 1.1rem;
}

/* Steps indicator */
.steps-container {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-bottom: 40px;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    opacity: 0.4;
    transition: all 0.3s;
}

.step.active {
    opacity: 1;
}

.step.completed {
    opacity: 0.7;
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: #666;
    transition: all 0.3s;
}

.step.active .step-number {
    background: #007bff;
    color: white;
    transform: scale(1.1);
}

.step.completed .step-number {
    background: #28a745;
    color: white;
}

.step-title {
    font-weight: 500;
    color: #666;
}

.step.active .step-title {
    color: #007bff;
    font-weight: bold;
}

/* Section cards */
.section-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.section-card h2 {
    margin: 0 0 25px 0;
    color: #333;
    font-size: 1.5rem;
}

/* Upload area */
.upload-area {
    border: 3px dashed #ccc;
    border-radius: 12px;
    padding: 60px 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #fafafa;
}

.upload-area:hover, .upload-area.drag-over {
    border-color: #007bff;
    background-color: #f0f8ff;
    transform: scale(1.02);
}

.upload-icon {
    font-size: 5rem;
    display: block;
    margin-bottom: 20px;
}

.upload-placeholder h3 {
    margin: 10px 0;
    color: #333;
}

.upload-placeholder p {
    color: #666;
    margin: 10px 0;
}

/* Frames section */
.frames-section {
    margin-top: 30px;
}

.frames-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.frames-header h3 {
    margin: 0;
    color: #333;
}

.frames-container {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    min-height: 160px;
}

.frames-container::-webkit-scrollbar {
    height: 8px;
}

.frames-container::-webkit-scrollbar-track {
    background: #e0e0e0;
    border-radius: 4px;
}

.frames-container::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 4px;
}

.frame-item {
    position: relative;
    flex-shrink: 0;
    width: 140px;
    height: 140px;
    border: 3px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    cursor: move;
    background: white;
    transition: all 0.3s;
}

.frame-item:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

.frame-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.frame-item .frame-number {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: bold;
}

.frame-item .frame-delete {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(220,53,69,0.9);
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    transition: all 0.2s;
}

.frame-item .frame-delete:hover {
    background: rgba(220,53,69,1);
    transform: scale(1.1);
}

/* Settings */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.setting-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.setting-item label {
    display: block;
    margin-bottom: 10px;
    color: #333;
    font-weight: 600;
    font-size: 1rem;
}

.size-presets {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.preset-btn {
    flex: 1;
    padding: 10px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 500;
}

.preset-btn:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.preset-btn.active {
    border-color: #007bff;
    background: #007bff;
    color: white;
}

/* Text overlay section */
.text-overlay-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.text-overlay-section h3 {
    margin: 0 0 15px 0;
    color: #333;
}

.text-input {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    margin-bottom: 15px;
    transition: all 0.3s;
}

.text-input:focus {
    outline: none;
    border-color: #007bff;
}

.text-options {
    margin-top: 15px;
}

/* Preview section */
.preview-container {
    text-align: center;
}

.preview-canvas-wrapper {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 20px;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#gifPreviewCanvas {
    max-width: 100%;
    border: 2px solid #ddd;
    border-radius: 8px;
    background: white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.preview-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Buttons */
.btn {
    padding: 12px 28px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-large {
    padding: 16px 36px;
    font-size: 1.1rem;
}

.btn-small {
    padding: 8px 16px;
    font-size: 0.9rem;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40,167,69,0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
    transform: translateY(-2px);
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
}

/* Progress bar */
.progress-bar {
    width: 100%;
    height: 40px;
    background: #e0e0e0;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 15px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #007bff, #28a745);
    width: 0%;
    transition: width 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

/* Navigation buttons */
.navigation-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

/* Responsive */
@media (max-width: 768px) {
    .steps-container {
        gap: 20px;
    }

    .step-number {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }

    .section-card {
        padding: 20px;
    }

    .settings-grid {
        grid-template-columns: 1fr;
    }

    .frames-container {
        gap: 10px;
    }

    .frame-item {
        width: 100px;
        height: 100px;
    }

    .preview-actions {
        flex-direction: column;
    }

    .btn-large {
        width: 100%;
    }
}

input[type="number"],
input[type="text"],
select {
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: all 0.3s;
}

input[type="number"]:focus,
input[type="text"]:focus,
select:focus {
    outline: none;
    border-color: #007bff;
}

input[type="range"] {
    -webkit-appearance: none;
    appearance: none;
    background: transparent;
    cursor: pointer;
}

input[type="range"]::-webkit-slider-track {
    background: #ddd;
    height: 8px;
    border-radius: 4px;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 24px;
    height: 24px;
    background: #007bff;
    border-radius: 50%;
    margin-top: -8px;
    cursor: pointer;
    transition: all 0.3s;
}

input[type="range"]::-webkit-slider-thumb:hover {
    background: #0056b3;
    transform: scale(1.2);
}

input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    margin-right: 8px;
}
</style>

@push('scripts')
<script src="{{ asset('js/gif.js') }}"></script>
<script>
// Global variables
let frames = [];
let gifBlob = null;
let previewInterval = null;
let currentStep = 1;

// DOM elements
const uploadArea = document.getElementById('gifUploadArea');
const imageInput = document.getElementById('gifImageInput');
const framesContainer = document.getElementById('framesContainer');
const framesSection = document.getElementById('framesSection');
const frameCount = document.getElementById('frameCount');
const previewCanvas = document.getElementById('gifPreviewCanvas');
const previewCtx = previewCanvas.getContext('2d');
const gifResult = document.getElementById('gifResult');
const gifResultImage = document.getElementById('gifResultImage');

// Step navigation
function showStep(step) {
    currentStep = step;

    // Update step indicators
    document.querySelectorAll('.step').forEach((el, index) => {
        el.classList.remove('active', 'completed');
        if (index + 1 < step) el.classList.add('completed');
        if (index + 1 === step) el.classList.add('active');
    });

    // Show/hide sections
    document.getElementById('uploadSection').style.display = step === 1 ? 'block' : 'none';
    document.getElementById('settingsSection').style.display = step === 2 ? 'block' : 'none';
    document.getElementById('previewSection').style.display = step === 3 ? 'block' : 'none';

    // Update navigation buttons
    document.getElementById('prevBtn').style.display = step > 1 ? 'inline-block' : 'none';
    document.getElementById('nextBtn').style.display = step < 3 && frames.length >= 2 ? 'inline-block' : 'none';
}

// Upload functionality
uploadArea.addEventListener('click', (e) => {
    if (e.target === imageInput) return;
    imageInput.click();
});

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('drag-over');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('drag-over');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
    handleImageFiles(files);
});

imageInput.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    handleImageFiles(files);
    e.target.value = ''; // Reset input để có thể chọn lại cùng file
});

function handleImageFiles(files) {
    if (files.length === 0) return;

    if (frames.length + files.length > 20) {
        alert('⚠️ Tối đa 20 ảnh! Bạn đã có ' + frames.length + ' ảnh.');
        return;
    }

    let loaded = 0;
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                frames.push(img);
                loaded++;
                if (loaded === files.length) {
                    renderFrames();
                    updateFrameCount();
                    showFramesSection();
                    if (frames.length >= 2) {
                        document.getElementById('nextBtn').style.display = 'inline-block';
                    }
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

function showFramesSection() {
    framesSection.style.display = 'block';
}

function addFrameToUI(img, index) {
    const frameDiv = document.createElement('div');
    frameDiv.className = 'frame-item';
    frameDiv.draggable = true;
    frameDiv.dataset.index = index;

    const frameImg = document.createElement('img');
    frameImg.src = img.src;

    const frameNumber = document.createElement('div');
    frameNumber.className = 'frame-number';
    frameNumber.textContent = '#' + (index + 1);

    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'frame-delete';
    deleteBtn.textContent = '×';
    deleteBtn.onclick = (e) => {
        e.stopPropagation();
        deleteFrame(index);
    };

    frameDiv.appendChild(frameImg);
    frameDiv.appendChild(frameNumber);
    frameDiv.appendChild(deleteBtn);

    // Drag and drop for reordering
    frameDiv.addEventListener('dragstart', handleDragStart);
    frameDiv.addEventListener('dragover', handleDragOver);
    frameDiv.addEventListener('drop', handleDrop);
    frameDiv.addEventListener('dragend', handleDragEnd);

    framesContainer.appendChild(frameDiv);
}

function deleteFrame(index) {
    frames.splice(index, 1);
    renderFrames();
    updateFrameCount();
    if (frames.length < 2) {
        document.getElementById('nextBtn').style.display = 'none';
    }
    if (frames.length === 0) {
        framesSection.style.display = 'none';
    }
}

function renderFrames() {
    framesContainer.innerHTML = '';
    frames.forEach((img, index) => {
        addFrameToUI(img, index);
    });
}

function updateFrameCount() {
    frameCount.textContent = frames.length;
}

// Drag and drop for reordering
let draggedIndex = null;

function handleDragStart(e) {
    const target = e.target.closest('.frame-item');
    if (!target) return;
    
    draggedIndex = parseInt(target.dataset.index);
    target.style.opacity = '0.5';
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/plain', draggedIndex); // Required for Firefox
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    
    const target = e.target.closest('.frame-item');
    if (target) {
        target.style.transform = 'scale(1.05)';
        target.style.borderColor = '#007bff';
    }
    return false;
}

function handleDragLeave(e) {
    const target = e.target.closest('.frame-item');
    if (target) {
        target.style.transform = '';
        target.style.borderColor = '';
    }
}

function handleDrop(e) {
    e.stopPropagation();
    e.preventDefault();
    
    const dropTarget = e.target.closest('.frame-item');
    if (!dropTarget) return;

    // Reset styles
    dropTarget.style.transform = '';
    dropTarget.style.borderColor = '';

    const dropIndex = parseInt(dropTarget.dataset.index);

    if (draggedIndex !== null && draggedIndex !== dropIndex && !isNaN(draggedIndex) && !isNaN(dropIndex)) {
        // Remove from old position
        const [draggedFrame] = frames.splice(draggedIndex, 1);
        // Insert at new position
        frames.splice(dropIndex, 0, draggedFrame);
        
        // Re-render to update UI and indices
        renderFrames();
    }
    return false;
}

function handleDragEnd(e) {
    const target = e.target.closest('.frame-item');
    if (target) target.style.opacity = '1';
    draggedIndex = null;
    
    // Clean up any stuck styles
    document.querySelectorAll('.frame-item').forEach(el => {
        el.style.transform = '';
        el.style.borderColor = '';
    });
}

// Size presets
function setSize(width, height) {
    document.getElementById('gifWidth').value = width;
    document.getElementById('gifHeight').value = height;

    // Update active state
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

// Controls
const frameDelay = document.getElementById('frameDelay');
const frameDelayValue = document.getElementById('frameDelayValue');
const gifTextSize = document.getElementById('gifTextSize');
const gifTextSizeValue = document.getElementById('gifTextSizeValue');
const gifText = document.getElementById('gifText');
const textOptions = document.getElementById('textOptions');

frameDelay.addEventListener('input', () => {
    frameDelayValue.textContent = frameDelay.value + 'ms';
});

gifTextSize.addEventListener('input', () => {
    gifTextSizeValue.textContent = gifTextSize.value + 'px';
});

gifText.addEventListener('input', () => {
    if (gifText.value.trim()) {
        textOptions.style.display = 'block';
    } else {
        textOptions.style.display = 'none';
    }
});

// Preview GIF (animated on canvas)
document.getElementById('previewBtn').addEventListener('click', () => {
    if (frames.length < 2) {
        alert('⚠️ Cần ít nhất 2 ảnh để tạo GIF!');
        return;
    }

    stopPreview();
    previewGif();
});

function previewGif() {
    const width = parseInt(document.getElementById('gifWidth').value);
    const height = parseInt(document.getElementById('gifHeight').value);
    const delay = parseInt(frameDelay.value);

    previewCanvas.width = width;
    previewCanvas.height = height;
    previewCanvas.style.display = 'block';
    gifResult.style.display = 'none';

    let currentFrame = 0;

    function animate() {
        previewCtx.clearRect(0, 0, width, height);

        // Draw frame
        const img = frames[currentFrame];
        previewCtx.drawImage(img, 0, 0, width, height);

        // Add text overlay
        addTextOverlay(previewCtx, width, height);

        currentFrame = (currentFrame + 1) % frames.length;

        previewInterval = setTimeout(animate, delay);
    }

    animate();
}

function stopPreview() {
    if (previewInterval) {
        clearTimeout(previewInterval);
        previewInterval = null;
    }
}

function addTextOverlay(ctx, width, height) {
    const text = gifText.value.trim();
    if (!text) return;

    const textSize = parseInt(gifTextSize.value);
    const textColor = document.getElementById('gifTextColor').value;
    const textPosition = document.getElementById('gifTextPosition').value;
    const strokeColor = document.getElementById('gifTextStroke').value;

    ctx.font = `bold ${textSize}px Impact, Arial Black, sans-serif`;
    ctx.fillStyle = textColor;
    ctx.strokeStyle = strokeColor;
    ctx.lineWidth = Math.max(3, textSize / 15);
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    let y;
    if (textPosition === 'top') y = textSize;
    else if (textPosition === 'middle') y = height / 2;
    else y = height - textSize / 2 - 10;

    // Draw text with stroke
    ctx.strokeText(text, width / 2, y);
    ctx.fillText(text, width / 2, y);
}

// Create GIF
document.getElementById('createGifBtn').addEventListener('click', () => {
    if (frames.length < 2) {
        alert('⚠️ Cần ít nhất 2 ảnh để tạo GIF!');
        return;
    }

    stopPreview();
    createGif();
});

function createGif() {
    const width = parseInt(document.getElementById('gifWidth').value);
    const height = parseInt(document.getElementById('gifHeight').value);
    const delay = parseInt(frameDelay.value);
    const quality = parseInt(document.getElementById('gifQuality').value);
    const loop = document.getElementById('loopGif').checked;

    console.log('Creating GIF with params:', { width, height, delay, quality, loop, frameCount: frames.length });

    const progressDiv = document.getElementById('gifProgress');
    const progressFill = document.getElementById('gifProgressFill');
    const progressText = document.getElementById('gifProgressText');

    progressDiv.style.display = 'block';
    progressFill.style.width = '0%';
    document.getElementById('createGifBtn').disabled = true;
    document.getElementById('createGifBtn').textContent = '⏳ Đang tạo...';

    try {
        const gif = new GIF({
            workers: 2,
            quality: quality,
            width: width,
            height: height,
            repeat: loop ? 0 : -1,
            workerScript: '{{ asset('js/gif.worker.js') }}',
            debug: true
        });

        gif.on('start', () => {
            console.log('GIF encoding started');
        });

        gif.on('progress', (p) => {
            const percent = Math.round(p * 100);
            progressFill.style.width = percent + '%';
            progressFill.textContent = percent + '%';
            progressText.textContent = `Đang xử lý GIF... ${percent}%`;
            console.log('Progress:', percent + '%');
        });

        gif.on('finished', (blob) => {
            console.log('GIF finished!', blob);
            gifBlob = blob;
            progressDiv.style.display = 'none';
            document.getElementById('createGifBtn').disabled = false;
            document.getElementById('createGifBtn').textContent = '✨ Tạo GIF';

            // Display the GIF
            const url = URL.createObjectURL(blob);
            gifResultImage.src = url;
            gifResult.style.display = 'block';
            previewCanvas.style.display = 'none';

            document.getElementById('downloadBtn').style.display = 'inline-block';
            @auth
            const saveBtn = document.getElementById('saveGifBtn');
            if (saveBtn) saveBtn.style.display = 'inline-block';
            @endauth

            alert('✅ GIF đã được tạo thành công! Bạn có thể xem và tải về bên dưới.');
        });

        gif.on('abort', () => {
            console.error('GIF encoding aborted');
            progressDiv.style.display = 'none';
            document.getElementById('createGifBtn').disabled = false;
            document.getElementById('createGifBtn').textContent = '✨ Tạo GIF';
            alert('❌ Quá trình tạo GIF bị hủy.');
        });

        // Add frames to gif
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = width;
        tempCanvas.height = height;
        const tempCtx = tempCanvas.getContext('2d');

        console.log('Adding frames...');
        frames.forEach((img, index) => {
            tempCtx.clearRect(0, 0, width, height);
            tempCtx.drawImage(img, 0, 0, width, height);
            addTextOverlay(tempCtx, width, height);
            gif.addFrame(tempCanvas, {delay: delay, copy: true});
            console.log(`Added frame ${index + 1}/${frames.length}`);
        });

        console.log('Starting render...');
        gif.render();
    } catch (error) {
        console.error('Error creating GIF:', error);
        progressDiv.style.display = 'none';
        document.getElementById('createGifBtn').disabled = false;
        document.getElementById('createGifBtn').textContent = '✨ Tạo GIF';
        alert('❌ Lỗi khi tạo GIF: ' + error.message);
    }
}

// Download GIF
document.getElementById('downloadBtn').addEventListener('click', () => {
    if (!gifBlob) {
        alert('⚠️ Chưa có GIF để tải!');
        return;
    }

    const url = URL.createObjectURL(gifBlob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'my-awesome-gif-' + Date.now() + '.gif';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});

@auth
// Save GIF
const saveGifBtn = document.getElementById('saveGifBtn');
if (saveGifBtn) {
    saveGifBtn.addEventListener('click', async () => {
        if (!gifBlob) {
            alert('⚠️ Chưa có GIF để lưu!');
            return;
        }

        const title = prompt('📝 Nhập tiêu đề cho GIF của bạn:');
        if (!title || title.trim() === '') {
            return;
        }

        const description = prompt('📄 Mô tả (tùy chọn):');

        // Convert blob to base64
        const reader = new FileReader();
        reader.onloadend = async function() {
            const base64data = reader.result;

            try {
                saveGifBtn.disabled = true;
                saveGifBtn.textContent = '⏳ Đang lưu...';

                const response = await fetch('{{ route('memes.saveImage') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        title: title.trim(),
                        description: description?.trim() || '',
                        type: 'gif',
                        image: base64data,
                        settings: {
                            width: parseInt(document.getElementById('gifWidth').value),
                            height: parseInt(document.getElementById('gifHeight').value),
                            delay: parseInt(document.getElementById('frameDelay').value),
                            quality: parseInt(document.getElementById('gifQuality').value),
                            frameCount: frames.length
                        }
                    })
                });

                const data = await response.json();

                saveGifBtn.disabled = false;
                saveGifBtn.textContent = '💾 Lưu GIF';

                if (data.success) {
                    alert('✅ ' + data.message + '\n\nBạn có thể xem GIF đã lưu trong mục "Memes" của mình.');
                    // Optionally redirect to memes page
                    // window.location.href = '{{ route('memes.index') }}';
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (error) {
                console.error('Error saving GIF:', error);
                saveGifBtn.disabled = false;
                saveGifBtn.textContent = '💾 Lưu GIF';
                alert('❌ Lỗi khi lưu GIF: ' + error.message);
            }
        };
        reader.readAsDataURL(gifBlob);
    });
}
@endauth

// Navigation
document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentStep < 3) {
        showStep(currentStep + 1);
    }
});

document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
});

// Reset
document.getElementById('resetBtn').addEventListener('click', () => {
    if (confirm('🔄 Bạn có chắc muốn làm lại từ đầu? Mọi thay đổi sẽ bị mất.')) {
        stopPreview();
        frames = [];
        gifBlob = null;
        framesContainer.innerHTML = '';
        framesSection.style.display = 'none';
        updateFrameCount();
        previewCtx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
        gifResult.style.display = 'none';
        previewCanvas.style.display = 'block';
        document.getElementById('downloadBtn').style.display = 'none';
        @auth
        const saveBtn = document.getElementById('saveGifBtn');
        if (saveBtn) saveBtn.style.display = 'none';
        @endauth
        document.getElementById('gifProgress').style.display = 'none';
        gifText.value = '';
        textOptions.style.display = 'none';
        showStep(1);
    }
});

// Initialize
showStep(1);
</script>
@endpush
@endsection

