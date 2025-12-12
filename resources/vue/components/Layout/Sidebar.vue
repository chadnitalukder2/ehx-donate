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

      <router-link to="/transaction" class="ehxdo_menu_item">
        Transaction
      </router-link>
        <router-link v-if="has_gift_aid_donation"  to="/gift-aid" class="ehxdo_menu_item">
          Gift Aid
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
   data() {
        return {
            has_gift_aid_donation: window.EHXDonate?.has_gift_aid_donation || false,
        };
    },

};
</script>

<style lang="scss" scoped></style>