<script setup lang="ts">
import DestinosViajes from '@/components/DestinosViajes.vue';
import ProfesoresCant from '@/components/ProfesoresCant.vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn } from '@/lib/utils';
import { Viaje, type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { DateFormatter, getLocalTimeZone } from '@internationalized/date';
import { CalendarDays, CalendarIcon, UserCog } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
});

const date = ref();
const props = defineProps<{
    viajes: Viaje[];
    viajes_cant: number;
}>();

const viajes = ref<Viaje[]>(props.viajes);
const actual_page = ref<number>(1);
const viajes_cant = ref<number>(props.viajes_cant);

const change_page = async () => {
    const res = await fetch(route('viajes.data') + `?page=${actual_page.value - 1}` + (date.value ? `&fecha=${date.value.toString()}` : ''));
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
watch(date, () => {
    filtrarViajes.value = date.value.toString();
});
const filtrarViajes = computed({
    async set(date: string) {
        actual_page.value = 1;
        const res = await fetch(route('viajes.data') + `?page=${actual_page.value - 1}` + `&fecha=${date}`);
        const data = await res.json();
        viajes.value = data.viajes;
        viajes_cant.value = data.viajes_cant;
    },
    get() {
        return viajes.value;
    },
});
</script>

<template>
    <Head title="Viajes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid h-full gap-4 rounded-xl p-4">
            <Label for="calendar" class="self-center">Buscar viaje por fecha</Label>
            <Popover>
                <PopoverTrigger as-child>
                    <Button
                        id="calendar"
                        variant="outline"
                        :class="cn('w-[280px] justify-start text-left font-normal', !date && 'text-muted-foreground')"
                    >
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ date ? df.format(date.toDate(getLocalTimeZone())) : 'Selecciona una fecha' }}
                    </Button>
                </PopoverTrigger>
                <PopoverContent class="w-auto p-0">
                    <Calendar v-model="date" initial-focus />
                </PopoverContent>
            </Popover>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <Card v-for="viaje in viajes" :key="viaje.id">
                    <CardHeader class="flex flex-row items-center justify-between py-2">
                        <CardTitle class="text-[#1E3A8A]">Viaje {{ viaje.id }}</CardTitle>
                        {{ viaje.fecha.split(' ')[0] }}<CalendarDays color="#64748B" />
                    </CardHeader>
                    <CardContent class="flex flex-row items-center justify-between py-2"
                        ><UserCog color="#4B5563" /> {{ viaje.chofer_nombre
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
