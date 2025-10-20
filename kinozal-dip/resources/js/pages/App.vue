<template>
  <div id="app">    
    <router-view v-slot="{ Component }">
      <component :is="Component" v-bind="$route.params" />
    </router-view>
  </div>
</template>

<script>
import Index from './client/Index.vue'
import Hall from './client/Hall.vue';
import Payment from './client/Payment.vue';
import Ticket from './client/Ticket.vue';

export default {
  name: 'App',
  // components: {
  //   Index
  // } // открывается то, что написано
};
</script>