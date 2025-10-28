import { createRouter, createWebHashHistory } from 'vue-router';
import TripsView from './module/TripsView.vue';
import DashboardIndex from './module/dashboard/DashboardIndex.vue';
// Create router
const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        { 
            path: '/trips',
            component: TripsView,
            name: 'trips',
            meta: {
                active_menu: 0
            }
        },
        { 
            path: '/dashboard', 
            name: 'dashboard',
            component: DashboardIndex,
            meta: {
                active_menu: 1
            }
        },
    ]
});

export default router;