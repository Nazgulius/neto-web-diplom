<template>
  <div id="app">
    <!-- <Index /> -->

    <!-- <router-view></router-view> -->
    <!-- <router-view v-slot="{ Component }">
      <suspense>
        <template #default>
          <component :is="Component" v-bind="$route.params" />
        </template>
        <template #fallback>
          <div>Загрузка...</div>
        </template>
      </suspense>
    </router-view> -->
    <router-view v-slot="{ Component }">
      <suspense>
        <template #default>
          <component :is="Component" v-bind="$route.params" />
        </template>
        <template #fallback>
          <div>Загрузка...</div>
        </template>
      </suspense>
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