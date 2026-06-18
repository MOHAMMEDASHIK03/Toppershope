<?php
    $initialToasts = [];

    foreach (['success', 'error', 'warning', 'info', 'status', 'message'] as $key) {
        if (session()->has($key) && session($key) !== null && session($key) !== '') {
            $initialToasts[] = [
                'type' => $key === 'status' || $key === 'message' ? 'success' : $key,
                'message' => session($key),
            ];
        }
    }

    if (isset($errors) && $errors->any()) {
        $initialToasts[] = [
            'type' => 'error',
            'message' => $errors->first(),
        ];
    }
?>


<div id="th-toast-root-placeholder" hidden aria-hidden="true"></div>

<?php if (! $__env->hasRenderedOnce('43a5af0a-6ebb-4b42-bd7b-c6e5e4b1799d')): $__env->markAsRenderedOnce('43a5af0a-6ebb-4b42-bd7b-c6e5e4b1799d'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const TYPE_META = {
        success: { 
            title: 'Success', 
            icon: `<svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0" /></svg>`, 
            bg: 'bg-green-50', 
            border: 'border-green-200', 
            titleColor: 'text-green-900', 
            textColor: 'text-green-800',
            closeHover: 'text-green-600 hover:text-green-800 hover:bg-green-100'
        },
        error: { 
            title: 'Error',   
            icon: `<svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0" /></svg>`, 
            bg: 'bg-rose-50',   
            border: 'border-rose-200',   
            titleColor: 'text-rose-900',   
            textColor: 'text-rose-800',
            closeHover: 'text-rose-600 hover:text-rose-800 hover:bg-rose-100'
        },
        warning: { 
            title: 'Warning', 
            icon: `<svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0" /></svg>`, 
            bg: 'bg-amber-50', 
            border: 'border-amber-200', 
            titleColor: 'text-amber-900', 
            textColor: 'text-amber-800',
            closeHover: 'text-amber-600 hover:text-amber-800 hover:bg-amber-100'
        },
        info: { 
            title: 'Info',    
            icon: `<svg class="w-5 h-5 text-sky-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0" /></svg>`, 
            bg: 'bg-sky-50',    
            border: 'border-sky-200',    
            titleColor: 'text-sky-900',    
            textColor: 'text-sky-800',
            closeHover: 'text-sky-600 hover:text-sky-800 hover:bg-sky-100'
        },
    };

    const HOST_CLASS = 'fixed flex flex-col gap-2.5 pointer-events-none p-0 m-0';
    const HOST_STYLE = 'position:fixed; top:24px; right:24px; width:min(calc(100vw - 48px),380px); z-index:9999999;';

    function ensureToastHost() {
        let host = document.getElementById('th-toast-root');
        if (!host) {
            host = document.createElement('div');
            host.id = 'th-toast-root';
            host.setAttribute('aria-live', 'polite');
            host.setAttribute('aria-atomic', 'true');
        }
        host.className = HOST_CLASS;
        host.setAttribute('style', HOST_STYLE);
        if (host.parentElement !== document.body) {
            document.body.appendChild(host);
        }
        return host;
    }

    function dismissToast(el) {
        if (!el || el.dataset.dismissing === '1') return;
        el.dataset.dismissing = '1';
        el.classList.add('opacity-0', 'translate-x-4');
        setTimeout(() => el.remove(), 220);
    }

    window.showToast = function (message, type = 'success', duration = 4500) {
        const host = ensureToastHost();
        if (!message) return;

        const meta = TYPE_META[type] || TYPE_META.success;
        const el = document.createElement('div');
        el.className = `pointer-events-auto relative overflow-hidden rounded-2xl border shadow-lg ${meta.bg} ${meta.border} transform transition-all duration-300 ease-out opacity-0 translate-x-4`;
        el.innerHTML = `
            <div class="flex items-center gap-3.5 py-2.5 px-4 relative">
                <span class="shrink-0 flex items-center justify-center">${meta.icon}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold ${meta.titleColor} leading-none">${meta.title}</p>
                    <p class="text-xs ${meta.textColor} mt-1 leading-snug break-words">${escapeHtml(String(message))}</p>
                </div>
                <button type="button" class="shrink-0 w-7 h-7 rounded-lg ${meta.closeHover} flex items-center justify-center transition-colors" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        `;

        el.querySelector('button').addEventListener('click', () => dismissToast(el));
        host.appendChild(el);
        requestAnimationFrame(() => el.classList.remove('opacity-0', 'translate-x-4'));

        if (duration > 0) {
            setTimeout(() => dismissToast(el), duration);
        }
    };

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    const boot = <?php echo json_encode($initialToasts, 15, 512) ?>;
    function runBoot() {
        ensureToastHost();
        if (Array.isArray(boot)) {
            boot.forEach((t, i) => {
                setTimeout(() => window.showToast(t.message, t.type || 'success'), i * 120);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runBoot);
    } else {
        runBoot();
    }
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/components/toast-stack.blade.php ENDPATH**/ ?>