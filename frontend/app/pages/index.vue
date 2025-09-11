<template>
  <div>
    <h1>{{ message }}</h1>
    <p v-if="!isLoggedIn" @click="navigateTo('/auth/register')">Please log in to access this content.</p>
    <NuxtLink v-if="!isLoggedIn" to="/auth/register" class="text-blue-500 underline">Go to Register</NuxtLink>
    <NuxtLink v-if="!isLoggedIn" to="/auth/login" class="text-blue-500 underline">Go to Login</NuxtLink>
    <a @click="logout" v-if="isLoggedIn">Logout</a>
  </div>
</template>

<script>
import axios from 'axios';
import { ref } from 'vue';

const refreshing = ref(false)

export default {
  data() {
    return {
      message: 'Loading...', 
    };
  },

  

  async mounted() {
    await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
      withCredentials: true
    });

    try {
      const user = await useSanctumFetch('/api/user');
      console.log(user);
      this.message = `User fetched: ${JSON.stringify(user)}`;
    } catch (error) {
      console.error(error);
      this.message = 'You are not logged in.';
    }
  },

  computed: {
    isLoggedIn() {
      return this.message !== 'You are not logged in.';
    },
  },

  methods: {
    navigateTo(path) {
      this.$router.push(path);
    },
    async logout() {
      try {
        const token = decodeURIComponent(
          document.cookie
            .split("; ")
            .find((row) => row.startsWith("XSRF-TOKEN="))
            ?.split("=")[1] ?? ""
        );
        await axios.post(
          'http://localhost:8000/logout',
          {}, // no request body for logout
          {
            withCredentials: true,
            headers: {
              "X-XSRF-TOKEN": token,
            },
          }
        );
        window.location.reload(true);
      } catch (error) {
        console.error('Logout failed:', error);
      }
    },
  },
};

  

</script>
