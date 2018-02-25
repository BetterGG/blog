<template>
  <div class="article-admin admin">
    <div class="tools clear">
      <div class="lt">
        <input type="text" v-model="kw" class="search-box" placeholder="请输入关键字">
        <a class="search-btn" @click="loadArticles()">查询</a>
      </div>
      <div class="rt">
        <a class="multi-delete" @click="deleteArticle()">批量删除</a>
        <router-link to="/edit" class="add-article">新增文章</router-link>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <td><input type="checkbox" class="all-check" value="all" v-model="isCheckAll"> 全选</td>
          <td>文章id</td>
          <td>标题</td>
          <td>类别</td>
          <td>来源</td>
          <td>推荐指数</td>
          <td>点击次数</td>
          <td>发布时间</td>
          <td>是否显示</td>
          <td>操作</td>
        </tr>
      </thead>
      <tbody>
        <tr  v-for="(article,index) in articles">
          <td><input type="checkbox" class="single-check" :value="article.article_id" v-model="checked" ></td>
          <td>{{article.article_id}}</td>
          <td v-html="article.article_title"></td>
          <td>{{article.family_name}}</td>
          <td>{{article.category_name}}</td>
          <td>{{article.article_recommend}}</td>
          <td>{{article.article_click_count}}</td>
          <td>{{article.article_update_date}}</td>
          <td>
            <div class="toggle-button-wrapper">
              <div  class="button-label" @click="isShowToggle($event,index)" :class="{articleShow: +article.article_show}">
                <span class="circle"></span>
                <span class="text on">ON</span>
                <span class="text off">OFF</span>
              </div>
            </div>
          </td>
          <td>
            <router-link :to="'/edit?id='+article.article_id" class="article-edit">编辑</router-link>
            <a class="article-delete" @click="deleteArticle(index)">删除</a>
            <router-link :to="'/details/'+article.article_id" class="article-check">查看</router-link>
          </td>
        </tr>
      </tbody>
    </table>
    <p v-if="!articles.length" class="notFound">您搜索的东西不存在，换个关键字试试</p>
    <ul class="page clear">
      <li v-if="articles.length && pno !== 1" @click="changePage(1)" class="fa fa-angle-double-left"></li>
      <li v-if="articles.length && pno !== 1" @click="pno--"  class="fa fa-angle-left"></li>
      <li v-for="(page,index) in pageCount"  v-if="isPage(index)" :class="{active:page==pno}" @click="changePage(page)">{{page}}</li>
      <li v-if="articles.length && pno !== pageCount" @click="pno++" class="fa fa-angle-right"></li>
      <li v-if="articles.length && pno !== pageCount" @click="changePage(pageCount)" class="fa fa-angle-double-right"></li>
    </ul>
  </div>
</template>

<script>
  export default{
    data () {
      return {
        articles: [],
        isCheckAll: false,
        checked: [],
        pno: 1,
        pageCount: 1,
        kw: '',
      }
    },
    mounted () {
     this.loadArticles();
    },
    methods:{
      loadArticles(){
        this.$ajax({
          method: 'get',
          url: 'http://127.0.0.1:8080/blog/data/admin/getArticles.php',
          params: {
            pno: this.pno,
            article_size: 10,
            kw: this.kw,
          }
        }).then( (response) =>{
          let articles = response.data;
          if(this.kw){
            const reg = new RegExp(`(${this.kw})`, 'ig');
            articles.data.forEach(article => {
              article.article_title = article.article_title.replace(reg, "<span style='color:red'>$1</span>")
            });
            this.kw = '';
          }
          this.articles = articles.data;
          this.pageCount = articles.pageCount;
        });
      },
      deleteArticle(index){
        this.$ajax({
          method: 'post',
          url: 'http://127.0.0.1:8080/blog/data/admin/deleteArticle.php',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          params: {
            article_id: index+1 && this.articles[index].article_id,
            article_id_list: this.checked.length && JSON.stringify(this.checked)
          }
        }).then( (data)=>{
          this.loadArticles();
        })
      },
      isShowToggle(e,index){
        this.articles[index].article_show = !Number(this.articles[index].article_show);
        this.$ajax({
          method: 'post',
          url: 'http://127.0.0.1:8080/blog/data/admin/toggleShow.php',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          params: {
            article_id: this.articles[index].article_id,
            article_show: this.articles[index].article_show
          }
        }).then(()=>{
           this.loadArticles();
        })
      },
      // 页码切换
      changePage(page) {
        this.pno = page;
      },
      // 页码显示判断
      isPage(i){
        return !!(this.pno < 3 ? i < 5 : (this.pno > this.pageCount - 3 ? i > this.pageCount - 6 : i >= this.pno - 3 && i< this.pno + 2));
      }
    },
    watch:{
      isCheckAll(v){
        if(v){
          this.checked = [];
          this.articles.forEach(article=>{
            this.checked.push(article.article_id)
          })
        }else{
          if(this.checked.length === this.articles.length){
            this.checked = [];
          }
        }
        },
      checked(v){
        if(v.length === this.articles.length){
          this.isCheckAll = true;
        }else{
          this.isCheckAll = false;
        }
        },
      pno() {
        this.loadArticles();
      }
    }
  }
</script>
