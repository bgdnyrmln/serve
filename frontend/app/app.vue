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
  mounted() {
    axios.get('http://127.0.0.1:8000/api/users')
      .then(response => {
        console.log(response.data);
        this.message = JSON.stringify(response.data); 
      })
      .catch(error => {
        console.error(error);
        this.message = 'Error fetching data';
      });
  },
};
</script>
