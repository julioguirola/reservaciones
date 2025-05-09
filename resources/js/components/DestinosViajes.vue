<script setup lang="ts">
import { MapPin } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    viaje_id: number;
}>();
const destinos = ref('');

onMounted(async () => {
    const data = await fetch(route('viajes.destinos', props.viaje_id));
    const res = await data.json();
    destinos.value = res
        .slice(0, 3)
        .map((d) => d.destino)
        .join(', ');
    if (res.length > 3) {
        destinos.value += ' ...';
    }
});
</script>

<template>
    <div class="flex gap-1">
        <MapPin />
        <span>{{ destinos }}</span>
    </div>
</template>
