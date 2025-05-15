<script setup lang="ts">
import { onMounted, ref } from 'vue';

const props = defineProps<{
    chofer_id: string;
}>();
const cant = ref(0);

onMounted(async () => {
    const data = await fetch(route('choferes.viajes.count', props.chofer_id));
    const res = await data.json();
    cant.value = res;
});
</script>

<template>
    {{ cant }}
</template>
