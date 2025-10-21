// Fetch current user
const fetchUser = async () => {
  try {
    loadingUser.value = true
    const data = await useSanctumFetch('/api/user')
    user.value = data
  } catch (err) {
    user.value = null
  } finally {
    loadingUser.value = false
  }
}

// Owner detection
const isOwner = computed(() => {
  return user.value && restaurant.value && user.value.id === restaurant.value.owner_id
})
<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 pt-32 pb-16 px-6 relative overflow-hidden">
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
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Restaurants
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

      <!-- Restaurant details -->
      <div v-else-if="restaurant" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 overflow-hidden">
        <!-- Header section -->
        <div class="relative bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-12">
          <div class="absolute inset-0 bg-black/10"></div>
          <div class="relative z-10">
            <div class="flex items-center space-x-6">
              <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-4xl font-bold text-white">
                {{ restaurant.name.charAt(0) }}
              </div>
              <div>
                <h1 class="text-4xl font-bold text-white mb-2">{{ restaurant.name }}</h1>
                <div class="flex items-center space-x-4 text-white/90">
                  <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">{{ restaurant.cuisine }}</span>
                  <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ restaurant.location }}
                  </span>
                  <span class="flex items-center" v-if="restaurant.rating">
                    <svg class="w-4 h-4 mr-1 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    {{ restaurant.rating }}
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Status badge -->
            <div class="mt-4">
              <span :class="restaurant.open ? 'bg-green-500/80' : 'bg-red-500/80'" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white">
                <span class="w-2 h-2 rounded-full mr-2" :class="restaurant.open ? 'bg-green-300' : 'bg-red-300'"></span>
                {{ restaurant.open ? 'Open' : 'Closed' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Content section -->
        <div class="px-8 py-8">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main information -->
            <div class="lg:col-span-2">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">About {{ restaurant.name }}</h2>
              <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                {{ restaurant.description || 'No description available for this restaurant.' }}
              </p>

              <!-- Additional details -->
              <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6">
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Cuisine Type</h3>
                  <p class="text-gray-600 dark:text-gray-300">{{ restaurant.cuisine || 'Not specified' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6">
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Location</h3>
                  <p class="text-gray-600 dark:text-gray-300">{{ restaurant.location || 'Location not specified' }}</p>
                </div>
              </div>
            </div>

            <!-- Sidebar information -->
            <div class="space-y-6">
              <!-- Rating card -->
              <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-800/50 rounded-2xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Rating</h3>
                <div class="flex items-center">
                  <div class="flex items-center space-x-1">
                    <template v-for="i in 5" :key="i">
                      <svg 
                        :class="i <= Math.floor(restaurant.rating || 0) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                        class="w-5 h-5" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                      </svg>
                    </template>
                  </div>
                  <span class="ml-2 text-lg font-bold text-gray-900 dark:text-white">
                    {{ restaurant.rating || 'No rating' }}
                  </span>
                </div>
              </div>

              <!-- Owner information -->
              <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Owner</h3>
                <p class="text-gray-900 dark:text-white font-medium">
                  {{ restaurant.owner ? `${restaurant.owner.first_name} ${restaurant.owner.last_name}` : 'Owner information not available' }}
                </p>
              </div>

              <!-- Status information -->
              <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Status</h3>
                <div class="flex items-center">
                  <div :class="restaurant.open ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                    {{ restaurant.open ? 'Currently Open' : 'Currently Closed' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Reservation Section (non-owner) -->
      <div v-if="restaurant && !isOwner" class="mt-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 overflow-hidden">
        <div class="px-8 py-8 text-center">
          <div v-if="restaurant.open">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Ready to dine with us?</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Reserve your table now and enjoy an amazing dining experience.</p>
            <NuxtLink 
              :to="`/reserve-${restaurant.id}`"
              class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Make a Reservation
            </NuxtLink>
          </div>
          <div v-else>
            <div class="text-gray-400 mb-4">
              <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Restaurant Closed</h3>
            <p class="text-gray-600 dark:text-gray-300">This restaurant is currently closed and not accepting reservations.</p>
          </div>
        </div>
      </div>

      <!-- Owner Calendar View -->
      <div v-if="restaurant && isOwner" class="mt-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 overflow-hidden">
        <div class="px-8 py-8">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Restaurant Reservations</h2>
            <div class="flex items-center gap-4">
              <div class="flex items-center gap-2 text-sm">
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 rounded-full bg-gradient-to-br from-green-400 to-green-600"></div>
                  <span class="text-gray-600 dark:text-gray-300">Confirmed</span>
                </div>
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 rounded-full bg-gradient-to-br from-amber-400 to-amber-600"></div>
                  <span class="text-gray-600 dark:text-gray-300">Pending</span>
                </div>
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 rounded-full bg-gradient-to-br from-red-400 to-red-600"></div>
                  <span class="text-gray-600 dark:text-gray-300">Cancelled</span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Loading state -->
          <div v-if="loadingReservations" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-300">Loading reservations...</p>
          </div>

          <!-- No reservations state -->
          <div v-else-if="!reservations || reservations.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-300">No reservations yet</p>
          </div>

          <!-- Calendar -->
          <div v-else class="rounded-2xl overflow-hidden shadow-lg border border-indigo-200 dark:border-indigo-700/50">
            <ClientOnly>
              <VueCal
                :events="calendarEvents"
                :time-from="9 * 60"
                :time-to="22 * 60"
                :disable-views="['years', 'year']"
                active-view="month"
                :on-event-click="onEventClick"
                :editable-events="false"
                :twelve-hour="false"
                style="height: 650px"
                class="vuecal--custom"
              >
                <template #event="{ event }">
                  <div class="vuecal__event-content">
                    <div class="font-semibold text-sm">{{ event.title }}</div>
                    <div class="text-xs opacity-90">{{ event.content }}</div>
                  </div>
                </template>
              </VueCal>
            </ClientOnly>
          </div>

          <!-- Selected Reservation Details -->
          <div v-if="selectedReservation" class="mt-6 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border border-indigo-200 dark:border-indigo-800/50 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reservation Details</h3>
              <button
                @click="selectedReservation = null"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <!-- Error message -->
            <div v-if="actionError" class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
              <p class="text-sm text-red-600 dark:text-red-400">{{ actionError }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Guest Name</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ selectedReservation.user?.first_name }} {{ selectedReservation.user?.last_name }}</p>
              </div>
              <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ selectedReservation.user?.email || selectedReservation.contact_email }}</p>
              </div>
              <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date & Time</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ formatReservationDateTime(selectedReservation.reservation_date, selectedReservation.reservation_time) }}</p>
              </div>
              <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Party Size</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ selectedReservation.party_size }} {{ selectedReservation.party_size === 1 ? 'guest' : 'guests' }}</p>
              </div>
              <div v-if="selectedReservation.contact_phone" class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Phone</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ selectedReservation.contact_phone }}</p>
              </div>
              <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                <span :class="{
                  'bg-gradient-to-br from-green-100 to-green-200 text-green-800 dark:from-green-900/50 dark:to-green-800/50 dark:text-green-200': selectedReservation.status === 'confirmed',
                  'bg-gradient-to-br from-amber-100 to-amber-200 text-amber-800 dark:from-amber-900/50 dark:to-amber-800/50 dark:text-amber-200': selectedReservation.status === 'pending',
                  'bg-gradient-to-br from-red-100 to-red-200 text-red-800 dark:from-red-900/50 dark:to-red-800/50 dark:text-red-200': selectedReservation.status === 'cancelled'
                }" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold capitalize shadow-sm">
                  {{ selectedReservation.status }}
                </span>
              </div>
              <div v-if="selectedReservation.special_requests" class="md:col-span-2 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Special Requests</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ selectedReservation.special_requests }}</p>
              </div>
              <div v-if="selectedReservation.cancellation_reason" class="md:col-span-2 bg-red-50/60 dark:bg-red-900/20 backdrop-blur-sm rounded-xl p-4 border border-red-200 dark:border-red-800/50">
                <p class="text-sm text-red-600 dark:text-red-400 mb-1 font-semibold">Cancellation Reason</p>
                <p class="font-medium text-red-800 dark:text-red-200">{{ selectedReservation.cancellation_reason }}</p>
              </div>
            </div>

            <!-- Action buttons for pending reservations -->
            <div v-if="selectedReservation.status === 'pending'" class="mt-6 flex gap-3">
              <button
                @click="acceptReservation(selectedReservation)"
                :disabled="actionLoading"
                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-green-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ actionLoading ? 'Processing...' : 'Accept Reservation' }}
              </button>
              <button
                @click="openDeclineModal(selectedReservation)"
                :disabled="actionLoading"
                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ actionLoading ? 'Processing...' : 'Decline Reservation' }}
              </button>
            </div>
          </div>
        </div>
      </div>

  <!-- Reviews Section (non-owner only) -->
  <div v-if="restaurant && !isOwner" class="mt-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 overflow-hidden">
        <div class="px-8 py-8">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Reviews & Ratings</h2>
          
          <!-- Add Review Form -->
          <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Leave a Review</h3>
            
            <form @submit.prevent="submitReview" class="space-y-4">
              <!-- Star Rating -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                <div class="flex items-center space-x-1">
                  <button
                    v-for="star in 5"
                    :key="star"
                    type="button"
                    @click="reviewForm.rating = star"
                    :class="star <= reviewForm.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                    class="text-2xl hover:text-yellow-400 transition-colors focus:outline-none"
                  >
                    ★
                  </button>
                  <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ reviewForm.rating > 0 ? `${reviewForm.rating} star${reviewForm.rating !== 1 ? 's' : ''}` : 'Select rating' }}
                  </span>
                </div>
              </div>

              <!-- Comment -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Comment (optional)</label>
                <textarea
                  v-model="reviewForm.comment"
                  rows="3"
                  placeholder="Share your experience..."
                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ></textarea>
              </div>

              <!-- Submit Button -->
              <div class="flex items-center space-x-3">
                <button
                  type="submit"
                  :disabled="reviewForm.rating === 0 || submittingReview"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                >
                  {{ submittingReview ? 'Submitting...' : 'Submit Review' }}
                </button>
                <div v-if="reviewError" class="text-sm text-red-600 dark:text-red-400">{{ reviewErrorMessage }}</div>
                <div v-if="reviewSuccess" class="text-sm text-green-600 dark:text-green-400">Review submitted successfully!</div>
              </div>
            </form>
          </div>

          <!-- Existing Reviews -->
          <div>
            <!-- Filter Section -->
            <div v-if="reviews.length > 0" class="mb-6">
              <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ reviews.length }} Review{{ reviews.length !== 1 ? 's' : '' }}</span>
                <div class="flex gap-2">
                  <button
                    v-for="filter in filterOptions"
                    :key="filter.value"
                    @click="currentFilter = filter.value"
                    class="px-3 py-1.5 text-sm rounded-lg transition-all duration-200"
                    :class="currentFilter === filter.value 
                      ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-medium'
                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                  >
                    {{ filter.label }}
                  </button>
                </div>
              </div>
            </div>

            <div v-if="loadingReviews" class="text-center py-8">
              <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-500"></div>
              <p class="mt-2 text-gray-600 dark:text-gray-300">Loading reviews...</p>
            </div>

            <div v-else-if="reviews.length === 0" class="text-center py-8">
              <p class="text-gray-600 dark:text-gray-300">No reviews yet. Be the first to leave one!</p>
            </div>

            <div v-else class="space-y-6">
              <div
                v-for="review in filteredReviews"
                :key="review.id"
                class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0"
              >
                <div class="flex items-start space-x-4">
                  <!-- User Avatar -->
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-sm">
                    {{ getUserInitials(review.user) }}
                  </div>
                  
                  <!-- Review Content -->
                  <div class="flex-1">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                      <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">
                          {{ getUserFullName(review.user) }}
                        </h4>
                        <div class="flex items-center space-x-2">
                          <!-- Stars -->
                          <div class="flex items-center">
                            <span
                              v-for="star in 5"
                              :key="star"
                              :class="star <= review.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                              class="text-sm"
                            >
                              ★
                            </span>
                          </div>
                          <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ formatReviewDate(review.created_at) }}
                          </span>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Comment -->
                    <p v-if="review.comment" class="text-gray-700 dark:text-gray-300 leading-relaxed">
                      {{ review.comment }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Decline Reservation Modal -->
    <div v-if="showDeclineModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeDeclineModal"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
            <div class="flex items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </div>
              <div class="ml-4 flex-1">
                <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white" id="modal-title">
                  Decline Reservation
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    Please provide a reason for declining this reservation. The guest will be notified with your explanation.
                  </p>
                </div>
              </div>
              <button
                @click="closeDeclineModal"
                class="ml-4 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400"
              >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <!-- Form -->
            <div class="mt-4">
              <label for="decline-reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Reason for declining <span class="text-red-500">*</span>
              </label>
              <textarea
                id="decline-reason"
                v-model="declineReason"
                rows="4"
                placeholder="e.g., We are fully booked for that time slot, or we have a private event scheduled..."
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                :class="{ 'border-red-500': actionError }"
              ></textarea>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Minimum 10 characters required
              </p>
              
              <!-- Error message -->
              <div v-if="actionError" class="mt-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                <p class="text-sm text-red-600 dark:text-red-400">{{ actionError }}</p>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <button
              @click="closeDeclineModal"
              :disabled="actionLoading"
              class="w-full sm:w-auto px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              Cancel
            </button>
            <button
              @click="declineReservation"
              :disabled="actionLoading || !declineReason || declineReason.trim().length < 10"
              class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              {{ actionLoading ? 'Declining...' : 'Decline Reservation' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

const route = useRoute()
const router = useRouter()

const restaurant = ref(null)
const loading = ref(true)
const error = ref(false)
const errorMessage = ref('')

// Reviews state
const reviews = ref([])
const loadingReviews = ref(false)
const reviewForm = ref({ rating: 0, comment: '' })
const submittingReview = ref(false)
const reviewError = ref(false)
const reviewErrorMessage = ref('')
const reviewSuccess = ref(false)

// Filter state
const currentFilter = ref('newest')
const filterOptions = [
  { label: 'Newest', value: 'newest' },
  { label: 'Oldest', value: 'oldest' },
  { label: 'Highest', value: 'highest' },
  { label: 'Lowest', value: 'lowest' }
]

// User state
const user = ref(null)
const loadingUser = ref(true)

// Reservations state (for owner)
const reservations = ref([])
const loadingReservations = ref(false)
const selectedReservation = ref(null)

// Actions state
const showDeclineModal = ref(false)
const declineReason = ref('')
const actionLoading = ref(false)
const actionError = ref('')

// Computed property for calendar events
const calendarEvents = computed(() => {
  if (!reservations.value || reservations.value.length === 0) return []
  
  return reservations.value.map(reservation => {
    // Parse the date and time properly
    const dateStr = reservation.reservation_date
    const timeStr = reservation.reservation_time
    
    // Create date object
    let startDate
    if (typeof dateStr === 'string' && typeof timeStr === 'string') {
      // Handle both "HH:mm" and "HH:mm:ss" formats
      const timeParts = timeStr.split(':')
      const hours = parseInt(timeParts[0])
      const minutes = parseInt(timeParts[1])
      
      startDate = new Date(dateStr)
      startDate.setHours(hours, minutes, 0, 0)
    } else {
      startDate = new Date()
    }
    
    // End date is 2 hours after start
    const endDate = new Date(startDate.getTime() + 2 * 60 * 60 * 1000)
    
    return {
      start: startDate,
      end: endDate,
      title: `${reservation.user?.first_name || 'Guest'} ${reservation.user?.last_name || ''}`,
      content: `${reservation.party_size} ${reservation.party_size === 1 ? 'guest' : 'guests'}`,
      class: `event-${reservation.status || 'confirmed'}`,
      reservation: reservation
    }
  })
})

// Computed property for filtered reviews
const filteredReviews = computed(() => {
  let sorted = [...reviews.value]
  
  switch (currentFilter.value) {
    case 'newest':
      return sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    case 'oldest':
      return sorted.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
    case 'highest':
      return sorted.sort((a, b) => b.rating - a.rating)
    case 'lowest':
      return sorted.sort((a, b) => a.rating - b.rating)
    default:
      return sorted
  }
})

// Owner detection
const isOwner = computed(() => {
  return user.value && restaurant.value && user.value.id === restaurant.value.owner_id
})

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
    const data = await useSanctumFetch(`/api/restaurants/${id}`)
    restaurant.value = data
  } catch (err) {
    error.value = true
    errorMessage.value = err?.message || 'Failed to load restaurant'
  } finally {
    loading.value = false
  }
}

// Fetch reviews for the restaurant
const fetchReviews = async () => {
  const id = route.params.id
  if (!id) return

  try {
    loadingReviews.value = true
    const data = await useSanctumFetch(`/api/restaurants/${id}/reviews`)
    reviews.value = data || []
  } catch (err) {
    reviews.value = []
  } finally {
    loadingReviews.value = false
  }
}

// Fetch reservations for restaurant owner
const fetchReservations = async () => {
  const id = route.params.id
  if (!id) return

  try {
    loadingReservations.value = true
    const data = await useSanctumFetch(`/api/restaurants/${id}/reservations`)
    reservations.value = data || []
  } catch (err) {
    console.error('Failed to load reservations:', err)
    reservations.value = []
  } finally {
    loadingReservations.value = false
  }
}

// Handle calendar event click
const onEventClick = (event) => {
  selectedReservation.value = event.reservation
}

// Format reservation date and time
const formatReservationDateTime = (date, time) => {
  if (!date || !time) return ''
  try {
    const dateTime = new Date(`${date}T${time}`)
    return dateTime.toLocaleString('en-US', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit'
    })
  } catch {
    return `${date} ${time}`
  }
}


