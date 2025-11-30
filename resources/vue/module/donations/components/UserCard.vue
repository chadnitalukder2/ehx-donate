<template>
    <div class="ehxdo-card">
      <div class="ehxdo-card__content">
        <img
          class="ehxdo-card__avatar"
          :src="donor?.profile"
          alt="User avatar"
        />
        <div class="ehxdo-card__text">
          <div class="ehxdo-card__name-id">
            <span class="ehxdo-card__name">{{ donor?.first_name }} {{ donor?.last_name }}</span>
            <span class="ehxdo-card__id">#{{ donor?.id }}</span>
          </div>
          <div class="ehxdo-card__email">{{ donor?.email }}</div>
        </div>
      </div>
      <!-- <button class="ehxdo-card__menu-button">
        <span class="ehxdo-card__dots">⋮</span>
      </button> -->
    </div>
  </template>
  
  <script>
  export default {
    name: 'UserCard',
    props: {
        donor: {
            type: Object,
            default: {}
        }
    }
  }
  </script>
  
  <style lang="scss" scoped>
  .ehxdo-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    padding: 1rem;
    border-radius: 1rem;
    box-shadow: 0 0 0 1px #f3f4f6;
  
    &__content {
      display: flex;
      align-items: center;
    }
  
    &__avatar {
      width: 48px;
      height: 48px;
      border-radius: 9999px;
      margin-right: 1rem;
    }
  
    &__text {
      display: flex;
      flex-direction: column;
    }
  
    &__name-id {
      display: flex;
      align-items: baseline;
    }
  
    &__name {
      font-weight: 600;
      font-size: 1rem;
      color: #111827;
    }
  
    &__id {
      font-size: 0.875rem;
      color: #6b7280;
      margin-left: 0.5rem;
    }
  
    &__email {
      font-size: 0.875rem;
      color: #6b7280;
    }
  
    &__menu-button {
      background-color: white;
      border: 1px solid #e5e7eb;
      padding: 0.25rem 0.5rem;
      border-radius: 0.5rem;
      cursor: pointer;
    }
  
    &__dots {
      font-size: 1rem;
      line-height: 1;
    }
  }
  </style>