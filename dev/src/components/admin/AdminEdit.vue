<!--
<template>
  <div class="edit-area">
    <div class="edit-header clear">
      <input type="text" class="edit-title"  :value="title" maxlength="40"  v-model="title" @input="updateArticle()">
      <p class="article-word lt">字数：<span class="word-number">{{text.length || 0}}</span><span class="save-tips">{{tips}}</span></p>
      <div class="edit-info rt">
        <label for="author">作者：</label>
        <input type="text" id="author" class="edit-author" :value="author" v-model="author" @input="updateArticle()">

        <span>分类：</span>
        <select name="family" class="family" v-model="family" @change="updateArticle()">
          <option value="1">前端技术</option>
          <option value="2">生活杂文</option>
          <option value="3">随笔</option>
        </select>
        <select name="category" class="category" v-model="category" @change="updateArticle()">
          <option value="0">转载</option>
          <option value="1">原创</option>
        </select>

        <span>推荐指数：</span>
        <select name="recommend" class="recommend" v-model="recommend" @change="updateArticle()">
          <option v-for="n in 10" :value="n">{{n}}</option>
        </select>
        <a class="public-article" @click="publicArticle()">发布文章</a>
      </div>
    </div>
    <div id="edit-body">

    </div>
-->
    <!--model弹窗--><!--
    <div class="wrap" :style="{height: windowHeight+'px'}" v-show="isAlert">
      <div class="alert">
        <span class="close" @click="isAlert=!isAlert"></span>
        <h2>发布成功</h2>
        <div class="btn-box">
          <router-link to="/drafts">去草稿箱</router-link>
          <a href="javascript:;" @click="isAlert=!isAlert">取消</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default{
        data(){
            return {
              editor: null,
              // 文章信息与提示
              id: null,
              title: '无标题文章',
              author: '宫关',
              family: 1,
              category: 1,
              recommend: 1,
              content: '',
              text: '',
              tips: '',
              // 弹出框
              isAlert: false,
              alertMsg: '',
            }
        },
        beforeMount(){
            let article_id = this.$route.query.id;
            this.$ajax({
              method: 'post',
              url: 'http://127.0.0.1:8080/blog/data/admin/loadArticle.php',
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              params: {
                article_id: article_id
              }
            }).then( (data)=>{
              //草稿箱数量变化
              if(!article_id){
                this.$store.commit('getDraftsCount',++this.$store.state.drafts_count);
              }
              // 数据初始化
              let d = data.data[0];
              this.id = d.article_id;
              this.title = d.article_title;
              this.author = d.article_author;
              this.family = d.article_family;
              this.category = d.article_category;
              this.recommend = d.article_recommend;
              this.content = d.article_content || "";
              this.text = d.article_text || "";
              document.querySelector('.w-e-text').innerHTML = this.content;
            })
        },
        mounted(){
          // 引用
          const E = require('wangeditor');
          // 创建编辑器
          this.editor = new E('#edit-body');
          this.editor.customConfig.zIndex = 100;
          this.editor.customConfig.uploadImgServer = 'http://127.0.0.1:8080/blog/data/admin/uploadImage.php';
          this.editor.customConfig.uploadFileName = 'mypic';
          this.editor.customConfig.onchange = this.updateArticle;
          this.editor.customConfig.uploadImgHeaders = {
            'Accept': 'text/x-json'
          };
          this.editor.customConfig.uploadImgHooks = {
            success: function (xhr, editor, result) {
              // 图片上传并返回结果，图片插入成功之后触发
              // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
              console.log('succ');
              console.log(result);
            },
            fail: function (xhr, editor, result) {
              // 图片上传并返回结果，但图片插入错误时触发
              // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
              console.log('fail');
              console.log(result);
            },
          };
          this.editor.create();
          let editor = document.querySelector('#edit-body .w-e-text-container');
          editor.style.width = '99%'
          editor.style.height = this.windowHeight-214+'px';
        },
        methods: {
            // 更新文章
            updateArticle(){
              this.content = this.editor.txt.html();
              this.text = this.editor.txt.text().replace(/&nbsp;/g,' ');
              this.tips = '自动保存中...';
              this.$ajax({
                method: 'post',
                url: 'http://127.0.0.1:8080/blog/data/admin/updateArticle.php',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                params: {
                  article_id: this.id,
                  article_title: this.title,
                  article_author: this.author,
                  article_family: +this.family,
                  article_category: +this.category,
                  article_recommend: +this.recommend,
                  article_content: this.content,
                  article_text: this.text
                }
              }).then((response)=>{
                let data = response.data;
                this.tips = data.msg;
              })
            },
            // 发布文章
            publicArticle(){
              this.isAlert = true;
              this.$ajax({
                method: 'post',
                url: 'http://127.0.0.1:8080/blog/data/admin/publicArticle.php',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                params: {
                  article_id: this.id
                }
              }).then((response)=>{
                let data = response.data
                if(data.code=200){
                  this.alertMsg = data.msg;
                  this.$store.commit('getDraftsCount',--this.$store.state.drafts_count);
                }
              });
            },
        },
        computed: {
          windowHeight(){
              return window.innerHeight;
          }
        }
    }
