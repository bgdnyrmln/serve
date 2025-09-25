<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <adminsidebar />

    <!-- Main content -->
    <div class="flex-1 p-6 overflow-y-auto">
      <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

      <!-- Stats section -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500">Active Users (last 5 min)</p>
          <p class="mt-2 text-3xl font-bold text-indigo-600">{{ activeUsers }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500">Total Users</p>
          <p class="mt-2 text-3xl font-bold text-green-600">{{ totalUsers }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500">Today’s Reservations</p>
          <p class="mt-2 text-3xl font-bold text-blue-600">{{ reservationsToday }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500">Available Tables</p>
          <p class="mt-2 text-3xl font-bold text-red-600">{{ availableTables }}</p>
        </div>
      </div>

      <!-- Active sessions table -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Active Sessions</h2>

        <div v-if="loading" class="text-gray-500">Loading sessions...</div>
        <div v-else>
          <table class="min-w-full border border-gray-200 bg-white rounded-lg">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Session ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">User ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Last Activity</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="session in sessions"
                :key="session.id"
                class="border-t hover:bg-gray-50"
              >
                <td class="px-4 py-2 text-sm text-gray-700">{{ session.id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ session.user_id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ formatTimestamp(session.last_activity) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

definePageMeta({
  middleware: ["isadmin"],
})

const totalUsers = ref(0)
const activeUsers = ref(0)
const reservationsToday = ref(0)
const availableTables = ref(0)
const sessions = ref([])
const loading = ref(true)

// Convert Unix timestamp to readable time
const formatTimestamp = (ts) => {
  const date = new Date(ts * 1000)
  return date.toLocaleString()
}

onMounted(async () => {
  try {
    // Total users
    const userRes = await axios.get('http://localhost:8000/api/users', { withCredentials: true })
    totalUsers.value = userRes.data.length

    // Active sessions
    const sessionsRes = await axios.get('http://localhost:8000/api/sessions', { withCredentials: true })
    sessions.value = sessionsRes.data
    activeUsers.value = sessions.value.length

    loading.value = false

    // Placeholders for reservations/tables
    // const resRes = await axios.get('http://localhost:8000/api/reservations/today', { withCredentials: true })
    // reservationsToday.value = resRes.data.count

    // const tablesRes = await axios.get('http://localhost:8000/api/tables/available', { withCredentials: true })
    // availableTables.value = tablesRes.data.count

  } catch (error) {
    console.error('Error fetching dashboard data:', error)
    loading.value = false
  }
})
</script>
