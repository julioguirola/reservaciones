<script setup lang="ts">
const props = defineProps<{
    cant_viajes_abril_julio: number;
    cant_viajes_agosto_septiembre: number;
    cant_viajes_octubre_marzo: number;
    cant_viajes: number;
}>();
</script>
<template>
    <div class="flex flex-col">
        <span>Total: {{ props.cant_viajes }}</span>
        <span>Oct-Mar: {{ props.cant_viajes_octubre_marzo }}</span>
        <span>Abr-Jul: {{ props.cant_viajes_abril_julio }}</span>
        <span>Agt-Sep: {{ props.cant_viajes_agosto_septiembre }}</span>
    </div>
</template>