// Submit a new review
const submitReview = async () => {
  const id = route.params.id
  if (!id || reviewForm.value.rating === 0) return

  try {
    submittingReview.value = true
    reviewError.value = false
    reviewSuccess.value = false

    await useSanctumFetch(`/api/restaurants/${id}/reviews`, {
      method: 'POST',
      body: reviewForm.value
    })

    // Reset form and reload reviews
    reviewForm.value = { rating: 0, comment: '' }
    reviewSuccess.value = true
    await fetchReviews()
    
    // Hide success message after 3 seconds
    setTimeout(() => {
      reviewSuccess.value = false
    }, 3000)

  } catch (err) {
    reviewError.value = true
    reviewErrorMessage.value = err?.data?.message || err?.message || 'Failed to submit review'
  } finally {
    submittingReview.value = false
  }
}
// Fetch current user
const fetchUser = async () => {
  try {
    loadingUser.value = true
    const data = await useSanctumFetch('/api/user')
    user.value = data
  } catch (err) {
    user.value = null
  } finally {
    loadingUser.value = false
  }
}

// Helper functions
const getUserFullName = (user) => {
  if (!user) return 'Anonymous'
  return `${user.first_name || ''} ${user.last_name || ''}`.trim() || user.email || 'Anonymous'
}

const getUserInitials = (user) => {
  if (!user) return 'A'
  const firstName = user.first_name || ''
  const lastName = user.last_name || ''
  if (firstName && lastName) {
    return (firstName.charAt(0) + lastName.charAt(0)).toUpperCase()
  }
  if (firstName) return firstName.charAt(0).toUpperCase()
  if (user.email) return user.email.charAt(0).toUpperCase()
  return 'A'
}

