/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */



require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import VueResource from "vue-resource";


//front views for users

import Login from './components/ExampleComponent.vue';
import NotFound from './components/NotFound.vue';
//authenticated views and js files
import Auth from './components/packages/auth/Auth.js';

Vue.use(VueRouter);
Vue.use(VeeValidate);
Vue.use(Auth);
Vue.use(VueResource);
const router = new VueRouter({
    hashbang:false,
    base:__dirname,
    routes:[
        {
            path:'/make/login',
            component:Login,
            name:'login',
            meta:{forVisitors:true}
        },
        {
            path:'*',
            component:NotFound
        }
    ],
    linkActiveClass:'active',
    mode:'history'

});

router.beforeEach(
    (to,from,next)=> {
    if(to.matched.some(record=>record.meta.forVisitors)){
    if (Vue.auth.isAuthenticated()){
        next({
            name:'welcome'
        })
    }else next()
} else if (to.matched.some(record=>record.meta.forAuth)){
    if (!Vue.auth.isAuthenticated()){
        next({
            name:'login'
        })
    }else next()
}
else next()
});

const app=new Vue({
    el: '#app',
    router
})