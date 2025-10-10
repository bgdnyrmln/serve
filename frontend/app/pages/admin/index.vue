<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar -->
    <adminsidebar />

    <!-- Main content -->
  <div class="flex-1 p-6 overflow-y-auto">
  <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Admin Dashboard</h1>

      <!-- Stats section -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Users (last 5 min)</p>
          <p class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ activeUsers }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
          <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ totalUsers }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Today’s Reservations</p>
          <p class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ reservationsToday }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 flex flex-col items-start">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Available Tables</p>
          <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ availableTables }}</p>
        </div>
      </div>

      <!-- Active sessions table -->
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Active Sessions</h2>

        <div v-if="loading" class="text-gray-500 dark:text-gray-400">Loading sessions...</div>
        <div v-else>
          <table class="min-w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-lg">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Session ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">User ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Last Activity</th>                
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
              <tr v-for="session in filteredSessions" :key="session.id" class="border-t hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ session.id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ session.user_id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ formatTimestamp(session.last_activity) }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                  <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                  @click="terminateSession(session.user_id)">terminate session</button>
                </td>
            </tr>
              <tr v-if="sessions.length === 0">
                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No active sessions</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'


const searchQuery = ref('')

// Filter sessions based on search input
const filteredSessions = computed(() => {
  if (!searchQuery.value) return sessions.value
  const q = searchQuery.value.toLowerCase()
  return sessions.value.filter(session =>
    String(session.id).includes(q) ||
    String(session.user_id).includes(q) ||
    formatTimestamp(session.last_activity).toLowerCase().includes(q)
  )
})


definePageMeta({
  middleware: ["isadmin"],
})

const totalUsers = ref(0)
const activeUsers = ref(0)
const reservationsToday = ref(0)
const availableTables = ref(0)
const sessions = ref([])
const loading = ref(true)
const token = ref('')




// Convert Unix timestamp to readable time
const formatTimestamp = (ts) => {
  const date = new Date(ts * 1000)
  return date.toLocaleString()
}

const terminateSession =async (userId) => {
      try {
        await axios.delete(`http://localhost:8000/api/sessions/${userId}`, {
            withCredentials: true,
            headers: { 'X-XSRF-TOKEN': token.value }
        })
        // Remove session from local list
        sessions.value = sessions.value.filter(s => s.user_id !== userId)
        activeUsers.value = sessions.value.length
      } catch (error) {
        console.error('Error terminating session:', error)
      }
    }

onMounted(async () => {
  try {
    // Fetch CSRF token
    await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true })
    token.value = decodeURIComponent(
      document.cookie
        .split('; ')
        .find((row) => row.startsWith("XSRF-TOKEN="))
        ?.split("=")[1] ?? ''
    )

    // Total users
    const userRes = await axios.get('http://localhost:8000/api/users', { withCredentials: true })
    totalUsers.value = userRes.data.length

    // Active sessions (only last 5 min + must have user_id)
    const sessionsRes = await axios.get('http://localhost:8000/api/sessions', { withCredentials: true })
    const now = Math.floor(Date.now() / 1000)


    const activeSessions = sessionsRes.data.filter(session => {
      return session.user_id && (now - session.last_activity <= 300)
    })

    sessions.value = activeSessions
    activeUsers.value = activeSessions.length
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
