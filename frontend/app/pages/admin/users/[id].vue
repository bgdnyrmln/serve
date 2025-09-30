<template>
  <div class="flex justify-center min-h-screen bg-gray-100 p-6">
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6">
      <h1 class="text-2xl font-bold mb-4">Edit User</h1>

      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- First Name -->
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
          <input
            id="first_name"
            v-model="form.first_name"
            type="text"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
          />
          <span v-if="error.first_name" class="text-red-600 text-sm">{{ error.first_name[0] }}</span>
        </div>

        <!-- Last Name -->
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
          <input
            id="last_name"
            v-model="form.last_name"
            type="text"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
          />
          <span v-if="error.last_name" class="text-red-600 text-sm">{{ error.last_name[0] }}</span>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
          />
          <span v-if="error.email" class="text-red-600 text-sm">{{ error.email[0] }}</span>
        </div>

        <!-- Submit button -->
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white py-2 rounded-md hover:opacity-90"
        >
          Update User
        </button>

        <div v-if="error.general" class="text-red-600 text-sm mt-2">
          {{ error.general }}
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const userId = route.params.id

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
})

const error = ref({})

onMounted(async () => {
  try {
    const res = await axios.get(`http://localhost:8000/api/users/${userId}`, { withCredentials: true })
    form.value.first_name = res.data.first_name
    form.value.last_name = res.data.last_name
    form.value.email = res.data.email
  } catch (err) {
    console.error('Error fetching user:', err)
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

    await axios.put(`http://localhost:8000/api/users/${userId}`, form.value, {
      withCredentials: true,
      headers: { 'X-XSRF-TOKEN': token }
    })

    router.push('/admin/users') // Redirect to users list
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
