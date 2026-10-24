@extends('layouts.admin')

@section('title', __('محول الصور إلى WebP فائقة السرعة'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span>{{ __('محول ومحسن الصور الذكي (WebP Converter)') }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold">فوري 100%</span>
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ __('حول صور PNG و JPG الضخمة إلى صيغة WebP الخفيفة بنقرة واحدة داخل متصفحك مباشرة بدون استهلاك إنترنت!') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button id="downloadAllBtn" onclick="downloadAll()" class="hidden px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-all shadow-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>{{ __('تحميل كافة الصور المحولة') }}</span>
            </button>
            <button id="clearAllBtn" onclick="clearAll()" class="hidden px-3.5 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                {{ __('مسح القائمة') }}
            </button>
        </div>
    </div>

    <!-- Controls Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
        <h2 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
            {{ __('إعدادات التحويل الموصى بها') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Sizing Preset -->
            <div>
                <label class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('الحجم / العرض الأقصى (Width)') }}</label>
                <select id="maxWidthSelect" class="w-full text-xs p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-medium focus:ring-1 focus:ring-emerald-500 cursor-pointer">
                    <option value="800" selected>⚡ {{ __('غلاف كتاب متجر سجى (عرض 800 بكسل - موصى به)') }}</option>
                    <option value="1200">🖼️ {{ __('صورة عالية الجودة (عرض 1200 بكسل)') }}</option>
                    <option value="1600">🖥️ {{ __('بانر عريض (عرض 1600 بكسل)') }}</option>
                    <option value="0">📐 {{ __('الحجم الأصلي بدون تصغير الأبعاد') }}</option>
                </select>
                <p class="text-[11px] text-slate-400 mt-1">{{ __('يتم الحفاظ على تناسق الطول مع العرض تلقائياً دون أي تشوه.') }}</p>
            </div>

            <!-- Quality Slider -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('جودة الضغط (Quality)') }}</label>
                    <span id="qualityDisplay" class="text-xs font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-md">82%</span>
                </div>
                <input type="range" id="qualityRange" min="50" max="100" value="82" 
                       class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                    <span>{{ __('حجم أصغر (50%)') }}</span>
                    <span>{{ __('متوازن ومثالي (82%)') }}</span>
                    <span>{{ __('أعلى نقاء (100%)') }}</span>
                </div>
            </div>

            <!-- Feature Highlight -->
            <div class="flex items-center p-3 rounded-xl bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 text-emerald-800 dark:text-emerald-200">
                <div class="text-xs space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <span>🛡️ تحويل محلي فوري وآمن:</span>
                    </div>
                    <p class="text-[11px] leading-relaxed opacity-90">
                        {{ __('التحويل يتم بالكامل على معالج جهازك وبسرعة فائقة. الصور لا تُرسل لأي سيرفر خارجي، مما يوفر 90% من حجم الملف قبل رفعه!') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Drag & Drop Zone -->
    <div id="dropZone" 
         class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl p-8 sm:p-12 text-center transition-all cursor-pointer group">
        <input type="file" id="fileInput" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        
        <div class="space-y-3 pointer-events-none">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center justify-center text-emerald-500 group-hover:scale-110 group-hover:bg-emerald-50 dark:group-hover:bg-emerald-950/40 transition-all">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                    {{ __('اسحب وأفلت الصور هنا، أو اضغط للاختيار من جهازك') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('يدعم كافة الصيغ: PNG, JPG, JPEG, GIF, BMP — يمكنك اختيار أكثر من صورة معاً') }}
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-100/70 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-xs font-bold">
                <span>⚡ يبدأ التحويل فور اختيار الصورة مباشرة</span>
            </div>
        </div>
    </div>

    <!-- Live Results Queue -->
    <div id="resultsContainer" class="hidden space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                <span>{{ __('الصور المحولة الجاهزة للاستخدام') }}</span>
                <span id="resultsCountBadge" class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-300 font-bold">0</span>
            </h3>
        </div>

        <div id="resultsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Dynamically populated converted cards -->
        </div>
    </div>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const maxWidthSelect = document.getElementById('maxWidthSelect');
    const qualityRange = document.getElementById('qualityRange');
    const qualityDisplay = document.getElementById('qualityDisplay');
    const resultsContainer = document.getElementById('resultsContainer');
    const resultsList = document.getElementById('resultsList');
    const resultsCountBadge = document.getElementById('resultsCountBadge');
    const downloadAllBtn = document.getElementById('downloadAllBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');

    let convertedFiles = [];

    // Quality slider display
    qualityRange.addEventListener('input', (e) => {
        qualityDisplay.textContent = e.target.value + '%';
    });

    // Drag events
    ['dragenter', 'dragover'].forEach(name => {
        dropZone.addEventListener(name, (e) => {
            e.preventDefault();
            dropZone.classList.add('border-emerald-500', 'bg-emerald-50/20');
        });
    });

    ['dragleave', 'drop'].forEach(name => {
        dropZone.addEventListener(name, (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-emerald-500', 'bg-emerald-50/20');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            handleFiles(e.dataTransfer.files);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files.length > 0) {
            handleFiles(e.target.files);
            fileInput.value = '';
        }
    });

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    async function handleFiles(files) {
        const quality = parseInt(qualityRange.value, 10) / 100;
        const maxW = parseInt(maxWidthSelect.value, 10);

        resultsContainer.classList.remove('hidden');
        downloadAllBtn.classList.remove('hidden');
        clearAllBtn.classList.remove('hidden');

        for (const file of Array.from(files)) {
            if (!file.type.startsWith('image/')) continue;
            await convertImage(file, maxW, quality);
        }
    }

    function convertImage(file, maxWidth, quality) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let targetW = img.width;
                    let targetH = img.height;

                    if (maxWidth > 0 && targetW > maxWidth) {
                        targetW = maxWidth;
                        targetH = Math.round(img.height * (targetW / img.width));
                    }

                    canvas.width = targetW;
                    canvas.height = targetH;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, targetW, targetH);

                    canvas.toBlob((blob) => {
                        const newFilename = file.name.replace(/\.[^.]+$/, '') + '.webp';
                        const url = URL.createObjectURL(blob);
                        const savedBytes = file.size - blob.size;
                        const savingsPercent = Math.max(0, Math.round((savedBytes / file.size) * 100));

                        const item = {
                            id: 'item_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9),
                            name: newFilename,
                            originalSize: file.size,
                            webpSize: blob.size,
                            savingsPercent: savingsPercent,
                            dimensions: `${targetW} × ${targetH}`,
                            blob: blob,
                            url: url
                        };

                        convertedFiles.push(item);
                        renderCard(item);
                        updateBadges();
                        resolve();
                    }, 'image/webp', quality);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    function renderCard(item) {
        const card = document.createElement('div');
        card.id = item.id;
        card.className = "p-3.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between gap-3 group hover:border-emerald-500/50 transition-all";
        
        card.innerHTML = `
            <div class="flex items-start gap-3">
                <img src="${item.url}" class="w-16 h-20 object-cover rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shrink-0">
                <div class="flex-1 min-w-0">
                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate" title="${item.name}">${item.name}</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">${item.dimensions} px</p>
                    
                    <div class="mt-2 flex flex-wrap items-center gap-1.5">
                        <span class="text-[10px] font-semibold text-slate-400 line-through">${formatBytes(item.originalSize)}</span>
                        <span class="text-[10px] font-bold text-slate-800 dark:text-slate-200">➔ ${formatBytes(item.webpSize)}</span>
                        <span class="text-[10px] font-black px-1.5 py-0.2 rounded bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">-${item.savingsPercent}%</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <a href="${item.url}" download="${item.name}" class="flex-1 py-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold text-center transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>تحميل WebP</span>
                </a>
                <button onclick="removeItem('${item.id}')" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors" title="حذف">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;
        resultsList.prepend(card);
    }

    function removeItem(id) {
        convertedFiles = convertedFiles.filter(f => f.id !== id);
        const el = document.getElementById(id);
        if (el) el.remove();
        updateBadges();
    }

    function clearAll() {
        convertedFiles.forEach(f => URL.revokeObjectURL(f.url));
        convertedFiles = [];
        resultsList.innerHTML = '';
        updateBadges();
    }

    function updateBadges() {
        resultsCountBadge.textContent = convertedFiles.length;
        if (convertedFiles.length === 0) {
            resultsContainer.classList.add('hidden');
            downloadAllBtn.classList.add('hidden');
            clearAllBtn.classList.add('hidden');
        }
    }

    function downloadAll() {
        convertedFiles.forEach((file, index) => {
            setTimeout(() => {
                const a = document.createElement('a');
                a.href = file.url;
                a.download = file.name;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }, index * 200);
        });
    }
</script>
@endsection
