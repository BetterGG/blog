<template>
 <div class="clear">
   <div id="side-menu" class=lt :style="{height:clientHeight+'px'}">
     <h2>Annani</h2>
     <ul class="menu">
       <li v-for="(list,index) in sideMenu" :class="list.className" :key="'list'+index">
         <router-link :to="list.link" tag="h3" activeClass="active" exact>
           <i :class="list.iconClass"></i>
           <span>{{list.title}}</span>
           <i v-if="list.children" class="fa fa-angle-down"></i>
         </router-link>
         <ul v-if="list.children">
           <router-link
             v-for="(item,index) in list.children"
             :key="'item'+index"
             :to="item.link"
             tag="li"
             activeClass="show" exact>
               <i :class="item.iconClass"></i>
               <span>{{item.name}}</span>
               <span v-if="item.name==='草稿箱'" class="msg-tips rt">{{count}}</span>
           </router-link>
         </ul>
       </li>
     </ul>
   </div>
   <div id="body" class="lt" :style="{height:clientHeight+'px'}">
     <div id="top-bar">
       <div class="music-player lt">
         <audio id="player">
           您的浏览器不支持html5播放器
         </audio>
         <div class="player-wrap clear">
           <div class="player-controls lt">
             <a class="menu-btn fa fa-navicon" @click="musicListToggle()"></a>
             <a class="prev-btn fa fa-backward" @click="preMusic()"></a>
             <a
               @click = changePlayer();
               :class="{
                 'main-btn': true,
                 fa: true,
                 'fa-pause': playerInfo.isPlaying,
                 'fa-play': !playerInfo.isPlaying
               }"
             ></a>
             <a class="next-btn fa fa-forward" @click="nextMusic()"></a>
           </div>
           <div class="player-info lt">
             <p class="track-info">{{playerInfo.name}}</p>
             <div class="player-length">
               <div class="player-position">{{playerInfo.currentTime}}</div>
               <div
                 class="player-progress"
                 id="progress"
                 @click="changeProgress($event)"
               >
                 <div class="playing"
                   :style="{width: playerInfo.curProgress}"
                 ></div>
                 <div class="player-dot"
                   :style="{left: playerInfo.curProgress}"
                 ></div>
               </div>
               <div class="player-time">{{playerInfo.totalTime}}</div>
             </div>
           </div>
           <div class="player-volume lt">
             <div class="fa fa-volume-up"></div>
             <div class="volume-panel" id="volume" @click="changeVolume($event)">
               <div class="volume-cur"
                 :style="{width: playerInfo.volume}"
               ></div>
               <div
                 class="volume-dot"
                 :style="{left: playerInfo.volume}"
               ></div>
             </div>
           </div>
         </div>
         <div :class="{'player-list':true,active:playerInfo.musicListShow}">
           <input type="text" class="music-search" placeholder="搜点你喜欢的歌曲">
           <ul class="music-list">
             <li v-for="(music,index) in musicList"
                 :class="{active:index===playerInfo.index}"
                 @click="changeMusic(index)"
             >
               <i class="fa fa-music"></i>
               <span class="music-name">{{music.name}}</span>
             </li>
           </ul>
         </div>
       </div>
       <div class="top-admin rt">
         <router-link to="/" class="back rt">回到首页</router-link>
         <div class="admin-info rt">
           <a class="personer">
             <i class="fa fa-user-circle-o"></i>
             <span>{{adminName}}</span>
             <i class="fa fa-angle-down"></i>
           </a>
           <a class="logout clear">
             <i class="fa fa-power-off"></i>
             <span class="logout-btn" @click="logout">注销</span>
           </a>
         </div>
         <a class="comment-msg rt">
           <i class="fa fa-envelope-o"></i>
           <span class="comment-tips">15</span>
         </a>
       </div>
     </div>
     <div id="main-body" :style="{height:clientHeight-50+'px'}">
       <router-view></router-view>
     </div>
   </div>
 </div>
</template>

