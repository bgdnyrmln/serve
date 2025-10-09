<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <adminsidebar />
    <!-- Main content -->
    <div class="flex-1 flex flex-col">
      <!-- Topbar -->
      <header class="flex items-center justify-between bg-white dark:bg-gray-800 shadow px-6 py-4">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Admin Dashboard</h1>
      </header>

      <!-- Dashboard content -->
      <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Users</h2>
        <div v-if="loading" class="text-gray-500 dark:text-gray-400">Loading users...</div>
        <div v-else>
          <table class="min-w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow rounded-lg">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Email</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Role</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="user in users"
                :key="user.id"
                class="border-t hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
              >
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.first_name + ' ' + user.last_name }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.email }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.role }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                  <a
                    :href="`/admin/users/${user.id}`"
                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                    >Edit</a
                  >
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">
                  <button
                    @click="deleteUser(user.id)"
                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
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
import { ref, onMounted } from 'vue'
import axios from 'axios'

definePageMeta({
  middleware: ["isadmin"], // Only admins can access
})

const users = ref([])
const loading = ref(true)


onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/users', { withCredentials: true })
    users.value = response.data
  } catch (error) {
    console.error('Error fetching users:', error)
  } finally {
    loading.value = false
  }
})


const deleteUser = async (userId) => {
  const token = decodeURIComponent(
      document.cookie
        .split('; ')
        .find((row) => row.startsWith("XSRF-TOKEN="))
        ?.split("=")[1] ?? ''
    )
  if (!confirm('Are you sure you want to delete this user?')) {
    return
  }
  try {
    await axios.delete(`http://localhost:8000/api/users/${userId}`, {
      withCredentials: true,
      headers: { 'X-XSRF-TOKEN': token }
    })
    users.value = users.value.filter(user => user.id !== userId)
  } catch (error) {
    console.error('Error deleting user:', error)
    alert('Failed to delete user. Please try again.')
  }
}



</script>
