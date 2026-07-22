<div class="max-w-4xl mx-auto p-5">
    <h1 class="text-2xl font-semibold mb-1 text-zinc-900 dark:text-zinc-100">App Theme</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        Pick a color for each role below. Changes preview instantly across the app - click Save to make them
        permanent for everyone.
    </p>

    <div id="themeSwatchGroups" class="space-y-6"></div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm p-5 mt-6">
        <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-200 uppercase tracking-wide mb-3">Dark Mode</h2>
        <div id="darkModeOptions" class="flex flex-wrap gap-2"></div>
    </div>

    <div class="flex justify-end mt-6">
        <button type="button" id="themeSaveBtn"
            class="inline-flex items-center px-5 py-2.5 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white text-sm font-semibold rounded-lg shadow-sm transition">
            Save Theme
        </button>
    </div>
</div>

<script>
    (function() {
        // Mirrors App\Support\TailwindPalette - keep in sync if that table changes.
        const PALETTE = {
            slate: '#64748b', gray: '#6b7280', zinc: '#71717a', neutral: '#737373', stone: '#78716c',
            red: '#ef4444', orange: '#f97316', amber: '#f59e0b', yellow: '#eab308', lime: '#84cc16',
            green: '#22c55e', emerald: '#10b981', teal: '#14b8a6', cyan: '#06b6d4', sky: '#0ea5e9',
            blue: '#3b82f6', indigo: '#6366f1', violet: '#8b5cf6', purple: '#a855f7', fuchsia: '#d946ef',
            pink: '#ec4899', rose: '#f43f5e',
        };

        const PALETTE_SHADES = {
            slate: ['#f8fafc', '#f1f5f9', '#e2e8f0', '#cbd5e1', '#94a3b8', '#64748b', '#475569', '#334155', '#1e293b', '#0f172a', '#020617'],
            gray: ['#f9fafb', '#f3f4f6', '#e5e7eb', '#d1d5db', '#9ca3af', '#6b7280', '#4b5563', '#374151', '#1f2937', '#111827', '#030712'],
            zinc: ['#fafafa', '#f4f4f5', '#e4e4e7', '#d4d4d8', '#a1a1aa', '#71717a', '#52525b', '#3f3f46', '#27272a', '#18181b', '#09090b'],
            neutral: ['#fafafa', '#f5f5f5', '#e5e5e5', '#d4d4d4', '#a3a3a3', '#737373', '#525252', '#404040', '#262626', '#171717', '#0a0a0a'],
            stone: ['#fafaf9', '#f5f5f4', '#e7e5e4', '#d6d3d1', '#a8a29e', '#78716c', '#57534e', '#44403c', '#292524', '#1c1917', '#0c0a09'],
            red: ['#fef2f2', '#fee2e2', '#fecaca', '#fca5a5', '#f87171', '#ef4444', '#dc2626', '#b91c1c', '#991b1b', '#7f1d1d', '#450a0a'],
            orange: ['#fff7ed', '#ffedd5', '#fed7aa', '#fdba74', '#fb923c', '#f97316', '#ea580c', '#c2410c', '#9a3412', '#7c2d12', '#431407'],
            amber: ['#fffbeb', '#fef3c7', '#fde68a', '#fcd34d', '#fbbf24', '#f59e0b', '#d97706', '#b45309', '#92400e', '#78350f', '#451a03'],
            yellow: ['#fefce8', '#fef9c3', '#fef08a', '#fde047', '#facc15', '#eab308', '#ca8a04', '#a16207', '#854d0e', '#713f12', '#422006'],
            lime: ['#f7fee7', '#ecfccb', '#d9f99d', '#bef264', '#a3e635', '#84cc16', '#65a30d', '#4d7c0f', '#3f6212', '#365314', '#1a2e05'],
            green: ['#f0fdf4', '#dcfce7', '#bbf7d0', '#86efac', '#4ade80', '#22c55e', '#16a34a', '#15803d', '#166534', '#14532d', '#052e16'],
            emerald: ['#ecfdf5', '#d1fae5', '#a7f3d0', '#6ee7b7', '#34d399', '#10b981', '#059669', '#047857', '#065f46', '#064e3b', '#022c22'],
            teal: ['#f0fdfa', '#ccfbf1', '#99f6e4', '#5eead4', '#2dd4bf', '#14b8a6', '#0d9488', '#0f766e', '#115e59', '#134e4a', '#042f2e'],
            cyan: ['#ecfeff', '#cffafe', '#a5f3fc', '#67e8f9', '#22d3ee', '#06b6d4', '#0891b2', '#0e7490', '#155e75', '#164e63', '#083344'],
            sky: ['#f0f9ff', '#e0f2fe', '#bae6fd', '#7dd3fc', '#38bdf8', '#0ea5e9', '#0284c7', '#0369a1', '#075985', '#0c4a6e', '#082f49'],
            blue: ['#eff6ff', '#dbeafe', '#bfdbfe', '#93c5fd', '#60a5fa', '#3b82f6', '#2563eb', '#1d4ed8', '#1e40af', '#1e3a8a', '#172554'],
            indigo: ['#eef2ff', '#e0e7ff', '#c7d2fe', '#a5b4fc', '#818cf8', '#6366f1', '#4f46e5', '#4338ca', '#3730a3', '#312e81', '#1e1b4b'],
            violet: ['#f5f3ff', '#ede9fe', '#ddd6fe', '#c4b5fd', '#a78bfa', '#8b5cf6', '#7c3aed', '#6d28d9', '#5b21b6', '#4c1d95', '#2e1065'],
            purple: ['#faf5ff', '#f3e8ff', '#e9d5ff', '#d8b4fe', '#c084fc', '#a855f7', '#9333ea', '#7e22ce', '#6b21a8', '#581c87', '#3b0764'],
            fuchsia: ['#fdf4ff', '#fae8ff', '#f5d0fe', '#f0abfc', '#e879f9', '#d946ef', '#c026d3', '#a21caf', '#86198f', '#701a75', '#4a044e'],
            pink: ['#fdf2f8', '#fce7f3', '#fbcfe8', '#f9a8d4', '#f472b6', '#ec4899', '#db2777', '#be185d', '#9d174d', '#831843', '#500724'],
            rose: ['#fff1f2', '#ffe4e6', '#fecdd3', '#fda4af', '#fb7185', '#f43f5e', '#e11d48', '#be123c', '#9f1239', '#881337', '#4c0519'],
        };
        const SHADES = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900', '950'];

        const SLOTS = [
            { key: 'main_color', varPrefix: 'main', label: 'Main', hint: 'Primary buttons, brand accents, links' },
            { key: 'accent_color', varPrefix: 'accent', label: 'Accent', hint: 'Search, tables, active tabs & pagination' },
            { key: 'button_secondary_color', varPrefix: 'secondary', label: 'Button - Secondary', hint: 'Secondary/cancel-style buttons' },
            { key: 'button_danger_color', varPrefix: 'danger', label: 'Button - Danger', hint: 'Delete/reject/danger buttons' },
        ];

        const DARK_MODE_OPTIONS = [
            { value: 'light', label: 'Light' },
            { value: 'dark', label: 'Dark' },
            { value: 'system', label: 'System' },
        ];

        function hexToRgbTriple(hex) {
            const n = parseInt(hex.slice(1), 16);
            return `${(n >> 16) & 255} ${(n >> 8) & 255} ${n & 255}`;
        }

        function previewSlot(varPrefix, hue) {
            const shades = PALETTE_SHADES[hue] || PALETTE_SHADES.gray;
            SHADES.forEach((shade, i) => {
                document.documentElement.style.setProperty(`--tw-color-${varPrefix}-${shade}`, hexToRgbTriple(shades[i]));
            });
        }

        function previewDarkMode(mode) {
            const root = document.documentElement;
            if (mode === 'dark') {
                root.classList.add('dark');
            } else if (mode === 'light') {
                root.classList.remove('dark');
            } else {
                root.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
        }

        let state = null;

        function renderSwatchGroups() {
            const container = document.getElementById('themeSwatchGroups');
            container.innerHTML = SLOTS.map(slot => `
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm p-5">
                    <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-200 uppercase tracking-wide">${slot.label}</h2>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-3">${slot.hint}</p>
                    <div class="flex flex-wrap gap-2" data-slot="${slot.key}">
                        ${Object.keys(PALETTE).map(hue => `
                            <button type="button" data-hue="${hue}" title="${hue}"
                                class="w-8 h-8 rounded-full border-2 transition ${state[slot.key] === hue ? 'border-zinc-900 dark:border-white scale-110' : 'border-transparent hover:scale-105'}"
                                style="background-color:${PALETTE[hue]}">
                            </button>
                        `).join('')}
                    </div>
                </div>
            `).join('');

            container.querySelectorAll('[data-slot]').forEach(group => {
                const slotKey = group.dataset.slot;
                const slot = SLOTS.find(s => s.key === slotKey);
                group.querySelectorAll('[data-hue]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        state[slotKey] = btn.dataset.hue;
                        previewSlot(slot.varPrefix, btn.dataset.hue);
                        renderSwatchGroups();
                    });
                });
            });
        }

        function renderDarkModeOptions() {
            const container = document.getElementById('darkModeOptions');
            container.innerHTML = DARK_MODE_OPTIONS.map(opt => `
                <button type="button" data-mode="${opt.value}"
                    class="px-4 py-2 rounded-lg text-sm font-medium border transition ${state.dark_mode === opt.value ? 'bg-orange-600 border-orange-600 text-white' : 'border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800'}">
                    ${opt.label}
                </button>
            `).join('');

            container.querySelectorAll('[data-mode]').forEach(btn => {
                btn.addEventListener('click', () => {
                    state.dark_mode = btn.dataset.mode;
                    previewDarkMode(btn.dataset.mode);
                    renderDarkModeOptions();
                });
            });
        }

        async function load() {
            const response = await apiCall({ mode: 'GET', url: '/api/app-theme' });
            if (!response.success) return;

            state = {
                main_color: response.data.main_color,
                accent_color: response.data.accent_color,
                button_secondary_color: response.data.button_secondary_color,
                button_danger_color: response.data.button_danger_color,
                dark_mode: response.data.dark_mode,
            };

            renderSwatchGroups();
            renderDarkModeOptions();
        }

        document.getElementById('themeSaveBtn').addEventListener('click', async (e) => {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: state,
                url: '/api/app-theme',
                button: e.currentTarget,
            });

            if (response.success) {
                showMessage({ status: 'success', message: 'Theme saved.' });
            }
        });

        load();
    })();
</script>
