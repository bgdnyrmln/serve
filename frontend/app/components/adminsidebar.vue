<template>
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-md hidden md:flex flex-col">
      <div class="p-6 text-2xl font-bold text-indigo-600 dark:text-indigo-400">
        Serve Admin
      </div>
      <nav class="flex-1 px-4 space-y-2">
        <a href="/admin/" class="block px-4 py-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-indigo-100 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400">
          Dashboard
        </a>
        <a href="/admin/users" class="block px-4 py-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-indigo-100 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400">
          Users
        </a>
        <a href="/admin/restaurants" class="block px-4 py-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-indigo-100 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400">
          Restaurants
        </a>
      </nav>
    </aside>
</template>