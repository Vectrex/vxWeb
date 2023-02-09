<script setup>
  import { useRouter } from 'vue-router';
  const router = useRouter();
  const linkedRoutes = router.options.routes.filter(route => route.meta?.label);
</script>

<template>
  <nav>
    <router-link
        v-for="(route, ndx) in linkedRoutes"
        :key="ndx"
        :to="{ name: route.name }"
        :class="[
            'group flex items-center px-2 py-2 text-base font-medium rounded',
            $route.name === route.name ? 'bg-slate-800 text-white' : 'text-slate-100 hover:bg-slate-600'
        ]"
    >{{ route.meta.label }}
    </router-link>
  </nav>
</template>

<script>
export default {
  name: "Sidebar"
}
</script>
