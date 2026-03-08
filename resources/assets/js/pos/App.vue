<script setup lang="ts">
import PosViewer from './components/PosViewer.vue'
import PosEditor from './components/PosEditor.vue'

const config = window.POS_CONFIG
</script>

<template>
  <PosViewer v-if="config.mode === 'viewer'" />
  <PosEditor v-else />
</template>
