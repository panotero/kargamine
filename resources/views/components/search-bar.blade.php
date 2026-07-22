{{--
    Reusable search bar. Triggers on button click or Enter key only -
    no oninput/onchange live search.

    Usage: <x-search-bar id="ports" placeholder="Search ports..." />

    Produces:
        #{id}SearchInput
        #{id}SearchBtn

    Pair with window.createRemoteTable({ searchInputSelector: '#{id}SearchInput', searchButtonSelector: '#{id}SearchBtn', ... })
    from public/js/remote-table.js
--}}
@props(['id', 'placeholder' => 'Search...'])

<div class="flex gap-2 mb-4">
    <input type="text" id="{{ $id }}SearchInput" placeholder="{{ $placeholder }}"
        class="w-full max-w-xs rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
    <button type="button" id="{{ $id }}SearchBtn"
        class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 px-3.5 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.3 5.3a7.5 7.5 0 0 0 10.5 10.5Z" />
        </svg>
        Search
    </button>
</div>
