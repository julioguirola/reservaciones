<script setup lang="ts">
import CrearProfesorDialog from '@/components/CrearProfesorDialog.vue';
import EditProfesorDialog from '@/components/EditProfesorDialog.vue';
import EliminarPersonaDialog from '@/components/EliminarPersonaDialog.vue';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import ViajesCounter from '@/components/ViajesCounter.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Profesor, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profesores',
        href: '/profesores',
    },
];

const props = defineProps<{
    profesores: Profesor[];
    profesores_cant: number;
}>();

const heads = ['Nombre', 'Carnet de Identidad', 'Origen', 'Facultad', 'Asignatura'];

interface ProfesorCampos {
    id?: number;
    nombre?: string;
}

const destinos = ref<ProfesorCampos[]>([]);
const asignaturas = ref<ProfesorCampos[]>([]);
const facultades = ref<ProfesorCampos[]>([]);

const profesores = ref<Profesor[]>(props.profesores);
const actual_page = ref<number>(1);
const profesores_cant = ref<number>(props.profesores_cant);

const change_page = async () => {
    const cond = parseInt(cant_viajes.value) >= 0;

    const res = await fetch(
        route('profesores.data') +
            `?page=${actual_page.value - 1}` +
            (cond ? `&cant_viajes=${cant_viajes.value}` : '') +
            (carnet.value ? `&carnet=${carnet.value}` : ''),
    );
    const data = await res.json();
    profesores.value = data.profesores;
    profesores_cant.value = data.profesores_cant;
};

watch(actual_page, async () => {
    change_page();
});

onMounted(async () => {
    let res = await fetch(route('destinos.data'));
    let data = await res.json();
    destinos.value = data;
    res = await fetch(route('profesores.asignaturas_facultades'));
    data = await res.json();
    asignaturas.value = data.asignaturas;
    facultades.value = data.facultades;
});

const cant_viajes = ref();
const carnet = ref();

watch(cant_viajes, () => {
    filtrarProfesores.value = { cv: cant_viajes.value, ci: carnet.value };
});

watch(carnet, () => {
    filtrarProfesores.value = { cv: cant_viajes.value, ci: carnet.value };
});

const filtrarProfesores = computed({
    async set(filtros: { cv: string; ci: string }) {
        const { cv, ci } = filtros;
        actual_page.value = 1;
        const cond = parseInt(cv) >= 0;
        const res = await fetch(
            route('profesores.data') +
                `?page=${actual_page.value - 1}` +
                (cond ? `&cant_viajes=${filtros.cv}` : '') +
                (ci ? `&carnet=${filtros.ci}` : ''),
        );
        const data = await res.json();
        profesores.value = data.profesores;
        profesores_cant.value = data.profesores_cant;
    },
    get() {
        return props.profesores;
    },
});
</script>

<template>
    <Head title="Profesores" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end gap-2">
                <Label for="carnteFilter" class="self-center">Filtrar por cantidad de viajes</Label>
                <Input v-model="cant_viajes" class="w-20" type="number" min="0" id="cantViajesFilter" placeholder="#" />
                <Input v-model="carnet" class="w-52" placeholder="Buscar profesor por carnet" />
                <CrearProfesorDialog :destinos="destinos" :facultades="facultades" :asignaturas="asignaturas" :change_page="change_page" />
            </div>
            <Table>
                <TableCaption>Informacion de los profesores</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="(head, i) in heads" :key="i"> {{ head }} </TableHead>
                        <TableHead> Cantidad de Viajes </TableHead>
                        <TableHead> Acciones </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="profesor in profesores" :key="profesor.id">
                        <TableCell class="font-medium">
                            {{ profesor.nombre }}
                        </TableCell>
                        <TableCell> {{ profesor.carnet_identidad }} </TableCell>
                        <TableCell> {{ profesor.destino }} </TableCell>
                        <TableCell> {{ profesor.facultad }} </TableCell>
                        <TableCell> {{ profesor.asignatura }} </TableCell>
                        <TableCell
                            ><ViajesCounter
                                :cant_viajes="profesor.cant_viajes"
                                :cant_viajes_abril_julio="profesor.cant_viajes_abril_julio"
                                :cant_viajes_agosto_septiembre="profesor.cant_viajes_agosto_septiembre"
                                :cant_viajes_octubre_marzo="profesor.cant_viajes_octubre_marzo"
                        /></TableCell>
                        <TableCell class="flex gap-1">
                            <EditProfesorDialog
                                :nombre="profesor.nombre"
                                :carnet_identidad="profesor.carnet_identidad"
                                :destino_id="profesor.destino_id"
                                :facultad_id="profesor.facultad_id"
                                :asignatura_id="profesor.asignatura_id"
                                :profesor_id="profesor.id"
                                :persona_id="profesor.persona_id"
                                :destinos="destinos"
                                :facultades="facultades"
                                :asignaturas="asignaturas"
                                :change_page="change_page"
                            />
                            <EliminarPersonaDialog :persona_cargo_id="profesor.id" :change_page="change_page" persona_cargo="profesor" />
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <Pagination v-model:page="actual_page" v-slot="{ page }" :items-per-page="5" :total="profesores_cant" :default-page="1">
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
