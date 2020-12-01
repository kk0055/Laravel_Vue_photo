import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

// store/index.js.auth.jsをインポートする
import store from './store'

import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'
import PhotoDetail from './pages/PhotoDetail.vue'

// VueRouterプラグインを使用する
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: PhotoList,
    props: route => {
      const page = route.query.page
      return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    }
  },
  {
    path: '/photos/:id',
    component: PhotoDetail,
    props: true
  },
  {
    path: '/login',
    component: Login,
    //beforeEnter は定義されたルートにアクセスされてページコンポーネントが切り替わる直前に呼び出される関数
    //第三引数 next はページの移動先（切り替わり先）を決めるための関数
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '*',
    component: NotFound
  },
  {
    path: '/500',
    component: SystemError
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode:'history',
  scrollBehavior() {
 return {x: 0, y: 0}
  },
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router