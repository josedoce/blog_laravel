import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Signin from '../views/Signin.vue';
import Logout from '../views/Logout.vue';
import Page from '../views/Page.vue';
import Show from '@/views/Show.vue';
import About from '@/views/About.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/page/:category?',
    name: 'Page',
    component: Page
  },
  {
    path: '/posts/:post_id',
    name: 'Show',
    component: Show
  },
  {
    path: '/about/:user_id',
    name: 'About',
    component: About,
  },
  {
    path: '/signin',
    name: 'Signin',
    component: Signin
  },
  {
    path: '/logout',
    name: 'Logout',
    component: Logout
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
