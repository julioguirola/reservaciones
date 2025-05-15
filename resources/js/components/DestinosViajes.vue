<script setup lang="ts">
import { MapPin } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    viaje_id: number;
}>();
const destinos = ref([]);
const expandido = ref(false);

onMounted(async () => {
    const data = await fetch(route('viajes.destinos', props.viaje_id));
    const res = await data.json();
    destinos.value = res.map((d) => d.destino);
});
</script>

<template>
    <div @click="expandido = !expandido" class="flex select-none gap-1 hover:cursor-pointer">
        <MapPin />
        <span v-if="expandido">{{ destinos.join(', ') }}</span>
        <span v-else>{{ destinos.slice(0, 3).join(', ') + ' ...' }}</span>
    </div>
</template>