<script>
  export default{
    data () {
      return {
        clientHeight: 0,
        timer: null,
        playerInfo: {
          name: '',
          index: 0,
          isPlaying: true,
          duration: 0,
          volume: '60%',
          currentTime: '00:00',
          curProgress: 0,
          totalTime: '00:00',
          musicListShow: false
        },
        sideMenu: [
          {
            iconClass: 'fa fa-home',
            title: '后台首页',
            link: '/admin'
          }, {
            className: 'artical-manage',
            iconClass: 'fa fa-files-o',
            title: '文章管理',
            link: '/article',
            children: [
              {
                link: '/article',
                iconClass: 'fa fa-list',
                name: '文章列表'
              }, {
                link: '/comment',
                iconClass: 'fa fa-table',
                name: '评论列表'
              }, {
                link: '/link',
                iconClass: 'fa fa-link',
                name: '友情链接'
              }
            ]
          }, {
            className: 'edit',
            iconClass: 'fa fa-edit',
            title: '写文章',
            link: '/drafts',
            children: [
              {
                link: '/edit',
                iconClass: 'fa fa-file',
                name: '新建文章'
              }, {
                link: '/drafts',
                iconClass: 'fa fa-tags',
                name: '草稿箱'
              }
            ]
          }, {
            iconClass: 'fa fa-info',
            title: '关于',
            link: '/bout',
            children: [
              {
                link: '/admin/about',
                iconClass: 'fa fa-info-circle',
                name: '我的简介',
              }, {
                link: 'admin/project',
                iconClass: 'fa fa-folder-o',
                name: '我的项目'
              }
            ]
          }
        ],
        musicList: [
          {
            name: '童话镇 - 陈一发儿',
            src: '../../static/music/童话镇 - 陈一发儿.mp3'
          },
          {
            name: 'Variations on the Kanon By Pachelbel',
            src: '../../static/music/George Winston - Variations on the Kanon By Pachelbel.mp3'
          },
          {
            name: 'What Remains of Our Chalk Road',
            src: '../../static/music/Circadian Eyes - What Remains of Our Chalk Road.mp3'
          },
          {
            name: 'Six Gnossiennes： No.1： Lent',
            src: '../../static/music/Anne Queffélec - Six Gnossiennes： No.1： Lent.mp3'
          },
          {
            name: 'Butterfly Waltz',
            src: '../../static/music/Brian Crain - Butterfly Waltz.mp3'
          },
          {
            name: ' Music Life In Forest',
            src: '../../static/music/DAISHI DANCE - Music Life In Forest.mp3'
          },
          {
            name: 'The Canon Stirs',
            src: '../../static/music/Dan Gibson - The Canon Stirs.mp3'
          },
          {
            name: 'Tears of The Sea (Interlude2) - intro',
            src: '../../static/music/good machine oil - Tears of The Sea (Interlude2) - intro.mp3'
          },
          {
            name: '비오는 토요일 오후',
            src: '../../static/music/January - 비오는 토요일 오후.mp3'
          }
        ]
      }
    },
    created () {
      //高度初始化
      this.clientHeight = window.innerHeight;
    },
    mounted(){
      const player = this.player;
      const _this = this;
      this.changeMusic(0);
      player.oncanplay = function(){
        _this.playerInfo.duration = player.duration;
        player.volume = 0.6;
        // 歌曲总时间计算
        let m = parseInt(player.duration / 60);
        let s = parseInt(player.duration % 60);
        m = m < 10 ? '0' + m : m;
        s = s < 10 ? '0' + s : s;
        _this.playerInfo.totalTime = m + ':' + s;
        // 每秒进度改变
        this.timer = setInterval(()=>{
          let m = parseInt(player.currentTime / 60);
          let s = parseInt(player.currentTime % 60);
          m = m < 10 ? '0' + m : m;
          s = s < 10 ? '0' + s : s;
          _this.playerInfo.currentTime = m + ':' + s;
          _this.playerInfo.curProgress = player.currentTime / player.duration * 100 + '%';
        },1000)
      };
      player.onended = function(){
        console.log('end');
        _this.playerInfo.index = (_this.playerInfo.index + 1) % _this.musicList.length;
        _this.changeMusic(_this.playerInfo.index)
      }
    },
    methods: {
      // 注销
      logout(){
        sessionStorage.removeItem('data');
        localStorage.setItem('data','');
        setTimeout(()=>{
          localStorage.removeItem('data');
        },0);
        this.$router.push('/');
      },
      // 播放器
      changePlayer() {
        if(this.player.paused){
          this.player.play();
        }else{
          this.player.pause();
        }
        this.playerInfo.isPlaying = !this.playerInfo.isPlaying;
      },
      changeMusic(index) {
        this.player.src = this.musicList[index].src;
        this.playerInfo.name = this.musicList[index].name;
        this.playerInfo.index = index;
        this.playerInfo.isPlaying = true;
        this.player.currentTime = 0;
        this.player.play();
      },
      nextMusic() {
        let len = this.musicList.length;
        if(this.playerInfo.index === len-1){
          this.playerInfo.index = 0;
        }else{
          this.playerInfo.index += 1;
        }
        this.changeMusic(this.playerInfo.index);
      },
      preMusic() {
        let len = this.musicList.length;
        if(this.playerInfo.index === 0){
          this.playerInfo.index = len-1;
        }else{
          this.playerInfo.index -= 1;
        }
        this.changeMusic(this.playerInfo.index);
      },
      changeProgress(e) {
        const progress = document.querySelector('#progress');
        this.playerInfo.curProgress = e.offsetX / progress.offsetWidth * 100 + '%';
        this.player.currentTime = e.offsetX / progress.offsetWidth * this.playerInfo.duration;
      },
      changeVolume(e) {
        const volume = document.querySelector('#volume');
        this.player.volume = e.offsetX / volume.offsetWidth;
        this.playerInfo.volume = e.offsetX / volume.offsetWidth * 100 + '%';
      },
      musicListToggle(){
        this.playerInfo.musicListShow = !this.playerInfo.musicListShow;
      }
    },
    computed: {
      player() {
        return document.querySelector('#player');
      },
      adminName() {
        return JSON.parse(sessionStorage.getItem('data')).uname;
      },
      count() {
        return this.$store.state.drafts_count;
      }
    },
    watch: {
      '$route' () {
        if(!sessionStorage.getItem('data')){
          this.$router.push('/');
        }
      }
    }
  }

</script>

<style>
  @import '../../../static/css/admin.css';
  @import '../../../static/css/tableAdmin.css';
</style>
