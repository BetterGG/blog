// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import store from './store'

import '@/assets/css/bootstrap.css'
import '@/assets/css/font-awesome.css'

import $ from '@/assets/js/jquery'
import '@/assets/js/bootstrap.js'

Vue.prototype.$ajax = axios;
Vue.config.productionTip = false;


if(!sessionStorage.getItem('data')){
  localStorage.setItem('getSession',Date.now());
}
window.addEventListener('storage',(e)=> {
  if (e.newValue === null) {
    return;
  }
  if (e.key === 'getSession') {
    if(sessionStorage.getItem('data')){
      localStorage.setItem('data', sessionStorage.getItem('data'));
      setTimeout(() => localStorage.removeItem('data'), 0)
    }
  }
  if(e.key === 'data'){
    sessionStorage.setItem('data', localStorage.getItem('data'));
    localStorage.removeItem('getSession');
  }
});

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
});
