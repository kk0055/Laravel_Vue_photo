<template>
  <div class="container--small">
    <ul class="tab">
      <li
        class="tab__item"
        :class="{'tab__item--active': tab === 1 }"
        @click="tab = 1"
      >Login</li>
      <li
        class="tab__item"
        :class="{'tab__item--active': tab === 2 }"
        @click="tab = 2"
      >Register</li>
    </ul>
<div class="panel" v-show="tab === 1">
  <!-- preventでページリロードを抑制 -->
  <form action="" class="form"  @submit.prevent="login">
   <label for="login-email">Email</label>
    <input type="text" class="form__item" id="login-email" v-model="loginForm.email" >
    <label for="login-password">Password</label>
    <input type="password" class="form__item" id="login-password"  v-model="loginForm.password"  >
    <div class="form__button">
      <button type="submit" class="button button--inverse">Login</button>
    </div>
  </form>
 </div>
<div class="panel" v-show="tab === 2">
    <form class="form" @submit.prevent="register">
    <label for="username">Name</label>
    <input type="text" class="form__item" id="username" v-model="registerForm.name">
    <label for="email">Email</label>
    <input type="text" class="form__item" id="email" v-model="registerForm.email">
    <label for="password">Password</label>
    <input type="password" class="form__item" id="password" v-model="registerForm.password">
    <label for="password-confirmation">Password (confirm)</label>
    <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
    <div class="form__button">
      <button type="submit" class="button button--inverse">Register</button>
    </div>
  </form>
   </div>
  </div>
</template>
<script>

export default {
  data() {
    return {
      tab:1,
      loginForm: {
       email: 'test1@test.com',
       password:'test1@test.com'
      },
      registerForm: {
       name:'test1',
       email: 'test1@test.com',
       password:'test1@test.com',
       password_confirmation:'test1@test.com'

      }
    }
  },
  methods: {
    login() {
      console.log(this.loginForm)
    },
    async register() {
  // authストアのresigterアクションを呼び出す.dispatch メソッドの第一引数はアクションの名前.第二引数にはフォームの入力値を渡している
  await this.$store.dispatch('auth/register', this.registerForm)

  // トップページに移動する
  this.$router.push('/')
    },
  async login () {
  // authストアのloginアクションを呼び出す
  await this.$store.dispatch('auth/login', this.loginForm)

  // トップページに移動する
  this.$router.push('/')
},
  }
}
</script>