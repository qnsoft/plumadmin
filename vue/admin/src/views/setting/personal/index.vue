<template>
  <el-card>
    <el-tabs v-model="tabIndex">
      <el-tab-pane name="userinfo" label="基本设置">
        <userinfo/>
      </el-tab-pane>
      <el-tab-pane name="password" label="密码设置">
        <password/>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import Password from './components/Password'
import Userinfo from './components/Userinfo'

export default {
  name: "setting_personal",
  components: {Password, Userinfo},
  data() {
    return {
      tabIndex: 'userinfo'
    }
  },
  methods: {},
  watch: {
    '$route.query': {
      immediate: true,
      handler(val) {
        if (val.tabIndex && ['userinfo', 'password'].includes(val.tabIndex)) {
          this.tabIndex = val.tabIndex
        }
      }
    }
  }
}
</script>

<style scoped>

</style>
