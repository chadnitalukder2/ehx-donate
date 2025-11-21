<template>
    <div class="ehxdo-wrapper">
        <!-- Header Section -->
        <div class="ehxdo-header">
            <div>
                <h1 class="ehxdo-title">Dashboard</h1>
                <p class="ehxdo-subtitle">Overview of your donation campaigns and activities</p>
            </div>
            <div class="ehxdo-view-toggle">
                <el-button class="ehxdo-toggle-btn" size="default">Admin View</el-button>
                <el-button class="ehxdo-toggle-btn ehxdo-active" type="primary" size="default">Admin View</el-button>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="ehxdo-metrics-grid">
            <div class="ehxdo-metric-card">
                <div class="ehxdo-metric-header">
                    <span class="ehxdo-metric-label">Total Donations</span>
                    <el-icon class="ehxdo-metric-icon-small"><Money /></el-icon>
                </div>
                <div class="ehxdo-metric-value">${{ formatNumber(totalDonations) }}</div>
                <div class="ehxdo-metric-change ehxdo-positive">+12% from last month</div>
            </div>

            <div class="ehxdo-metric-card">
                <div class="ehxdo-metric-header">
                    <span class="ehxdo-metric-label">Total Donors</span>
                    <el-icon class="ehxdo-metric-icon-small"><User /></el-icon>
                </div>
                <div class="ehxdo-metric-value">{{ formatNumber(totalDonors) }}</div>
                <div class="ehxdo-metric-change ehxdo-positive">+6% from last month</div>
            </div>

            <div class="ehxdo-metric-card">
                <div class="ehxdo-metric-header">
                    <span class="ehxdo-metric-label">Active Campaigns</span>
                    <el-icon class="ehxdo-metric-icon-small"><Flag /></el-icon>
                </div>
                <div class="ehxdo-metric-value">{{ activeCampaigns }}</div>
                <div class="ehxdo-metric-change">3 ending soon</div>
            </div>

        </div>

        <!-- Donation Trends Chart -->
        <el-card class="ehxdo-chart-card" shadow="never">
            <template #header>
                <div class="ehxdo-card-header">
                    <div>
                        <h3 class="ehxdo-chart-title">Donation Trends</h3>
                        <p class="ehxdo-chart-subtitle">Monthly donation overview for 2024</p>
                    </div>
                </div>
            </template>
            <div class="ehxdo-chart-container">
                <div id="ehxdo-line-chart" style="width: 100%; height: 300px;"></div>
            </div>
        </el-card>

        <!-- Recent Donations -->
        <el-card class="ehxdo-donations-card" shadow="never">
            <template #header>
                <h3 class="ehxdo-chart-title">Recent Donations</h3>
            </template>
            <div class="ehxdo-donations-list">
                <div v-for="(donation, index) in recentDonations" :key="index" class="ehxdo-donation-item">
                    <div class="ehxdo-donation-left">
                        <div class="ehxdo-donation-name">{{ donation.name }}</div>
                        <div class="ehxdo-donation-campaign">{{ donation.campaign }}</div>
                    </div>
                    <div class="ehxdo-donation-right">
                        <div class="ehxdo-donation-amount">${{ donation.amount }}</div>
                        <div class="ehxdo-donation-time">{{ donation.time }}</div>
                    </div>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script>
import * as echarts from 'echarts';
import { Money, User, Flag, TrendCharts } from '@element-plus/icons-vue';

