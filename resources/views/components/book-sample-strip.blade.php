@props(['product'])

@if($product->sample_pdf)
@php
    $samplePdfUrl = '/' . ltrim(\Illuminate\Support\Facades\Storage::url($product->sample_pdf), '/');
    $downloadUrl = route('shop.sample', $product->slug);
@endphp

<section id="book-sample-strip" class="mt-14 pt-10 border-t border-gray-200/80 dark:border-gray-800 scroll-mt-24 transition-colors">
    <!-- Section Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-primary-50 dark:bg-primary-950/60 text-primary-700 dark:text-primary-300 border border-primary-200/60 dark:border-primary-800/60 mb-2.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>{{ __('مقتطفات حصرية') }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ __('عينة تفاعلية من صفحات الكتاب') }}
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ __('استعرض صفحات وتدريبات من الكتاب مباشرة كما تظهر في النسخة المطبوعة') }}
            </p>
        </div>

        <!-- Controls: Page Indicator, Fullscreen & Download Buttons -->
        <div class="flex items-center flex-wrap gap-2.5 shrink-0">
            <!-- Page Indicator -->
            <div class="px-3.5 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 flex items-center gap-1.5 shadow-sm">
                <span>{{ __('الصفحة') }}</span>
                <span id="sample-active-page-label" class="text-primary-600 dark:text-primary-400 font-black text-sm">1</span>
                <span class="text-gray-400">/</span>
                <span id="sample-total-pages-label" class="font-bold text-gray-600 dark:text-gray-400">...</span>
            </div>

            <!-- Fullscreen Reader Button -->
            <button type="button" 
                    id="btn-sample-open-modal"
                    class="btn btn-outline py-2 px-3.5 text-xs font-bold rounded-xl flex items-center gap-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all shadow-sm cursor-pointer">
                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                <span>{{ __('قراءة بملء الشاشة') }}</span>
            </button>

            <!-- Direct PDF Download Link -->
            <a href="{{ $downloadUrl }}"
               title="{{ __('تحميل النسخة كملف PDF') }}"
               class="btn btn-outline py-2 px-3.5 text-xs font-bold rounded-xl flex items-center gap-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all shadow-sm">
                <svg class="w-4 h-4 text-leaf-600 dark:text-leaf-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>{{ __('تحميل العينة (PDF)') }}</span>
            </a>
        </div>
    </div>

    <!-- ==================== THE HORIZONTAL SAMPLE RAIL ==================== -->
    <div dir="ltr" class="relative my-4 rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-5 sm:p-6 lg:p-7 shadow-soft transition-colors">
        
        <div class="flex flex-col md:flex-row items-center gap-6 lg:gap-8">
            
            <!-- 1. FEATURED ACTIVE PAGE CARD (Elevated Book Preview Card) -->
            <div id="sample-featured-card"
                 class="relative shrink-0 w-48 sm:w-56 md:w-64 aspect-[1/1.4] bg-gray-50 dark:bg-gray-950 rounded-2xl sm:rounded-3xl shadow-xl p-2 sm:p-2.5 border-2 border-gray-200/90 dark:border-gray-800 group cursor-pointer transition-all duration-300 hover:scale-[1.02] flex items-center justify-center overflow-hidden z-10 ring-1 ring-black/5 dark:ring-white/10"
                 title="{{ __('انقر للتكبير وقراءة ملء الشاشة') }}">
                
                <!-- Active Page Canvas -->
                <canvas id="sample-active-canvas" class="w-full h-full object-contain rounded-xl sm:rounded-2xl bg-white shadow-sm transition-opacity duration-200"></canvas>

                <!-- Loading Skeleton / Spinner -->
                <div id="sample-active-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/95 dark:bg-gray-900/95 rounded-2xl sm:rounded-3xl p-4 text-center z-20">
                    <div class="w-10 h-10 border-4 border-primary-500/30 border-t-primary-600 rounded-full animate-spin mb-3"></div>
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('جاري تحميل صفحات العينة...') }}</span>
                </div>

                <!-- Hover Overlay with Expand Action -->
                <div class="absolute inset-0 bg-gray-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-2xl sm:rounded-3xl flex items-center justify-center p-3 pointer-events-none">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 dark:bg-gray-900/95 text-gray-900 dark:text-white text-xs font-extrabold shadow-lg backdrop-blur">
                        <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                        <span>{{ __('تكبير الصفحة') }}</span>
                    </span>
                </div>

                <!-- Corner Badge with Current Page -->
                <div class="absolute top-3.5 start-3.5 bg-black/75 backdrop-blur text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full pointer-events-none shadow-sm flex items-center gap-1">
                    <span>{{ __('صفحة') }}</span>
                    <span id="sample-card-page-label">1</span>
                </div>
            </div>

            <!-- 2. HORIZONTAL THUMBNAIL TRACK (Numbered Cards in Strip) -->
            <div class="flex-1 min-w-0 w-full relative flex items-center bg-gray-50/70 dark:bg-gray-950/50 rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-gray-800/70 p-1.5 sm:p-2.5">
                
                <!-- Floating Left Arrow -->
                <button type="button" 
                        id="btn-scroll-strip-left" 
                        aria-label="{{ __('السابق') }}"
                        class="absolute left-1 sm:left-2 z-30 w-10 h-10 rounded-full bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-md border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 hover:scale-110 active:scale-95 transition-all cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <!-- Scrollable Strip Container -->
                <div id="sample-thumbnails-track" 
                     style="scrollbar-width: none; -ms-overflow-style: none;"
                     class="w-full flex items-center gap-3 sm:gap-4 overflow-x-auto py-3 px-8 sm:px-12 scroll-smooth select-none">
                    
                    <!-- Placeholder skeleton cards before PDF loads -->
                    @for($i = 1; $i <= 8; $i++)
                        <div class="sample-skeleton-card w-24 sm:w-28 md:w-32 aspect-[1/1.4] shrink-0 rounded-2xl bg-gray-200/80 dark:bg-gray-800/80 animate-pulse relative overflow-hidden flex items-center justify-center">
                            <span class="text-2xl font-black text-gray-400 dark:text-gray-500">{{ $i }}</span>
                        </div>
                    @endfor
                </div>

                <!-- Floating Right Arrow -->
                <button type="button" 
                        id="btn-scroll-strip-right" 
                        aria-label="{{ __('التالي') }}"
                        class="absolute right-1 sm:right-2 z-30 w-10 h-10 rounded-full bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-md border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 hover:scale-110 active:scale-95 transition-all cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

        </div>
    </div>
