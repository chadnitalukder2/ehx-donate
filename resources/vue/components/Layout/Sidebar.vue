<template>
  <div class="ehxdo-sidebar">
    <div class="ehxdo-menu">
      <router-link to="/dashboard" class="ehxdo_menu_item">
        Dashboard
      </router-link>
      <router-link to="/campaigns" class="ehxdo_menu_item">
        Campaigns
      </router-link>
      <router-link to="/donations" class="ehxdo_menu_item">
        Donations
      </router-link>
      <router-link to="/donors" class="ehxdo_menu_item">
        Donors
      </router-link>
      
      <router-link to="/transitions" class="ehxdo_menu_item">
        Transitions
      </router-link>
      <router-link to="/subscriptions" class="ehxdo_menu_item">
        Subscriptions
      </router-link>
      <router-link to="/reports" class="ehxdo_menu_item">
        Reports
      </router-link>
      <router-link to="/settings" class="ehxdo_menu_item">
        Settings
      </router-link>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'EhxdoSidebar',

};
</script>

<style lang="scss" scoped></style>