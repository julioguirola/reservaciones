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
import { Trash } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from './ui/toast';

const props = defineProps<{
    viaje_id: number;
    change_page: () => void;
}>();

async function deleteViaje(viaje_id: number) {
    await fetch(route(`viajes.eliminar`, { viaje_id }), {
        method: 'DELETE',
    });
    toast({
        title: '✅ Operación realizada',
        description: `Viaje eliminado con éxito`,
        duration: 1500,
    });
    props.change_page();
    isOpen.value = false;
}

const isOpen = ref(false);
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button class="h-8 w-10 bg-red-600"><Trash></Trash></Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Eliminar Viaje {{ props.viaje_id }}</DialogTitle>
                <DialogDescription> ¿ Está seguro de que desea eliminar este viaje ? </DialogDescription>
            </DialogHeader>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="deleteViaje(props.viaje_id)" class="bg-red-600"> Eliminar</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