</section>

<!-- ==================== FULLSCREEN SAMPLE READER MODAL ==================== -->
<div id="sample-modal-reader" 
     class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex flex-col bg-gray-950/95 backdrop-blur-md text-white select-none"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="sample-modal-title">
    
    <!-- Modal Header -->
    <div class="h-14 sm:h-16 px-4 sm:px-6 border-b border-gray-800 flex items-center justify-between gap-4 shrink-0 bg-gray-900/90">
        <div class="flex items-center gap-3 min-w-0">
            <svg class="w-5 h-5 text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <div class="min-w-0">
                <h3 id="sample-modal-title" class="text-xs sm:text-sm font-bold text-white truncate max-w-xs sm:max-w-md">
                    {{ $product->title }} — {{ __('عينة الكتاب') }}
                </h3>
                <span class="text-[10px] text-gray-400">
                    {{ __('الصفحة') }} <span id="modal-page-num" class="text-primary-400 font-bold">1</span> {{ __('من') }} <span id="modal-total-num">--</span>
                </span>
            </div>
        </div>

        <!-- Zoom, Download & Close Controls -->
        <div class="flex items-center gap-2">
            <!-- Zoom Out -->
            <button type="button" id="btn-modal-zoom-out" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer" title="{{ __('تصغير') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
            <span id="modal-zoom-label" class="text-xs font-bold text-white/70 min-w-[40px] text-center">100%</span>
            <!-- Zoom In -->
            <button type="button" id="btn-modal-zoom-in" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer" title="{{ __('تكبير') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <!-- Reset Zoom -->
            <button type="button" id="btn-modal-zoom-reset" class="hidden sm:inline-flex px-2 py-1 text-[11px] font-bold rounded-lg bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer" title="{{ __('إعادة الضبط') }}">
                {{ __('إعادة الضبط') }}
            </button>

            <!-- Download Button -->
            <a href="{{ $downloadUrl }}" class="ms-1 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-primary-600 hover:bg-primary-500 text-white shadow transition-colors" title="{{ __('تحميل PDF') }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span class="hidden sm:inline">{{ __('تحميل PDF') }}</span>
            </a>

            <!-- Close Modal -->
            <button type="button" id="btn-sample-close-modal" class="ms-2 w-9 h-9 rounded-xl bg-white/10 hover:bg-rose-600 text-white flex items-center justify-center transition-colors cursor-pointer" title="{{ __('إغلاق') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Modal Canvas Viewer -->
    <div class="flex-1 relative overflow-auto flex items-center justify-center p-4 sm:p-6" id="modal-viewer-container">
        
        <!-- Previous Page Floating Chevron -->
        <button type="button" 
                id="btn-modal-prev-page" 
                class="fixed left-4 top-1/2 -translate-y-1/2 z-30 w-11 h-11 rounded-full bg-gray-900/80 hover:bg-primary-600 text-white shadow-xl border border-gray-700 flex items-center justify-center hover:scale-110 active:scale-95 transition-all cursor-pointer"
                title="{{ __('الصفحة السابقة') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <!-- Canvas Wrapper -->
        <div id="modal-canvas-wrapper" class="relative transition-transform duration-150 origin-center">
            <canvas id="sample-modal-canvas" class="max-h-[75vh] w-auto max-w-full rounded-2xl bg-white shadow-2xl transition-all duration-200"></canvas>
            
            <div id="modal-loading-spinner" class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 backdrop-blur-sm rounded-2xl z-20">
                <div class="w-10 h-10 border-4 border-white/20 border-t-primary-400 rounded-full animate-spin mb-2"></div>
                <span class="text-xs font-bold text-white">{{ __('جاري تحميل الصفحة...') }}</span>
            </div>
        </div>

        <!-- Next Page Floating Chevron -->
        <button type="button" 
                id="btn-modal-next-page" 
                class="fixed right-4 top-1/2 -translate-y-1/2 z-30 w-11 h-11 rounded-full bg-gray-900/80 hover:bg-primary-600 text-white shadow-xl border border-gray-700 flex items-center justify-center hover:scale-110 active:scale-95 transition-all cursor-pointer"
                title="{{ __('الصفحة التالية') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>

    <!-- Modal Bottom Thumbnails Strip -->
    <div dir="ltr" class="h-20 sm:h-24 px-4 bg-gray-900/90 border-t border-gray-800 flex items-center justify-center shrink-0">
        <div id="modal-thumbnails-strip" class="flex items-center gap-2.5 overflow-x-auto max-w-4xl py-2 px-2 no-scrollbar scroll-smooth">
            <!-- Injected thumbnail buttons for modal -->
        </div>
    </div>
</div>

<!-- ==================== PDF.JS SCRIPT & CONTROLLER ==================== -->
<script src="/vendor/pdfjs/pdf.min.js"></script>
<script>
(function() {
    const PDF_URL = @js($samplePdfUrl);
    const WORKER_URL = '/vendor/pdfjs/pdf.worker.min.js';
    let pdfDoc = null;
    let currentPage = 1;
    let totalPages = 0;
    let currentRenderTask = null;
    let modalRenderTask = null;
    let modalZoom = 1.0;

    // Configure PDF.js Worker locally
    if (window.pdfjsLib) {
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_URL;
    }

    // Elements
    const activeCanvas = document.getElementById('sample-active-canvas');
    const activeLoading = document.getElementById('sample-active-loading');
    const activePageLabel = document.getElementById('sample-active-page-label');
    const totalPagesLabel = document.getElementById('sample-total-pages-label');
    const cardPageLabel = document.getElementById('sample-card-page-label');
    const thumbnailsTrack = document.getElementById('sample-thumbnails-track');
    const featuredCard = document.getElementById('sample-featured-card');

    // Modal Elements
    const modal = document.getElementById('sample-modal-reader');
    const modalCanvas = document.getElementById('sample-modal-canvas');
    const modalLoading = document.getElementById('modal-loading-spinner');
    const modalPageNum = document.getElementById('modal-page-num');
    const modalTotalNum = document.getElementById('modal-total-num');
    const modalZoomLabel = document.getElementById('modal-zoom-label');
    const modalWrapper = document.getElementById('modal-canvas-wrapper');
    const modalThumbnailsStrip = document.getElementById('modal-thumbnails-strip');

    // Global hooks so the button under the book cover can trigger it
    window.scrollToSampleStrip = function() {
        const el = document.getElementById('book-sample-strip');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    };

    window.openSampleModal = function(pageNumber) {
        if (pageNumber) currentPage = pageNumber;
        openModal();
    };

    // 1. Dynamic PDF.js Loader with Polling
    function ensurePdfJs(callback) {
        if (window.pdfjsLib) {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_URL;
            callback();
            return;
        }

        let attempts = 0;
        const interval = setInterval(() => {
            attempts++;
            if (window.pdfjsLib) {
                clearInterval(interval);
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_URL;
                callback();
            } else if (attempts > 40) {
                clearInterval(interval);
            }
        }, 50);
    }

    // 2. Load PDF Document
    function initSampleViewer() {
        if (!PDF_URL) return;

        ensurePdfJs(() => {
            const loadingTask = window.pdfjsLib.getDocument(PDF_URL);

            loadingTask.promise.then((pdf) => {
                pdfDoc = pdf;
                totalPages = pdf.numPages;

                totalPagesLabel.textContent = totalPages;
                modalTotalNum.textContent = totalPages;

                // Build thumbnail buttons in rail
                buildThumbnails();

                // Render page 1
                renderActivePage(1);

            }).catch((err) => {
                console.error('Error loading sample PDF:', err);
                if (activeLoading) {
                    activeLoading.innerHTML = `
                        <div class="text-center p-3">
                            <p class="text-xs text-rose-500 font-bold mb-2">{{ __('تعذر عرض العينة التفاعلية مباشرة') }}</p>
                            <a href="${@js($downloadUrl)}" class="btn btn-primary text-xs py-1.5 px-3 rounded-lg">{{ __('تحميل كملف PDF') }}</a>
                        </div>
                    `;
                }
            });
        });
    }

    // 3. Render Active Page on Featured Card
    async function renderActivePage(pageNum) {
        if (!pdfDoc) return;
        currentPage = pageNum;

        // Update labels
        if (activePageLabel) activePageLabel.textContent = pageNum;
        if (cardPageLabel) cardPageLabel.textContent = pageNum;
        if (modalPageNum) modalPageNum.textContent = pageNum;

        // Highlight active thumbnail in rail
        highlightActiveThumbnail(pageNum);

        if (currentRenderTask) {
            try { currentRenderTask.cancel(); } catch (e) {}
        }

        if (activeLoading) activeLoading.style.display = 'flex';

        try {
            const page = await pdfDoc.getPage(pageNum);
            const containerWidth = featuredCard ? featuredCard.clientWidth - 20 : 260;
            const unscaledViewport = page.getViewport({ scale: 1.0 });
            const scale = (containerWidth / unscaledViewport.width) * (window.devicePixelRatio || 1.5);
            const viewport = page.getViewport({ scale: Math.max(scale, 1.2) });

            activeCanvas.width = viewport.width;
            activeCanvas.height = viewport.height;

            const ctx = activeCanvas.getContext('2d');
            currentRenderTask = page.render({ canvasContext: ctx, viewport: viewport });
            await currentRenderTask.promise;

            if (activeLoading) activeLoading.style.display = 'none';
        } catch (err) {
            if (err?.name !== 'RenderingCancelledException') {
                console.error('Render error:', err);
            }
        }
    }

    // 4. Build Thumbnails in Horizontal Rail (Matching Image 2)
    function buildThumbnails() {
        thumbnailsTrack.innerHTML = '';
        if (modalThumbnailsStrip) modalThumbnailsStrip.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            // Thumbnail in Strip Rail
            const card = document.createElement('div');
            card.className = `sample-thumb-item relative w-24 sm:w-28 md:w-32 aspect-[1/1.4] shrink-0 rounded-2xl overflow-hidden cursor-pointer border-2 transition-all duration-300 hover:scale-105 active:scale-95 select-none bg-gray-200 dark:bg-gray-800 shadow-sm ${i === 1 ? 'border-primary-500 ring-4 ring-primary-500/30 scale-105 shadow-xl' : 'border-transparent opacity-90 hover:opacity-100'}`;
            card.dataset.page = i;

            // Thumbnail Canvas
            const thumbCanvas = document.createElement('canvas');
            thumbCanvas.className = 'w-full h-full object-cover block';
            thumbCanvas.id = `thumb-canvas-${i}`;

            // Tint Overlay - Exactly like Image 2
            const overlay = document.createElement('div');
            overlay.className = 'absolute inset-0 z-10 flex items-center justify-center transition-colors pointer-events-none';
            overlay.style.backgroundColor = 'rgba(15, 23, 42, 0.42)';
            overlay.style.backdropFilter = 'blur(0.5px)';

            // Bold Number Overlay (Exact match to Image 2 typography)
            const numSpan = document.createElement('span');
            numSpan.className = 'z-20 text-3xl sm:text-4xl font-black text-white drop-shadow-[0_2px_8px_rgba(0,0,0,0.95)] tracking-tight select-none';
            numSpan.textContent = i;

            overlay.appendChild(numSpan);
            card.appendChild(thumbCanvas);
            card.appendChild(overlay);

            card.addEventListener('mouseenter', () => {
                overlay.style.backgroundColor = 'rgba(15, 23, 42, 0.18)';
            });
            card.addEventListener('mouseleave', () => {
                overlay.style.backgroundColor = 'rgba(15, 23, 42, 0.42)';
            });

            card.addEventListener('click', () => {
                renderActivePage(i);
            });

            thumbnailsTrack.appendChild(card);

            // Modal Mini Thumbnail
            if (modalThumbnailsStrip) {
                const modalThumb = document.createElement('button');
                modalThumb.type = 'button';
                modalThumb.className = `modal-thumb-btn w-12 h-16 rounded-xl border-2 shrink-0 overflow-hidden relative transition-all duration-200 cursor-pointer flex items-center justify-center font-black text-xs text-white ${i === 1 ? 'border-primary-400 bg-primary-600/40 ring-2 ring-primary-400' : 'border-white/20 bg-white/10 hover:border-white/50'}`;
                modalThumb.dataset.page = i;
                modalThumb.textContent = i;
                modalThumb.addEventListener('click', () => {
                    renderModalPage(i);
                });
                modalThumbnailsStrip.appendChild(modalThumb);
            }
        }

        // Progressively render thumbnail canvases in background
        renderThumbnailsProgressively(1);
    }

    // Render thumbnail canvases progressively
    async function renderThumbnailsProgressively(pageIndex) {
        if (!pdfDoc || pageIndex > totalPages) return;

        try {
            const page = await pdfDoc.getPage(pageIndex);
            const canvas = document.getElementById(`thumb-canvas-${pageIndex}`);
            if (canvas) {
                const viewport = page.getViewport({ scale: 0.35 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                const ctx = canvas.getContext('2d');
                await page.render({ canvasContext: ctx, viewport }).promise;
            }
        } catch (e) {
            // Ignore cancelled render
        }

        // Render next thumbnail with tiny delay to keep UI buttery smooth
        if (window.requestIdleCallback) {
            window.requestIdleCallback(() => renderThumbnailsProgressively(pageIndex + 1));
        } else {
            setTimeout(() => renderThumbnailsProgressively(pageIndex + 1), 25);
        }
    }

    // Highlight active thumbnail
    function highlightActiveThumbnail(pageNum) {
        document.querySelectorAll('.sample-thumb-item').forEach(el => {
            const p = parseInt(el.dataset.page, 10);
            if (p === pageNum) {
                el.classList.add('border-primary-500', 'ring-4', 'ring-primary-500/30', 'scale-105', 'shadow-xl', 'opacity-100');
                el.classList.remove('border-transparent', 'opacity-85');
                // Scroll into view if needed
                el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                el.classList.remove('border-primary-500', 'ring-4', 'ring-primary-500/30', 'scale-105', 'shadow-xl', 'opacity-100');
                el.classList.add('border-transparent', 'opacity-85');
            }
        });

        // Highlight in modal strip
        document.querySelectorAll('.modal-thumb-btn').forEach(btn => {
            const p = parseInt(btn.dataset.page, 10);
            if (p === pageNum) {
                btn.classList.add('border-primary-400', 'bg-primary-600/40', 'ring-2', 'ring-primary-400');
                btn.classList.remove('border-white/20', 'bg-white/10');
                btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                btn.classList.remove('border-primary-400', 'bg-primary-600/40', 'ring-2', 'ring-primary-400');
                btn.classList.add('border-white/20', 'bg-white/10');
            }
        });
    }

    // 5. Scroll Strip Controls
    const btnScrollLeft = document.getElementById('btn-scroll-strip-left');
    const btnScrollRight = document.getElementById('btn-scroll-strip-right');

    btnScrollLeft?.addEventListener('click', () => {
        thumbnailsTrack.scrollBy({ left: -260, behavior: 'smooth' });
    });

    btnScrollRight?.addEventListener('click', () => {
        thumbnailsTrack.scrollBy({ left: 260, behavior: 'smooth' });
    });

    // 6. Modal Reader Controls & Rendering
    function openModal() {
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
        });
        document.body.classList.add('overflow-hidden');
        modalZoom = 1.0;
        updateModalZoom();
        renderModalPage(currentPage);
    }

    function closeModal() {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }

    async function renderModalPage(pageNum) {
        if (!pdfDoc) return;
        currentPage = pageNum;
        if (modalPageNum) modalPageNum.textContent = pageNum;
        if (activePageLabel) activePageLabel.textContent = pageNum;
        if (cardPageLabel) cardPageLabel.textContent = pageNum;
        highlightActiveThumbnail(pageNum);

        if (modalRenderTask) {
            try { modalRenderTask.cancel(); } catch (e) {}
        }

        if (modalLoading) modalLoading.style.display = 'flex';

        try {
            const page = await pdfDoc.getPage(pageNum);
            const containerHeight = window.innerHeight * 0.72;
            const unscaledViewport = page.getViewport({ scale: 1.0 });
            const baseScale = (containerHeight / unscaledViewport.height) * (window.devicePixelRatio || 1.5);
            const viewport = page.getViewport({ scale: Math.max(baseScale, 1.4) });

            modalCanvas.width = viewport.width;
            modalCanvas.height = viewport.height;

            const ctx = modalCanvas.getContext('2d');
            modalRenderTask = page.render({ canvasContext: ctx, viewport: viewport });
            await modalRenderTask.promise;

            if (modalLoading) modalLoading.style.display = 'none';
        } catch (err) {
            if (err?.name !== 'RenderingCancelledException') {
                console.error('Modal render error:', err);
            }
        }
    }

    function updateModalZoom() {
        if (modalWrapper) {
            modalWrapper.style.transform = `scale(${modalZoom})`;
        }
        if (modalZoomLabel) {
            modalZoomLabel.textContent = `${Math.round(modalZoom * 100)}%`;
        }
    }

    // Modal Events
    document.getElementById('btn-sample-open-modal')?.addEventListener('click', openModal);
    featuredCard?.addEventListener('click', openModal);
    document.getElementById('btn-sample-close-modal')?.addEventListener('click', closeModal);

    document.getElementById('btn-modal-zoom-in')?.addEventListener('click', () => {
        if (modalZoom < 2.5) {
            modalZoom = Math.min(2.5, modalZoom + 0.25);
            updateModalZoom();
        }
    });

    document.getElementById('btn-modal-zoom-out')?.addEventListener('click', () => {
        if (modalZoom > 0.6) {
            modalZoom = Math.max(0.6, modalZoom - 0.25);
            updateModalZoom();
        }
    });

    document.getElementById('btn-modal-zoom-reset')?.addEventListener('click', () => {
        modalZoom = 1.0;
        updateModalZoom();
    });

    document.getElementById('btn-modal-prev-page')?.addEventListener('click', () => {
        if (currentPage > 1) {
            renderModalPage(currentPage - 1);
            renderActivePage(currentPage);
        }
    });

    document.getElementById('btn-modal-next-page')?.addEventListener('click', () => {
        if (currentPage < totalPages) {
            renderModalPage(currentPage + 1);
            renderActivePage(currentPage);
        }
    });

    // Keyboard Navigation
    document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeModal();
            if (e.key === 'ArrowLeft') {
                if (currentPage > 1) {
                    renderModalPage(currentPage - 1);
                    renderActivePage(currentPage);
                }
            }
            if (e.key === 'ArrowRight') {
                if (currentPage < totalPages) {
                    renderModalPage(currentPage + 1);
                    renderActivePage(currentPage);
                }
            }
        }
    });

    // Touch Swipe on Active Card
    let touchStartX = 0;
    featuredCard?.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    featuredCard?.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].screenX;
        const diff = touchEndX - touchStartX;
        if (Math.abs(diff) > 40) {
            if (diff > 0 && currentPage > 1) {
                renderActivePage(currentPage - 1);
            } else if (diff < 0 && currentPage < totalPages) {
                renderActivePage(currentPage + 1);
            }
        }
    }, { passive: true });

    // Initialize when DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSampleViewer);
    } else {
        initSampleViewer();
    }
})();
</script>

<style>
#sample-thumbnails-track::-webkit-scrollbar,
#modal-thumbnails-strip::-webkit-scrollbar {
    display: none !important;
}
</style>
@endif
