<template>
  <div class="header-bar">
            <h1>Bangbang</h1>
            <ul class="header-nav">
                <li><a @click="$router.push('/note/create')">发表</a></li>
                <li><a @click="$router.push('/note/report')">帖子</a></li>
                <li><a @click="$router.push('/note/join')">记录</a></li>
                <li><a @click="$router.push('/note/msg')">消息</a></li>
            </ul>
  </div>
</template>

<script>
export default {
  name: 'header-bar',
  data () {
    return {}
  },
  methods: {}
}
</script>

<style lang="less" scoped>
  .header-bar{
      opacity: 0.8;
      padding-bottom: 10px;
      background-size: cover;
      background-image: url(http://bpic.588ku.com/back_pic/03/66/28/2857b014876d04f.jpg!ww1200);
      h1{
          padding-top: 10px;
          color: white;
          padding-left: 10px;
          font-weight: bolder;
      }
  }
  ul {
      color: #dadada;
      position: relative;
      padding-top: 10px;
      display: flex;
  }

  li {
      display: inline-block;
      font-size: 16px;;
      width: 25%;
      text-align: center;
  }
  li>a{color: #bfbfbf}
  li:hover{
      cursor: pointer;
  }
</style>
