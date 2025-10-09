<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 pt-32 pb-16 px-6 relative overflow-hidden">
    <!-- Background decorations  -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-300 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-300 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
      <div class="absolute top-40 left-1/2 w-60 h-60 bg-pink-300 dark:bg-pink-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-indigo-900 to-purple-900 dark:from-white dark:via-indigo-200 dark:to-purple-200 bg-clip-text text-transparent mb-4">Restaurants</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300">Browse restaurants in the catalogue. Click any card to view detailed information about the restaurant.</p>
      </div>

      <div class="mb-8">
        <div class="mb-6 max-w-xl mx-auto">
          <label for="search" class="sr-only">Search restaurants</label>
          <div class="relative">
            <input
              id="search"
              v-model="searchQuery"
              type="text"
              placeholder="Search restaurants by name..."
              class="w-full rounded-2xl border border-gray-300 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm px-5 py-3 pr-12 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
          </div>
        </div>
        <div v-if="loading" class="text-center text-gray-700 dark:text-gray-300">Loading restaurants…</div>
        <div v-else-if="error" class="text-center text-red-600 dark:text-red-400">{{ errorMessage }}</div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <NuxtLink 
            v-for="r in restaurants" 
            :key="r.id" 
            :to="`/restaurant/${r.id}`"
            class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-6 rounded-3xl shadow hover:shadow-lg transition-all border border-white/20 dark:border-gray-700/20 cursor-pointer block"
          >
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-gray-200">{{ r.name.charAt(0) }}</div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ r.name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ r.cuisine }} · {{ r.location }}</p>
              </div>
            </div>
            <p class="mt-4 text-sm text-gray-700 dark:text-gray-300">{{ r.description }}</p>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
              <span>Rating: <strong class="text-gray-900 dark:text-white">{{ r.rating ?? '—' }}</strong></span>
              <span>{{ r.open ? 'Open' : 'Closed' }}</span>
            </div>
            
            <!-- Click indicator -->
            <div class="mt-4 flex items-center text-indigo-600 dark:text-indigo-400 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
              <span>View details</span>
              <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const restaurants = ref([]);
const loading = ref(true);
const error = ref(false);
const errorMessage = ref('');
const searchQuery = ref('');

const sample = [
  { 
    id: 1, 
    name: 'La Piazza', 
    cuisine: 'Italian', 
    location: 'Center', 
    description: 'Cozy Italian with wood-fired pizzas.', 
    rating: 4.6, 
    open: true,
    owner: { id: 1, first_name: 'Mario', last_name: 'Rossi', email: 'mario.rossi@lapiazza.com' }
  },
  { 
    id: 2, 
    name: 'Sushi Hana', 
    cuisine: 'Japanese', 
    location: 'Riverside', 
    description: 'Fresh nigiri and creative rolls.', 
    rating: 4.8, 
    open: false,
    owner: { id: 2, first_name: 'Hiroshi', last_name: 'Tanaka', email: 'hiroshi.tanaka@sushihana.com' }
  },
  { 
    id: 3, 
    name: 'Spice Route', 
    cuisine: 'Indian', 
    location: 'Old Town', 
    description: 'Bold flavors and aromatic curries.', 
    rating: 4.5, 
    open: true,
    owner: { id: 3, first_name: 'Raj', last_name: 'Patel', email: 'raj.patel@spiceroute.com' }
  },
];

async function fetchRestaurants(query = '') {
  loading.value = true;
  try {
    const endpoint = query ? `/api/restaurants?q=${encodeURIComponent(query)}` : '/api/restaurants';
    const data = await useSanctumFetch(endpoint);
    restaurants.value = data?.data || data || sample;
    error.value = false;
    errorMessage.value = '';
  } catch (err) {
    error.value = true;
    errorMessage.value = 'Failed to load from API — showing sample data.';
    restaurants.value = sample;
  } finally {
    loading.value = false;
  }
}

// initial load
onMounted(() => {
  fetchRestaurants('');
});

// debounce search
let debounceTimer;
watch(searchQuery, (q) => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchRestaurants(q.trim());
  }, 350);
});
</script>

<style scoped>
/* small visual tweak for group hover */
.group:hover { transform: translateY(-2px); }
</style>
