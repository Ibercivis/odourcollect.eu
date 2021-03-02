import Vue from 'vue';
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from '../views/Home'
// import About from '../views/About.vue'
// import Methodology from '../views/Methodology.vue'
// import Legal from '../views/Legal.vue'
//
// import Login from '../views/Login'
// import Register from '../views/Register'
// import Recover from '../views/Recover'
//
// import User from '../views/User/User'
// import UserProfile from '../views/User/UserProfile'
// import UserPassword from '../views/User/UserPassword'
// import UserOdours from '../views/User/UserOdours'
// import UserZones from '../views/User/UserZones'
// import UserNotifications from '../views/User/UserNotifications'
// import UserMap from '../views/User/UserMap'
//
// import Odour from '../views/Odour/Odour'
// import OdourCreate from '../views/Odour/OdourCreate'

const router = new VueRouter({
    mode: 'history',
    routes: [
      {
        path: '/',
        name: 'home',
        component: Home
      },
      // {
      //   path: '/about',
      //   name: 'about',
      //   component: About
      // },
      // {
      //   path: '/methodology',
      //   name: 'methodology',
      //   component: Methodology
      // },
      // {
      //     path: '/legal',
      //     name: 'legal',
      //     component: Legal
      // },
      // {
      //   path: '/login',
      //   name: 'login',
      //   component: Login,
      // },
      // {
      //   path: '/register',
      //   name: 'register',
      //   component: Register,
      // },
      //   {
      //     path: '/recover',
      //     name: 'recover',
      //     component: Recover,
      //   },
      // {
      //   path: '/user',
      //   name: 'user',
      //   component: User,
      //   children: [
      //     {
      //       // UserProfile will be rendered inside User's <router-view>
      //       // when /user/profile is matched
      //       path: 'profile',
      //       name: 'user-profile',
      //       component: UserProfile,
      //     },
      //     {
      //       // UserPassword will be rendered inside User's <router-view>
      //       // when /user/password is matched
      //       path: 'password',
      //       name: 'user-password',
      //       component: UserPassword,
      //     },
      //     {
      //       // UserOdours will be rendered inside User's <router-view>
      //       // when /user/odours is matched
      //       path: 'odours',
      //       name: 'user-odours',
      //       component: UserOdours,
      //     },
      //     {
      //       // UserZones will be rendered inside User's <router-view>
      //       // when /user/zones is matched
      //       path: 'zones',
      //       name: 'user-zones',
      //       component: UserZones,
      //     },
      //     {
      //       // UserNotifications will be rendered inside User's <router-view>
      //       // when /user/notifications is matched
      //       path: 'notifications',
      //       name: 'user-notifications',
      //       component: UserNotifications,
      //     },
      //     {
      //       path: 'map/:id',
      //       name: 'user-map',
      //       component: UserMap,
      //     },
      //     {
      //       path: 'map/zone/:id',
      //       name: 'user-zone-map',
      //       component: UserMap,
      //     }
      //   ]
      // },
      // {
      //   path: '/odour/create',
      //   name: 'odour-create',
      //   component: OdourCreate,
      // },
      // {
      //   path: '/odour/:id',
      //   name: 'odour',
      //   component: Odour,
      // },
    ],
});

export default router;
