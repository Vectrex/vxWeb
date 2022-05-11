<script setup>
  import PasswordInput from 'vx-vue/src/components/formelements/password-input.vue';
</script>

<script>
  export default {
    data () {
      return {
        formData: {
          passwortInput: ''
        }
      };
    }
  }
</script>

<template>
  <h1>Welcome</h1>
  <div class="my-4 items-center">
    <label for="password-input" class="inline-block w-1/3">password-input</label>
    <password-input placeholder="6 characters or more" class="w-2/3" id="password-input" v-model="formData.passwordInput" />
  </div>
</template>