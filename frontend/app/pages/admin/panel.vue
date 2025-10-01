<template>
<p>Admin Panel - Access Granted</p>
</template>

<script setup>
definePageMeta({
  middleware: ["isadmin"],
})


</script>