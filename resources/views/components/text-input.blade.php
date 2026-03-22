@props(['disabled' => false])

<input @disabled($disabled)
       {{ $attributes->merge(['class' => 'w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:focus:border-violet-400 dark:focus:ring-violet-400 transition disabled:opacity-50 disabled:cursor-not-allowed']) }}>