const formatReviewDate = (dateString) => {
  if (!dateString) return ''
  try {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    })
  } catch {
    return ''
  }
}

// Accept reservation
const acceptReservation = async (reservation) => {
  if (!confirm('Are you sure you want to accept this reservation?')) return

  try {
    actionLoading.value = true
    actionError.value = ''
    
    await useSanctumFetch(`/api/reservations/${reservation.id}/accept`, {
      method: 'POST'
    })

    // Reload reservations
    await fetchReservations()
    
    // Clear selection if it was the accepted reservation
    if (selectedReservation.value?.id === reservation.id) {
      selectedReservation.value = null
    }

  } catch (err) {
    actionError.value = err?.data?.message || err?.message || 'Failed to accept reservation'
    setTimeout(() => {
      actionError.value = ''
    }, 5000)
  } finally {
    actionLoading.value = false
  }
}

// Show decline modal
const openDeclineModal = (reservation) => {
  selectedReservation.value = reservation
  showDeclineModal.value = true
  declineReason.value = ''
  actionError.value = ''
}

// Close decline modal
const closeDeclineModal = () => {
  showDeclineModal.value = false
  declineReason.value = ''
  actionError.value = ''
}

// Decline reservation
const declineReservation = async () => {
  if (!declineReason.value || declineReason.value.trim().length < 10) {
    actionError.value = 'Please provide a reason of at least 10 characters'
    return
  }

  try {
    actionLoading.value = true
    actionError.value = ''
    
    await useSanctumFetch(`/api/reservations/${selectedReservation.value.id}/decline`, {
      method: 'POST',
      body: {
        cancellation_reason: declineReason.value
      }
    })

    // Reload reservations
    await fetchReservations()
    
    // Close modal and clear selection
    closeDeclineModal()
    selectedReservation.value = null

  } catch (err) {
    actionError.value = err?.data?.message || err?.message || 'Failed to decline reservation'
  } finally {
    actionLoading.value = false
  }
}

