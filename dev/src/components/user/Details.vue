<template>
  <div class="article-details clear">
    <div class="article-body lt">
      <article v-if="article">
        <h2>{{article.article_title}}</h2>
        <div class="article-bar clear">
          <div class="article-meta lt">
            <span class="article-category">{{article.category_name}}</span>
            <span>分类：[ <span class="article-family">{{article.family_name}}</span> ]</span>
            <span>作者：<span class="article-author">{{article.article_author}}</span></span>
          </div>
          <span class="public-date rt">{{article.article_public_date}}</span>
        </div>
        <div class="article-content" v-html="article.article_content"></div>
      </article>
       <div class="article-comment" ref="commentArea">
         <h4>{{comments.length}}条评论</h4>
         <div class="comment-form" ref="commentForm">
           <p class="comment-title">发表你的看法：</p>
           <p><input type="text" v-model="name"><span>名字(必填)</span></p>
           <p><input type="text" v-model="mail"><span>邮箱地址(必填，非公开)</span></p>
           <p><input type="text" v-model="web"><span>个人网址(选填)</span></p>
           <h5>您的看法:</h5>
           <textarea v-model="content"></textarea>
           <p>
             <button @click="sendComment($event)">发表</button>
             <span v-show="tipsShow" :style="{color: tipsColor}">{{tips}}</span>
           </p>
         </div>
         <ul class="floors">
           <li v-for="(comment,index) in comments">
             <div class="floor" :id="comment.data.comment_id">
               <div class="comment">
                 <div class="comment-header">
                   <p class="observer">{{comment.data.comment_person_name}}</p>
                   <p class="comment-date">{{++index+'楼 - '+comment.data.comment_date}}</p>
                 </div>
                 <div class="comment-body">
                   {{comment.data.comment_content}}
                 </div>
                 <div class="comment-footer">
                   <a class="reply-btn" @click="reply($event,comment.data.comment_id)">回复</a>
                 </div>
               </div>
               <ul class="inner-floors" v-if="comment.replys">
                 <li class="inner-floor" v-for="reply in comment.replys">
                   <p class="reply">
                     <span>{{reply.reply_person_name}}</span>：
                     <span v-if="reply.reply_to">{{'@'+reply.reply_to}}</span>
                     {{reply.reply_content}}
                   </p>
                   <p class="reply-date">
                     <span class="reply-date">{{reply.reply_date}}</span>
                     <a class="inner-reply-btn" @click="replyTo($event,comment.data.comment_id,reply.reply_person_name)">回复</a>
                   </p>
                 </li>
                 <p class="loadMore"
                    v-if="comment.count>3"
                    @click="loadMoreReplys($event,index-1,comment.data.comment_id)">
                   加载更多...
                 </p>
               </ul>
             </div>
           </li>
         </ul>
       </div>
    </div>
    <div class="aside lt">
      <div class="introduce">
        <h4>关于博主</h4>
        <p>
          <i class="fa fa fa-envelope-o"></i>
          <span>邮箱：</span>
          <span class="introduce-info">&nbsp;&nbsp;&nbsp;&nbsp;15770529960@163.com</span></p>
        <p class="weibo">
          <a href="https://weibo.com/u/5453475506?refer_flag=1001030102_&ssl_rnd=1510043248.0315">
            <i class="fa fa fa-weibo"></i>
            <span>微博：</span>
            <span class="introduce-info">AnaniZhu</span>
          </a>
        </p>
        <p><i class="fa fa-user-o"></i><span>简介:</span></p>
        <p>宫关，九五后，IT男，喜欢文学、游戏，在前端路上磕磕绊绊，但怀揣梦想的那颗心却从来没有动摇，我希望有一天，未来的女儿大眼睛里充满这惊喜，粉嘟嘟的小手指着五彩斑斓的屏幕，我能笑着答：这是爸爸做的。</p>
      </div>
      <div class="calender" ref="calender"></div>
    </div>
    <a :class="toTopClass" title="回到顶部" @click="toTop()" ></a>
  </div>
</template>

