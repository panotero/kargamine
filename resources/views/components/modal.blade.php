{{--
    Panel sizing/rounding can be set two ways:
    1) legacy named props - minWidth/maxWidth/panelClass/rounded (still used
       by most existing <x-modal> call sites, e.g. maxWidth="lg:max-w-md").
    2) a plain class="..." attribute on <x-modal> itself, with real Tailwind
       utility classes (min-w-*/max-w-*/h-*/rounded-*/etc), same as styling
       any other element - it's merged straight onto the inner panel div.
    When class="..." is used, the old hardcoded lg:min-w-[40vw]/
    lg:max-w-[60vw]/rounded-2xl defaults are skipped so there's no
    conflicting duplicate utility fighting the classes you passed in.
    overlayPadding overrides the outer overlay's default px-4 (e.g.
    overlay-padding="p-0 md:p-4" for a true edge-to-edge full-screen-on-
    mobile panel).
--}}
@props(['maxWidth' => null, 'minWidth' => null, 'panelClass' => '', 'overlayPadding' => 'px-4', 'rounded' => null])
@php
    $hasCustomClass = $attributes->has('class');
    $roundedClass = $rounded ?? ($hasCustomClass ? '' : 'rounded-2xl');
    $minWidthClass = $minWidth ?? ($hasCustomClass ? '' : 'lg:min-w-[40vw]');
    $maxWidthClass = $maxWidth ?? ($hasCustomClass ? '' : 'lg:max-w-[60vw]');
@endphp
<div id="{{ $attributes->get('id', 'defaultModal') }}"
    class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 {{ $overlayPadding }} modal">
    <div {{ $attributes->except('id')->merge(['class' => "bg-white dark:bg-zinc-800 {$roundedClass} shadow-2xl w-full {$minWidthClass} {$maxWidthClass} {$panelClass}"]) }}>
        {{ $slot }}
    </div>
</div>
