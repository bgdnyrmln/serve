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

        <!-- Filter Tabs -->
        <div class="flex justify-center mb-8">
          <div class="inline-flex p-1 space-x-1 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl border border-gray-200/50 dark:border-gray-700/50">
            <button
              v-for="option in filterOptions"
              :key="option.id"
              @click="activeFilter = option.id"
              class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="activeFilter === option.id 
                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' 
                : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800'"
            >
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="{{ option.icon }}" />
              </svg>
              {{ option.label }}
            </button>
          </div>
        </div>

        <div v-if="loading" class="text-center text-gray-700 dark:text-gray-300">Loading restaurants…</div>
        <div v-else-if="error" class="text-center text-red-600 dark:text-red-400">{{ errorMessage }}</div>

        <div v-else>
          <!-- Results count -->
          <p class="text-center mb-6 text-sm text-gray-600 dark:text-gray-400">
            {{ filteredRestaurants.length }} restaurant{{ filteredRestaurants.length !== 1 ? 's' : '' }} found
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="r in filteredRestaurants" 
              :key="r.id" 
              class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-6 rounded-3xl shadow hover:shadow-lg transition-all border border-white/20 dark:border-gray-700/20"
            >
              <NuxtLink :to="`/restaurant/${r.id}`" class="block">
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
                  <span :class="r.open ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">{{ r.open ? 'Open' : 'Closed' }}</span>
                </div>
              </NuxtLink>
              
              <!-- Action buttons -->
              <div class="mt-4 flex items-center justify-between">
                <NuxtLink 
                  :to="`/restaurant/${r.id}`"
                  class="flex items-center text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors"
                >
                  <span>View details</span>
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </NuxtLink>
                
                <NuxtLink 
                  v-if="r.open"
                  :to="`/reserve-${r.id}`"
                  class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"
                >
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Book Table
                </NuxtLink>
                
                <span 
                  v-else
                  class="inline-flex items-center px-3 py-1.5 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed"
                >
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Closed
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';

const restaurants = ref([]);
const loading = ref(true);
const error = ref(false);
const errorMessage = ref('');
const searchQuery = ref('');
const activeFilter = ref('all');

// Sort options
const filterOptions = [
  { id: 'all', label: 'All', icon: 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414v6.586a1 1 0 01-1.414.914l-3-1.5a1 1 0 01-.586-.914v-5.086l-6.414-6.414a1 1 0 01-.293-.707v-2.586z' },
  { id: 'newest', label: 'New Added', icon: 'M9.752 6.193c.599.6 1.73.437 2.528-.362.798-.799.96-1.932.362-2.531-.599-.6-1.73-.438-2.528.361-.798.8-.96 1.933-.362 2.532z' },
  { id: 'highest', label: 'Highest Rating', icon: 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z' },
  { id: 'lowest', label: 'Lowest Rating', icon: 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z' }
];

// Filtered and sorted restaurants
const filteredRestaurants = computed(() => {
  let result = [...restaurants.value];
  
  switch (activeFilter.value) {
    case 'newest':
      result.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
      break;
    case 'highest':
      result.sort((a, b) => (b.rating || 0) - (a.rating || 0));
      break;
    case 'lowest':
      result.sort((a, b) => (a.rating || 0) - (b.rating || 0));
      break;
  }
  
  return result;
});

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
