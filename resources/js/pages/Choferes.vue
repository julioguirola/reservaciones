<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Pencil, Trash } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Choferes',
        href: '/choferes',
    },
];
</script>

<template>
    <Head title="Choferes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table>
                <TableCaption>Información de los choferes</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead> Nombre </TableHead>
                        <TableHead> Carnet de Identidad </TableHead>
                        <TableHead> Número de Licencia </TableHead>
                        <TableHead> Acciones </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="chofer in $page.props.choferes" :key="chofer.id">
                        <TableCell class="font-medium">
                            {{ chofer.nombre }}
                        </TableCell>
                        <TableCell> {{ chofer.carnet_identidad }} </TableCell>
                        <TableCell> {{ chofer.licencia_numero }} </TableCell>
                        <TableCell class="flex gap-1"
                            ><Button class="bg-green-500"><Pencil></Pencil></Button><Button class="bg-red-600"><Trash></Trash></Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
