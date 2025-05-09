<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Pencil } from 'lucide-vue-next';

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
                    <TableRow v-for="destino in $page.props.destinos" :key="destino.id">
                        <TableCell class="font-medium">
                            {{ destino.nombre }}
                        </TableCell>
                        <TableCell> {{ destino.precio }} $ </TableCell>
                        <TableCell
                            ><Button class="bg-green-500"><Pencil></Pencil></Button
                        ></TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
