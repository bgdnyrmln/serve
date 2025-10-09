<template>
  <nav
    class="fixed top-0 left-0 w-full flex justify-between items-center p-6 bg-white dark:bg-gray-900 z-50 transition-colors"
  >
    <!-- Left: Logo -->
    <div>
      <a class="text-xl font-bold text-gray-900 dark:text-white" href="/">serve</a>
      <p class="text-sm text-gray-500 dark:text-gray-400">Reservations made human.</p>
    </div>

    <!-- Mobile menu button -->
    <button
      @click="isOpen = !isOpen"
      class="md:hidden text-gray-600 dark:text-gray-300 focus:outline-none"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16"
        />
      </svg>
    </button>

    <!-- Middle: Nav links -->
      <div class="hidden md:flex space-x-6">
      <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/">Home</a>
  <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/about">About us</a>
  <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/restaurants">Restaurants</a>
  <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/contacts">Contact</a>
      <a v-if="isLoggedIn" class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/profile">Profile</a>
      <a v-if="isAdmin" href="/admin" class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600">Admin</a>
    </div>

    <!-- Right: Auth links + Theme switch -->
    <div class="hidden md:flex items-center space-x-4">
      <a
        v-if="!isLoggedIn"
        href="/login"
        class="text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95"
        >Login</a
      >
      <a
        v-if="!isLoggedIn"
        href="/register"
        class="text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95"
        >Register</a
      >
      <a
        v-if="isLoggedIn"
        @click="logout"
        class="text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95"
      >Logout</a
      >

      <!-- Theme Toggle -->
      <button
        @click="toggleTheme"
        class="relative inline-flex items-center h-9 rounded-full w-28 bg-white/20 dark:bg-gray-700/30 border border-gray-200/50 dark:border-gray-700/60 backdrop-blur-sm shadow-sm transition-all duration-300 hover:shadow-md group overflow-hidden"
        :aria-pressed="isDark ? 'true' : 'false'"
        title="Toggle dark mode"
      >
        <span class="absolute inset-0 rounded-full bg-gradient-to-r from-indigo-500/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity"></span>

<!-- Sun (left) and label (right) when light; swap when dark -->
<span class="absolute inset-0 flex items-center justify-between px-3 text-xs font-medium select-none">
  <!-- Sun icon -->
  <span 
    class="flex items-center gap-1 text-amber-500" 
    :class="isDark ? 'order-2 opacity-60' : 'order-1'"
  >
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
      <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zM10 16a5 5 0 100-10 5 5 0 000 10zM18 9h1a1 1 0 110 2h-1zM1 9a1 1 0 100 2H0a1 1 0 100-2h1z"/>
    </svg>
  </span>

  <!-- Label changes based on isDark -->
  <span 
    class="text-gray-700 dark:text-gray-300" 
    :class="isDark ? 'order-1 opacity-60' : 'order-2'"
  >
    {{ isDark ? 'Dark' : 'Light' }}
  </span>
</span>


        <!-- Knob -->
        <span
          class="w-7 h-7 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 dark:from-indigo-500 dark:to-purple-600 flex items-center justify-center text-white shadow-md transform transition-transform duration-300"
          :class="isDark ? 'translate-x-[76px]' : 'translate-x-1'"
        >
          <svg v-if="!isDark" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1z"/></svg>
          <svg v-else class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
        </span>
      </button>
    </div>

    <!-- Mobile dropdown -->
    <div
      v-if="isOpen"
      class="absolute top-full left-0 w-full bg-white dark:bg-gray-900 shadow-md md:hidden flex flex-col items-start p-6 space-y-4 transition-colors"
    >
      <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/about"
        >About us</a
      >
      <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/restaurants"
        >Restaurants</a
      >
      <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/"
        >Home</a
      >
      <a class="text-sm text-gray-500 dark:text-gray-300 hover:text-indigo-600" href="/contacts"
        >Contact</a
      >
      <div class="border-t border-gray-200 dark:border-gray-700 w-full pt-4">
        <!-- Mobile Theme Toggle -->
        <button
          @click="toggleTheme"
          class="mb-4 w-full flex items-center justify-between px-4 py-2 rounded-xl bg-white/20 dark:bg-gray-700/30 border border-gray-200/50 dark:border-gray-700/60"
        >
          <span class="text-sm text-gray-700 dark:text-gray-300">Theme: {{ isDark ? 'Dark' : 'Light' }}</span>
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 dark:from-indigo-500 dark:to-purple-600 text-white">
            <svg v-if="!isDark" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm0 13a5 5 0 100-10 5 5 0 000 10z"/></svg>
            <svg v-else class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
          </span>
        </button>
        <a
          v-if="!isLoggedIn"
          href="/login"
          class="block w-full text-center text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95"
          >Login</a
        >
        <a
          v-if="!isLoggedIn"
          href="/register"
          class="block w-full text-center text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95 mt-2"
          >Register</a
        >
        <a
          v-if="isLoggedIn"
          @click="logout"
          class="block w-full text-center text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95"
          >Logout</a
        >
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
const message = ref('Loading...');
var isAdmin = ref(false);
const isOpen = ref(false);

// Theme handling
const isDark = ref(false);

const applyThemeClass = (dark) => {
  const root = document.documentElement;
  const body = document.body;
  if (dark) {
    root.classList.add('dark');
    body.classList.add('dark');
  } else {
    root.classList.remove('dark');
    body.classList.remove('dark');
  }
};

const setTheme = (theme) => {
  isDark.value = theme === 'dark';
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
  applyThemeClass(isDark.value);
};

const toggleTheme = () => {
  setTheme(isDark.value ? 'light' : 'dark');
};

onMounted(() => {
  axios.get('http://localhost:8000/sanctum/csrf-cookie', {
    withCredentials: true,
  });

  (async () => {
    try {
      const user = await useSanctumFetch('/api/user');
      console.log(user);
        if (user.role === 'admin') {
            isAdmin.value = true;
        }
      message.value = `User fetched: ${JSON.stringify(user)}`;
    } catch (error) {
      console.error(error);
      message.value = 'You are not logged in.';
    }
  })();
  // Initialize theme from storage or system preference
  try {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || saved === 'light') {
      setTheme(saved);
    } else {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      setTheme(prefersDark ? 'dark' : 'light');
    }
    // sync isDark with current DOM class in case of SSR hydration differences
    isDark.value = document.documentElement.classList.contains('dark');
    // React to system changes
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        const savedTheme = localStorage.getItem('theme');
        if (!savedTheme) {
          setTheme(e.matches ? 'dark' : 'light');
        }
      });
    }
  } catch (_) {
    // no-op if storage not available
  }
});

const isLoggedIn = computed(() => message.value !== 'You are not logged in.');

const logout = async () => {
  try {
    const token = decodeURIComponent(
      document.cookie.split('; ').find((row) => row.startsWith('XSRF-TOKEN='))?.split('=')[1] ?? ''
    );

    await axios.post(
      'http://localhost:8000/logout',
      {},
      {
        withCredentials: true,
        headers: {
          'X-XSRF-TOKEN': token,
        },
      }
    );

    window.location.reload();
  } catch (error) {
    console.error('Logout failed:', error);
  }
};
</script>
