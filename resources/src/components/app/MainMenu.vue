<script setup>
  import { useRouter } from 'vue-router';
  const router = useRouter();
  const linkedRoutes = router.options.routes.filter(route => route.meta?.label);
  const userRoles = JSON.parse(sessionStorage.getItem('currentUser')).roles || [];
</script>

<template>
  <nav>
    <template v-for="route in linkedRoutes" :key="route.name">
      <router-link
          v-if="
            !route.meta.roles ||
            !route.meta.roles.length ||
            userRoles.filter(item => route.meta.roles.includes(item)).length"
          :to="{ name: route.name }"
          :class="[
              'flex items-center px-2 py-2 text-base font-medium rounded items-center space-x-2 flex-nowrap overflow-hidden',
              $route.name === route.name ? 'bg-vxvue-600 text-white' : 'text-slate-100 hover:bg-vxvue-700'
          ]"
      >
        <component :is="route.meta.icon" v-if="route.meta.icon" class="w-8 h-8 flex-shrink-0" />
        <span v-if="expanded">{{ route.meta.label }}</span>
      </router-link>
    </template>
  </nav>
</template>

<script>
export default {
  name: "MainMenu",
  props: {
    expanded: Boolean
  }
}
</script>
