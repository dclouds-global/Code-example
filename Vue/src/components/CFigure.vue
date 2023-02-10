<template>
  <figure class="c-figure" :class="baseClasses">
    <template v-if="!props.isVideo">
      <picture class="c-figure__picture" @click="clickPictureHandler">
        <img
          class="c-figure__img"
          :srcset="props.srcset"
          :src="isShowImg ? imageSrc : crashImg"
          :alt="props.alt"
          @error="errorHandler"
        />
        <slot name="picture" />
      </picture>
    </template>
    <template v-if="props.isVideo">
      <div class="c-figure__video">
        <div v-if="!isShowVideo" class="c-figure__video-preview" @click="renderVideoHandler">
          <img :src="isShowImg ? props.poster : crashImg" @error="errorHandler" />
          <button v-if="enableVideoToggler" class="c-figure__video-play">
            <icon-template name="play" />
          </button>
          <div v-if="props.durationVideo && enableVideoToggler" class="c-figure__video-duration">
            {{ duration }}
          </div>
        </div>
        <video v-else autoplay :controls="props.controls" class="c-figure__video-inner" :src="videoSrc">
          <slot name="video" />
          Sorry, your browser does not support embedded videos, but don't worry, you can
          <a :href="videoSrc">download it</a>
          and watch in your favorite video player!
        </video>
      </div>
    </template>
    <figcaption v-if="$slots.figcaption" class="c-figure__caption">
      <slot name="figcaption" />
    </figcaption>
  </figure>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import EmptyImg from '@/assets/images/empty.svg?url'
import EmptySecondaryImg from '@/assets/images/empty-secondary.svg?url'
import CrashImg from '@/assets/images/crash.svg?url'
import CrashSecondaryImg from '@/assets/images/crash-secondary.svg?url'
import { secondsToHMS } from '@/utils/utils'

type Emits = {
  (event: 'picture-handler'): void
  (event: 'video-handler'): void
}

type Props = {
  mode?: 'primary' | 'secondary'
  src?: string
  srcset?: string
  alt?: string
  square?: boolean
  circle?: boolean
  emptyImg?: string
  poster?: string
  isVideo?: boolean
  durationVideo?: number
  enableVideoToggler?: boolean
  controls?: boolean
}

const emit = defineEmits<Emits>()
const props = withDefaults(defineProps<Props>(), {
  mode: 'primary',
  src: '',
  srcset: '',
  alt: '',
  poster: '',
  square: false,
  circle: false,
  emptyImg: EmptyImg,
  isVideo: false,
  durationVideo: 0,
  enableVideoToggler: false,
  controls: true,
})

const isShowImg = ref(true)
const isShowVideo = ref(false)

const isPrimary = computed(() => props.mode === 'primary')
const crashImg = computed(() => (isPrimary.value ? CrashImg : CrashSecondaryImg))
const emptyImg = computed(() => (isPrimary.value ? EmptyImg : EmptySecondaryImg))
const duration = computed(() => {
  return secondsToHMS(props.durationVideo)
})
const imageSrc = computed(() => {
  return props.src || emptyImg.value
})
const videoSrc = computed(() => {
  return props.src || emptyImg.value
})
const baseClasses = computed(() => {
  return [
    `c-figure--${props.mode}`,
    {
      'c-figure--square': props.square,
      'c-figure--circle': props.circle,
    },
  ]
})

watch(
  () => props.src,
  () => {
    isShowImg.value = true
  },
  {
    immediate: true,
  }
)

const clickPictureHandler = () => {
  emit('picture-handler')
}

const errorHandler = () => {
  isShowImg.value = false
}

const renderVideoHandler = () => {
  emit('picture-handler')

  if (!props.enableVideoToggler) return

  isShowVideo.value = true
}
</script>

<style lang="scss" scoped>
.c-figure {
  --c-figure--fill-color: #{$color--primary};
  $root: &;

  width: 100%;
  display: inline-block;
  margin: 0;

  &__video {
    padding-top: 56.25%;
  }

  &__picture {
    padding-top: 134%;
  }

  &__caption {
    width: 100%;
  }

  &__video-preview {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;

    &::before {
      content: '';
      top: 0;
      bottom: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      background: rgba($color: $color--black, $alpha: 0.2);
    }

    * {
      pointer-events: none;
    }

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__video-play {
    top: 50%;
    left: 50%;
    width: 50px;
    height: 50px;
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    outline: none;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    color: $color--white;
    background: rgba($color: $color--white, $alpha: 0.32);
    transform: translate(-50%, -50%);
    padding: 0;
  }

  &__video-duration {
    right: 12px;
    bottom: 12px;
    position: absolute;
    border-radius: 4px;
    font-weight: 400;
    font-size: 12px;
    line-height: 24px;
    color: $color--subtitle-font;
    background: rgba($color: $color--white, $alpha: 0.32);
    padding: 0 8px;
  }

  &__video-inner {
    object-fit: contain;
  }

  &__img {
    object-fit: cover;
  }

  &--primary {
    --c-figure--fill-color: #{$color--primary};
  }

  &--secondary {
    --c-figure--fill-color: #{$color--white};
  }

  &--square {
    #{$root}__video,
    #{$root}__picture {
      padding-top: 100%;
    }
  }

  &--circle {
    #{$root}__picture {
      border-radius: 50%;
      padding-top: 100%;
    }
  }

  &__video,
  &__picture {
    width: 100%;
    height: auto;
    position: relative;
    display: flex;
    border-radius: 20px;
    color: var(--c-figure--fill-color);
    background: var(--c-figure--fill-color);
    overflow: hidden;

    rect {
      fill: var(--c-figure--fill-color);
    }
  }

  &__video-inner,
  &__img {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    position: absolute;
  }
}
</style>
