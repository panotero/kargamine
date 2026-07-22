<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-theme-secondary-300 dark:border-theme-secondary-600 rounded-md font-semibold text-xs text-theme-secondary-700 dark:text-theme-secondary-300 uppercase tracking-widest shadow-sm hover:bg-theme-secondary-50 dark:hover:bg-theme-secondary-700 focus:outline-none focus:ring-2 focus:ring-theme-secondary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
