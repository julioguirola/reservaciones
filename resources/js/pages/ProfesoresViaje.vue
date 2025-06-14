<script setup lang="ts">
import AddProfesorViaje from '@/components/AddProfesorViaje.vue';
import DesvincularProfesorViaje from '@/components/DesvincularProfesorViaje.vue';
import Input from '@/components/ui/input/Input.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Profesor, ProfesorCampos, type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    profesores: Profesor[];
    viaje_id: number;
    realizado: boolean;
    lleno: boolean;
    destinos: ProfesorCampos[];
}>();

const profesores = ref<Profesor[]>(props.profesores);
const destino_seleccionado = ref(0);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: `Profesores del viaje ${props.viaje_id}`,
        href: '/profesores',
    },
];

const heads = ['Nombre', 'Destino', 'Acciones'];

const patron = ref('');

const profesoresFiltrados = computed(() => {
    if (!destino_seleccionado.value && !patron.value) return profesores.value;
    return profesores.value.filter((p) => {
        return (
            p.nombre.toLocaleLowerCase().includes(patron.value) && (p.destino_id === destino_seleccionado.value || destino_seleccionado.value === 0)
        );
    });
});

function refresh() {
    router.get(route('viajes.profesores', { viaje_id: props.viaje_id }));
}
</script>

<template>
    <Head :title="`Profesores del viaje ${props.viaje_id}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <p v-if="props.realizado" class="self-center">No se pueden desvincular o asignar profesores a un viaje realizado.</p>
            <p v-if="props.lleno && !props.realizado" class="self-center">Viaje lleno!</p>
            <div class="flex justify-end gap-2">
                <Select id="origen" v-model="destino_seleccionado">
                    <SelectTrigger class="col-span-3 w-52">
                        <SelectValue placeholder="Selecciona una provincia" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem :value="0" :key="0"> -- Filtrar por destino --</SelectItem>

                            <SelectItem v-for="destino in props.destinos" :value="destino.id!" :key="destino.id"> {{ destino.nombre }} </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
                <Input v-model="patron" placeholder="Buscar profesor por nombre" class="w-52" />
                <AddProfesorViaje v-if="!props.realizado && !props.lleno" :viaje_id="props.viaje_id" :refresh="refresh" />
            </div>
            <div class="flex w-full gap-4">
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
                                ><DesvincularProfesorViaje
                                    :viaje_id="props.viaje_id"
                                    :profesor_id="profesor.id"
                                    :viaje_realizado="realizado"
                                    :refresh="refresh"
                            /></TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <p v-else class="self-center">No se encuentran profesores con ese criterio de búsqueda.</p>
            </div>
        </div>
    </AppLayout>
</template>
