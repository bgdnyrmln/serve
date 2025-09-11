<template>
  <div
    class="flex flex-col items-center justify-center min-h-screen bg-gray-100"
  >
    <div class="w-full max-w-md p-8 space-y-3 bg-white shadow-lg rounded-xl">
      <h1 class="text-2xl font-bold text-center">Login</h1>
      <form @submit.prevent="submitForm">
        <div class="flex flex-col mt-3 space-y-1">
          <label for="email" class="text-sm font-medium">Email</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
            :class="{ 'border-red-600': error.email }"
          />
          <span v-if="error.email" class="text-sm text-red-600">
            {{ error.email[0] }}
          </span>
        </div>
        <div class="flex flex-col mt-3 space-y-1">
          <label for="password" class="text-sm font-medium">Password</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
            :class="{ 'border-red-600': error.password }"
          />
          <span v-if="error.password" class="text-sm text-red-600">
            {{ error.password[0] }}
          </span>
        </div>

        <div v-if="error.general" class="mt-2 text-sm text-red-600 text-center">
          {{ error.general }}
        </div>

        <button
          type="submit"
          class="w-full px-4 py-2 mt-3 text-sm font-medium text-white bg-blue-500 rounded-md"
        >
          Login
        </button>
      </form>
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

    // Redirect to main page on success
    router.push('/');
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
