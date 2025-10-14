<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <adminsidebar />
    <!-- Main content -->
    <div class="flex-1 flex flex-col">
      <!-- Dashboard content -->
      <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Restaurants</h2>
        <div v-if="loading" class="text-gray-500 dark:text-gray-400">Loading restaurants...</div>
        <div v-else>
          <table class="min-w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow rounded-lg">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Cuisine</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Owner</th>
                <th>
                    <div class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">
                        <!-- Placeholder for search input -->
                        <input 
                            type="text" 
                            placeholder="Search..." 
                            class="w-full bg-gray-100 dark:bg-gray-700 px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" 
                            v-model="searchQuery"
                        />
                    </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="restaurant in filteredRestaurants"
                :key="restaurant.id"
                class="border-t hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
              >
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ restaurant.id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ restaurant.name }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ restaurant.cuisine }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ restaurant.owner_name }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                  <a
                    :href="`/admin/restaurants/${restaurant.id}`"
                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                    >Edit</a
                  >
                  <button
                    @click="deleteRestaurant(restaurant.id)"
                    class="ml-4 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                  >Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

definePageMeta({
  middleware: ["isadmin"], // Only admins can access
})

const restaurants = ref([])
const loading = ref(true)
const searchQuery = ref('')

// Computed property for filtered results
const filteredRestaurants = computed(() => {
  if (!searchQuery.value) return restaurants.value
  const q = searchQuery.value.toLowerCase()
  return restaurants.value.filter(r =>
    String(r.id).includes(q) ||
    r.name.toLowerCase().includes(q) ||
    r.cuisine.toLowerCase().includes(q) ||
    (r.owner_name && r.owner_name.toLowerCase().includes(q))
  )
})

// Fetch data on mount
onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/restaurants', { withCredentials: true })
    restaurants.value = response.data.map(r => ({
      ...r,
      owner_name: r.owner ? `${r.owner.first_name} ${r.owner.last_name}` : 'N/A'
    }))
  } catch (error) {
    console.error('Error fetching restaurants:', error)
  } finally {
    loading.value = false
  }
})

// Delete function
const deleteRestaurant = async (restaurantId) => {
  const token = decodeURIComponent(
    document.cookie
      .split('; ')
      .find((row) => row.startsWith("XSRF-TOKEN="))
      ?.split("=")[1] ?? ''
  )

  if (!confirm('Are you sure you want to delete this restaurant?')) return

  try {
    await axios.delete(`http://localhost:8000/api/restaurants/${restaurantId}`, {
      withCredentials: true,
      headers: { 'X-XSRF-TOKEN': token }
    })
    restaurants.value = restaurants.value.filter(r => r.id !== restaurantId)
  } catch (error) {
    console.error('Error deleting restaurant:', error)
    alert('Failed to delete restaurant. Please try again.')
  }
}
</script>
