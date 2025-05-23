<script setup lang="ts">
import { UserPen } from 'lucide-vue-next';

const props = defineProps<{
    profesores_count: number;
}>();
</script>

<template>
    <div class="flex gap-1 rounded-sm p-1 hover:cursor-pointer hover:bg-gray-200">
        <span>{{ props.profesores_count }}</span
        ><UserPen />
    </div>
</template>
