<script setup lang="ts">
import { UserPen } from 'lucide-vue-next';

const props = defineProps<{
    profesores_count: number;
}>();
</script>

<template>
    <div class="flex gap-1">
        <span>{{ props.profesores_count }}</span
        ><UserPen />
    </div>
</template>
