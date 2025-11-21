<template>
    <div class="ehxdo-wrapper">
        <!-- Header Section -->
        <div class="ehxdo-header">
            <div>
                <h1 class="ehxdo-title">Reports</h1>
                <p class="ehxdo-subtitle">Analytics and insights for your campaigns</p>
            </div>
            <div class="ehxdo-export-buttons">
                <el-button class="ehxdo-btn" size="large">
                    <el-icon><Download /></el-icon>
                    Export CSV
                </el-button>
                <el-button class="ehxdo-btn" size="large">
                    <el-icon><Document /></el-icon>
                    Export XLS
                </el-button>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="ehxdo-metrics-grid">
            <div class="ehxdo-metric-card">
                <div class="ehxdo-metric-header">
                    <span class="ehxdo-metric-icon">💰</span>
                    <span class="ehxdo-metric-label">Total Revenue</span>
                </div>
                <div class="ehxdo-metric-value">${{ formatNumber(totalRevenue) }}</div>
                <div class="ehxdo-metric-change ehxdo-positive">All time</div>
            </div>

            <div class="ehxdo-metric-card">
                <div class="ehxdo-metric-header">
                    <span class="ehxdo-metric-icon">👥</span>
                    <span class="ehxdo-metric-label">Total Donors</span>
                </div>
                <div class="ehxdo-metric-value">{{ formatNumber(totalDonors) }}</div>
                <div class="ehxdo-metric-change">Unique donors</div>
            </div>

            <div class="ehxdo-metric-card">
                <div class="ehxdo-metric-header">
                    <span class="ehxdo-metric-icon">📊</span>
                    <span class="ehxdo-metric-label">Avg Donation</span>
                </div>
                <div class="ehxdo-metric-value">${{ avgDonation }}</div>
                <div class="ehxdo-metric-change ehxdo-positive">+4% this month</div>
            </div>

        </div>

        <!-- Charts Grid -->
        <div class="ehxdo-charts-grid">
            <!-- Monthly Donations Chart -->
            <el-card class="ehxdo-chart-card" shadow="never">
                <template #header>
                    <h3 class="ehxdo-chart-title">Monthly Donations</h3>
                </template>
                <div class="ehxdo-chart-container">
                    <div id="ehxdo-bar-chart" style="width: 100%; height: 280px;"></div>
                </div>
            </el-card>

            <!-- Campaign Distribution Chart -->
            <el-card class="ehxdo-chart-card" shadow="never">
                <template #header>
                    <h3 class="ehxdo-chart-title">Campaign Distribution</h3>
                </template>
                <div class="ehxdo-chart-container">
                    <div id="ehxdo-pie-chart" style="width: 100%; height: 280px;"></div>
                </div>
            </el-card>
        </div>

        <!-- Top Performing Campaigns -->
        <el-card class="ehxdo-campaigns-card" shadow="never">
            <template #header>
                <h3 class="ehxdo-chart-title">Top Performing Campaigns</h3>
            </template>
            <div class="ehxdo-campaigns-list">
                <div v-for="(campaign, index) in campaigns" :key="index" class="ehxdo-campaign-item">
                    <div class="ehxdo-campaign-info">
                        <span class="ehxdo-campaign-dot" :style="{ backgroundColor: campaign.color }"></span>
                        <span class="ehxdo-campaign-name">{{ campaign.name }}</span>
                    </div>
                    <span class="ehxdo-campaign-amount">{{ campaign.amount }}</span>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script>
import * as echarts from 'echarts';
import { Download, Document } from '@element-plus/icons-vue';

