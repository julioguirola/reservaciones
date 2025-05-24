<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { useSidebar } from './ui/sidebar';
const { state } = useSidebar();
</script>

<template>
    <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />
    <div class="ml-1 grid flex-1 text-left text-sm" v-if="state !== 'collapsed'">
        <span class="mb-0.5 truncate font-semibold leading-none">Reservaciones</span>
    </div>
</template>
