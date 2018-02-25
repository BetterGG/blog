<template>
  <div class="admin-home">
    <ul class="module">
      <li class="clear">
        <div class="module-icon lt">
          <i class="fa fa-comments"></i>
        </div>
        <div class="module-content lt">
          <h3 class="module-num">153</h3>
          <h5>总访问量</h5>
        </div>
      </li>
      <li class="clear">
        <div class="module-icon lt">
          <i class="fa fa-user"></i>
        </div>
        <div class="module-content lt">
          <h3 class="module-num">7</h3>
          <h5>今日访问</h5>
        </div>
      </li>
      <li class="clear">
        <div class="module-icon lt">
          <i class="fa fa-file-text-o"></i>
        </div>
        <div class="module-content lt">
          <h3 class="module-num">{{articles_count}}</h3><h5>文章总数</h5>
        </div>
      </li>
      <li class="clear">
        <div class="module-icon lt">
          <i class="fa fa-tag"></i>
        </div>
        <div class="module-content lt">
          <h3 class="module-num">{{count}}</h3>
          <h5>草稿箱</h5>
        </div>
      </li>
    </ul>
    <div class="charts">
      <div class="constant-visitor" ref="visitorChart">

      </div>
    </div>
  </div>
</template>

<script>
    export default{
        data () {
          return {
            articles_count: 0,
            chartTimer: null
          }
        },
        created(){
          this.$ajax({
            method: 'get',
            url: 'http://127.0.0.1:8080/blog/data/admin/getAdminHomeData.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(({data})=>{
            this.articles_count = data.articles_count;
            this.$store.commit('getDraftsCount',data.drafts_count)
          });
        },
        mounted(){
          this.createEcharts();
        },
        beforeDestroy(){
          clearInterval(this.chartTimer);
          this.chartTimer = null;
        },
        methods: {
          //创建 实时访客折线图
          createEcharts() {
            let echarts = require('echarts');
            let myChart = echarts.init(this.$refs.visitorChart);
            function randomData() {
              now = new Date(+now + interval);
              value = value + Math.random() * 21 - 10;
              value = value > 0 ? value : 0;
              return {
                name: now.toString(),
                value: [
                  new Date(`${now.getFullYear()}-${now.getMonth() + 1}-${now.getDate()} ${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`),
                  Math.round(value)
                ]
              }
            }
            let data = [];
            let now = +new Date();
            let interval = 1000;
            let value = Math.random() * 300;
            let _this = this;
            for (let i = 0; i < 60; i++) {
              data.push(randomData());
            }

            let option = {
              title: {
                text: '实时访问量',
                left: 80
              },
              tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                  params = params[0];
                  let date = new Date(params.name);
                  let h = _this.completeDate( date.getHours() );
                  let m = _this.completeDate( date.getMinutes() );
                  let s = _this.completeDate( date.getSeconds() );
                  return h + ':' + m + ':' + s + ' => ' + params.value[1];
                },
                axisPointer: {
                  animation: false
                }
              },
              xAxis: {
                type: 'time',
                splitLine: {
                  show: false
                },
                maxInterval: 10 * 1000
              },
              yAxis: {
                type: 'value',
                boundaryGap: [0, '100%'],
                splitLine: {
                  show: false
                }
              },
              series: [{
                name: '访客量',
                type: 'line',
                showSymbol: false,
                hoverAnimation: false,
                data: data
              }]
            };
            myChart.setOption(option);
            this.chartTimer = setInterval(function () {
              for (var i = 0; i < 5; i++) {
                data.shift();
                data.push(randomData());
              }

              myChart.setOption({
                series: [{
                  data: data
                }]
              });
            }, 1000);
          },
          completeDate(num) {
            if(num<10){
              return '0'+num;
            }else{
              return num;
            }
          }
        },
        computed: {
          count(){
              return this.$store.state.drafts_count;
          }
        }
    }
</script>

<style>
  .admin-home{
    width: 100%;
    height: 100%;
    background-color: #fff;
    border: 1px solid transparent;
    overflow: hidden;
  }
  .module{
    margin: 10px 50px;
    display: flex;
    justify-content: space-between;
  }
  .module li{
    float: left;
    width: 22%;
    height: 80px;
    line-height: 80px;
    text-align: center;
  }
  .module li .module-icon,
  .module li .module-content{
    height: 80px;
  }
  .module li .module-icon{
    width: 35%;
    color: #fff;
    line-height: 80px;
    border-radius: 5% 0 0 5%;
    font-size: 35px;
  }
  .module li:first-child .module-icon{
    background-color: #5AB2DB;
  }
  .module li:nth-child(2) .module-icon{
    background-color: #FF5D2A;
  }
  .module li:nth-child(3) .module-icon{
    background-color: #58BA77;
  }
  .module li:nth-child(4) .module-icon{
    background-color: #F7BC1F;
  }
  .module li .module-content{
    width: 65%;
    background-color: #EFEFEF;
    color: #000;
    padding: 16px 10px;
    border-radius: 0 5% 5% 0;
  }
  .module li .module-content h3{
    margin-bottom: 5px;
  }
  .charts{
    height: 80%;
  }
  .charts .constant-visitor {
    width: 100%;
    height: 90%;
    margin-top: 5%;
  }
</style>
