const state = {
  user:null
}

const getters = {}

//ミューテーションの第一引数は必ずステート。ミューテーションを呼び出すときの実引数は仮引数では第二引数以降として渡される
const mutations = {
  setUser (state,user) {
    state.user = user
  }
}


const actions = {
  async register (context,data) {
    //会員登録 API を呼び出し
    const response = await axios.post('/api/register',data)
    //コミットでsetUser ミューテーションを実行することでuser ステートを更新
    context.commit('setUser', response.data)
  },
  async login(context,data) {
    const response = await axios.post('/api/login',data)
    context.commit('setUser', response.data)
  },
  async logout (context) {
    const response = await axios.post('/api/logout')
    context.commit('setUser', null)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}