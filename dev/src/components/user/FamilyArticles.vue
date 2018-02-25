<template>
  <div class="family-essay">
    <ul class="essay-list">
      <li class="essay" v-for="article in articles">
        <h3 class="essay-title" v-html="article.article_title"></h3>
        <p class="essay-meta">
          <span>[ <span class="essay-category">{{article.category_name}}</span> ]</span>
          <span>[ <span class="essay-family">{{article.family_name}}</span> ]</span>
          <span class="essay-date">{{article.article_public_date}}</span>
        </p>
        <p class="essay-text">{{getText(article.article_text)}}</p>
        <p class="essay-entry"><router-link :to="'/details/'+article.article_id">阅读全文>></router-link></p>
      </li>
    </ul>
    <p v-if="!articles.length" class="notFound">您搜索的东西不存在，换个关键字试试吧！</p>
    <div id="page-nav" v-if="articles.length">
      <p class="page-info">第 {{pno}} 页，共 {{pageCount}} 页</p>
      <ul class="pages clearfix">
        <li v-if="pno !== 1" @click="changePage(1)">首页</li>
        <li v-for="(page,index) in pageCount"  v-if="isPage(index)" :class="{active:page==pno}" @click="changePage(page)">{{page}}</li>
        <li v-if="pno !== pageCount" @click="changePage(pageCount)">尾页</li>
      </ul>
    </div>
  </div>
</template>

<script>
    export default{
      data() {
        return {
          family: this.$route.params.family || '',
          kw: this.$route.query.kw,
          articles: [],
          pno: 1,
          pageCount: 0,
        }
      },
      mounted() {
        this.getArticles();
      },
      methods: {
        getArticles() {
          this.$ajax({
            method: 'get',
            url: 'http://127.0.0.1:8080/blog/data/admin/getArticles.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            params: {
              article_family: this.family,
              kw: this.kw,
              article_size: 10,
              pno: this.pno
            }
          }).then((response)=>{
            let articles = response.data;
            if(this.kw){
              const reg = new RegExp(`(${this.kw})`, 'ig');
              articles.data.forEach(article => {
                article.article_title = article.article_title.replace(reg,"<span style='color:red'>$1</span>")
              });
              this.kw = '';
            }
            this.articles = articles.data;
            this.pageCount = articles.pageCount;
          })
        },
        getText (text) {
          const reg1 = /&lt;/g;
          const reg2 = /&gt;/g;
          text = text.replace(reg1, '<').replace(reg2, '>');
          return text;
        },
        // 页码切换
        changePage(page) {
          this.pno = page;
          this.getArticles();
        },
        // 页码显示判断
        isPage(i){
          return !!(this.pno < 3 ? i < 5 : (this.pno > this.pageCount - 3 ? i > this.pageCount - 6 : i >= this.pno - 3 && i< this.pno + 2));
        }
      },
      watch: {
        '$route' () {
          this.family = this.$route.params.family || '';
          this.kw = this.$route.query.kw;
          this.pno = 1;
          this.getArticles();
        }
      }
    }
</script>

<style>
  .family-essay{
    background-color: #F2F2F2;
    overflow: hidden;
  }
  .family-essay .notFound{
    text-align: center;
    line-height: 40px;
    font-size: 16px;
    color: #999;
  }
  @media screen and (min-device-width:768px){
    .essay-list {
      width: 80%;
      margin: 20px auto;
    }
  }
  @media screen and (max-device-width:768px){
    .essay-list {
      width: 100%;
      margin: 20px 0;
    }
  }
  .essay{
    padding: 10px 2%;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 3px;
    box-shadow: -4px 4px 2px 2px rgba(0,0,0,0.5);
  }
  .essay .essay-title{
    margin-bottom: 5px;
  }
  .essay .essay-meta{
    line-height: 40px;
  }
  .essay .essay-meta>span{
    margin-right: 10px;
  }
  .essay .essay-meta .essay-category{
    color: #87CEEB;
  }
  .essay .essay-meta .essay-family{
    color: #F29EC2;
  }
  .essay .essay-meta .essay-date{
    color: #888;
  }
  .essay .essay-text{
    max-height: 4.5em;
    overflow: hidden;
  }
  .essay .essay-entry{
    margin-top: 10px;
    line-height: 30px;
  }
  .essay .essay-entry a{
    color: #e84545;
    transition: all .3s linear;
    cursor: pointer;
  }
  .essay .essay-entry a:hover{
    color: #555;
  }
  /* 分页*/
  #page-nav {
    margin-top: 40px;
  }
  .page-info {
    text-align: center;
    font-size: 17px;
    font-weight: 300;
    color: #aaa;
  }
  #page-nav .pages {
    display: inline-block;
    position: relative;
    left: 50%;
    top: 10px;
    transform: translateX(-50%);
  }
  #page-nav .pages li {
    float: left;
    padding: 10px;
    border-bottom: 2px solid #ccc;
    margin: 0 2px;
    color: #62BBC3;
    font-size: 15px;
    cursor: pointer;
    transition: all .3s linear;
  }
  #page-nav .pages li:hover,
  #page-nav .pages li.active {
    color: #333;
    border-bottom: 2px solid #62BBC3;
    font-weight: 800;
  }
</style>
