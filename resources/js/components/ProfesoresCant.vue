<script setup lang="ts">
import { UserPen } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    viaje_id: number;
}>();
const cant = ref(0);

onMounted(async () => {
    const data = await fetch(route('viajes.profesores.count', props.viaje_id));
    const res = await data.json();
    cant.value = res;
});
</script>

<template>
    <div class="flex gap-1">
        <span>{{ cant }}</span
        ><UserPen />
    </div>
</template>
