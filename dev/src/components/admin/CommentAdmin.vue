<template>
  <div class="comment-admin admin">
    <div class="tools clear">
      <div class="lt">
        <input type="text" v-model="kw" class="search-box" placeholder="请输入关键字">
        <a class="search-btn" @click="loadComments()">查询</a>
      </div>
      <div class="rt">
        <a class="multi-delete" @click="deleteComment()">批量删除</a>
      </div>
    </div>
    <table>
      <thead>
      <tr>
        <td><input type="checkbox" class="all-check" value="all" v-model="isCheckAll"> 全选</td>
        <td>评论id</td>
        <td>评论文章</td>
        <td>评论人</td>
        <td>内容</td>
        <td>评论时间</td>
        <td>评论人邮箱</td>
        <td>评论人网址</td>
        <td>操作</td>
      </tr>
      </thead>
      <tbody>
      <tr  v-for="(comment,index) in comments">
        <td><input type="checkbox" class="single-check" :value="comment.comment_id" v-model="checked" ></td>
        <td>{{comment.comment_id}}</td>
        <td>{{comment.article_title}}</td>
        <td v-html="comment.comment_person_name"></td>
        <td><p v-html="comment.comment_content"></p></td>
        <td>{{comment.comment_date}}</td>
        <td>{{comment.comment_person_mail}}</td>
        <td>{{comment.comment_person_web}}</td>
        <td>
          <a class="comment-delete" @click="deleteComment(index)">删除</a>
          <router-link :to="'/details/'+comment.article_id" class="article-check">查看</router-link>
        </td>
      </tr>
      </tbody>
    </table>
    <p v-if="!comments.length" class="notFound">空空如也</p>
    <ul class="page clear" v-if="comments.length">
      <li v-if="pno !== 1" @click="changePage(1)" class="fa fa-angle-double-left"></li>
      <li v-if="pno !== 1" @click="pno--"  class="fa fa-angle-left"></li>
      <li v-for="(page,index) in pageCount"  v-if="isPage(index)" :class="{active:page==pno}" @click="changePage(page)">{{page}}</li>
      <li v-if="pno !== pageCount" @click="pno++" class="fa fa-angle-right"></li>
      <li v-if="pno !== pageCount" @click="changePage(pageCount)" class="fa fa-angle-double-right"></li>
    </ul>
  </div>
</template>

<script>
  export default{
    data () {
      return {
        comments: [],
        isCheckAll: false,
        checked: [],
        pno: 1,
        pageCount: '',
        kw: '',
      }
    },
    mounted () {
      this.loadComments();
    },
    methods:{
      loadComments(){
        this.$ajax({
          method: 'get',
          url: 'http://127.0.0.1:8080/blog/data/admin/loadAllComments.php',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          params: {
            pno: this.pno,
            size: 6,
            kw: this.kw,
          }
        }).then((response)=>{
          let comments = response.data;
          if(this.kw){
            console.log('s')
            const reg = new RegExp(`(${this.kw})`, 'ig');
            comments.data.forEach(comment => {
              comment.comment_content = comment.comment_content.replace(reg,"<span style='color:red'>$1</span>")
              comment.comment_person_name = comment.comment_person_name.replace(reg,"<span style='color:red'>$1</span>")
            });
            this.kw = '';
          }
          this.comments = comments.data;
          this.pageCount = comments.pageCount;
        })
      },
      deleteComment(index){
        this.$ajax({
          method: 'post',
          url: 'http://127.0.0.1:8080/blog/data/admin/deleteComment.php',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          params: {
            comment_id: index+1 && this.comments[index].comment_id,
            comment_id_list: this.checked.length && JSON.stringify(this.checked)
          }
        }).then( (data)=>{
          this.loadComments();
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
          this.comments.forEach(comment=>{
            this.checked.push(comment.comment_id)
          })
        }else{
          if(this.checked.length === this.comments.length){
            this.checked = [];
          }
        }
      },
      checked(v){
        if(v.length === this.comments.length){
          this.isCheckAll = true;
        }else{
          this.isCheckAll = false;
        }
      },
      pno() {
        this.loadcomments();
      }
    }
  }
</script>

<style>

</style>
