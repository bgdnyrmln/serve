<template>
  <nav class="fixed top-0 left-0 w-full flex justify-between items-center p-6 bg-white z-50">
   <!-- Left: Logo -->
    <div>
      <a class="text-xl font-bold" href="/">serve</a>
      <p class="text-sm text-gray-500">Reservations made human.</p>
    </div>

    <!-- Mobile menu button -->
    <button @click="isOpen = !isOpen" class="md:hidden text-gray-600 focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Middle: Nav links -->
    <div class="hidden md:flex space-x-6">
        <a class="text-sm text-gray-500 hover:text-indigo-600" href="/">Home</a>
        <a class="text-sm text-gray-500 hover:text-indigo-600" href="/about">About us</a>
        <a class="text-sm text-gray-500 hover:text-indigo-600" href="/contacts">Contact</a>
    </div>

    <!-- Right: Auth links -->
    <div class="hidden md:flex space-x-4">
        <a v-if="!isLoggedIn" href="/login" class="text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95">Login</a>
        <a v-if="!isLoggedIn" href="/register" class="text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95">Register</a>
        <a v-if="isLoggedIn" @click="logout" class="text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95">Logout</a>
    </div>

    <!-- Mobile dropdown -->
    <div v-if="isOpen" class="absolute top-full left-0 w-full bg-white shadow-md md:hidden flex flex-col items-start p-6 space-y-4">
        <a class="text-sm text-gray-500 hover:text-indigo-600" href="/about">About us</a>
        <a class="text-sm text-gray-500 hover:text-indigo-600" href="/">Home</a>
        <a class="text-sm text-gray-500 hover:text-indigo-600" href="/contacts">Contact</a>
        <div class="border-t w-full pt-4">
            <a v-if="!isLoggedIn" href="/login" class="block w-full text-center text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95">Login</a>
            <a v-if="!isLoggedIn" href="/register" class="block w-full text-center text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95 mt-2">Register</a>
            <a v-if="isLoggedIn" @click="logout" class="block w-full text-center text-white bg-indigo-600 px-4 py-2 rounded-md shadow-sm hover:opacity-95">Logout</a>
        </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const message = ref('Loading...');
const isOpen = ref(false);

onMounted(async () => {
  await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
    withCredentials: true,
  });

  try {
    const user = await useSanctumFetch('/api/user');
    console.log(user);
    message.value = `User fetched: ${JSON.stringify(user)}`;
  } catch (error) {
    console.error(error);
    message.value = 'You are not logged in.';
  }
});

const isLoggedIn = computed(() => message.value !== 'You are not logged in.');

const logout = async () => {
  try {
    const token = decodeURIComponent(
      document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1] ?? ''
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
