<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 pt-32 pb-16 px-6 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-300 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-300 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
      <div class="absolute top-40 left-1/2 w-60 h-60 bg-pink-300 dark:bg-pink-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-md mx-auto relative z-10">
      <!-- Header -->
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mb-5 shadow-lg">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-11a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-indigo-900 to-purple-900 dark:from-white dark:via-indigo-200 dark:to-purple-200 bg-clip-text text-transparent">
          Login
        </h1>
        <p class="mt-3 text-gray-600 dark:text-gray-300">Welcome back. Please enter your details.</p>
      </div>

      <!-- Card -->
      <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20">
        <form @submit.prevent="submitForm" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input
              id="email"
              type="email"
              v-model="form.email"
              class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-2xl bg-white/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm"
              :class="{ 'border-red-600': error.email }"
            />
            <span v-if="error.email" class="text-sm text-red-600">{{ error.email[0] }}</span>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
            <input
              id="password"
              type="password"
              v-model="form.password"
              class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-2xl bg-white/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm"
              :class="{ 'border-red-600': error.password }"
            />
            <span v-if="error.password" class="text-sm text-red-600">{{ error.password[0] }}</span>
          </div>

          <div v-if="error.general" class="text-sm text-red-600 text-center">{{ error.general }}</div>

          <button type="submit" class="group w-full py-4 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
            </svg>
            Login
          </button>
        </form>
        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-300">Don't have an account? <a href="/register" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Register</a></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

definePageMeta({
  middleware: ["$guest"], // Ensures only unauthenticated users can access this page
});

onMounted(async () => {
  // Get CSRF token on page mount
  await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
    withCredentials: true
  });
});

const router = useRouter();

const form = ref({
  email: "",
  password: "",
});

const error = ref({});

// Handles login form submission
const submitForm = async () => {
  try {
    // Retrieve CSRF token from cookies
    const token = decodeURIComponent(
      document.cookie
        .split("; ")
        .find((row) => row.startsWith("XSRF-TOKEN="))
        ?.split("=")[1] ?? ""
    );

    // Send POST request to login user
    await axios.post("http://localhost:8000/login", form.value, {
      withCredentials: true,
      headers: {
        "X-XSRF-TOKEN": token,
      },
    });

  window.location.href = '/';
  } catch (err) {
    // Handle validation or server errors
    console.error("Login error:", err);
    if (err.response?.data?.errors) {
      error.value = err.response.data.errors;
    } else if (err.response?.data?.message) {
      error.value = { general: err.response.data.message };
    } else {
      error.value = { general: "An unexpected error occurred." };
    }
  }
};
</script>
