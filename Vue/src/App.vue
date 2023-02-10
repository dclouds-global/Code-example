<template>
  <div class="container app">
    <c-figure
      class="app__figure"
      is-video
      enable-video-toggler
      :duration-video="596"
      poster="https://ia902900.us.archive.org/19/items/Big_Buck_Bunny-13302/Big_Buck_Bunny-13302.jpg?cnt=0"
      src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"
      :style="{
        width: '550px',
      }"
    />
    <c-figure
      src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Big_buck_bunny_poster_big.jpg/636px-Big_buck_bunny_poster_big.jpg?20080321154201"
      :style="{
        width: '230px',
      }"
    />
    <hr class="w-100" />
    <div class="app__list">
      <template v-if="!isLoading">
        <c-figure v-for="photo in albumList" :key="photo.id" square class="app__list-item" :src="photo.url" />
      </template>
      <div v-else>loading...</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import CFigure from '@/components/CFigure.vue'
import AlbumsApi from '@/api/User/Albums.api'

const albumList = ref<ApiAlbumItemType[]>([])
const isLoading = ref(false)

onMounted(() => {
  getAlbumList()
})

const getAlbumList = async () => {
  isLoading.value = true

  const [, response] = await AlbumsApi.getPhotos({ id: 1 })

  isLoading.value = false

  if (response) {
    albumList.value = response.data
  }
}
</script>

<style scoped lang="scss">
.app {
  --app-list--gap: 20px;
  --app-list--columns: 4;

  &__figure {
    margin-right: 20px;
  }

  &__list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: var(--app-list--gap);
  }

  &__list-item {
    max-width: calc(
      (100% / var(--app-list--columns)) -
        (var(--app-list--gap) * (var(--app-list--columns) - 1) / var(--app-list--columns))
    );
    flex: 0 0 calc(100% / var(--app-list--columns));
  }
}
</style>

<style lang="scss">
.container {
  max-width: 800px;
  margin: 0 auto;
}
</style>
