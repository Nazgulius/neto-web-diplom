 <!-- добавил -->
 
<template>
  <div id="app">
    <!-- <Index /> -->
    <router-view></router-view>
    <!-- <Hall :hallId="1" :seanceId="1" /> -->
  </div>
</template>

<script>
import Hall from './components/Hall.vue';
import Index from './components/Index.vue';
// import Index from './client/Index.vue';
import Hall from './client/Hall.vue';
import Payment from './client/Payment.vue';
import Ticket from './client/Ticket.vue';

export default {
  name: 'App',
  components: { Index } // открывается то, что написано
};
</script>