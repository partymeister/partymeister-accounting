<script setup lang="ts">
import { provide } from 'vue'
import { LoginGuard } from '@auth'
import PosViewer from './components/PosViewer.vue'
import PosEditor from './components/PosEditor.vue'

// Config from URL params instead of window.POS_CONFIG
const params = new URLSearchParams(window.location.search)
const mode = params.get('mode') || 'viewer'
const accountId = params.get('account') || '1'

provide('posConfig', { mode, accountId })
</script>

<template>
  <LoginGuard>
    <PosViewer v-if="mode === 'viewer'" />
    <PosEditor v-else />
  </LoginGuard>
</template>
