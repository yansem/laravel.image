import Vue from 'vue'
import VueRouter from "vue-router";

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/posts', component: () => import('./components/Post/Index'),
            name: 'index'
        },
        {
            path: '/create', component: () => import('./components/Post/Store'),
            name: 'store'
        },
        {
            path: '/posts/:id/edit', component: () => import('./components/Post/Edit'),
            name: 'edit'
        }
    ]
})

