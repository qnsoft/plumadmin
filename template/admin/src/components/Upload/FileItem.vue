<template>
  <div class="file-item-container">
    <img class="image" v-if="type==='image'" :src="file.url" :alt="file.name">
    <video class="video" v-else-if="type==='video'" :src="file.url"></video>
    <div class="file" v-else>
      <img :src="require('@/assets/file.png')" alt="文件">
      <div>{{ file.name }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FileItem",
  props: {
    file: {
      type: Object
    }
  },
  computed: {
    type() {
      return this.file.mime.split('/')[0] ?? 'file'
    }
  }
}
</script>

<style scoped lang="scss">
.file-item-container {
  width: 100%;
  height: 100%;
  overflow: hidden;
  .image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .video{
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: relative;
    z-index: 9;
  }
  .file{
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    img{
      width: 80%;
      height: 80%;
    }
    div{
      width: 100%;
      font-size: 14px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      text-align: center;
    }
  }
}
</style>
