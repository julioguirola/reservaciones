<script setup lang="ts">
import EditDestinoDialog from '@/components/EditDestinoDialog.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Destino {
    id: number;
    nombre: string;
    precio: number;
}

const props = defineProps<{
    destinos: Destino[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Destinos',
        href: '/destinos',
    },
];
</script>

<template>
    <Head title="Destinos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table>
                <TableCaption>Tarifas de los destinos</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead> Destino </TableHead>
                        <TableHead> Precio </TableHead>
                        <TableHead> Acciones </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="destino in props.destinos" :key="destino.id">
                        <TableCell class="font-medium">
                            {{ destino.nombre }}
                        </TableCell>
                        <TableCell> {{ destino.precio }} $ </TableCell>
                        <TableCell><EditDestinoDialog :precio="destino.precio" :destino_id="destino.id" :nombre="destino.nombre" /></TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
