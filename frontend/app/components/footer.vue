<template>
  <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-6 py-8">
      <!-- Main footer content -->
      <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-8">
        <!-- Company Info -->
        <div>
          <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Serve</h3>
          </div>
          <p class="text-gray-600 dark:text-gray-300 text-sm">
            Your trusted platform for seamless service management and user administration.
          </p>
        </div>

        <!-- Contact Info -->
        <div class="text-right">
          <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Contact</h4>
          <div class="space-y-2">
            <div class="flex items-center justify-end text-sm text-gray-600 dark:text-gray-300">
              <span>+371 meow</span>
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
            </div>
            <div class="flex items-center justify-end text-sm text-gray-600 dark:text-gray-300">
              <span>serveinfo@gmail.com</span>
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="flex items-center justify-end text-sm text-gray-600 dark:text-gray-300">
              <span>Riga, Latvia</span>
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom bar -->
      <div class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 md:mb-0">
          © <span id="year"></span> Serve — Made with care.
        </p>
        <div class="flex items-center space-x-6 text-sm">
          <a href="/privacy" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Privacy</a>
          <a href="/terms" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Terms</a>
          <a href="/contacts" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Contact</a>
        </div>
      </div>
    </div>
  </footer>
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

  // set footer year dynamically
  document.getElementById('year').textContent = new Date().getFullYear();
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