<script>
    export default{
      data(){
        return {
          article_id: '',
          article: {},
          // 返回顶部
          toTopClass: {
            toTop: true,
            fa: true,
            'fa-angle-double-up': true,
            toTopIsShow:false
          },
          // 发布评论
          comments: {},
          isLoadComments: false,
          name: '',
          mail: '',
          web: '',
          content: '',
          // 评论、回复 判断
          comment_id: null,
          reply_id: null,
          reply_to: '',
          // 评论结果
          tipsShow: false,
          tips: '',
          tipsColor: ''
        }
      },
      created(){
        this.article_id = this.$route.params.id;
        // 获取文章
        this.$ajax({
          method: 'get',
          url: 'http://127.0.0.1:8080/blog/data/admin/loadArticle.php',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          params: {
            article_id: this.article_id
          }
        }).then((response)=>{
          const _this = this;
          let article = response.data;
          this.article = article[0];
          let commentTop = this.getTop(this.$refs.commentArea);
          // 回到顶部按钮监控
          window.onscroll = function(e){
            let scrollTop = document.body.scrollTop ? document.body.scrollTop : document.documentElement.scrollTop;

            if (scrollTop > 300) {
              _this.toTopClass.toTopIsShow = true;
            } else {
              _this.toTopClass.toTopIsShow = false;
            }
            if (scrollTop > commentTop - window.innerHeight && !_this.isLoadComments) {
              _this.isLoadComments = true;
              _this.loadComments();
            }
          };
        });
      },
      mounted(){
        //创建日历
        this.calender(this.$refs.calender)
      },
      methods: {
        // 返回顶部-运动
        toTop(){
          this.to(0,4);
        },
        // 加载评论
        loadComments(){
          this.$ajax({
            methods: 'get',
            url: 'http://127.0.0.1:8080/blog/data/user/loadComments.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            params: {
              article_id: this.article_id
            }
          }).then(({data})=>{
            this.comments = data;
          })
        },
        // 发送评论
        sendComment(event){
          let article_id = this.$route.params.id;
          if( !this.name || !this.mail || !this.content ){
            this.tipsColor = 'red';
            this.tips = '必填项不能为空';
            this.displayTips();
          }else {
            this.$ajax({
              method: 'post',
              url: 'http://127.0.0.1:8080/blog/data/user/sendComment.php',
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              params: {
                article_id: this.article_id,
                comment_id: this.comment_id,
                reply_to: this.reply_to,
                name: this.name,
                mail: this.mail,
                web: this.web,
                content: this.content
              }
            }).then((response) => {
              let result = response.data;
              const commentForm = this.$refs.commentForm;
              const commentArea = this.$refs.commentArea;
              const floors = commentArea.querySelector('.floors');
              // 发送结果提示
              if (result.code === 500) {
                this.tipsColor = 'red';
              } else {
                this.tipsColor = '#6CBE83';
                //清空评论数据
                this.content = '';
              }
              this.tips = result.msg;
              this.displayTips();
              // 页面数据更新
              this.loadComments();
              // 页面数据重置
              commentArea.insertBefore(commentForm, floors);
              this.comment_id = '';
            })
          }
        },
        // 回复评论
        reply(event,comment_id){
          let commentForm = this.$refs.commentForm;
          let innerFloors = event.target.parentNode.parentNode.nextElementSibling;
          innerFloors.insertBefore(commentForm,innerFloors.firstChild);
          this.reply_to = '';
          this.comment_id = comment_id;
        },
        // 楼中楼回复
        replyTo(event,comment_id,replyTo){
          let commentForm = this.$refs.commentForm;
          let innerFloors = event.target.parentNode.parentNode.parentNode;
          let targetElement = event.target.parentNode.parentNode.nextElementSibling;
          innerFloors.insertBefore(commentForm,targetElement);
          this.reply_to = replyTo;
          this.comment_id = comment_id;
        },
        // 加载更多评论
        loadMoreReplys(event,index,comment_id){
          const e = event.target;
          let hasLoadMore = e.hasLoadMore;
          let comment = this.comments[index];
          if(!hasLoadMore){
            this.$ajax({
              method: 'get',
              url: 'http://127.0.0.1:8080/blog/data/user/loadMoreReplys.php',
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              params:{
                comment_id
              }
            }).then((response)=>{
              let replys = response.data;
              e.hasLoadMore = true;
              e.isOpen = true;
              comment.otherReplys = replys;
              // 收起
              this.comments[index].replys.push(...comment.otherReplys);
              e.innerHTML = '收起';
            });
          }else{
            if( e.isOpen ){
              this.comments[index].replys = this.comments[index].replys.slice(0,3);
              e.innerHTML = '展开';
            }else{
              this.comments[index].replys.push(...comment.otherReplys);
              e.innerHTML = '收起';
            }
            e.isOpen = !e.isOpen;
          }
        },
        displayTips(){
          this.tipsShow = true;
          setTimeout(()=>{
            this.tipsShow = false;
          },3000);
        },
        getTop(el){
          var top = el.offsetTop;
          while( el.offsetParent ){
            el = el.offsetParent;
            top += el.offsetTop;
          }
          return top;
        },
        to(end, rate, callback) {

          if(!window.requestAnimationFrame){
            requestAnimationFrame = function(fn) {
              setTimeout(fn, 17);
            };
          }
          //头判断
          end += 1;
          document.body.scrollTop += 1;
          document.documentElement.scrollTop += 1;
          var doc =  document.body.scrollTop ? document.body : document.documentElement;

          var pos = doc.scrollTop;
          function step(){
            pos = pos + ( end - pos )/ (rate || 2);
            doc.scrollTop = pos;
            if( Math.abs(end-pos) < 1 ){
              callback && callback();
              return;
            }
            requestAnimationFrame(step);
          }
          step();
        },
        calender(container){
          function isLeapYear(year){
            if( year%4==0 && year%100!==0 || year%400==0){
              return true;
            }
            return false;
          }

          function getDays(year,month){
            switch(month){
              case 1:
              case 3:
              case 5:
              case 7:
              case 8:
              case 10:
              case 12:
                return 31;
                break;
              case 4:
              case 6:
              case 9:
              case 11:
                return 30;
                break;
              case 2:
                return isLeapYear(year) ? 29 : 28;

            }
          }

          function getNextDate(year,month){
            var m,y;
            if(month==12){
              m = 1;
              y = year+1;
            }else{
              m = month+1;
              y = year;
            }
            return {m,y};
          }
          function getPreDate(year,month){
            var m,y;
            if(month==1){
              m = 12;
              y = year-1;
            }else{
              m = month-1;
              y = year;
            }
            return {m,y};
          }


          function getBoxDate(){
            var cDate = document.getElementsByClassName("currentDate")[0];

            var date =  cDate.innerHTML.split("-");

            var y = Number(date[0]);

            var m = Number(date[1]);

            return {y,m};
          }

          function createCalender(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();

            var preM,preY;

            preY = getPreDate(y,m).y;
            preM = getPreDate(y,m).m;

            var pd  = getDays(preY,preM);

            var html =
              `
				<table>
					<thead>
						<tr>
							<td colspan="2"><a class="preMonth"><</a></td>
							<td colspan="3" class="currentDate">${y}-${m}</td>
							<td colspan="2"><a class="nextMonth">></a></td>
						</tr>
						<tr>`;

            for(let i=0;i<7;i++){
              html += "<td>"+arr[i]+"</td>"
            }

            html +=	`</tr></thead>`

            var w = date.getDay();

            var num = 0;
            var days = getDays(y,m);

            var start = w - d%7 + 1;
            start = start >= 0 ? start : start+7;
            var end = start + days;


            for(let i=0;i<6;i++){
              html += "<tbody><tr>";
              for(let j=0;j<7;j++){
                if(num>=start && num <end){
                  if(num-start+1 == d){
                    if(m == new Date().getMonth()+1){
                      html += "<td class='today remainDays'>"+(num-start+1)+"</td>"
                    }else{
                      html += "<td>1</td>"
                    }
                  }else if(num-start+1 > d){
                    html += "<td class='remainDays'>"+(num-start+1)+"</td>"
                  }else{
                    html += "<td>"+(num-start+1)+"</td>"
                  }
                }else if(num<start){
                  html += "<td style='color:#ccc'>"+(pd-start+1+num)+"</td>"
                }else{
                  html += "<td style='color:#ccc'>"+(num-end+1)+"</td>"
                }
                num++;
              }
              html += "</tr>";
            }
            html += "</tbody></table";
            container.innerHTML = html;

            //next month
            document.getElementsByClassName("nextMonth")[0].onclick = function(){

              var date = getBoxDate();
              var newDate = new Date();
              var nextDate = getNextDate(date.y,date.m);

              var d = 1;
              var nextDate=getNextDate(date.y,date.m);

              if(  nextDate.y==newDate.getFullYear() && nextDate.m==newDate.getMonth()+1 ){
                d = newDate.getDate();
                console.log("equal")
              }


              var nDate = new Date( `${nextDate.y}-${nextDate.m}-${d}`)

              createCalender(nDate);

            }
            // previous month
            document.getElementsByClassName("preMonth")[0].onclick = function(){

              var date = getBoxDate();
              var newDate = new Date();
              var d = 1;
              var preDate=getPreDate(date.y,date.m);

              if(  preDate.y==newDate.getFullYear() && preDate.m==newDate.getMonth()+1 ){
                d = newDate.getDate();
              }


              var pDate = new Date( `${preDate.y}-${preDate.m}-${d}`)

              createCalender(pDate);

            }
          }

          var arr = ["日","一","二","三","四","五","六",]
          var cDate = new Date();

          createCalender(cDate);
        }
      }
    }
