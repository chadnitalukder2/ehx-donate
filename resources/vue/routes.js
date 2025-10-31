import { createRouter, createWebHashHistory } from 'vue-router';

import DashboardIndex from './module/dashboard/DashboardIndex.vue';
import AllCampaign from './module/campaigns/AllCampaign.vue';
import AllDonation from './module/donations/AllDonation.vue';
import AllDonor from './module/donor/AllDonor.vue';
import Reports from './module/reports/Reports.vue';
import Transitions from './module/transitions/Transitions.vue';
import Subscriptions from './module/subscriptions/Subscriptions.vue';
import SettingsIndex from './module/settings/SettingsIndex.vue';
// Create router
const router = createRouter({
    history: createWebHashHistory(),
    routes: [
      
        { 
            path: '/dashboard', 
            name: 'dashboard',
            component: DashboardIndex,
            meta: {
                active_menu: 1
            }
        },
          { 
            path: '/campaigns',
            component: AllCampaign,
            name: 'campaigns',
            meta: {
                active_menu: 0
            }
        },
          { 
            path: '/donations',
            component: AllDonation,
            name: 'donations',
            meta: {
                active_menu: 0
            }
        },
          { 
            path: '/donors',
            component: AllDonor,
            name: 'donors',
            meta: {
                active_menu: 0
            }
        },
          { 
            path: '/reports',
            component: Reports,
            name: 'reports',
            meta: {
                active_menu: 0
            }
        },
          { 
            path: '/transitions',
            component: Transitions,
            name: 'transitions',
            meta: {
                active_menu: 0
            }
        },
         { 
            path: '/subscriptions',
            component: Subscriptions,
            name: 'subscriptions',
            meta: {
                active_menu: 0
            }
        },
          { 
            path: '/settings',
            component: SettingsIndex,
            name: 'settings',
            meta: {
                active_menu: 0
            }
        },
    ]
});

export default router;