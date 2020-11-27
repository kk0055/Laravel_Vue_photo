<template>
  <div class="photo-list">
    <div class="grid">
      <Photo
      class="grid_item"
      v-for="photo in photos"
      :key="photo.id"
      :item="photo"
      />
     
    </div>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>
</template>
<script>
import { OK } from '../util'
import Photo from '../components/Photo.vue'
import Pagination from '../components/Pagination.vue'
export default {
  components: {
    Photo,
    Pagination
  },
  props: {
   page: {
     type: Number,
     required: false,
     default: 1
   }
  },
  data(){
    return{
      photos:[],
      currentPage: 0,
      lastPage: 0
    }
  },
  methods: {
    // API を呼び出して、結果を photos に代入
    async fetchPhotos () {
      const response = await axios.get(`/api/photos/?page=${this.page}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
      console.log(response);
      this.photos = response.data.data
      this.currentPage = response.data.current_page
      this.lastPage = response.data.last_page
    }
  },
   //$route の監視ハンドラ内で fetchPhotos を実行
  watch: {
    $route: {
      async handler () {
        await this.fetchPhotos()
      },
      //<PhotoList> をレンダリングする度に実行するためimmediate:trueを書く。書かないと初めて<PhotoList> をレンダリングするときに実行されない
      immediate: true
    }
  }
}
</script>