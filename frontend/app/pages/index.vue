<template>
<p>{{ message }}</p>
<a v-if="!isLoggedIn" href="/login">Login</a>
<button v-if="isLoggedIn" @click="logout">Logout</button>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

// State
const message = ref('Loading...');

// Check if logged in on mount
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

// Computed
const isLoggedIn = computed(() => message.value !== 'You are not logged in.');

// Logout function
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
