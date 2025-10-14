<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <adminsidebar />

    <div class="flex-1 flex flex-col">
      <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Users</h2>

        <div v-if="loading" class="text-gray-500 dark:text-gray-400">Loading users...</div>
        <div v-else>
          <table class="min-w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow rounded-lg">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">First Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Last Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Email</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Role</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">
                  <input 
                    type="text" 
                    placeholder="Search..." 
                    v-model="searchQuery"
                    class="w-full bg-gray-100 dark:bg-gray-700 px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
                  />
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="user in filteredUsers"
                :key="user.id"
                class="border-t hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
              >
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.first_name }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.last_name }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ user.email }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 capitalize">{{ user.role }}</td>
                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                  <a
                    :href="`/admin/users/${user.id}`"
                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                  >Edit</a>
                  <button
                    @click="deleteUser(user.id)"
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
  middleware: ["isadmin"],
})

const users = ref([])
const loading = ref(true)
const searchQuery = ref('')

// ✅ Filter users by search
const filteredUsers = computed(() => {
  if (!searchQuery.value) return users.value
  const q = searchQuery.value.toLowerCase()
  return users.value.filter(user =>
    String(user.id).includes(q) ||
    user.first_name.toLowerCase().includes(q) ||
    user.last_name.toLowerCase().includes(q) ||
    user.email.toLowerCase().includes(q) ||
    user.role.toLowerCase().includes(q)
  )
})

// ✅ Fetch users
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

// ✅ Delete user
const deleteUser = async (userId) => {
  const token = decodeURIComponent(
    document.cookie
      .split('; ')
      .find((row) => row.startsWith("XSRF-TOKEN="))
      ?.split("=")[1] ?? ''
  )

  if (!confirm('Are you sure you want to delete this user?')) return

  try {
    await axios.delete(`http://localhost:8000/api/users/${userId}`, {
      withCredentials: true,
      headers: { 'X-XSRF-TOKEN': token }
    })
    users.value = users.value.filter(u => u.id !== userId)
  } catch (error) {
    console.error('Error deleting user:', error)
    alert('Failed to delete user. Please try again.')
  }
}
</script>
