<script setup lang="ts">
import CrearChoferDialog from '@/components/CrearChoferDialog.vue';
import EditChoferDialog from '@/components/EditChoferDialog.vue';
import EliminarPersonaDialog from '@/components/EliminarPersonaDialog.vue';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Choferes',
        href: '/choferes',
    },
];

interface Chofer {
    id: string;
    persona_id: string;
    licencia_numero: string;
    carnet_identidad: string;
    nombre: string;
    cant_viajes: number;
}

const props = defineProps<{
    choferes: Chofer[];
    choferes_cant: number;
}>();

const actual_page = ref<number>(0);
const choferes = ref<Chofer[]>(props.choferes);
const choferes_cant = ref<number>(props.choferes_cant);

const change_page = async () => {
    const res = await fetch(route('choferes.data') + `?page=${actual_page.value - 1}`);
    const data = await res.json();
    choferes.value = data.choferes;
    choferes_cant.value = data.choferes_cant;
};

watch(actual_page, async () => {
    change_page();
});
</script>

<template>
    <Head title="Choferes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <CrearChoferDialog :change_page="change_page" />
            <Table>
                <TableCaption>Información de los choferes</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead> Nombre </TableHead>
                        <TableHead> Carnet de Identidad </TableHead>
                        <TableHead> Número de Licencia </TableHead>
                        <TableHead> Cantidad de viajes </TableHead>
                        <TableHead> Acciones </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="chofer in choferes" :key="chofer.id">
                        <TableCell class="font-medium">
                            {{ chofer.nombre }}
                        </TableCell>
                        <TableCell> {{ chofer.carnet_identidad }} </TableCell>
                        <TableCell> {{ chofer.licencia_numero }} </TableCell>
                        <TableCell> {{ chofer.cant_viajes }} </TableCell>
                        <TableCell class="flex gap-1">
                            <EditChoferDialog
                                :licencia_numero="chofer.licencia_numero"
                                :nombre="chofer.nombre"
                                :persona_id="chofer.persona_id"
                                :chofer_id="chofer.id"
                                :carnet_identidad="chofer.carnet_identidad"
                                :change_page="change_page"
                            />
                            <EliminarPersonaDialog :persona_cargo_id="chofer.id" :change_page="change_page" persona_cargo="chofer" />
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <Pagination v-model:page="actual_page" v-slot="{ page }" :items-per-page="5" :total="choferes_cant" :default-page="1">
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
        </div>
    </AppLayout>
</template>
