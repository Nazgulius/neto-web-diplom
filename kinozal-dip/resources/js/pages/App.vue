 <!-- добавил -->
 
<template>
  <div id="app">
    <router-view></router-view>
    <Hall :hallId="1" :seanceId="1" />
  </div>
</template>

<script>
import Hall from './components/Hall.vue';

export default {
  name: 'App',
  components: { Hall }
};
</script>