onMounted(() => {
  fetchUser().then(() => {
    fetchRestaurant().then(() => {
      if (isOwner.value) {
        fetchReservations()
      } else {
        fetchReviews()
      }
    })
  })
})

// Set page metadata
useHead({
  title: () => restaurant.value ? `${restaurant.value.name} - Restaurant Details` : 'Restaurant Details'
})
</script>

<style>
/* Custom VueCal Styling */
.vuecal--custom {
  font-family: inherit;
  border-radius: 1rem;
  overflow: hidden;
}

/* Calendar Header */
.vuecal--custom .vuecal__title-bar {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%) !important;
  color: white !important;
  padding: 1rem !important;
  border-bottom: none !important;
}

.vuecal--custom .vuecal__title {
  color: white !important;
  font-weight: 600 !important;
  font-size: 1.25rem !important;
}

.vuecal--custom .vuecal__arrow {
  color: white !important;
}

.vuecal--custom .vuecal__arrow:hover {
  background: rgba(255, 255, 255, 0.2) !important;
}

/* View buttons */
.vuecal--custom .vuecal__view-btn {
  color: white !important;
  border-color: rgba(255, 255, 255, 0.3) !important;
}

.vuecal--custom .vuecal__view-btn--active {
  background: rgba(255, 255, 255, 0.2) !important;
}

