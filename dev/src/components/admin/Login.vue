<template>
  <div class="login-bg" :style="{height:windowHeight+'px'}">
    <div id="form">
      <h1>登录</h1>
      <input v-model="uname" class="uname" type="text" placeholder="用户名">
      <input v-model="upwd" class="upwd" type="password" placeholder="密码">
      <p><input type="checkbox"><span class="remember">记住我</span><span class="tips">{{tips}}</span></p>
      <input @click="login()" type="button" value="登录">
    </div>
  </div>
</template>

<script>
  export default{
    name: 'Login',
    data () {
      return {
        uname: '',
        upwd: '',
        tips: ''
      }
    },
    methods: {
      login(){
        this.$ajax({
          method: 'post',
          url: 'http://127.0.0.1:8080/blog/data/admin/login.php',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          params: {
            uname: this.uname,
            upwd: this.upwd
          },
        }).then(({data}) => {
          if (data.code === 500) {
            this.tips = data.msg;
          }else if(data.code === 200){
            sessionStorage.setItem('data',JSON.stringify(data.info));
            this.$router.push('/admin');
          }
        })
      }
    },
    computed: {
      windowHeight() {
        return window.innerHeight;
      }
    }
  }
</script>

<style>
  @import '../../../static/css/login.css';
</style>
