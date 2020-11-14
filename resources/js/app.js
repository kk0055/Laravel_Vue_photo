import './bootstrap'

import Vue from 'vue'
// ルーティングの定義をインポートする
import router from './router'
// ルートコンポーネントをインポートする
import App from './App.vue'
// store/index.js.auth.jsをインポートする
import store from './store'

//アプリ起動時、Vue インスタンス生成前に store/auth.js(index.js)のcurrentUser アクション呼び出し
//
// const createApp = async () => {
//   await store.dispatch('auth/currentUser')

new Vue({
  el: '#app',
  router, // ルーティングの定義を読み込む
  store,//store/index.jsの使用を宣言する
  components: { App }, // ルートコンポーネントの使用を宣言する
  template: '<App />' // ルートコンポーネントを描画する

})
// }
// createApp()