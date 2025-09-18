<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-16 px-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
          Get in Touch
        </h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
          We’d love to hear from you! Fill out the form or reach us through the details below.
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Send us a message</h2>
          <form class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
              <input
                type="text"
                placeholder="Your full name"
                class="w-full p-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
              <input
                type="email"
                placeholder="you@example.com"
                class="w-full p-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</label>
              <textarea
                rows="5"
                placeholder="Write your message..."
                class="w-full p-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500"
              ></textarea>
            </div>

            <button
              type="submit"
              class="w-full py-3 px-6 bg-indigo-600 text-white rounded-lg font-medium shadow-md hover:bg-indigo-700 transition"
            >
              Send Message
            </button>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="flex flex-col justify-between">
          <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Contact Information</h2>
            <ul class="space-y-4 text-gray-700 dark:text-gray-300">
              <li>
                📍 <span class="ml-2">123 Somewhat iela, Riga, Latvia</span>
              </li>
              <li>
                📞 <span class="ml-2">+371 22113344</span>
              </li>
              <li>
                ✉️ <span class="ml-2">serveinfo@gmail.com</span>
              </li>
            </ul>
          </div>

          <!-- Map -->
          <div class="h-full w-full rounded-2xl overflow-hidden shadow-lg">
            <iframe
            class="w-full h-full"
            frameborder="0"
            scrolling="no"
            marginheight="0"
            marginwidth="0"
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6875.000000000000!2d24.105186!3d56.949649!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTYuOTQ5NjQ5LCAyNC4xMDUxODY!5e0!3m2!1sen!2s!4v1695060000000!5m2!1sen!2s"
            allowfullscreen=""
            aria-hidden="false"
            tabindex="0"
            ></iframe>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// No logic yet, just UI
</script>
