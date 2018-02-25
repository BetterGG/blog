<template>
  <div id="app" class="clear">
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'app'
}
</script>

<style>
#app {
  font: 14px "lucida grande", "lucida sans unicode", lucida, "Helvetica Neue", helvetica, "Hiragino Sans GB", "Microsoft YaHei", "WenQuanYi Micro Hei", sans-serif;
  width: 100%;
}
.app{
  padding-top: 70px;
  width: 100%;
}
*{
  box-sizing: border-box;
}

body h1,
body h2,
body h3,
body h4,
body h5,
body h6,
body ul,
body ol,
body dd,
body dt,
body p{
  margin: 0;
  padding: 0;
}
h1,h2,h3,h4,h5{
  font-weight: 500;
}
ul,ol{
  list-style: none;
}
body a{
  text-decoration: none;
  color: #666;
}
body a:hover,
body a:focus{
  text-decoration: none;
}
.lt{
  float: left;
}
.rt{
  float: right;
}
.clear:after{
  content: "";
  display: table;
  clear: both;
}
.close::before{
  content: "X";
  position: absolute;
  top: 5px;
  right: 5px;
  width: 20px;
  height: 20px;
  font-size: 21px;
  text-align: center;
  line-height: 20px;
  color: #888;
}
::-webkit-scrollbar {
  width: 12px;
  height: 10px;
}
::-webkit-scrollbar-thumb {
  height: 30px;
  background-color: #999;
  -webkit-border-radius: 7px;
  outline: 2px solid #fff;
  outline-offset: -2px;
  border: 2px solid #fff;
}
::-webkit-scrollbar-track-piece {
  background-color: #fff;
  -webkit-border-radius: 3px;
}
</style>
