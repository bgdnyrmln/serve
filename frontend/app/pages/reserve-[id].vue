<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 pt-32 pb-16 px-6">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-300 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-300 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
      <!-- Back button -->
      <div class="mb-6">
        <button 
          @click="$router.back()" 
          class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back
        </button>
      </div>

      <!-- Loading state -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-300">Loading restaurant details...</p>
      </div>

      <!-- Error state -->
      <div v-else-if="error" class="text-center py-12">
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-8">
          <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
          <h3 class="mt-4 text-lg font-medium text-red-800 dark:text-red-200">Restaurant not found</h3>
          <p class="mt-2 text-red-600 dark:text-red-300">{{ errorMessage }}</p>
        </div>
      </div>

      <!-- Main content -->
      <div v-else-if="restaurant" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Restaurant Info Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 p-8">
          <div class="flex items-center space-x-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-2xl font-bold text-white">
              {{ restaurant.name.charAt(0) }}
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ restaurant.name }}</h1>
              <p class="text-gray-600 dark:text-gray-400">{{ restaurant.cuisine }} • {{ restaurant.location }}</p>
            </div>
          </div>

          <div class="space-y-4">
            <div>
              <h3 class="font-semibold text-gray-900 dark:text-white mb-2">About</h3>
              <p class="text-gray-700 dark:text-gray-300">{{ restaurant.description || 'No description available.' }}</p>
            </div>

            <div v-if="restaurant.rating" class="flex items-center space-x-2">
              <div class="flex items-center">
                <svg v-for="i in 5" :key="i" :class="i <= Math.floor(restaurant.rating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
              </div>
              <span class="text-lg font-bold text-gray-900 dark:text-white">{{ restaurant.rating }}</span>
            </div>

            <div class="flex items-center">
              <span :class="restaurant.open ? 'bg-green-500/80' : 'bg-red-500/80'" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white">
                <span class="w-2 h-2 rounded-full mr-2" :class="restaurant.open ? 'bg-green-300' : 'bg-red-300'"></span>
                {{ restaurant.open ? 'Open' : 'Closed' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Reservation Form Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 p-8">
          <div v-if="!restaurant.open" class="text-center py-12">
            <div class="text-gray-400 mb-4">
              <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Restaurant Closed</h3>
            <p class="text-gray-600 dark:text-gray-300">This restaurant is currently closed and not accepting reservations.</p>
          </div>

          <div v-else>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Make a Reservation</h2>
            
            <form @submit.prevent="submitReservation" class="space-y-6">
              <!-- Date Selection -->
              <div>
                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Reservation Date *
                </label>
                <input
                  v-model="form.reservation_date"
                  type="date"
                  id="date"
                  :min="minDate"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                />
              </div>

              <!-- Time Selection -->
              <div>
                <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Preferred Time *
                </label>
                <input
                  v-model="form.reservation_time"
                  type="time"
                  id="time"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                />
              </div>

              <!-- Party Size -->
              <div>
                <label for="party_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Party Size *
                </label>
                <select
                  v-model="form.party_size"
                  id="party_size"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                >
                  <option value="">Select party size</option>
                  <option v-for="size in Array.from({length: 20}, (_, i) => i + 1)" :key="size" :value="size">
                    {{ size }} {{ size === 1 ? 'person' : 'people' }}
                  </option>
                </select>
              </div>

              <!-- Contact Phone -->
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Contact Phone
                </label>
                <input
                  v-model="form.contact_phone"
                  type="tel"
                  id="phone"
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                  placeholder="Your phone number"
                />
              </div>

              <!-- Special Requests -->
              <div>
                <label for="requests" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Special Requests (Optional)
                </label>
                <textarea
                  v-model="form.special_requests"
                  id="requests"
                  rows="4"
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                  placeholder="Any dietary restrictions, celebrations, or special needs..."
                  maxlength="500"
                ></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ form.special_requests?.length || 0 }}/500 characters</p>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                :disabled="submitting"
                class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-200 font-medium"
              >
                <span v-if="submitting">Making Reservation...</span>
                <span v-else>Make Reservation</span>
              </button>
            </form>

            <!-- Success/Error Messages -->
            <div v-if="message" class="mt-6 p-4 rounded-lg" :class="messageType === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'">
              {{ message }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface ReservationForm {
  reservation_date: string
  reservation_time: string
  party_size: number | string
  contact_phone: string
  special_requests: string
}

interface Restaurant {
  id: number
  name: string
  cuisine: string
  location: string
  description?: string
  rating?: number
  open: boolean
}

const route = useRoute()
const router = useRouter()

const restaurant = ref<Restaurant | null>(null)
const loading = ref(true)
const error = ref(false)
const errorMessage = ref('')

const form = ref<ReservationForm>({
  reservation_date: '',
  reservation_time: '',
  party_size: '',
  contact_phone: '',
  special_requests: ''
})

const submitting = ref(false)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')

// Get minimum date (today)
const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

// Fetch restaurant details
const fetchRestaurant = async () => {
  const id = route.params.id
  
  if (!id) {
    error.value = true
    errorMessage.value = 'Restaurant ID is required'
    loading.value = false
    return
  }

  try {
    loading.value = true
    error.value = false
    
    const data = await useSanctumFetch(`/api/restaurants/${id}`) as Restaurant
    restaurant.value = data
  } catch (err: any) {
    error.value = true
    errorMessage.value = err?.data?.message || 'Restaurant not found'
  } finally {
    loading.value = false
  }
}

const submitReservation = async () => {
  submitting.value = true
  message.value = ''

  try {
    const reservationData = {
      restaurant_id: restaurant.value?.id,
      ...form.value,
      party_size: Number(form.value.party_size)
    }

    const response = await useSanctumFetch('/api/reservations', {
      method: 'POST',
      body: reservationData
    }) as { message: string; reservation: any }

    message.value = response.message
    messageType.value = 'success'
    
    // Reset form
    form.value = {
      reservation_date: '',
      reservation_time: '',
      party_size: '',
      contact_phone: '',
      special_requests: ''
    }

    // Redirect to reservations page after 3 seconds
    setTimeout(() => {
      router.push('/reservations')
    }, 3000)

  } catch (error: any) {
    message.value = error.data?.message || 'An error occurred while making the reservation'
    messageType.value = 'error'
  } finally {
    submitting.value = false
  }
}

// Clear message after 8 seconds
watch(message, (newMessage) => {
  if (newMessage) {
    setTimeout(() => {
      message.value = ''
    }, 8000)
  }
})

onMounted(() => {
  fetchRestaurant()
})

// Set page metadata
useHead({
  title: () => restaurant.value ? `Make Reservation - ${restaurant.value.name}` : 'Make Reservation'
})
</script>