/* Weekdays header */
.vuecal--custom .vuecal__weekdays-headings {
  background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%) !important;
  border-bottom: none !important;
}

.vuecal--custom .vuecal__heading {
  color: white !important;
  font-weight: 600 !important;
  padding: 0.75rem 0 !important;
  border: none !important;
}

/* Calendar body */
.vuecal--custom .vuecal__body {
  background: white !important;
}

/* Cells */
.vuecal--custom .vuecal__cell {
  border-color: #e5e7eb !important;
  transition: all 0.2s ease !important;
}

.vuecal--custom .vuecal__cell:hover {
  background: rgba(99, 102, 241, 0.05) !important;
}

.vuecal--custom .vuecal__cell-content {
  color: #374151 !important;
  font-weight: 500 !important;
}

/* Today's cell */
.vuecal--custom .vuecal__cell--today {
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%) !important;
}

.vuecal--custom .vuecal__cell--today .vuecal__cell-content {
  color: #4f46e5 !important;
  font-weight: 700 !important;
}

/* Weekend cells */
.vuecal--custom .vuecal__cell--weekend {
  background: rgba(249, 250, 251, 0.5) !important;
}

/* Events */
.vuecal--custom .vuecal__event {
  border-radius: 0.5rem !important;
  border: none !important;
  font-size: 0.875rem !important;
  padding: 0.5rem !important;
  cursor: pointer !important;
  transition: all 0.2s ease !important;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.vuecal--custom .vuecal__event:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
}

