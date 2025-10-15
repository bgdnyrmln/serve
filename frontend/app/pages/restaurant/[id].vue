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

      <!-- Reservation Section -->
      <div v-if="restaurant" class="mt-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 overflow-hidden">
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

      <!-- Reviews Section -->
      <div v-if="restaurant" class="mt-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/20 overflow-hidden">
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
            <div v-if="loadingReviews" class="text-center py-8">
              <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-500"></div>
              <p class="mt-2 text-gray-600 dark:text-gray-300">Loading reviews...</p>
            </div>

            <div v-else-if="reviews.length === 0" class="text-center py-8">
              <p class="text-gray-600 dark:text-gray-300">No reviews yet. Be the first to leave one!</p>
            </div>

            <div v-else class="space-y-6">
              <div
                v-for="review in reviews"
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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

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
    
    // Try to fetch from API first
    const data = await useSanctumFetch(`/api/restaurants/${id}`)
    restaurant.value = data
  } catch (err) {
    // If API fails, use sample data for demonstration
    console.warn('API request failed, using sample data:', err)
    
    const sampleRestaurants = [
      { 
        id: 1, 
        name: 'La Piazza', 
        cuisine: 'Italian', 
        location: 'Center', 
        description: 'Cozy Italian restaurant with wood-fired pizzas and authentic pasta dishes. Our chefs bring traditional Italian flavors with a modern twist, using fresh ingredients and time-honored recipes.', 
        rating: 4.6, 
        open: true, 
        owner_id: 1,
        owner: { id: 1, first_name: 'Mario', last_name: 'Rossi', email: 'mario.rossi@lapiazza.com' }
      },
      { 
        id: 2, 
        name: 'Sushi Hana', 
        cuisine: 'Japanese', 
        location: 'Riverside', 
        description: 'Fresh nigiri and creative rolls in an elegant atmosphere. Experience the art of Japanese cuisine with our expertly crafted sushi, made with the finest fish flown in daily.', 
        rating: 4.8, 
        open: false, 
        owner_id: 2,
        owner: { id: 2, first_name: 'Hiroshi', last_name: 'Tanaka', email: 'hiroshi.tanaka@sushihana.com' }
      },
      { 
        id: 3, 
        name: 'Spice Route', 
        cuisine: 'Indian', 
        location: 'Old Town', 
        description: 'Bold flavors and aromatic curries that transport you to the heart of India. Our spice masters create authentic dishes using traditional cooking methods and premium spices.', 
        rating: 4.5, 
        open: true, 
        owner_id: 3,
        owner: { id: 3, first_name: 'Raj', last_name: 'Patel', email: 'raj.patel@spiceroute.com' }
      },
    ]
    
    const foundRestaurant = sampleRestaurants.find(r => r.id === parseInt(id))
    
    if (foundRestaurant) {
      restaurant.value = foundRestaurant
    } else {
      error.value = true
      errorMessage.value = 'Restaurant not found'
    }
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
    console.warn('Failed to load reviews:', err)
    // Sample reviews for testing when API is unavailable
    const sampleReviews = [
      {
        id: 1,
        rating: 5,
        comment: 'Amazing food and excellent service! The pasta was perfectly cooked and the atmosphere was cozy.',
        created_at: '2024-10-05T10:30:00Z',
        user: { id: 1, first_name: 'John', last_name: 'Doe', email: 'john@example.com' }
      },
      {
        id: 2,
        rating: 4,
        comment: 'Great experience overall. The pizza was delicious, though the wait time was a bit long.',
        created_at: '2024-10-03T15:45:00Z',
        user: { id: 2, first_name: 'Jane', last_name: 'Smith', email: 'jane@example.com' }
      },
      {
        id: 3,
        rating: 5,
        comment: 'Best Italian restaurant in the area! Highly recommend the tiramisu.',
        created_at: '2024-10-01T19:20:00Z',
        user: { id: 3, first_name: 'Mike', last_name: 'Johnson', email: 'mike@example.com' }
      }
    ]
    
    const restaurantId = parseInt(route.params.id)
    if (restaurantId <= 3) {
      reviews.value = sampleReviews
    } else {
      reviews.value = []
    }
  } finally {
    loadingReviews.value = false
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

onMounted(() => {
  fetchRestaurant()
  fetchReviews()
})

// Set page metadata
useHead({
  title: () => restaurant.value ? `${restaurant.value.name} - Restaurant Details` : 'Restaurant Details'
})
</script>

<style scoped>
/* Custom animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.restaurant-card {
  animation: fadeIn 0.6s ease-out;
}
</style>