<div
    {{ $attributes->merge([
        'id' => 'defaultSideModal',
        'class' => 'fixed inset-0 hidden z-50 bg-black/50 side-modal',
    ]) }}>

    <div
        class="side-modal-panel absolute top-0 right-0 h-screen w-full md:w-[500px] lg:w-[700px] bg-white dark:bg-zinc-800 shadow-2xl translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">

        {{ $slot }}

    </div>

</div>