export default {
    name: 'DashboardView',
    components: {
        Money,
        User,
        Flag,
        TrendCharts
    },
    data() {
        return {
            totalDonations: 48329,
            totalDonors: 1247,
            activeCampaigns: 12,
            avgDonation: '87.50',
            donationTrends: [
                { month: 'Jan', amount: 4200 },
                { month: 'Feb', amount: 5800 },
                { month: 'Mar', amount: 4900 },
                { month: 'Apr', amount: 7200 },
                { month: 'May', amount: 8100 },
                { month: 'Jun', amount: 9500 }
            ],
            recentDonations: [
                { name: 'John Smith', campaign: 'Clean Water Project', amount: '100', time: '2 hours ago' },
                { name: 'Sarah Johnson', campaign: 'Education Fund', amount: '250', time: '5 hours ago' },
                { name: 'Mike Davis', campaign: 'Food Drive', amount: '50', time: '1 day ago' },
                { name: 'Emily Brown', campaign: 'Clean Water Project', amount: '500', time: '1 day ago' }
            ],
            lineChart: null
        }
    },
    methods: {
        formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        
        initLineChart() {
            const chartDom = document.getElementById('ehxdo-line-chart');
            this.lineChart = echarts.init(chartDom);
            
            const option = {
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '10%',
                    top: '10%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: this.donationTrends.map(item => item.month),
                    boundaryGap: false,
                    axisLine: {
                        lineStyle: {
                            color: '#E5E7EB'
                        }
                    },
                    axisLabel: {
                        color: '#9CA3AF',
                        fontSize: 12
                    },
                    axisTick: {
                        show: false
                    }
                },
                yAxis: {
                    type: 'value',
                    axisLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        color: '#9CA3AF',
                        fontSize: 12
                    },
                    splitLine: {
                        lineStyle: {
                            color: '#F3F4F6',
                            type: 'dashed'
                        }
                    }
                },
                series: [{
                    data: this.donationTrends.map(item => item.amount),
                    type: 'line',
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: {
                        color: '#3B82F6',
                        width: 2
                    },
                    itemStyle: {
                        color: '#3B82F6',
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            {
                                offset: 0,
                                color: 'rgba(59, 130, 246, 0.1)'
                            },
                            {
                                offset: 1,
                                color: 'rgba(59, 130, 246, 0.01)'
                            }
                        ])
                    }
                }],
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: '#fff',
                    borderColor: '#E5E7EB',
                    borderWidth: 1,
                    textStyle: {
                        color: '#374151'
                    },
                    formatter: function(params) {
                        return `${params[0].name}<br/>$${params[0].value.toLocaleString()}`;
                    }
                }
            };
            
            this.lineChart.setOption(option);
        },
        
        handleResize() {
            if (this.lineChart) {
                this.lineChart.resize();
            }
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.initLineChart();
            window.addEventListener('resize', this.handleResize);
        });
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.handleResize);
        if (this.lineChart) {
            this.lineChart.dispose();
        }
    }
}
</script>

<style lang="scss" scoped>
.ehxdo-wrapper {
    min-height: 100vh;
    background: #F9FAFB;
    padding: 32px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.ehxdo-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.ehxdo-title {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 4px 0;
}

.ehxdo-subtitle {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.ehxdo-view-toggle {
    display: flex;
    gap: 8px;

    .ehxdo-toggle-btn {
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
    }
}

.ehxdo-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.ehxdo-metric-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #F3F4F6;
}

.ehxdo-metric-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.ehxdo-metric-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
}

.ehxdo-metric-icon-small {
    font-size: 18px;
    color: #9CA3AF;
}

.ehxdo-metric-value {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.ehxdo-metric-change {
    font-size: 13px;
    color: #6B7280;

    &.ehxdo-positive {
        color: #10B981;
    }
}

.ehxdo-chart-card {
    border-radius: 12px;
    margin-bottom: 24px;
    border: 1px solid #F3F4F6;

    :deep(.el-card__header) {
        padding: 20px 24px;
        border-bottom: 1px solid #F3F4F6;
        background: white;
    }

    :deep(.el-card__body) {
        padding: 24px;
    }
}

.ehxdo-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ehxdo-chart-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
}

.ehxdo-chart-subtitle {
    font-size: 13px;
    color: #6B7280;
    margin: 0;
}

.ehxdo-chart-container {
    width: 100%;
}

.ehxdo-donations-card {
    border-radius: 12px;
    border: 1px solid #F3F4F6;

    :deep(.el-card__header) {
        padding: 20px 24px;
        border-bottom: 1px solid #F3F4F6;
        background: white;
    }

    :deep(.el-card__body) {
        padding: 0;
    }
}

.ehxdo-donations-list {
    display: flex;
    flex-direction: column;
}

.ehxdo-donation-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #F3F4F6;

    &:last-child {
        border-bottom: none;
    }

    &:hover {
        background: #F9FAFB;
    }
}

.ehxdo-donation-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.ehxdo-donation-name {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
}

.ehxdo-donation-campaign {
    font-size: 13px;
    color: #6B7280;
}

.ehxdo-donation-right {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: flex-end;
}

.ehxdo-donation-amount {
    font-size: 16px;
    font-weight: 700;
    color: #3B82F6;
}

.ehxdo-donation-time {
    font-size: 12px;
    color: #9CA3AF;
}

@media (max-width: 768px) {
    .ehxdo-wrapper {
        padding: 16px;
    }

    .ehxdo-header {
        flex-direction: column;
        gap: 16px;
    }

    .ehxdo-metrics-grid {
        grid-template-columns: 1fr;
    }

    .ehxdo-donation-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .ehxdo-donation-right {
        align-items: flex-start;
    }
}
</style>