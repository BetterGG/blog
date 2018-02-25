<template>
  <div class="drafts-bg clear" :style="{height: windowHeight-50+'px'}">
    <div class="notes">
      <div class="note" v-for="(draft,index) in drafts" :key="index">
        <h4 class="note-header">
          {{draft.article_title}}
          <router-link :to="'/edit?id='+draft.article_id" class="fa fa-edit"></router-link>
        </h4>
        <p class="note-body">{{draft.article_text}}</p>
        <p class="note-footer">
          <a class="draft-delete lt" @click="deleteArticle(draft.article_id)">删除</a>
          <span class="rt">{{draft.article_update_date}}</span>
        </p>
      </div>
      <div class="note-arrow clear" v-if="drafts.length">
        <a class="lt fa fa-arrow-left" @click="prePage()"></a>
        <span class="pageBox">{{pno+'/'+pageCount}}</span>
        <a class="rt fa fa-arrow-right" @click="nextPage()"></a>
      </div>
      <h3 class="drafts-null" v-if="!drafts.length">草稿箱空无一物，写点什么吧</h3>
      <p :class="{'drafts-tips': true, active: hasTips}">{{tips}}</p>
    </div>
  </div>
</template>

<script>
    export default{
      data(){
        return {
          pno: 1,
          draftSize: 6,
          pageCount: 0,
          drafts: [],
          tips: '131654654',
          hasTips: false,
          tipsTimer: null
        }
      },
      mounted(){
        this.loadDrafts();
      },
      methods: {
        prePage(){
          if(this.pno>1){
            this.pno--;
            this.loadDrafts();
          }else{
            this.showTips('这是第一页，别再点了');
          }
        },
        nextPage(){
          if(this.pno !== this.pageCount){
              this.pno++;
              this.loadDrafts();
          }else{
              this.showTips('已经是最后一页了');
          }
        },
        loadDrafts(){
          this.$ajax({
            method: 'get',
            url: 'http://127.0.0.1:8080/blog/data/admin/loadDrafts.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            params: {
              pno: this.pno,
              draftSize: this.draftSize
            }
          }).then((response)=>{
            let drafts = response.data;
            this.drafts = drafts.data;
            this.pageCount = drafts.pageCount;
          })
        },
        deleteArticle(id){
          this.$ajax({
            method: 'post',
            url: 'http://127.0.0.1:8080/blog/data/admin/deleteArticle.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            params: {
              article_id: id
            }
          }).then( (response)=>{
            this.$store.commit('getDraftsCount',--this.$store.state.drafts_count)
            this.loadDrafts();
          })
        },
        showTips(tips){
          this.tips = tips;
          this.hasTips = true;
          if(this.tipsTimer){
            clearTimeout(this.tipsTimer)
          }
          this.tipsTimer = setTimeout(()=>{
            this.hasTips = false;
          }, 2000);
        }
      },
      computed: {
        windowHeight(){
          return window.innerHeight;
        }
      }
    }
</script>

<style>
  .drafts-bg{
    background: url('../../assets/images/admin/bg2.jpg') -200px 0 no-repeat;
    background-size: 120% 100%;
    border: 1px solid transparent;
  }
  .notes{
    position: relative;
    margin: 5% 0 0 28%;
    height: 90%;
  }
  .note{
    float: left;
    position: relative;
    width: 30%;
    height: 38%;
    padding: 0 5px;
    margin: 2% 3% 3% 0;
    background-color: #E8F9EF;
    border-radius: 4px;
    box-shadow: -4px 3px 5px 5px rgba(0,0,0,0.6);
  }
  .note .fa-edit{
    position: absolute;
    top: 2%;
    right: 2%;
    font-size: 1.5em;
    color: #36B0C8;
    transition: all .3s linear;
  }
  .note .fa-edit:hover{
    color: #000;
    transform: scale(1.2);
  }
  .note .note-header{
    height: 13%;
    padding: 0 6% 0 0;
    border-bottom: 1px solid #ccc;
    text-align: center;
    line-height: 2em;
    font-size: 16px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
  }
  .note .note-body{
    max-height: 66%;
    margin-top: 10px;
    text-indent: 2em;
    font-size: 15px;
    line-height: 1.5em;
    overflow: hidden;
  }
  .note .note-footer{
    position: absolute;
    bottom: 3%;
    left: 0;
    width: 100%;
    padding: 0 10px;
    color: #999;
  }
  .note-footer .draft-delete{
    display: none;
  }
  .note-footer .draft-delete:hover{
    text-decoration: underline;
  }
  .note:hover .draft-delete{
    color: #f00;
    display: inline;
    cursor: pointer;
  }
  .drafts-bg .note-arrow{
    position: absolute;
    bottom: 3%;
    left: 50%;
    transform: translateX(-50%);
    width: 40%;
  }
  .drafts-bg .note-arrow a{
    font-size: 50px;
    color: #ccc;
    opacity: 0.3;
    cursor: pointer;
  }
  .drafts-bg .note-arrow a:hover{
    opacity: 0.6;
  }
  .pageBox{
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: 15px;
    font-size: 18px;
    color: #fff;
    opacity: 0.6;
  }
  .drafts-tips{
    position: absolute;
    top: 46%;
    left: 50%;
    color: #F8F840;
    font-size: 18px;
    transform: translate(-50%,100%);
    opacity: 0;
    transition: all .5s linear;
  }
  .drafts-tips.active{
    transform: translate(-50%,-100%);
    opacity: 1;
  }
  .drafts-null{
    position: absolute;
    left: 50%;
    top: 20%;
    transform: translateX(-50%);
    color: #fff;
    opacity: 0.6;
  }
</style>
