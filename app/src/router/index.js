import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/views/Home.vue'
import Signin from '@/views/Signin.vue';
import Logout from '@/views/Logout.vue';
import Page from '@/views/Page.vue';
import Show from '@/views/Show.vue';
import Post from '@/views/Post.vue';
import Dashboard from '@/views/Dashboard.vue';
import Signup from '@/views/Signup.vue';
import PError from '@/views/Error.vue';
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
    path: '/post/:post_id',
    name: 'Show',
    component: Show
  },
  {
    path: '/post/:post_id/user',
    name: 'Post',
    component: Post,
  },
  {
    path: '/about',
    name: 'About',
    component: About,
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
  },
  {
    path: '/signin',
    name: 'Signin',
    component: Signin
  },
  {
    path: '/signup',
    name: 'Signup',
    component: Signup
  },
  {
    path: '/logout',
    name: 'Logout',
    component: Logout
  },
  {
    path: '/error',
    name: 'Error',
    props: true,
    component: PError,
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