</script>

<style>
  .edit-header{
    overflow: hidden;
  }
  .edit-header input,
  .edit-header select{
    outline: none;
  }
  .edit-header .edit-title{
    width: 100%;
    height: 80px;
    padding: 0 5%;
    font-size: 30px;
    color: #555;
    text-align: center;
    border: none;
    border-bottom: 1px solid #ccc;
  }
  .edit-header .edit-info{
    padding: 5px 20px 5px 0;
  }
  .edit-header .article-word{
    position: relative;
    top: 20px;
    left: 5px;
  }
  .edit-header .save-tips{
    margin-left: 35px;
  }
  .edit-header .edit-info .edit-author,
  .edit-header .edit-info .family,
  .edit-header .edit-info .category,
  .edit-header .edit-info .recommend{
    height: 29px;
    border: 1px solid #ccc;
  }
  .edit-header .edit-info .edit-author{
    width: 150px;
    text-indent: 15px;
    border-radius: 5px;
    margin-right: 30px;
  }
  .edit-header .edit-info .family{
    padding: 0 5px;
    border-radius: 5px 0 0 5px;
  }
  .edit-header .edit-info .category{
    padding: 0 5px;
    margin-left: -8px;
    margin-right: 50px;
    border-radius: 0 5px 5px 0;
  }
  .edit-header .edit-info .recommend{
    margin-right: 50px;
    border-radius: 5px;
  }
  .edit-header .edit-info .public-article{
    display: inline-block;
    padding: 4px 8px;
    color: #0080FF;
    border: 1px solid #0080FF;
    border-radius: 5px;
    cursor: pointer;
  }
  .edit-header .edit-info .public-article:hover{
    background-color: #F0F7FD;
  }
  .wrap{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: rgba(0,0,0,0.3);
    z-index: 1000;
  }
  .alert{
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width: 30%;
    height: 30%;
    text-align: center;
    background-color: #fff;
    border-radius: 15px;
  }
  .alert h2{
    margin-top: 70px;
    color: #62C6E8;
  }
  .alert .btn-box{
    position: absolute;
    left: 0;
    right: 0;
    margin: 0 auto;
    bottom: 30px;
  }
  .alert .btn-box a{
    display: inline-block;
    width: 100px;
    padding: 6px 0;
    font-size: 16px;
    border-radius: 20px;
  }
  .alert .btn-box a:first-child{
    background-color: #62C6E8;
    color: #fff;
  }
  .alert .btn-box a:last-child{
    border: 2px solid #62C6E8;
    color: #62C6E8;
    margin-left: 10px;
  }
  #edit-body ul{
    list-style-type: disc;
  }
  #edit-body ol{
    list-style-type: decimal;
  }
</style> -->
