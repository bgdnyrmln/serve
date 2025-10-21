<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 pt-32 pb-16 px-6 relative overflow-hidden">
    <!-- Background decorations -->
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
            
            <!-- Create restaurant form -->
            <div class="mt-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Create a Restaurant</h3>
              <form @submit.prevent="createRestaurant" class="grid grid-cols-1 gap-3">
                <input v-model="form.name" required placeholder="Name" class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                <input v-model="form.cuisine" placeholder="Cuisine" class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                <input v-model="form.location" placeholder="Location" class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                <textarea v-model="form.description" placeholder="Short description" class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
                <div class="flex items-center space-x-3">
                  <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Create</button>
                  <span v-if="creating" class="text-sm text-gray-600 dark:text-gray-300">Creating…</span>
                </div>
                <div v-if="createError" class="text-sm text-red-600 dark:text-red-400">{{ createErrorMessage }}</div>
              </form>
            </div>

            <!-- Owned restaurants -->
            <div class="mt-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Your Restaurants</h3>
              <div v-if="ownedLoading" class="text-sm text-gray-700 dark:text-gray-300">Loading…</div>
              <div v-else>
                <div v-if="owned.length === 0" class="text-sm text-gray-600 dark:text-gray-400">You don't own any restaurants yet.</div>
                <div class="space-y-3 mt-3">
                  <NuxtLink 
                    v-for="r in owned" 
                    :key="r.id" 
                    :to="`/restaurant/${r.id}`"
                    class="group block p-3 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:shadow-md transition-all cursor-pointer"
                  >
                    <div class="flex justify-between items-center">
                      <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ r.name }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ r.cuisine }} · {{ r.location }}</div>
                        <div v-if="r.description" class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ r.description }}</div>
                      </div>
                      <div class="flex items-center space-x-3">
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ r.open ? 'Open' : 'Closed' }}</div>
                        <!-- Click indicator -->
                        <div class="flex items-center text-indigo-600 dark:text-indigo-400 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                          <span class="hidden sm:inline">View</span>
                          <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </NuxtLink>
                </div>
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
    window.location.href = '/';
  } finally {
    loading.value = false;
  }
});

// create restaurant form state
const form = ref({ name: '', cuisine: '', location: '', description: '' });
const creating = ref(false);
const createError = ref(false);
const createErrorMessage = ref('');

const owned = ref([]);
const ownedLoading = ref(true);

async function loadOwned() {
  ownedLoading.value = true;
  try {
    const data = await useSanctumFetch('/api/restaurants');
    const list = data?.data || data || [];
    owned.value = list.filter(r => r.owner_id === user.value?.id);
  } catch (err) {
    // fallback: empty
    owned.value = [];
  } finally {
    ownedLoading.value = false;
  }
}

async function createRestaurant() {
  createError.value = false;
  createErrorMessage.value = '';
  creating.value = true;
  try {
    await useSanctumFetch('/api/restaurants', {
      method: 'POST',
      body: form.value,
    });
    // reset form and reload owned
    form.value = { name: '', cuisine: '', location: '', description: '' };
    await loadOwned();
  } catch (err) {
    createError.value = true;
    // handle validation errors (Laravel 422)
    const resp = err?.response || err;
    if (resp?.status === 422 || resp?.statusCode === 422) {
      const errors = resp?.data?.errors || resp?.data || {};
      // flatten messages
      if (typeof errors === 'object') {
        const first = Object.values(errors)[0];
        createErrorMessage.value = Array.isArray(first) ? first[0] : (first || 'Validation failed');
      } else {
        createErrorMessage.value = String(errors);
      }
    } else {
      createErrorMessage.value = err?.message || 'Failed to create restaurant';
    }
  } finally {
    creating.value = false;
  }
}

// watch user and load owned when available
onMounted(() => {
  // wait for user to be loaded, then fetch owned
  const un = setInterval(() => {
    if (!loading.value) {
      clearInterval(un);
      loadOwned();
    }
  }, 150);
});

</script>
