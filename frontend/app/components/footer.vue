<template class="min-h-screen bg-gray-50 flex flex-col">
    <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
      <p>© <span id="year"></span> Serve — Made with care.</p>
      <div class="flex gap-4">
        <a href="#" class="hover:text-indigo-600">Privacy</a>
        <a href="#" class="hover:text-indigo-600">Terms</a>
        <a href="#" class="hover:text-indigo-600">Contact</a>
      </div>
    </div>
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