/* Event status colors */
.vuecal--custom .vuecal__event.event-confirmed {
  background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
  color: white !important;
}

.vuecal--custom .vuecal__event.event-pending {
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
  color: white !important;
}

.vuecal--custom .vuecal__event.event-cancelled {
  background: linear-gradient(135deg, #f87171 0%, #ef4444 100%) !important;
  color: white !important;
  opacity: 0.7 !important;
}

.vuecal--custom .vuecal__event-content {
  color: inherit !important;
}

/* Time column (for week/day view) */
.vuecal--custom .vuecal__time-column {
  background: #f9fafb !important;
  border-right: 2px solid #e5e7eb !important;
}

.vuecal--custom .vuecal__time-cell {
  color: #6b7280 !important;
  font-weight: 500 !important;
}

/* Dark mode styles */
.dark .vuecal--custom .vuecal__body {
  background: rgba(31, 41, 55, 0.5) !important;
}

.dark .vuecal--custom .vuecal__cell {
  border-color: rgba(75, 85, 99, 0.5) !important;
}

.dark .vuecal--custom .vuecal__cell:hover {
  background: rgba(99, 102, 241, 0.1) !important;
}

.dark .vuecal--custom .vuecal__cell-content {
  color: #e5e7eb !important;
}

.dark .vuecal--custom .vuecal__cell--today {
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.2) 100%) !important;
}

