<script setup lang="ts">
import EditChoferDialog from '@/components/EditChoferDialog.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';

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
}>();

async function deleteChofer(chofer_id: string) {
    await fetch(route('choferes.eliminar', { chofer_id }), { method: 'DELETE' });
    router.reload();
}
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
                        <TableHead> Cantidad de viajes </TableHead>
                        <TableHead> Acciones </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="chofer in props.choferes" :key="chofer.id">
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
                            />
                            <Button class="bg-red-600" @click="deleteChofer(chofer.id)"><Trash></Trash></Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
