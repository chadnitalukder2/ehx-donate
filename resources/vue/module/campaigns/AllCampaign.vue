<template>
  <div class="ehxdo-dashboard">
    <!-- Header -->
    <div class="ehxdo-header">
      <h1 class="ehxdo-title">All Campaign</h1>
     
        <!-- <router-link to="" class="ehxdo-add-btn">
          <el-icon><Plus /></el-icon>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
            <path d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z"/></svg>
          Add Campaign
        </router-link> -->

            <el-button @click="openCategoryAddModal()" size="large" type="primary" icon="Plus" class="ltm_button">
                    Add New Campaign
                </el-button>

    </div>

    <!-- Stats Cards -->
    <div class="ehxdo-stats-grid">
      <div class="ehxdo-stat-card">
        <div class="ehxdo-stat-header">
          <span class="ehxdo-stat-label">Campaign Active</span>
          <span class="ehxdo-stat-icon ehxdo-active"></span>
        </div>
        <div class="ehxdo-stat-value">1,263</div>
        <div class="ehxdo-stat-change ehxdo-positive">8.5% from last month</div>
      </div>

      <div class="ehxdo-stat-card">
        <div class="ehxdo-stat-header">
          <span class="ehxdo-stat-label">Campaign Pending</span>
          <span class="ehxdo-stat-icon ehxdo-pending"></span>
        </div>
        <div class="ehxdo-stat-value">32</div>
        <div class="ehxdo-stat-change ehxdo-negative">4.3% from last month</div>
      </div>

      <div class="ehxdo-stat-card">
        <div class="ehxdo-stat-header">
          <span class="ehxdo-stat-label">Campaign Completed</span>
          <span class="ehxdo-stat-icon ehxdo-completed"></span>
        </div>
        <div class="ehxdo-stat-value">936</div>
        <div class="ehxdo-stat-change ehxdo-negative">8.3% from last month</div>
      </div>

      <div class="ehxdo-stat-card">
        <div class="ehxdo-stat-header">
          <span class="ehxdo-stat-label">Campaign Rejected</span>
          <span class="ehxdo-stat-icon ehxdo-rejected"></span>
        </div>
        <div class="ehxdo-stat-value">33</div>
        <div class="ehxdo-stat-change ehxdo-negative">8.5% from last month</div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="ehxdo-table-section">
      <div class="ehxdo-table-header">
        <h2 class="ehxdo-table-title">Campaigns Table</h2>
        <div class="ehxdo-table-controls">
          <div class="ehxdo-search-box">
            <input type="text" placeholder="Search" class="ehxdo-search-input" />
          </div>
          <button class="ehxdo-filter-btn">Filter</button>
          <button class="ehxdo-sort-btn">Sort by</button>
        </div>
      </div>

      <!-- Table -->
      <div class="ehxdo-table-wrapper">
        <table class="ehxdo-table">
          <thead class="ehxdo-thead">
            <tr class="ehxdo-tr">
              <th class="ehxdo-th">No</th>
              <th class="ehxdo-th">Campaign Title</th>
              <th class="ehxdo-th">Goal Amount</th>
              <th class="ehxdo-th">Raised</th>
              <th class="ehxdo-th">Status</th>
              <th class="ehxdo-th"></th>
            </tr>
          </thead>
          <tbody class="ehxdo-tbody">
            <tr v-for="campaign in campaigns" :key="campaign.no" class="ehxdo-tr">
              <td class="ehxdo-td">{{ campaign.no }}</td>
              <td class="ehxdo-td ehxdo-campaign-title">{{ campaign.title }}</td>
              <td class="ehxdo-td">{{ campaign.goal }}</td>
              <td class="ehxdo-td">{{ campaign.raised }}</td>
              <td class="ehxdo-td">
                <span :class="['ehxdo-status-badge', `ehxdo-status-${campaign.status.toLowerCase()}`]">
                  {{ campaign.status }}
                </span>
              </td>
              <td class="ehxdo-td">
                <button class="ehxdo-menu-btn">...</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="ehxdo-pagination">
        <div class="ehxdo-pagination-info">
          Showing 1 to 10 of 500 results
        </div>
        <div class="ehxdo-pagination-controls">
          <span class="ehxdo-per-page">Per page: 10</span>
          <div class="ehxdo-page-buttons">
            <button class="ehxdo-page-btn">&lt;</button>
            <button class="ehxdo-page-btn">1</button>
            <button class="ehxdo-page-btn">2</button>
            <button class="ehxdo-page-btn ehxdo-active">3</button>
            <button class="ehxdo-page-btn">...</button>
            <button class="ehxdo-page-btn">5</button>
            <button class="ehxdo-page-btn">&gt;</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'CampaignDashboard',
  data() {
    return {
      campaigns: [
        {
          no: 1,
          title: 'Strength in Unity',
          fundraiser: 'Sustainable ',
          category: 'Medical',
          goal: '$500.00',
          raised: '-',
          status: 'Pending'
        },
        {
          no: 2,
          title: 'Journey of Hope',
          fundraiser: 'Green Horizons',
          category: 'Medical',
          goal: '$500.00',
          raised: '$100.00',
          status: 'Active'
        },
        {
          no: 3,
          title: 'Hope Blooms',
          fundraiser: 'Earth\'s Embrace',
          category: 'Medical',
          goal: '$500.00',
          raised: '-',
          status: 'Rejected'
        },
        {
          no: 4,
          title: 'Light of Hope',
          fundraiser: 'Nature\'s Hope',
          category: 'Medical',
          goal: '$500.00',
          raised: '$500.08',
          status: 'Completed'
        },
        {
          no: 5,
          title: 'Together We Heal',
          fundraiser: 'Eco Champions',
          category: 'Medical',
          goal: '$500.00',
          raised: '-',
          status: 'Pending'
        },
        {
          no: 6,
          title: 'Healing Hearts',
          fundraiser: 'Green Dreams',
          category: 'Medical',
          goal: '$500.00',
          raised: '$100.00',
          status: 'Active'
        },
        {
          no: 7,
          title: ' A Cancer Support',
          fundraiser: 'Planet Protectors',
          category: 'Medical',
          goal: '$500.00',
          raised: '-',
          status: 'Rejected'
        },
        {
          no: 8,
          title: 'A Journey Through Cancer',
          fundraiser: 'Eco Warriors',
          category: 'Medical',
          goal: '$500.00',
          raised: '$500.00',
          status: 'Completed'
        }
      ]
    };
  }
};
</script>

