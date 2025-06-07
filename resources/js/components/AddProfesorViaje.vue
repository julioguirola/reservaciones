<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type Profesor } from '@/types';
import { Plus } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { toast } from './ui/toast';
const props = defineProps<{
    refresh: () => void;
    viaje_id: number;
}>();

const profesores_not = ref<Profesor[]>([]);
const heads = ['Nombre', 'Destino', 'Carnet de identidad', 'Acciones'];

const isOpen = ref(false);
onMounted(async () => {
    const res = await fetch(route('viajes.profesores', { viaje_id: props.viaje_id }) + '?not_in_viaje=1');
    const data = await res.json();
    profesores_not.value = data.profesores_not_viaje;
});

const addProfesorViaje = async (profesor_id: string) => {
    const res = await fetch(route('viajes.addProfesor', { viaje_id: props.viaje_id }), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            profesor_id: profesor_id,
        }),
    });
    const data = await res.json();
    if (data.error) {
        toast({
            title: 'Error',
            duration: 1500,
        });
    } else {
        toast({
            title: '✅ Operación realizada',
            description: 'Profesor agregado al viaje con éxito',
            duration: 1500,
        });
        isOpen.value = false;
        props.refresh();
    }
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button variant="outline">Asignar profesor</Button>
        </DialogTrigger>
        <DialogContent class="h-[90vh] sm:max-w-[750px]">
            <DialogHeader>
                <DialogTitle> Agregar profesor al viaje </DialogTitle>
                <DialogDescription>Seleccionar profesores para agregar al viaje</DialogDescription>
            </DialogHeader>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="(head, i) in heads" :key="i"> {{ head }} </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="profesor in profesores_not" :key="profesor.id">
                        <TableCell class="font-medium">
                            {{ profesor.nombre }}
                        </TableCell>
                        <TableCell> {{ profesor.destino }} </TableCell>
                        <TableCell> {{ profesor.carnet_identidad }} </TableCell>
                        <TableCell>
                            <Button variant="outline" @click="addProfesorViaje(profesor.id)"><Plus></Plus>Agregar</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