</script>

<style>
  .article-details{
    margin-top: -30px;
    padding-top: 20px;
    background-color: #EFEFEF;
  }

  @media screen and (min-device-width:768px){
    .article-details .article-body{
      width: 60%;
      margin: 20px 5% 20px 10%;
      box-shadow: 0 2px 4px 0 rgba(0,0,0,0.05);
    }
    .article-details .aside{
      width: 20%;
    }
    .article-details .article-comment .comment-form p{
      width: 60%;
    }
  }
  @media screen and (max-device-width:767px) {
    .article-details .article-body,
    .article-details .aside{
      margin: 20px 5%;
      width: 90%;
    }
    .article-details .article-comment .comment-form p{
      width: 100%;
    }
  }
  .article-details article,
  .article-comment{
    background-color: #fff;
  }
  .article-details article{
    margin-bottom: 20px;
  }
  article>h2{
    padding: 20px 20px 0 20px;
    text-align: center;
  }
  .article-details .article-bar{
    color: #888;
    border-bottom: 1px solid #e5e5e5;
    font-size: 14px;
    line-height: 38px;
    padding: 20px 20px 8px 20px;
    margin-top: 5px;
  }
  .article-details .article-bar .article-meta>span{
    margin-right: 20px;
  }
  .article-details .article-bar .article-category{
    border: 1px solid #e4ebf4;
    padding: 2px 6px;
    font-size: 14px;
    color: #78909c;
  }
  .article-details .article-bar .article-family{
    color: #F29EC2;
  }
  .article-details .article-bar .article-author{
    font-size: 16px;
    color: #000;
  }
  .article-details .article-bar .public-date{
    color: #bbb;
    font-size: 14px;
  }
  .article-details .article-content{
    padding: 20px;
    font-size: 16px;
    color: #666;
  }
  /*文章内容样式重置*/
  .article-details .article-content h1,
  .article-details .article-content h2,
  .article-details .article-content h3,
  .article-details .article-content h4,
  .article-details .article-content h5,
  .article-details .article-content h6,
  .article-details .article-content p{
    margin: 10px 0;
    line-height: 1.5;
  }

  .article-details .article-content h4,
  .article-details .article-content h5,
  .article-details .article-content h6{
    font-weight: 800;
  }
  .article-details .article-content p,
  .article-details .article-content ul,
  .article-details .article-content ol{
    font-size: 15px;
  }
  .article-details .article-content ul,
  .article-details .article-content ol{
    margin: 10px 0 10px 20px;
  }
  .article-details .article-content ul{
    list-style: disc;
  }
  .article-details .article-content ol{
    list-style-type: decimal;
  }
  /*文章评论区*/
  .article-details .article-comment h4{
    font-weight: bold;
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
  }
  /*发表评论框*/
  .article-details .article-comment .comment-form{
    padding: 20px;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    margin-bottom: 20px;
  }
  .article-details .article-comment .comment-form .comment-title{
    font-size: 18px;
    font-weight: 500;
  }
  .article-details .article-comment .comment-form p{
    padding: 0 20px 20px 0;
  }
  .article-details .article-comment .comment-form h5{
    margin-bottom: 10px;
  }
  .article-details .article-comment .comment-form input,
  .article-details .article-comment .comment-form textarea{
    border: 1px solid #87CEEB;
    outline: none;
  }
  .article-details .article-comment .comment-form input{
    width: 60%;
    height: 35px;
    margin-right: 15px;
  }
  .article-details .article-comment .comment-form textarea{
    width: 80%;
    height: 200px;
  }
  .article-details .article-comment .comment-form button{
    min-width: 100px;
    height: 40px;
    padding: 5px;
    border: 1px solid #ccc;
    margin-top: 10px;
    background-color: #fff;
    outline: none;
  }
  .article-details .article-comment .comment-form button:hover{
    background-color: #f0f0f0;
  }
  /*评论楼层*/
  .article-details .article-comment .floors{
    padding: 20px;
  }
  .article-details .article-comment .floor{
    padding: 5px 5px 10px 10px;
    border: 1px solid #ddd;
    margin-bottom: 20px;
  }
  .article-details .article-comment .floor .comment-date{
    font-size: 12px;
    color: #969696;
  }
  .article-details .article-comment .floor .comment-body{
    margin: 20px 0;
    font-size: 16px;
  }
  /*评论楼中楼*/
  .article-details .article-comment .floor .reply-btn,
  .article-details .article-comment .floor .inner-floor .inner-reply-btn{
    color: #3194d0;
    cursor: pointer;
  }
  .article-details .article-comment .floor .reply-btn:hover,
  .article-details .article-comment .floor .inner-floor .inner-reply-btn:hover{
    text-decoration: underline;
  }
  .article-details .article-comment .floor .inner-floors{
    margin-top: 20px;
    padding: 5px 0 5px 0;
    background-color: #f9f9f9;
    border-left: 2px solid #d9d9d9;
  }
  .article-details .article-comment .floor .inner-floor{
    padding: 15px 0 15px 20px;
    border-bottom: 1px dashed #ddd;
  }
  .article-details .article-comment .floor .inner-floor .reply{
    margin-bottom: 5px;
  }
  .article-details .article-comment .floor .inner-floor .reply span{
    color: #3194d0;
  }
  .article-details .article-comment .floor .inner-floor .reply+p{
    color: #969696;
  }
  .article-details .article-comment .floor .inner-floor .reply-date{
    margin-right: 20px;
    font-size: 12px;
  }
  .article-details .article-comment .floor .inner-floor .inner-reply-btn{

  }
  .article-details .article-comment .loadMore{
    line-height: 40px;
    text-align: center;
    background-color: #fff;
    border: 1px solid #ccc;
    cursor: pointer;
  }
  .article-details .article-comment .loadMore:hover{
    background-color: #f5f5f5;
    color: #2e6da4;
  }
  /*侧边栏*/
  .article-details .aside{
    margin-top: 20px;
  }
  /*个人简介*/
  .article-details .aside .introduce{
    background-color: #FAFCFD;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,0.05);
    padding: 5%
  }
  .article-details .aside .introduce h4{
    margin-bottom: 10px;
  }
  .article-details .aside .introduce .weibo .fa-weibo{
    color: #D32024;
  }
  .article-details .aside .introduce .weibo .fa-weibo:hover{
    text-decoration: underline;
  }
  .article-details .aside .introduce p span{
    margin: 0 5% 0 3%;
  }
  .article-details .aside .introduce p span+.introduce-info{
    color: #b3b3b3;
  }
  .article-details .aside .introduce p:last-child{
    text-indent: 2em;
    color: #b3b3b3;
  }
  /*日历*/
  .article-details .aside .calender{
    margin: 20px 0;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,0.05);
  }
  .article-details .aside .calender table{
    background-color: #fff;
    text-align: center;
    margin: 0 auto;
  }
  .article-details .aside .calender .today{
    background-color: #87CEEB;
    color: #fff;
  }
  .article-details .aside .calender thead a{
    display: inline-block;
    width: 100%;
    height: 100%;
    cursor: pointer;
    transition: all .3s linear;
  }
  .article-details .aside .calender thead td:first-child a:hover{
    transform: scale(1.5);
  }
  .article-details .aside .calender thead td:last-child a:hover{
    transform: scale(1.5);
  }
  .article-details .aside .calender table td{
    height: 30px;
    padding: 5px 11px;
  }
  /*回到顶部*/
  .article-details .toTop{
    position: fixed;
    right: 30px;
    bottom: 30px;
    display: inline-block;
    width: 50px;
    height: 50px;
    text-align: center;
    line-height: 50px;
    background-color: #fff;
    cursor: pointer;
    font-size: 25px;
    opacity: 0;
    transform: translateX(150%);
    transition: all .5s linear;
  }
  .article-details .toTop.toTopIsShow{
    transform: translateX(0);
    opacity: 1;
  }
</style>
