<script setup lang="ts">
import CrearProfesorDialog from '@/components/CrearProfesorDialog.vue';
import EditProfesorDialog from '@/components/EditProfesorDialog.vue';
import { Button } from '@/components/ui/button';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useToast } from '@/components/ui/toast';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

const { toast } = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profesores',
        href: '/profesores',
    },
];

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
    profesores_cant: number;
}>();

const heads = ['Nombre', 'Carnet de Identidad', 'Origen', 'Facultad', 'Asignatura'];

async function deleteProfesor(profesor_id: string) {
    await fetch(route('profesores.eliminar', { profesor_id }), { method: 'DELETE' });
    toast({
        title: '✅ Operación realizada',
        description: 'Profesor eliminado con éxito',
        duration: 1500,
    });
    change_page();
}

interface ProfesorCampos {
    id?: number;
    nombre?: string;
}

const destinos = ref<ProfesorCampos[]>([]);
const asignaturas = ref<ProfesorCampos[]>([]);
const facultades = ref<ProfesorCampos[]>([]);

const profesores = ref<Profesor[]>(props.profesores);
const actual_page = ref<number>(0);
const profesores_cant = ref<number>(props.profesores_cant);

const change_page = async () => {
    const res = await fetch(route('profesores.data') + `?page=${actual_page.value - 1}`);
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
</script>

<template>
    <Head title="Profesores" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <CrearProfesorDialog :destinos="destinos" :facultades="facultades" :asignaturas="asignaturas" :change_page="change_page" />
            <Table>
                <TableCaption>Informacion de los profesores</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="(head, i) in heads" :key="i"> {{ head }} </TableHead>
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
                            <Button @click="deleteProfesor(profesor.id)" class="bg-red-600"><Trash></Trash></Button>
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
