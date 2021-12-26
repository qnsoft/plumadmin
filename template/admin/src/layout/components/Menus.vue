<template>
  <div>
    <div v-for="item in list">
      <template v-if="item.hidden===false">
        <!--子菜单-->
        <el-submenu
            v-if="item.children && item.children.length"
            :index="item.id+''"
            :key="item.id"
        >
          <template slot="title">
            <div class="menu-title">
              <svg-icon v-if="item.icon" class-name="title-icon" :icon-class="item.icon"/>
              <span>{{ item.title }}</span>
            </div>
          </template>
          <!--递归-->
          <menus :list="item.children"></menus>
        </el-submenu>
        <!--无子菜单,直接显示-->
        <el-menu-item
            v-else
            :index="item.url?item.url:item.path"
            :key="item.id"
        >
          <div class="menu-title">
            <svg-icon v-if="item.icon" class-name="title-icon" :icon-class="item.icon"/>
            <span>{{ item.title }}</span>
          </div>
        </el-menu-item>
      </template>
    </div>
  </div>


</template>

<script>
export default {
  name: 'menus',
  props: ['list']
}
</script>

<style lang="scss">
.slider-menu .el-menu-item, .slider-menu .el-submenu__title {
  &.is-active {
    background: #2d8cf0 !important;
  }

  height: 42px;
  line-height: 42px;
  border-radius: 4px;
  box-sizing: border-box;
  width: calc(100% - 18px);
  min-width: auto !important;
  margin: 0 auto 10px;
}
</style>


<style lang="scss" scoped>
.menu-title {
  display: flex;
  align-items: center;
  .title-icon {
    font-size: 18px;
    line-height: 20px;
    margin-right: 8px;
  }
}

</style>
