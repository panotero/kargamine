<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open" id="notificationIcon"
        class="relative p-2 rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-800">
        <svg class="h-6 w-6 text-zinc-600 dark:text-zinc-300" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        <span id="notifcount"
            class="absolute top-0 right-0 hidden h-5 w-5 text-white rounded-full text-sm bg-red-500"></span>
    </button>
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="
fixed lg:absolute
inset-x-4 top-20 lg:inset-auto lg:right-0 lg:top-auto
lg:mt-2
w-auto lg:w-96
max-h-[32rem]
overflow-y-auto
rounded-xl shadow-lg dark:shadow-black/40
bg-white dark:bg-zinc-900
border border-zinc-200 dark:border-zinc-800
z-50
"
        style="display: none;" id="notificationWrapper">
        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Notifications</h3>
            <button data-notification-mark-all class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Mark
                all as read</button>
        </div>
        <div id="notificationsContainer" class="divide-y divide-zinc-100 dark:divide-zinc-800">

        </div>
        <div class="text-center py-3 border-t border-zinc-100 dark:border-zinc-800">
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                See all notifications
            </a>
        </div>
    </div>
</div>
