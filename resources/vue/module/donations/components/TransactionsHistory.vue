<template>
    <div class="ehxdo-transactions">
      <h3 class="ehxdo-transactions__title">Transactions History</h3>
      <table class="ehxdo-transactions__table">
        <thead>
          <tr>
            <th>
              Trans. ID <span class="ehxdo-transactions__sort">⇅</span>
            </th>
            <th>
              Date Paid <span class="ehxdo-transactions__sort">⇅</span>
            </th>
            <th>
              Payment Method <span class="ehxdo-transactions__sort">⇅</span>
            </th>
            <th>
              Amount <span class="ehxdo-transactions__sort">⇅</span>
            </th>
            <th>
              Status <span class="ehxdo-transactions__sort">⇅</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="transaction in transactions" :key="transaction.id">
            <td>{{ transaction?.id }}</td>
            <td>
              <div class="ehxdo-transactions__date">
                <div>{{ getDate(transaction.created_at) }}</div>
                <small>{{ getTime(transaction.created_at) }}</small>
              </div>
            </td>
            <td>{{ transaction?.payment_method }} - {{ transaction?.payment_method_type }}</td>
            <td> {{ transaction?.currency }} {{ transaction?.payment_total }}</td>
            <td>
              <span :class="['ehxdo-transactions__status', `is-${transaction.status.toLowerCase()}`]">
                {{ transaction.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    name: 'TransactionsHistory',
    props: {
        transactions: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        getDate(date) {
            if (!date) return '';
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },
        getTime(date) {
            if (!date) return '';
            const options = { hour: 'numeric', minute: 'numeric' };
            return new Date(date).toLocaleTimeString('en-GB', options);
        }
    }
  }
  </script>
  
  <style lang="scss" scoped>
  .ehxdo-transactions {
    background-color: #ffffff;
    border-radius: 1rem;
    padding: 1rem;
    box-shadow: 0 0 0 1px #f3f4f6;
    margin-bottom: 20px;
  
    &__title {
      font-size: 1rem;
      font-weight: 600;
      color: #111827;
      margin-bottom: 1rem;
    }
  
    &__table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.875rem;
  
      th {
        text-align: left;
        font-weight: 500;
        color: #6b7280;
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid #e5e7eb;
      }
  
      td {
        padding: 1rem 0.5rem;
        border-bottom: 1px solid #e5e7eb;
        color: #111827;
      }
    }
  
    &__sort {
      font-size: 0.75rem;
      margin-left: 0.25rem;
      opacity: 0.5;
    }
  
    &__date {
      small {
        display: block;
        font-size: 0.75rem;
        color: #6b7280;
      }
    }
  
    &__status {
      display: inline-block;
      padding: 0.2rem 0.6rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 500;
      white-space: nowrap;
  
      &.is-completed {
        background-color: #d1fae5;
        color: #065f46;
      }
  
      &.is-refunded {
        background-color: #f3f4f6;
        color: #6b7280;
      }
    }
  }
  </style>