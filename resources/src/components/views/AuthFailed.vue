<script>
export default {
  name: "AuthFailedView",
  created () {
    this.$emit('notify', { success: false, message: 'Authentifizierung nicht mehr gültig.' });
    this.$router.replace({ name: 'login' });
  }
}
</script>