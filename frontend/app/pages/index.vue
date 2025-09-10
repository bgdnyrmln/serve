<template>
  <div>
    <h1>{{ message }}</h1>
  </div>
</template>

<script>
import axios from 'axios';


export default {
  data() {
    return {
      message: 'Loading...', 
    };
  },
  

async mounted() {
  try {
    const user = await useSanctumFetch('/api/user');
    console.log(user);
    this.message = `User fetched: ${JSON.stringify(user)}`;
  } catch (error) {
    console.error(error);
    this.message = 'Error fetching user data';
  }
},
};
</script>
