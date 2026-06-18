<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['panelKey' => 'panel']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['panelKey' => 'panel']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    const SIDEBAR_COLLAPSED_KEY = '<?php echo e($panelKey); ?>SidebarCollapsed';

    function isSidebarCollapsed() {
        return localStorage.getItem(SIDEBAR_COLLAPSED_KEY) === 'true';
    }

    function applySidebarCollapse(collapsed) {
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('sidebar-collapse-icon');
        const btn = document.getElementById('sidebar-collapse-btn');
        if (!sidebar) return;

        sidebar.classList.toggle('is-collapsed', collapsed);

        if (icon) {
            icon.className = collapsed ? 'ph ph-caret-right text-sm' : 'ph ph-caret-left text-sm';
        }
        if (btn) {
            btn.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
            btn.setAttribute('title', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
        }
    }

    function toggleSidebarCollapse() {
        const collapsed = !isSidebarCollapsed();
        localStorage.setItem(SIDEBAR_COLLAPSED_KEY, collapsed ? 'true' : 'false');
        applySidebarCollapse(collapsed);
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (!sidebar || !backdrop) return;
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }

    function scrollActiveNavIntoView() {
        const nav = document.getElementById('sidebar-nav');
        const active = nav?.querySelector('.sidebar-link.active');
        if (!nav || !active) return;

        const pad = 12;
        const navTop = nav.scrollTop;
        const navHeight = nav.clientHeight;
        const itemTop = active.offsetTop;
        const itemBottom = itemTop + active.offsetHeight;

        if (itemTop < navTop + pad) {
            nav.scrollTop = Math.max(0, itemTop - pad);
        } else if (itemBottom > navTop + navHeight - pad) {
            nav.scrollTop = itemBottom - navHeight + pad;
        }
    }

    /** Prevent main panel scroll jump when focusing card-style radios */
    (function () {
        let savedMainScroll = 0;
        const main = () => document.querySelector('main');

        document.addEventListener(
            'mousedown',
            (event) => {
                const input = event.target.closest?.('.panel-radio-card__input');
                if (!input) {
                    return;
                }
                const scrollEl = main();
                if (scrollEl) {
                    savedMainScroll = scrollEl.scrollTop;
                }
            },
            true,
        );

        document.addEventListener(
            'focusin',
            (event) => {
                if (!event.target.matches?.('.panel-radio-card__input')) {
                    return;
                }
                const scrollEl = main();
                if (!scrollEl) {
                    return;
                }
                requestAnimationFrame(() => {
                    scrollEl.scrollTop = savedMainScroll;
                });
            },
            true,
        );
    })();

    document.addEventListener('DOMContentLoaded', () => {
        applySidebarCollapse(isSidebarCollapsed());
        scrollActiveNavIntoView();
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('panelPerPagePicker', () => ({
            open: false,
            menuStyle: '',

            toggle() {
                this.open ? this.close() : this.openMenu();
            },

            openMenu() {
                const trigger = this.$refs.trigger;
                const menu = this.$refs.menu;
                if (!trigger || !menu) return;

                if (menu.parentElement !== document.body) {
                    document.body.appendChild(menu);
                }

                const rect = trigger.getBoundingClientRect();
                const menuWidth = 88;
                const left = Math.min(
                    Math.max(8, rect.right - menuWidth),
                    window.innerWidth - menuWidth - 8
                );

                this.menuStyle = `position:fixed;top:${rect.bottom + 6}px;left:${left}px;width:${menuWidth}px;z-index:9999;`;
                this.open = true;

                this.$nextTick(() => {
                    const menuRect = menu.getBoundingClientRect();
                    if (menuRect.bottom > window.innerHeight - 8) {
                        const top = Math.max(8, rect.top - menuRect.height - 6);
                        this.menuStyle = `position:fixed;top:${top}px;left:${left}px;width:${menuWidth}px;z-index:9999;`;
                    }
                });
            },

            close() {
                this.open = false;
                const menu = this.$refs.menu;
                const anchor = this.$el.querySelector('.relative');
                if (menu && anchor && menu.parentElement === document.body) {
                    anchor.appendChild(menu);
                    this.menuStyle = '';
                }
            },
        }));
    });
</script>
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/components/panel/scripts.blade.php ENDPATH**/ ?>