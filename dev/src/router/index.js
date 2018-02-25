import Vue from 'vue'
import Router from 'vue-router'

import MyHome from '@/components/user/MyHome'
import Index from '@/components/user/Index'
import Details from '@/components/user/Details'
import FamilyArticles from '@/components/user/FamilyArticles'


import Login from '@/components/admin/Login'
import Admin from '@/components/admin/Admin'
import AdminHome from '@/components/admin/AdminHome'
import ArticleAdmin from '@/components/admin/ArticleAdmin'
import CommentAdmin from '@/components/admin/CommentAdmin'
import AdminEdit from '@/components/admin/AdminEdit'
import Drafts from '@/components/admin/AdminDrafts'


Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: MyHome,
      children: [
        {
          path: '/',
          name: 'Index',
          component: Index
        },
        {
          path: '/details/:id',
          name: 'Details',
          component: Details
        },
        {
          path: '/family/:family?',
          name: 'FamilyArticles',
          component: FamilyArticles,
        }
      ]
    },
    {
      path: '/admin',
      component: Admin,
      beforeEnter: (to, from, next) => {
        setTimeout(()=>{
          if(sessionStorage.getItem('data')){
            next();
          }else{
            this.a.app.$router.push('/login');
          }
        },100)
      },
      children: [
        {
          path: '/admin',
          name: 'AdminHome',
          component: AdminHome
        },
        {
          path: '/article',
          name: 'ArticleAdmin',
          component: ArticleAdmin
        },
        {
          path: '/comment',
          name: 'CommentAdmin',
          component: CommentAdmin
        },
        {
          path: '/edit',
          name: 'AdminEdit',
          component: AdminEdit
        },
        {
          path: '/drafts',
          name: 'AdminDrafts',
          component: Drafts
        }
      ]
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '*',
      redirect: '/'
    }

  ]
})
