<script setup>
  import { UserIcon, PowerIcon, Cog6ToothIcon } from '@heroicons/vue/24/solid';
</script>
<template>
  <div class="w-full flex justify-between items-center text-vxvue-100">
    <div id="tools"></div>
    <div class="relative">
      <button
        type="button"
        class="flex max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-vxvue-200 focus:ring-offset-0"
        id="user-menu-button"
        aria-expanded="false"
        aria-haspopup="true"
        @click.stop="showUserMenu = !showUserMenu"
      >
        <span class="sr-only">Open user menu</span>
        <user-icon class="h-8 w-8" />
      </button>
      <transition name="appear">
        <div
          v-if="showUserMenu"
          class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
          role="menu"
          aria-orientation="vertical"
          aria-labelledby="user-menu-button"
          tabindex="-1"
        >
          <router-link
            :to="{ name: 'profile' }"
            class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex space-x-1 items-center"
            role="menuitem"
            tabindex="-1"
            @click="showUserMenu = false"
          ><cog-6-tooth-icon class="h-5 w-5" /><span>Profileinstellungen</span></router-link>
          <router-link
            :to="{ name: 'login' }"
            class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex space-x-1 items-center"
            role="menuitem"
            tabindex="-1"
            @click="showUserMenu = false"
          ><power-icon class="h-5 w-5" /><span>Abmelden</span></router-link>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  name: "Headerbar",
  data () {
    return {
      showUserMenu: false
    }
  },
  mounted() {
    document.body.addEventListener('click', this.handleBodyClick);
  },
  beforeUnmount() {
    document.body.removeEventListener('click', this.handleBodyClick);
  },
  methods: {
    handleBodyClick() {
      this.showUserMenu = false;
    },
  }
}
</script>
