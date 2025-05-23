<script setup lang="ts">
import CrearProfesorDialog from '@/components/CrearProfesorDialog.vue';
import EditProfesorDialog from '@/components/EditProfesorDialog.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useToast } from '@/components/ui/toast';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

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
}>();

const heads = ['Nombre', 'Carnet de Identidad', 'Origen', 'Facultad', 'Asignatura'];

async function deleteProfesor(profesor_id: string) {
    await fetch(route('profesores.eliminar', { profesor_id }), { method: 'DELETE' });
    toast({
        title: '✅ Operacion realizada',
        description: 'Profesor eliminado con exito',
        duration: 1500,
    });
    router.reload();
}

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
</script>

<template>
    <Head title="Profesores" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <CrearProfesorDialog :destinos="destinos" :facultades="facultades" :asignaturas="asignaturas" />
            <Table>
                <TableCaption>Informacion de los profesores</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="(head, i) in heads" :key="i"> {{ head }} </TableHead>
                        <TableHead> Acciones </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="profesor in props.profesores" :key="profesor.id">
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
                            />
                            <Button @click="deleteProfesor(profesor.id)" class="bg-red-600"><Trash></Trash></Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
