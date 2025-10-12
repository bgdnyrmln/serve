<template>
  <div v-if="mounted" class="space-y-8">
    <!-- Header -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 p-8">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Reservations</h1>
          <p class="text-gray-600 dark:text-gray-400">Manage your upcoming and past restaurant reservations</p>
        </div>
        <button
          @click="refreshReservations"
          class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Refresh
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 p-16 text-center">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
      <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Loading your reservations...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="!reservations.length" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 p-16 text-center">
      <div class="text-gray-400 dark:text-gray-500 mb-6">
        <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">No reservations yet</h3>
      <p class="text-gray-600 dark:text-gray-400 mb-8">Start by making a reservation at your favorite restaurant!</p>
      <NuxtLink 
        to="/restaurants"
        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 transition-all duration-200 shadow-lg hover:shadow-xl"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        Browse Restaurants
      </NuxtLink>
    </div>

    <!-- Reservations List -->
    <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="reservation in reservations"
        :key="reservation.id"
        class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 p-6 transition-all hover:shadow-2xl hover:-translate-y-1"
        :class="getStatusColor(reservation.status)"
      >
        <!-- Restaurant Info -->
        <div class="mb-6">
          <div class="flex items-center space-x-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
              {{ reservation.restaurant.name.charAt(0) }}
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ reservation.restaurant.name }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ reservation.restaurant.location }}</p>
            </div>
          </div>
        </div>

        <!-- Reservation Details -->
        <div class="space-y-3 mb-6">
          <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <span class="font-medium">{{ formatDate(reservation.reservation_date) }}</span>
          </div>
          <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="font-medium">{{ formatTime(reservation.reservation_time) }}</span>
          </div>
          <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <span class="font-medium">{{ reservation.party_size }} {{ reservation.party_size === 1 ? 'person' : 'people' }}</span>
          </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
          <span 
            class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold"
            :class="getStatusBadgeColor(reservation.status)"
          >
            <span class="w-2 h-2 rounded-full mr-2" :class="getStatusDotColor(reservation.status)"></span>
            {{ getStatusText(reservation.status) }}
          </span>
        </div>

        <!-- Special Requests -->
        <div v-if="reservation.special_requests" class="mb-4">
          <p class="text-xs text-gray-500 mb-1">Special Requests:</p>
          <p class="text-sm text-gray-700">{{ reservation.special_requests }}</p>
        </div>

        <!-- Actions -->
        <div class="flex space-x-3">
          <button
            v-if="reservation.status === 'pending'"
            @click="editReservation(reservation)"
            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-xl hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-500/50 transition-all duration-200"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
          </button>
          <button
            v-if="canCancel(reservation)"
            @click="cancelReservation(reservation)"
            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/50 transition-all duration-200"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Cancel
          </button>
        </div>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="message" class="fixed bottom-8 right-8 p-4 rounded-2xl shadow-2xl z-50 backdrop-blur-sm border" :class="messageType === 'success' ? 'bg-green-100/90 text-green-800 border-green-200 dark:bg-green-900/90 dark:text-green-300 dark:border-green-800' : 'bg-red-100/90 text-red-800 border-red-200 dark:bg-red-900/90 dark:text-red-300 dark:border-red-800'">
      <div class="flex items-center">
        <svg v-if="messageType === 'success'" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        {{ message }}
      </div>
    </div>
  </div>
  
  <!-- Loading state for hydration -->
  <div v-else class="space-y-8">
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 p-16 text-center">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
      <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Loading your reservations...</p>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Reservation {
  id: number
  restaurant_id: number
  user_id: number
  reservation_date: string
  reservation_time: string
  party_size: number
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed'
  special_requests?: string
  contact_phone?: string
  contact_email?: string
  restaurant: {
    id: number
    name: string
    location: string
  }
}

const reservations = ref<Reservation[]>([])
const loading = ref(true)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')
const mounted = ref(false)

onMounted(() => {
  mounted.value = true
  fetchReservations()
})

const fetchReservations = async () => {
  loading.value = true
  try {
    const data = await useSanctumFetch('/api/reservations') as Reservation[]
    reservations.value = data
  } catch (error) {
    console.error('Error fetching reservations:', error)
    message.value = 'Failed to load reservations'
    messageType.value = 'error'
  } finally {
    loading.value = false
  }
}

const refreshReservations = () => {
  fetchReservations()
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatTime = (time: string) => {
  const [hours, minutes] = time.split(':')
  const date = new Date()
  date.setHours(parseInt(hours || '0'), parseInt(minutes || '0'))
  return date.toLocaleTimeString('en-US', { 
    hour: 'numeric', 
    minute: '2-digit',
    hour12: true 
  })
}

const getStatusColor = (status: string) => {
  const colors = {
    pending: 'border-l-4 border-yellow-400',
    confirmed: 'border-l-4 border-green-400',
    cancelled: 'border-l-4 border-red-400',
    completed: 'border-l-4 border-blue-400'
  }
  return colors[status as keyof typeof colors] || 'border-l-4 border-gray-400'
}

const getStatusBadgeColor = (status: string) => {
  const colors = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
    confirmed: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
    cancelled: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
    completed: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
  }
  return colors[status as keyof typeof colors] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300'
}

const getStatusDotColor = (status: string) => {
  const colors = {
    pending: 'bg-yellow-400',
    confirmed: 'bg-green-400',
    cancelled: 'bg-red-400',
    completed: 'bg-blue-400'
  }
  return colors[status as keyof typeof colors] || 'bg-gray-400'
}

const getStatusText = (status: string) => {
  const texts = {
    pending: 'Pending',
    confirmed: 'Confirmed',
    cancelled: 'Cancelled',
    completed: 'Completed'
  }
  return texts[status as keyof typeof texts] || status
}

const canCancel = (reservation: Reservation) => {
  return ['pending', 'confirmed'].includes(reservation.status)
}

const editReservation = (reservation: Reservation) => {
  // TODO: Implement edit functionality
  console.log('Edit reservation:', reservation)
  message.value = 'Edit functionality will be implemented soon!'
  messageType.value = 'success'
}

const cancelReservation = async (reservation: Reservation) => {
  if (!confirm('Are you sure you want to cancel this reservation?')) {
    return
  }

  try {
    const response = await useSanctumFetch(`/api/reservations/${reservation.id}`, {
      method: 'DELETE'
    }) as { message: string }

    message.value = response.message
    messageType.value = 'success'
    
    // Update the reservation status locally
    const index = reservations.value.findIndex(r => r.id === reservation.id)
    if (index !== -1 && reservations.value[index]) {
      reservations.value[index].status = 'cancelled'
    }
  } catch (error: any) {
    message.value = error.data?.message || 'Failed to cancel reservation'
    messageType.value = 'error'
  }
}

// Clear message after 5 seconds
watch(message, (newMessage) => {
  if (newMessage) {
    setTimeout(() => {
      message.value = ''
    }, 5000)
  }
})
</script>