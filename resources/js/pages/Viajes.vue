<script setup lang="ts">
import DestinosViajes from '@/components/DestinosViajes.vue';
import ProfesoresCant from '@/components/ProfesoresCant.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { CalendarDays, UserCog } from 'lucide-vue-next';

interface Viaje {
    id: number;
    chofer_nombre: string;
    fecha: string;
    destinos: string[];
    profesores_count: number;
    recaudado: number;
}

const props = defineProps<{
    viajes: Viaje[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Viajes',
        href: '/viajes',
    },
];
</script>

<template>
    <Head title="Viajes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid h-full gap-4 rounded-xl p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <Card v-for="viaje in props.viajes" :key="viaje.id">
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle>Viaje {{ viaje.id }}</CardTitle>
                        <ProfesoresCant
                            @click="router.get(route('viajes.profesores', { viaje_id: viaje.id }))"
                            :profesores_count="viaje.profesores_count"
                        />
                    </CardHeader>
                    <CardContent class="flex items-center gap-1"><UserCog /> {{ viaje.chofer_nombre }} </CardContent>
                    <CardContent class="flex items-center gap-1"> <CalendarDays />{{ viaje.fecha.split(' ')[0] }}</CardContent>
                    <CardContent><DestinosViajes :destinos="viaje.destinos"></DestinosViajes></CardContent>
                    <CardContent>Recaudado: $ {{ viaje.recaudado }}</CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
