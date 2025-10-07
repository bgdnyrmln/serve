<template>
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
  <p class="text-2xl font-bold text-gray-900 dark:text-white">Admin Panel - Access Granted</p>
</div>
</template>

<script setup>
definePageMeta({
  middleware: ["isadmin"],
})


</script>