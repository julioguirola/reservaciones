<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Pencil, Trash } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profesores',
        href: '/profesores',
    },
];

interface Profesor {
    profesor_id: string;
    nombre: string;
    carnet_identidad: string;
    destino: string;
    facultad: string;
    asignatura: string;
}
const props = defineProps<{
    profesores: Profesor[];
}>();

function deleteProfesor(profesor_id: string) {
    return router.delete(route('profesores.eliminar', { profesor_id }));
}
</script>

<template>
    <Head title="Profesores" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table>
                <TableCaption>Informacion de los profesores</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead> Nombre </TableHead>
                        <TableHead> Carnet de Identidad </TableHead>
                        <TableHead> Origen </TableHead>
                        <TableHead> Facultad </TableHead>
                        <TableHead> Asignatura </TableHead>
                        <TableHead> Acciones </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="profesor in props.profesores" :key="profesor.profesor_id">
                        <TableCell class="font-medium">
                            {{ profesor.nombre }}
                        </TableCell>
                        <TableCell> {{ profesor.carnet_identidad }} </TableCell>
                        <TableCell> {{ profesor.destino }} </TableCell>
                        <TableCell> {{ profesor.facultad }} </TableCell>
                        <TableCell> {{ profesor.asignatura }} </TableCell>
                        <TableCell class="flex gap-1"
                            ><Button class="bg-green-500"><Pencil></Pencil></Button>
                            <Button @click="deleteProfesor(profesor.profesor_id)" class="bg-red-600"><Trash></Trash></Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
