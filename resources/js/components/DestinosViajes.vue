<script setup lang="ts">
import { MapPin } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    destinos: string[];
}>();
const expandido = ref(false);
</script>

<template>
    <div @click="expandido = !expandido" class="flex select-none gap-1 hover:cursor-pointer">
        <MapPin color="#2563EB" />
        <span v-if="expandido">{{ props.destinos.join(', ') }}</span>
        <span v-else>{{ props.destinos.slice(0, 3).join(', ') + ' ...' }}</span>
    </div>
</template>
