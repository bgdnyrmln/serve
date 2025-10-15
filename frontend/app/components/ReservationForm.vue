<template>
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Make a Reservation</h3>
    
    <form @submit.prevent="submitReservation" class="space-y-4">
      <!-- Date Selection -->
      <div>
        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
          Reservation Date
        </label>
        <input
          v-model="form.reservation_date"
          type="date"
          id="date"
          :min="minDate"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <!-- Time Selection -->
      <div>
        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
          Reservation Time
        </label>
        <input
          v-model="form.reservation_time"
          type="time"
          id="time"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          min="11:00"
          max="21:00"
        />
        <p class="text-xs text-gray-500 mt-1">Available hours: 11:00 AM - 9:00 PM</p>
      </div>

      <!-- Party Size -->
      <div>
        <label for="party_size" class="block text-sm font-medium text-gray-700 mb-2">
          Party Size
        </label>
        <select
          v-model="form.party_size"
          id="party_size"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Select party size</option>
          <option v-for="size in Array.from({length: 20}, (_, i) => i + 1)" :key="size" :value="size">
            {{ size }} {{ size === 1 ? 'person' : 'people' }}
          </option>
        </select>
      </div>

      <!-- Contact Phone -->
      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
          Contact Phone
        </label>
        <input
          v-model="form.contact_phone"
          type="tel"
          id="phone"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Your phone number"
        />
      </div>

      <!-- Special Requests -->
      <div>
        <label for="requests" class="block text-sm font-medium text-gray-700 mb-2">
          Special Requests (Optional)
        </label>
        <textarea
          v-model="form.special_requests"
          id="requests"
          rows="3"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Any dietary restrictions, celebrations, or special needs..."
          maxlength="500"
        ></textarea>
        <p class="text-xs text-gray-500 mt-1">{{ form.special_requests?.length || 0 }}/500 characters</p>
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-200"
      >
        <span v-if="loading">Making Reservation...</span>
        <span v-else>Make Reservation</span>
      </button>
    </form>

    <!-- Success/Error Messages -->
    <div v-if="message" class="mt-4 p-3 rounded-md" :class="messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
      {{ message }}
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

const props = defineProps<{
  restaurantId: number
}>()

const emit = defineEmits<{
  'reservation-created': [reservation: any]
}>()

const form = ref<ReservationForm>({
  reservation_date: '',
  reservation_time: '',
  party_size: '',
  contact_phone: '',
  special_requests: ''
})

const loading = ref(false)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')

// Get minimum date (today)
const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

const submitReservation = async () => {
  loading.value = true
  message.value = ''

  try {
    const reservationData = {
      restaurant_id: props.restaurantId,
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

    emit('reservation-created', response.reservation)

  } catch (error: any) {
    message.value = error.data?.message || 'An error occurred while making the reservation'
    messageType.value = 'error'
  } finally {
    loading.value = false
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