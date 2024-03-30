import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/Home.vue";
import Login from "@/views/auth/Login.vue";
import Register from "@/views/auth/Register.vue";
import Verify from "@/views/auth/Verify.vue";
import ForgetPassword from "@/views/auth/ForgetPassword.vue";
import VerifyPassword from "@/views/auth/VerifyPassword.vue";
import ResetPassword from "@/views/auth/ResetPassword.vue";
import store from "@/store/index.js";



const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      needAuth: false,
    },

  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      needAuth: false,
    },

  },
  {
    path: '/activate',
    name: 'activate',
    component: Verify,
    meta: { needAuth: false },


  },
  {
    path: '/forget-password',
    name: 'forget-password',
    component: ForgetPassword,
    meta: {
      needAuth: false,
    },

  },
  {
    path: '/password-recovery',
    name: 'password-recovery',
    component: VerifyPassword,
    meta: {
      needAuth: false,
    },

  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: ResetPassword,
    meta: {
      needAuth: false,
    },

  },
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { needAuth: true },
    beforeEnter: ifAuthenticated,
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});


function ifAuthenticated(to, from, next) {
  if (to.meta.needAuth === true && store.state.isLoggedIn === 'true') {
    if (store.state.user.otp_date != null) {
      router.push({ name: "activate" });
    } else {
      next();
      return;
    }

  }
  else {
    router.push({ name: 'login' });
  }

};





export default router;
