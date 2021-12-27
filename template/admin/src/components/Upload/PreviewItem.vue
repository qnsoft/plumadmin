<template>
  <el-dialog title="预览" :visible.sync="visible" @close="file=undefined">
    <template v-if="file">
      <img v-if="type==='image'" style="max-width:100%;max-height:100%;object-fit: cover" :src="file.url"
           :alt="file.name">
      <video v-else-if="type==='video'" style="max-width:100%;max-height:100%;object-fit: cover" controls
             :src="file.url"></video>
      <div class="file" v-else>
        <img :src="require('@/assets/file.png')" alt="文件">
        <div>{{ file.name }}</div>
      </div>
    </template>
  </el-dialog>
</template>

<script>
export default {
  name: "PreviewItem",
  data() {
    return {
      visible: false,
      file: undefined
    }
  },
  computed: {
    type() {
      if (this.file) {
        return this.file.mime.split('/')[0] ?? 'file'
      }
      return 'file'
    }
  },
  methods: {
    open(file) {
      console.log('jineru')
      this.file = file
      this.visible = true
    }
  }
}
</script>

<style scoped lang="scss">
.file {
  width: 200px;
  height: 200px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  img {
    width: 80%;
    height: 80%;
  }

  div {
    width: 100%;
    font-size: 14px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    text-align: center;
  }
}
</style>
