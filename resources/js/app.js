/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import VueRouter from "vue-router";
Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        component: require("./components/TopGamesComponent").default,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/games",
        component: require("./components/GamesComponent").default,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/players",
        component: require("./components/PlayersComponent").default,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/top-games",
        component: require("./components/TopGamesComponent").default,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/login",
        component: require("./components/LoginComponent").default,
        meta: {
            guest: true
        }
    },
    {
        path: "/register",
        component: require("./components/RegisterComponent").default,
        meta: {
            guest: true
        }
    },
    {
        path: "/token",
        component: require("./components/TokenComponent").default,
        meta: {
            requiresAuth: true
        }
    }
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem("access_token") == null) {
            next({
                path: "/login",
                params: { nextUrl: to.fullPath }
            });
        } else {
            let token = localStorage.getItem("access_token");
            fetch("api/auth/me", {
                method: "post",
                headers: {
                    "content-type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
                .then(res => res.json())
                .then(() => {
                    next();
                })
                .catch(err => {
                    console.log(err);
                    localStorage.removeItem("access_token");
                    next("/login");
                });
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (localStorage.getItem("access_token") == null) {
            next();
        } else {
            next("/");
        }
    } else {
        next();
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component(
    "navbar-component",
    require("./components/NavbarComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router
});