export default {
    name: 'CampaignReports',
    components: {
        Download,
        Document
    },
    data() {
        return {
            totalRevenue: 104600,
            totalDonors: 1247,
            avgDonation: '87.50',
            thisMonth: 9800,
            monthlyData: [
                { month: 'Jan', donations: 2800 },
                { month: 'Feb', donations: 3200 },
                { month: 'Mar', donations: 2900 },
                { month: 'Apr', donations: 3600 },
                { month: 'May', donations: 3800 },
                { month: 'Jun', donations: 4200 }
            ],
            pieData: [
                { name: 'Clean Water', value: 37, color: '#3B82F6' },
                { name: 'Education', value: 28, color: '#10B981' },
                { name: 'Medical Aid', value: 21, color: '#8B5CF6' },
                { name: 'Food Drive', value: 14, color: '#F59E0B' }
            ],
            campaigns: [
                { name: 'Clean Water', amount: '$38,500', color: '#3B82F6' },
                { name: 'Education', amount: '$29,000', color: '#10B981' },
                { name: 'Medical Aid', amount: '$22,020', color: '#8B5CF6' },
                { name: 'Food Drive', amount: '$15,080', color: '#F59E0B' }
            ],
            barChart: null,
            pieChart: null
        }
    },
    methods: {
        formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        
        initBarChart() {
            const chartDom = document.getElementById('ehxdo-bar-chart');
            this.barChart = echarts.init(chartDom);
            
            const option = {
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    top: '10%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: this.monthlyData.map(item => item.month),
                    axisLine: {
                        lineStyle: {
                            color: '#E5E7EB'
                        }
                    },
                    axisLabel: {
                        color: '#6B7280'
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
                        color: '#6B7280'
                    },
                    splitLine: {
                        lineStyle: {
                            color: '#F3F4F6'
                        }
                    }
                },
                series: [{
                    data: this.monthlyData.map(item => item.donations),
                    type: 'bar',
                    barWidth: '40%',
                    itemStyle: {
                        color: '#3B82F6',
                        borderRadius: [8, 8, 0, 0]
                    }
                }]
            };
            
            this.barChart.setOption(option);
        },
        
        initPieChart() {
            const chartDom = document.getElementById('ehxdo-pie-chart');
            this.pieChart = echarts.init(chartDom);
            
            const option = {
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}: {c}%'
                },
                legend: {
                    orient: 'vertical',
                    right: '10%',
                    top: 'center',
                    formatter: function(name) {
                        const item = this.pieData.find(d => d.name === name);
                        return `${name} ${item ? item.value : ''}%`;
                    }.bind(this),
                    textStyle: {
                        color: '#6B7280'
                    }
                },
                series: [{
                    type: 'pie',
                    radius: ['50%', '75%'],
                    center: ['35%', '50%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false
                    },
                    labelLine: {
                        show: false
                    },
                    data: this.pieData.map(item => ({
                        value: item.value,
                        name: item.name,
                        itemStyle: { color: item.color }
                    }))
                }]
            };
            
            this.pieChart.setOption(option);
        },
        
        handleResize() {
            if (this.barChart) {
                this.barChart.resize();
            }
            if (this.pieChart) {
                this.pieChart.resize();
            }
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.initBarChart();
            this.initPieChart();
            window.addEventListener('resize', this.handleResize);
        });
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.handleResize);
        if (this.barChart) {
            this.barChart.dispose();
        }
        if (this.pieChart) {
            this.pieChart.dispose();
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
    padding-bottom: 16px;
    border-bottom: 1px solid #E5E7EB;
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

.ehxdo-export-buttons {
    display: flex;
    gap: 12px;

    .ehxdo-btn {
        display: flex;
        align-items: center;
        gap: 8px;
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
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ehxdo-metric-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}

.ehxdo-metric-icon {
    font-size: 20px;
}

.ehxdo-metric-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
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

.ehxdo-charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

.ehxdo-chart-card {
    border-radius: 12px;

    :deep(.el-card__header) {
        padding: 20px 24px;
        border-bottom: 1px solid #F3F4F6;
    }

    :deep(.el-card__body) {
        padding: 24px;
    }
}

.ehxdo-chart-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.ehxdo-chart-container {
    width: 100%;
}

.ehxdo-campaigns-card {
    border-radius: 12px;

    :deep(.el-card__header) {
        padding: 20px 24px;
        border-bottom: 1px solid #F3F4F6;
    }

    :deep(.el-card__body) {
        padding: 24px;
    }
}

.ehxdo-campaigns-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.ehxdo-campaign-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
}

.ehxdo-campaign-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.ehxdo-campaign-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.ehxdo-campaign-name {
    font-size: 15px;
    color: #374151;
    font-weight: 500;
}

.ehxdo-campaign-amount {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
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

    .ehxdo-charts-grid {
        grid-template-columns: 1fr;
    }
}
</style>