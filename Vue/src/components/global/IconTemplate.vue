<template>
  <svg class="app-icon" :class="iconClass" :width="props.width" :height="props.height" aria-hidden="true">
    <use :href="symbolId" :fill="props.color" />
  </svg>
</template>

<script setup lang="ts">
import { computed } from 'vue'

type Props = {
  name: string
  prefix?: string
  color?: string
  width?: string
  height?: string
  reverse?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  prefix: 'icon',
  color: '#333',
  width: '23px',
  height: '24px',
  reverse: false,
})

const symbolId = computed(() => `#${props.prefix}-${props.name}`)

const iconClass = computed(() => {
  return [{ 'reversed-icon': props.reverse }, `app-icon--${props.name}`]
})
</script>

<style scoped>
.app-icon {
  position: relative;
  display: inline-block;
  flex-shrink: 0;
}

.reversed-icon {
  transform: rotate(180deg);
}
</style>
