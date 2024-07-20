import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../components/Dashboard.vue';
import Dashboards from '../components/Dashboards.vue';
import Add from '../components/Add.vue';
import Edit from '../components/Edit.vue'

//For all otherpage
import NotFound from '../components/NotFound.vue';

const routes = [
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    },  
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard
    },   
    {
        path: '/d2',
        name: 'Dashboards',
        component: Dashboards.vue
    },   
    {
        path: '/add',
        name: 'AddCustomer',
        component: Add
    },  
    {
        path: '/edit/:id',
        name: 'EditCustomer',
        component: Edit,
        props: true
    },
]

export const router = createRouter({
    history: createWebHistory(),
    routes
  })
