<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 pt-32 pb-16 px-6 relative overflow-hidden">
    <!-- Background decorations copied from about.vue -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-300 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-300 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
      <div class="absolute top-40 left-1/2 w-60 h-60 bg-pink-300 dark:bg-pink-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
      <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white dark:bg-gray-900/60 dark:border dark:border-gray-800 rounded-2xl shadow-sm dark:shadow-none p-6 backdrop-blur-sm">
          <div v-if="loading" class="text-center py-8 text-gray-700 dark:text-gray-300">Loading profile…</div>
          <div v-else-if="error" class="text-center text-red-600 dark:text-red-400 py-8">{{ errorMessage }}</div>
          <div v-else>
            <div class="flex items-center space-x-6">
              <div class="w-24 h-24 rounded-full overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 flex items-center justify-center ring-2 ring-gray-100 dark:ring-gray-700">
                <img :src="staticAvatar" alt="avatar" class="w-full h-full object-cover" />
              </div>
              <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50">{{ fullName }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">Role: <span class="font-medium text-gray-800 dark:text-gray-100">{{ user.role }}</span></p>
                <p class="text-sm text-gray-600 dark:text-gray-300">Member since: <span class="text-gray-800 dark:text-gray-100">{{ joinedAt }}</span></p>
              </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm text-gray-500 dark:text-gray-300">Email</h3>
                <p class="font-medium text-gray-900 dark:text-gray-50 break-all">{{ user.email }}</p>
              </div>
              <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm text-gray-500 dark:text-gray-300">User ID</h3>
                <p class="font-medium text-gray-900 dark:text-gray-50">{{ user.id }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const user = ref(null);
const loading = ref(true);
const error = ref(false);
const errorMessage = ref('');

const staticAvatar = '/favicon.ico';

const fullName = computed(() => {
  if (!user.value) return '';
  return `${user.value.first_name || ''} ${user.value.last_name || ''}`.trim() || user.value.email;
});

const joinedAt = computed(() => {
  if (!user.value) return '';
  if (user.value.created_at) return new Date(user.value.created_at).toLocaleDateString();
  return '';
});

onMounted(async () => {
  try {
    const data = await useSanctumFetch('/api/user');
    user.value = data;
  } catch (err) {
    console.error('Failed to fetch user:', err);
    error.value = true;
    errorMessage.value = err.message || 'Unable to load profile';
  } finally {
    loading.value = false;
  }
});

</script>
