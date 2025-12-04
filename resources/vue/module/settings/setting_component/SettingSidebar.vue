<template>
  <div className="ehxdo-sidebar">
    <div class="ehxdo-menu">
      <router-link :to="{ name: 'general' }" class="ehxdo_menu_item">
        General
      </router-link>
      <router-link :to="{ name: 'shortcode' }" class="ehxdo_menu_item">
        Shortcode
      </router-link>
      <router-link :to="{ name: 'email' }" class="ehxdo_menu_item">
        Email
      </router-link>
       <router-link :to="{ name: 'integration' }"class="ehxdo_menu_item">
        Integration
      </router-link>
        <router-link :to="{ name: 'recaptcha' }"class="ehxdo_menu_item">
        reCAPTCHA
      </router-link>
        <router-link :to="{ name: 'color' }" class="ehxdo_menu_item">
        Colors & Style
      </router-link>

     
    </div>
  </div>
</template>
<script>


</script>

<style scoped lang="scss">
.ehxdo-sidebar {
  width: 200px;
  height: 100%;
  border-radius: 16px 0px 0px 16px;
  box-sizing: border-box;
  background-color: #fff;
  border-right: 1px solid #e5e5e5;
  box-shadow: none;
}

.ehxdo-sidebar-title {
  font-size: 20px;
  font-weight: 600;
  color: #000;
  padding: 0 24px;
  margin-bottom: 24px;
}

.ehxdo-nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.ehxdo-nav-item {
  padding: 12px 24px;
  color: #666;
  cursor: pointer;
  transition: background-color 0.2s;
  font-size: 14px;
}

.ehxdo-nav-item:hover {
  background-color: #f9f9f9;
}

.ehxdo-nav-item.ehxdo-active {
  color: #000;
  font-weight: 500;
  background-color: #f9f9f9;
}
</style>