<style lang="scss">
.ehxdo-dashboard {
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.ehxdo-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  margin-top: 10px;
}

.ehxdo-title {
  font-size: 20px;
  font-weight: 600;
  color: #0D0D12;
  margin: 0;
}

.ehxdo-add-btn {
  padding: 8px 16px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-size: 14px;
  color: #fff;
  background-color: #1a1a1a;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
  a{
    color: #fff !important;
  }
  &:hover {
    background-color: #1a1a1aec;
    color: #fff;
  }
}

.ehxdo-stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}

.ehxdo-stat-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.ehxdo-stat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.ehxdo-stat-label {
  font-size: 14px;
  color: #666;
  font-weight: 500;
}

.ehxdo-stat-icon {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;

  &::after {
    content: '✓';
    color: white;
    font-size: 16px;
    font-weight: bold;
  }

  &.ehxdo-active {
    background-color: #10b981;
  }

  &.ehxdo-pending {
    background-color: #10b981;
  }

  &.ehxdo-completed {
    background-color: #10b981;
  }

  &.ehxdo-rejected {
    background-color: #10b981;
  }
}

.ehxdo-stat-value {
  font-size: 32px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 8px;
}

.ehxdo-stat-change {
  font-size: 13px;
  font-weight: 500;

  &.ehxdo-positive {
    color: #10b981;
  }

  &.ehxdo-negative {
    color: #ef4444;
  }
}

.ehxdo-table-section {
  background: white;
  border-radius: 8px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.ehxdo-table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.ehxdo-table-title {
  font-size: 18px;
  font-weight: 600;
  color: #1a1a1a;
  margin: 0;
}

.ehxdo-table-controls {
  display: flex;
  gap: 12px;
  align-items: center;
}

.ehxdo-search-box {
  position: relative;
}

.ehxdo-search-input {
  padding: 8px 12px 8px 32px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-size: 14px;
  width: 200px;
  outline: none;

  &:focus {
    border-color: #3b82f6;
  }

  &::placeholder {
    color: #999;
  }
}

.ehxdo-filter-btn,
.ehxdo-sort-btn {
  padding: 8px 16px;
  background-color: white;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-size: 14px;
  color: #333;
  cursor: pointer;
  font-weight: 500;

  &:hover {
    background-color: #f9f9f9;
  }
}

.ehxdo-table-wrapper {
  overflow-x: auto;
  margin-bottom: 20px;
}

.ehxdo-table {
  width: 100%;
  border-collapse: collapse;
}

.ehxdo-thead {
  background-color: #f9fafb;
}

.ehxdo-th {
  padding: 12px 16px;
  text-align: left;
  font-size: 13px;
  font-weight: 600;
  color: #666;
  border-bottom: 1px solid #e5e7eb;
}

.ehxdo-tbody {
  .ehxdo-tr {
    border-bottom: 1px solid #f0f0f0;

    &:hover {
      background-color: #f9fafb;
    }
  }
}

.ehxdo-td {
  padding: 16px;
  font-size: 14px;
  color: #333;
}

.ehxdo-campaign-title {
  font-weight: 500;
  color: #1a1a1a;
  max-width: 300px;
}

.ehxdo-status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  display: inline-block;

  &.ehxdo-status-pending {
    background-color: #fef3c7;
    color: #92400e;
  }

  &.ehxdo-status-active {
    background-color: #d1fae5;
    color: #065f46;
  }

  &.ehxdo-status-rejected {
    background-color: #fee2e2;
    color: #991b1b;
  }

  &.ehxdo-status-completed {
    background-color: #e0e7ff;
    color: #3730a3;
  }
}

.ehxdo-menu-btn {
  background: none;
  border: none;
  font-size: 18px;
  color: #666;
  cursor: pointer;
  padding: 4px 8px;

  &:hover {
    color: #333;
  }
}

.ehxdo-pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 16px;
  border-top: 1px solid #f0f0f0;
}

.ehxdo-pagination-info {
  font-size: 14px;
  color: #666;
}

.ehxdo-pagination-controls {
  display: flex;
  align-items: center;
  gap: 16px;
}

.ehxdo-per-page {
  font-size: 14px;
  color: #666;
}

.ehxdo-page-buttons {
  display: flex;
  gap: 4px;
}

.ehxdo-page-btn {
  padding: 6px 12px;
  border: 1px solid #e0e0e0;
  background: white;
  border-radius: 4px;
  font-size: 14px;
  color: #333;
  cursor: pointer;
  min-width: 36px;

  &:hover {
    background-color: #f9f9f9;
  }

  &.ehxdo-active {
    background-color: #10b981;
    color: white;
    border-color: #10b981;
  }
}

@media (max-width: 1200px) {
  .ehxdo-stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .ehxdo-stats-grid {
    grid-template-columns: 1fr;
  }

  .ehxdo-table-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .ehxdo-pagination {
    flex-direction: column;
    gap: 12px;
    align-items: flex-start;
  }
}
</style>