import './bootstrap'

import Vue from 'vue'
// ルーティングの定義をインポートする
import router from './router'
// ルートコンポーネントをインポートする
import App from './App.vue'
// store/index.jsをインポートする
import store from './store'

new Vue({
  el: '#app',
  router, // ルーティングの定義を読み込む
  store,//store/index.jsの使用を宣言する
  components: { App }, // ルートコンポーネントの使用を宣言する
  template: '<App />' // ルートコンポーネントを描画する

})