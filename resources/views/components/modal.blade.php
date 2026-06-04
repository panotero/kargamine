<div
    {{ $attributes->merge([
        'id' => 'defaultModal',
        'class' => 'fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-4 modal',
    ]) }}>
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full lg:max-w-[700px]">
        {{ $slot }}
    </div>
</div>
