<template>
  <el-container style="height: 100%">

    <!--侧边栏-->
    <el-aside width="200px" style="background: #001428">
      <div class="layout-aside-logo">
        <el-image class="image-logo" fit="cover" :src="sliderLogo"></el-image>
        <span>{{ sliderTitle }}</span>
      </div>
      <!--侧边菜单-->
      <el-scrollbar style="height: calc(100% - 64px)" wrap-style="overflow-x:hidden;">
        <el-menu
            class="slider-menu"
            background-color="#001428"
            text-color="#bbb"
            active-text-color="#fff"
            :default-active="$route.path"
            @select="selectMenus"
        >
          <Menus :list="menus"></Menus>
        </el-menu>
      </el-scrollbar>
    </el-aside>

    <el-container style="background: #f5f7f9">
      <!--头部-->
      <el-header class="header">
        <!--面包屑-->
        <div class="header-left">
          <i class="el-icon-refresh-right"/>
          <el-breadcrumb separator="/">
            <el-breadcrumb-item v-for="(item,index) in breadcrumbList" :key="index">
              {{ item.title }}
            </el-breadcrumb-item>
          </el-breadcrumb>
        </div>
        <!--用户-->
        <el-dropdown class="header-right">
          <div>
            <el-avatar size="small" :src="avatar" @error="()=>true">
              <i class="el-icon-user-solid"></i>
            </el-avatar>
            <span style="padding-left: 12px">{{ name }}</span>
          </div>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item>
              <div @click="$router.push('/setting/personal?tabIndex=userinfo')">个人设置</div>
            </el-dropdown-item>
            <el-dropdown-item>
              <div @click="logout">退出登录</div>
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </el-header>

      <el-main style="padding: 0">
        <Tabs style="box-sizing: border-box;padding: 6px 10px"></Tabs>

        <el-scrollbar
            style="height: calc(100vh - 104px);width: calc(100vw - 200px);overflow-y:hidden;"
            view-style="padding:0 10px 10px 10px;box-sizing:border-box;"
        >
          <keep-alive :include="keepAlive" :exclude="keepExclude">
            <router-view v-if="routerView"></router-view>
          </keep-alive>
        </el-scrollbar>

      </el-main>
    </el-container>
  </el-container>
</template>
<script>
import {mapGetters, mapActions} from 'vuex'
import Menus from './components/Menus'
import Tabs from './components/Tabs'
import {sliderLogo, sliderTitle} from '@/config'
import {findLinks} from '@/utils'
import {isExternal} from "@/utils/validate"

export default {
  components: {Menus, Tabs},
  data() {
    return {
      sliderLogo,
      sliderTitle,
      routerView: true,
      keepExclude: [],
      breadcrumbList: []
    }
  },
  provide() {
    return {
      reload: this.reload
    }
  },
  computed: {
    ...mapGetters(['menus', 'name', 'avatar']),
    keepAlive() {
      let keepAlive = this.$store.state.permission.keep_alive_name
          , tabs = this.$store.state.tabs.activeTabs
      tabs = tabs.map(e => e.name)
      return this.$tools.intersection(keepAlive, tabs)
    },
  },
  methods: {
    ...mapActions({userLogout: 'user/logout'}),
    logout() {
      this.userLogout()
          .then(({message}) => {
            this.$message.success(message)
          })
    },
    selectMenus(path, index) {
      if(isExternal(path)){
        window.open(path,'_blank')
        this.$router.push('/')
        this.$router.go(-1)
      }else{
        this.$router.push(path)
      }
    },
    reload() {
      this.keepExclude = [this.$route.name]
      this.routerView = false
      this.$nextTick(() => {
        this.routerView = true
        this.keepExclude = []
      })
    }
  },
  watch: {
    '$route.path': {
      immediate: true,
      handler() {
        const menus = this.$store.state.permission.menus
        this.breadcrumbList = findLinks(menus, e => e.path === this.$route.path)
      }
    }
  }
}
</script>
<style scoped lang="scss">

//头部
.header {
  background: #ffffff;
  box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
  display: flex;
  justify-content: space-between;

  .header-left {
    display: flex;
    align-items: center;
  }

  .header-right {
    display: flex;
    align-items: center;
    cursor: default;

    &:hover {
      background: rgba(0, 0, 0, .025);
    }

    & > div {
      display: flex;
      align-items: center;
      font-size: 15px;
      color: #000;
      padding: 0 12px;
    }
  }
}

//侧边菜单
.slider-menu {
  border-right: none;
}

//侧边LOGO
.layout-aside-logo {
  height: 64px;
  overflow: hidden;
  display: flex;
  color: #fff;
  align-items: center;
  justify-content: center;
  font-size: 16px;

  .image-logo {
    width: 32px;
    height: 32px;
    margin-right: 10px

  }
}

//顶部菜单刷新按钮
.el-icon-refresh-right {
  font-size: 18px;
  padding-right: 20px;
}
</style>