.dark .vuecal--custom .vuecal__cell--today .vuecal__cell-content {
  color: #a5b4fc !important;
}

.dark .vuecal--custom .vuecal__cell--weekend {
  background: rgba(17, 24, 39, 0.5) !important;
}

.dark .vuecal--custom .vuecal__time-column {
  background: rgba(17, 24, 39, 0.5) !important;
  border-right: 2px solid rgba(75, 85, 99, 0.5) !important;
}

.dark .vuecal--custom .vuecal__time-cell {
  color: #9ca3af !important;
}

/* No events message */
.vuecal--custom .vuecal__no-event {
  color: #9ca3af !important;
  font-style: italic !important;
}

.dark .vuecal--custom .vuecal__no-event {
  color: #6b7280 !important;
}

/* All day events */
.vuecal--custom .vuecal__all-day {
  background: rgba(249, 250, 251, 0.8) !important;
  border-bottom: 2px solid #e5e7eb !important;
}

.dark .vuecal--custom .vuecal__all-day {
  background: rgba(17, 24, 39, 0.8) !important;
  border-bottom: 2px solid rgba(75, 85, 99, 0.5) !important;
}

/* Now indicator */
.vuecal--custom .vuecal__now-line {
  color: #ef4444 !important;
}

/* Scrollbar styling */
.vuecal--custom ::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.vuecal--custom ::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.vuecal--custom ::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  border-radius: 4px;
}

.vuecal--custom ::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
}

.dark .vuecal--custom ::-webkit-scrollbar-track {
  background: rgba(31, 41, 55, 0.5);
}

.dark .vuecal--custom ::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%);
}
</style>