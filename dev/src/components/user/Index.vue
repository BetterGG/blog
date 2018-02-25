<template>
  <div class="container">
    <div class="row">
      <section class="section-main col-md-8 col-xs-12">
        <h3 class="passage-newest"><span class="newest">最新</span>文章</h3>
        <div class="article-list clearfix">
          <article class="article-item col-lg-6 col-sm-12" v-for="(item,index) in newest_items">
            <router-link class="article-link" :to="'/details/'+item.article_id">
              <img src="../../assets/images/user/1.jpg">
            </router-link>
            <div class="article-meta clearfix">
              <span class="category pull-left">[{{item.family_name}}] [{{item.category_name}}]</span>
              <span class="public-date pull-right">{{item.article_public_date}}</span>
            </div>
            <h4 class="article-title"><router-link :to="'/details/'+item.article_id">{{item.article_title}}</router-link></h4>
            <p class="article-excerpt">{{getText(item.article_text)}}</p>
          </article>
        </div>
      </section>
      <aside class="aside-bar col-md-4 col-xs-12">
        <ul class="aside-articles">
          <li class="article-recommend aside-module">
            <h4>文章<span class="recommend">推荐</span></h4>
            <ul class="recommend-items">
              <li v-for="(item,index) in recommend_items"><router-link :to="{path:'/details/'+item.article_id}">{{item.article_title}}</router-link></li>
            </ul>
          </li>
          <li class="article-top aside-module">
            <h4>文章<span class="top">排行</span></h4>
            <ol class="top-items">
              <li v-for="(item,index) in top_items"><router-link :to="{path:'/details/'+item.article_id}">{{item.article_title}}</router-link></li>
            </ol>
          </li>
          <li class="links aside-module">
            <h4><span class="friendship">技术</span>直达</h4>
            <ul class="friendship-links">
              <li v-for="(item,index) in friendship_links"><a :href="item.link" target="_blank">{{item.name}}</a></li>
            </ul>
          </li>
        </ul>
      </aside>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Header',
    data () {
      return {
        images: [
          '../../src/assets/images/user/1.jpg',
          '../../assets/images/user/2.jpg',
          '../../assets/images/user/3.jpg',
          '../../assets/images/user/4.jpg',
          '../../assets/images/user/5.jpg',
          '../../assets/images/user/6.jpg',
          '../../assets/images/user/7.jpg',
          '../../assets/images/user/8.jpg',
        ],
        newest_items: [],
        recommend_items: [],
        top_items: [],
        friendship_links: [
          {
            name: '百度EUX',
            link: 'http://eux.baidu.com/'
          },{
            name: '腾讯CDC',
            link: 'http://cdc.tencent.com/'
          },{
            name: '淘宝FED',
            link: 'http://taobaofed.org/'
          },{
            name: '阮一峰博客',
            link: 'http://www.ruanyifeng.com/'
          },
        ]
      }
    },
    mounted(){
      this.$ajax({
        url: 'http://127.0.0.1:8080/blog/data/user/loadIndexArticles.php',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        methods: 'get'
      }).then((response)=>{
        let articles = response.data;
        this.recommend_items = articles.article_recommend;
        this.newest_items = articles.article_update_date;
        this.top_items = articles.article_click_count;
      })
    },
    methods: {
      getText (text) {
        const reg1 = /&lt;/g;
        const reg2 = /&gt;/g;
        text = text.replace(reg1, '<').replace(reg2, '>');
        return text;
      }
    }
  }
</script>

<style>
  @import '../../../static/css/index.css';
</style>
