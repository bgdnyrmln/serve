<template>
  <div class="flex justify-center min-h-screen bg-gray-100 dark:bg-gray-900 p-6">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
      <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Edit Restaurant</h1>

      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
          />
          <span v-if="error.name" class="text-red-600 dark:text-red-400 text-sm">{{ error.name[0] }}</span>
        </div>

        <!-- Owner -->
        <div>
            <label for="owner_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Owner ID</label>
            <input
              id="owner_id"
              v-model="form.owner_id"
              type="number"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
            />
            <span v-if="error.owner_id" class="text-red-600 dark:text-red-400 text-sm">{{ error.owner_id[0] }}</span>
        </div>

        <!-- Cuisine -->
        <div>
          <label for="cuisine" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cuisine</label>
          <input
            id="cuisine"
            v-model="form.cuisine"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
          />
          <span v-if="error.cuisine" class="text-red-600 dark:text-red-400 text-sm">{{ error.cuisine[0] }}</span>
        </div>

        <!-- Location -->
        <div>
          <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
          <input
            id="location"
            v-model="form.location"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
          />
          <span v-if="error.location" class="text-red-600 dark:text-red-400 text-sm">{{ error.location[0] }}</span>
        </div>

        <div>
            <!-- Rating (read-only) -->
            <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rating</label>
            <input
              id="rating"
              v-model="form.rating"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
              readonly
              disabled
            />
        </div>
        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark
            :text-gray-300">Description</label>
            <textarea
                id="description"
                v-model="form.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
            ></textarea>
            <span v-if="error.description" class="text-red-600 dark:text-red-400 text-sm">{{ error.description[0] }}</span>
        </div>
        <!-- Open -->
        <div>
            <label for="open" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Open Hours</label>
            <input
                id="open"
                v-model="form.open"
                type="text"
                placeholder="e.g., 9:00 AM - 10:00 PM"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
            />
            <span v-if="error.open" class="text-red-600 dark:text-red-400 text-sm">{{ error.open[0] }}</span>
        </div>  

        <!-- Submit button -->
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white py-2 rounded-md hover:opacity-90"
        >
          Update Restaurant
        </button>

        <div v-if="error.general" class="text-red-600 dark:text-red-400 text-sm mt-2">
          {{ error.general }}
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
  definePageMeta({
    middleware: ["isadmin"], // Only admins can access
  })
  import { ref, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import axios from 'axios'


  const route = useRoute()
  const router = useRouter()
  const restaurantId = route.params.id

  const form = ref({
      'name': '',
      'cuisine': '',
      'location': '',
      'description': '',
      'rating': null,
      'open': '',
      'owner_id': null
  })
      
  const error = ref({})

  onMounted(async () => {
    try {
      const res = await axios.get(`http://localhost:8000/api/restaurants/${restaurantId}`, { withCredentials: true })
      form.value.name = res.data.name
      form.value.cuisine = res.data.cuisine
      form.value.location = res.data.location
      form.value.description = res.data.description
      form.value.rating = res.data.rating
      form.value.open = res.data.open
      form.value.owner_id = res.data.owner_id
    } catch (err) {
      console.error('Error fetching restaurant:', err)
    }
  })

  const submitForm = async () => {
    try {
      const token = decodeURIComponent(
        document.cookie
          .split('; ')
          .find((row) => row.startsWith("XSRF-TOKEN="))
          ?.split("=")[1] ?? ''
      )

      await axios.put(`http://localhost:8000/api/restaurants/${restaurantId}`, form.value, {
        withCredentials: true,
        headers: { 'X-XSRF-TOKEN': token }
      })

      router.push('/admin/restaurants') // Redirect to restaurants list
    } catch (err) {
      if (err.response?.data?.errors) {
        error.value = err.response.data.errors
      } else if (err.response?.data?.message) {
        error.value = { general: err.response.data.message }
      } else {
        error.value = { general: "Unexpected error occurred." }
      }
    }
  }
</script>
