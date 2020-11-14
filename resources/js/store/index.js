import Vue from 'vue'
import Vuex from 'vuex'

//auth.jsをimport
import auth from './auth'
import error from './error'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error
  }
})

export default store