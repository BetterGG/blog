<template>
  <div class="app clear ">
    <transition name="slide">
      <nav v-show="show"  class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
          <div class="row">
            <h2 class="navbar-left">我的个人博客</h2>
            <button class="btn btn-default navbar-toggle" data-toggle="collapse"  data-target="#navbar-collapse">
              <span class="sr-only">切换导航</span>
              <span class="fa fa-bars fa-lg"></span>
            </button>
            <div class="collapse navbar-collapse navbar-left" id="navbar-collapse" >
              <ul class="nav navbar-nav">
                <li class="focus"><router-link to="/" activeClass="focus" exact>首页</router-link></li>
                <li><router-link to="/family/1" activeClass="focus" exact>前端技术</router-link></li>
                <li><router-link to="/family/2" activeClass="focus" exact>生活杂文</router-link></li>
                <li><router-link to="/family/3" activeClass="focus" exact>随笔</router-link></li>
                <li><router-link to="/details/351">关于我</router-link></li>
              </ul>
            </div>
            <form class="navbar-form">
              <input type="text" class="form-control" v-model="kw" placeholder="搜点你喜欢的东西吧">
              <a class="search btn btn-info btn-block hidden-lg hidden-md visible-xs visible-sm" @click="search()">搜索</a>
              <a class="search btn btn-info hidden-xs hidden-sm" @click="search()">搜索</a>
              <router-link class="rt admin-entry" to="/admin"><i class="fa fa-arrow-circle-right"></i> 管理员入口</router-link>
            </form>
          </div>
        </div>
      </nav>
    </transition>
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    name: 'HelloWorld',
    data () {
      return {
        navMarginTop: 0,
        show: true,
        kw: ''
      }
    },
    methods: {
      search(){
        let kw = this.kw;
        this.kw = '';
        this.$router.push({path: '/family', query: {kw}})
      }
    },
    mounted(){
      window.onmousewheel = e => {
        if (e.wheelDelta > 0) {
          this.show = true;
        } else {
          this.show = false;
        }
      }
    }
  }
</script>

<style>
  @import "../../../static/css/nav.css";

  .slide-enter-active, .slide-leave-active {
    transition: all .3s linear;
  }
  .slide-enter, .slide-leave-to {
    transform: translateY(-60px);
  }
</style>
