<script setup lang="ts">
import DestinosViajes from '@/components/DestinosViajes.vue';
import ProfesoresCant from '@/components/ProfesoresCant.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { CalendarDays, UserCog } from 'lucide-vue-next';
import { ref, watch } from 'vue';

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
    viajes_cant: number;
}>();

const viajes = ref<Viaje[]>(props.viajes);
const actual_page = ref<number>(0);
const viajes_cant = ref<number>(props.viajes_cant);

const change_page = async () => {
    const res = await fetch(route('viajes.data') + `?page=${actual_page.value - 1}`);
    const data = await res.json();
    viajes.value = data.viajes;
    viajes_cant.value = data.viajes_cant;
};

watch(actual_page, async () => {
    change_page();
});

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
                <Card v-for="viaje in viajes" :key="viaje.id">
                    <CardHeader class="flex flex-row items-center justify-between py-2">
                        <CardTitle>Viaje {{ viaje.id }}</CardTitle>
                        {{ viaje.fecha.split(' ')[0] }}<CalendarDays />
                    </CardHeader>
                    <CardContent class="flex flex-row items-center justify-between py-2"
                        ><UserCog /> {{ viaje.chofer_nombre
                        }}<ProfesoresCant
                            @click="router.get(route('viajes.profesores', { viaje_id: viaje.id }))"
                            :profesores_count="viaje.profesores_count"
                        />
                    </CardContent>
                    <CardContent class="flex items-center py-2"><DestinosViajes :destinos="viaje.destinos"></DestinosViajes></CardContent>
                    <CardContent class="flex items-center py-2">Recaudado: $ {{ viaje.recaudado }}</CardContent>
                </Card>
            </div>
        </div>
        <caption class="mt-4 text-sm text-muted-foreground">
            Informacion de los viajes
        </caption>
        <Pagination class="mb-4" v-model:page="actual_page" v-slot="{ page }" :items-per-page="8" :total="viajes_cant" :default-page="1">
            <PaginationContent v-slot="{ items }">
                <PaginationPrevious></PaginationPrevious>

                <template v-for="(item, index) in items" :key="index">
                    <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page">
                        {{ item.value }}
                    </PaginationItem>
                </template>

                <PaginationEllipsis :index="4" />

                <PaginationNext />
            </PaginationContent>
        </Pagination>
    </AppLayout>
</template>
