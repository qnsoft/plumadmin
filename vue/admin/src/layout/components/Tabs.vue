<template>
  <div class="tabs-view-container">
    <div class="tabs-card">
      <div class="prev-button" v-if="showButton" @click="handleSlide('left')">
        <i class="el-icon-arrow-left"></i>
      </div>
      <div class="tabs-scroll-container" ref="container">
        <div class="tabs-scroll" ref="slide" :style="{transform: `translateX(${left}px)`}">
          <div class="tabs-item" :class="{active:item.path===currentPath}" v-for="item in tabs"
               @click="$router.push(item.path)"
          >
            <span class="tabs-title">{{ item.title }}</span>
            <i class="el-icon-close" v-if="tabs.length!==1" @click.stop="closeCurrent(item.path)"/>
          </div>
        </div>
      </div>
      <div class="next-button" v-if="showButton" @click="handleSlide('right')">
        <i class="el-icon-arrow-right"></i>
      </div>
    </div>
    <el-dropdown class="tabs-close">
      <div style="width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;">
        <i class="el-icon-arrow-down"></i>
      </div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item>
          <div class="tabs-dropdown-item" @click="reload">
            <svg-icon icon-class="refresh" class-name="tabs-icon"/>
            <span>刷新当前</span>
          </div>
        </el-dropdown-item>
        <el-dropdown-item :disabled="tabsCloseHide.current">
          <div class="tabs-dropdown-item" @click="closeCurrent(currentPath)">
            <svg-icon icon-class="close" class-name="tabs-icon"/>
            <span>关闭当前</span>
          </div>
        </el-dropdown-item>
        <el-dropdown-item :disabled="tabsCloseHide.right">
          <div class="tabs-dropdown-item" @click="closeRight(currentPath)">
            <svg-icon icon-class="close-other" class-name="tabs-icon"/>
            <span>关闭右侧</span>
          </div>
        </el-dropdown-item>
        <el-dropdown-item :disabled="tabsCloseHide.other">
          <div class="tabs-dropdown-item" @click="closeOther(currentPath)">
            <svg-icon icon-class="close-right" class-name="tabs-icon"/>
            <span>关闭其他</span>
          </div>
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'Tabs',
  inject: ['reload'],
  data() {
    return {
      left: 0,
      showButton: false
    }
  },
  computed: {
    tabs() {
      return JSON.parse(JSON.stringify(this.$store.state.tabs.activeTabs))
    },
    tabsCloseHide() {
      //获取当前的路由
      const path = this.$route.path
      const index = this.tabs.findIndex(e => {
        return e.path === path
      })
      return {
        current: this.tabs.length <= 1,
        other: this.tabs.length <= 1,
        right: index + 1 >= this.tabs.length
      }
    },
    currentPath(){
      return this.$route.path
    }
  },
  watch: {
    tabs: {
      immediate: true,
      handler(val, old) {
        this.$nextTick(() => {
          if (val && old && val.length && old.length && val.length > old.length) {
            //新增,默认向右拉去一下
            this.handleSlide('right')
          } else {
            this.correctSlide()
          }
          this.showButton = this.$refs.container.offsetWidth < this.$refs.slide.scrollWidth
        })
      }
    }
  },
  methods: {
    ...mapActions({ addTabs:'tabs/add',closeCurrent:'tabs/closeCurrent',closeOther:'tabs/closeOther',closeRight:'tabs/closeRight' }),
    handleSlide(direction) {
      let left = this.left
      if (direction === 'left') {
        left += this.$refs.container.offsetWidth
      } else {
        left -= this.$refs.container.offsetWidth
      }
      this.correctSlide(left)
    },
    //调整
    correctSlide(left = null) {
      let container = this.$refs.container.offsetWidth,
          slide = this.$refs.slide.scrollWidth
      left = left ?? this.left
      //删除,导致有空余
      if (left > 0 || container >= slide) {
        left = 0
      } else if (left < container - slide) {
        //空间
        left = container - slide
      }
      this.left = left
    }
  }
}
</script>
<style scoped lang="scss">

.tabs-view-container {
  display: flex;
  font-size: 16px;
  color: rgb(81, 90, 110);
  cursor: pointer;
  user-select: none;

  [class*="el-icon-"], [class^=el-icon-] {
    font-weight: 700;
  }

  .tabs-card {
    flex-grow: 1;
    display: flex;
    position: relative;
    overflow: hidden;

    .prev-button, .next-button {
      flex-shrink: 0;
      width: 32px;
      height: 32px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .tabs-scroll-container {
      overflow: hidden;
      position: relative;
      flex-grow: 1;
      height: 32px;

      .tabs-scroll {
        height: 32px;
        float: left;
        white-space: nowrap;
        position: relative;
        transition: transform .5s ease-in-out;

        .tabs-item {
          .tabs-title {
            padding-right: 2px;
          }

          &.active .tabs-title {
            color: #1890ff;
          }

          [class*="el-icon-"], [class^=el-icon-] {
            font-weight: 550;

            &:hover {
              font-weight: 600;
            }
          }

          background: #ffffff;
          height: 32px;
          line-height: 32px;
          font-size: 14px;
          padding: 0 16px;
          border-radius: 3px;
          margin-right: 6px;
          display: inline-block;
          position: relative;

        }
      }
    }

  }

  .tabs-close {
    display: flex;
    flex-shrink: 0;
    flex-basis: 32px;
    justify-content: center;
    align-items: center;
    height: 32px;
    border-radius: 2px;
    background: #ffffff;

    .tabs-dropdown-item {
      display: flex;
      align-items: center;
      font-size: 16px;
      line-height: 32px;
    }
  }

}

::v-deep .svg-icon.tabs-icon {
  font-size: 16px;
  line-height: 32px;
  margin-right: 5px;
}
</style>
