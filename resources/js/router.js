import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

// store/index.js.auth.jsをインポートする
import store from './store'

import SystemError from './pages/errors/System.vue'
import PhotoDetail from './pages/PhotoDetail.vue'

// VueRouterプラグインを使用する
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: PhotoList
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
    path: '/500',
    component: SystemError
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode:'history',
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router