<template>
  <div id="app">
    <!-- <Navbar /> -->
    <div class="ehxdo_app_content">
      <div class="ehxdo_sidebar_content">
        <Sidebar />
      </div>
      <div class="ehxdo_main_content">
        <router-view />
      </div>

    </div>

  </div>
</template>

<script>
import Sidebar from './components/Layout/Sidebar.vue';
import Navbar from './components/Layout/Navbar.vue';

export default {
  name: 'App',
  components: {
    Sidebar,
    Navbar
  },
}
</script>

<style lang="scss" scoped>
.ehxdo_app_content {
 font-family: Inter Tight, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  background: #F8F9FC;
  margin-left: -20px;
  display: flex;
  gap: 20px;
  padding: 20px 20px 20px 20px;

  .ehxdo_main_content{
    flex: 1;
  }
}
</style>