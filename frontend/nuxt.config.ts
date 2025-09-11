// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: ["@qirolab/nuxt-sanctum-authentication", '@nuxtjs/tailwindcss'],
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  app: {
    head: {
      title: 'Serve',
      meta: [
        { name: 'description', content: 'The best reservation system for your restaurant' }
      ],
    },
  },

  laravelSanctum: {
    apiUrl: "http://localhost:8000",  // Make sure this points to your backend URL
    authMode: "cookie",  // Cookie-based authentication with Sanctum
  },

  runtimeConfig: {
    public: {
      axios: {
        credentials: true // important to send cookies
      }
    }
  },
  
  routeRules: {
    "/register": {
      redirect: "/auth/register",
    },
    "/login": {
      redirect: "/auth/login",
    }
  },

  nitro: {
    routeRules: {
      '/api/**': {
        cors: true,
        proxy: process.env.BACKEND_URL + '/api/**',  // Ensure this points to your backend API
      },
      '/sanctum/csrf-cookie': {
        cors: true,
        proxy: process.env.BACKEND_URL + '/sanctum/csrf-cookie',  // Handle CSRF token request
      },
    },
  },




})