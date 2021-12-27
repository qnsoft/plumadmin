<template>
  <el-card shadow="never">
    <span>多图上传</span>
    <pl-upload-library v-model="images" multiple :limit="10" type="file" />
    <span>单图上传</span>
    <pl-upload-library v-model="image"/>


    <file-item v-for="item in fileItemList" :file="item" style="width:100px;height:100px;" />

    <preview-item></preview-item>
  </el-card>
</template>

<script>
import FileItem from '@/components/Upload/FileItem'
import PreviewItem from '@/components/Upload/PreviewItem'

export default {
  name: "dashboard",
  components: {FileItem, PreviewItem},
  data() {
    return {
      image: undefined,
      images: undefined,
      fileItemList: [
        {
          url: 'http://localhost:8000/storage/20211227/4bf6cf5d7b2248a7b23be5250fc9199b.mp4',
          name:'视频',
          mime:'video/mp4'
        },
        {
          url: 'http://localhost:8000/storage/20211227/9a5ddcaaef8be30ddf50957ae30b3921.sql',
          name:'wenjianasdjfsadjfklsadf',
          mime:'application/octet-stream'
        },
        {
          url: 'http://localhost:8000/storage/20211227/12d7d7b2e1be031411495434bc61c2f5.jpg',
          name:'图片',
          mime:'image/jpeg'
        }
      ]
    }
  }
}
</script>

<style scoped>

</style>
