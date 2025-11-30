<template>
    <div class="ehxdo-breadcrumb">
      <router-link to="/donations" class="ehxdo-breadcrumb__back">
        Donations
      </router-link>
      <span class="ehxdo-breadcrumb__separator">›</span>
      <span class="ehxdo-breadcrumb__current">{{ donation?.donation_hash }}</span>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Breadcrumb',
    props: {
        donation: {
            type: Object,
            default: {}
        }
    }
  }
  </script>
  
  <style scoped lang="scss">
  .ehxdo-breadcrumb {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #f9fafb;
    font-size: 0.875rem;
    margin: 20px 0;
  
    &__back {
      color: #6b7280;
      text-decoration: none;
  
      &:hover {
        text-decoration: underline;
      }
    }
  
    &__separator {
      margin: 0 0.5rem;
      color: #6b7280;
    }
  
    &__current {
      font-weight: 600;
      color: #111827;
    }
  }
  </style>