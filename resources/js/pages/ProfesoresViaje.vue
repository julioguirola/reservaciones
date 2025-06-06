<script setup lang="ts">
import AddProfesorViaje from '@/components/AddProfesorViaje.vue';
import DesvincularProfesorViaje from '@/components/DesvincularProfesorViaje.vue';
import Input from '@/components/ui/input/Input.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
interface Profesor {
    id: string;
    persona_id: string;
    nombre: string;
    carnet_identidad: string;
    destino: string;
    facultad: string;
    asignatura: string;
    destino_id: number;
    facultad_id: number;
    asignatura_id: number;
}
const props = defineProps<{
    profesores: Profesor[];
    viaje_id: number;
    realizado: boolean;
    lleno: boolean;
}>();

const profesores = ref<Profesor[]>(props.profesores);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: `Profesores del viaje ${props.viaje_id}`,
        href: '/profesores',
    },
];

const heads = ['Nombre', 'Destino', 'Acciones'];

interface ProfesorCampos {
    id?: number;
    nombre?: string;
}

const destinos = ref<ProfesorCampos[]>([]);
const asignaturas = ref<ProfesorCampos[]>([]);
const facultades = ref<ProfesorCampos[]>([]);

onMounted(async () => {
    let res = await fetch(route('destinos.data'));
    let data = await res.json();
    destinos.value = data;
    res = await fetch(route('profesores.asignaturas_facultades'));
    data = await res.json();
    asignaturas.value = data.asignaturas;
    facultades.value = data.facultades;
});

const patron = ref('');

const profesoresFiltrados = computed(() => {
    if (!patron.value) return profesores.value;
    return profesores.value.filter((p) => {
        return p.nombre.toLocaleLowerCase().includes(patron.value);
    });
});
</script>

<template>
    <Head :title="`Profesores del viaje ${props.viaje_id}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <p v-if="props.realizado" class="self-center">No se pueden desvincular o asignar profesores a un viaje realizado.</p>
            <p v-if="props.lleno && !props.realizado" class="self-center">Viaje lleno!</p>
            <div class="flex justify-end gap-2">
                <Input v-model="patron" placeholder="Buscar profesor por nombre" class="w-52" />
                <AddProfesorViaje v-if="!props.realizado && !props.lleno" :viaje_id="props.viaje_id" />
            </div>
            <Table v-if="profesoresFiltrados.length">
                <TableCaption>Información de los profesores</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="(head, i) in heads" :key="i"> {{ head }} </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="profesor in profesoresFiltrados" :key="profesor.id">
                        <TableCell class="font-medium">
                            {{ profesor.nombre }}
                        </TableCell>
                        <TableCell> {{ profesor.destino }} </TableCell>
                        <TableCell
                            ><DesvincularProfesorViaje :viaje_id="props.viaje_id" :profesor_id="profesor.id" :viaje_realizado="realizado"
                        /></TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <p v-else class="self-center">No se encuentran profesores con ese criterio de búsqueda.</p>
        </div>
    </AppLayout>
</template>
