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
import { router } from '@inertiajs/vue3';
import { ListMinus } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from './ui/toast';

const props = defineProps<{
    viaje_id: number;
    profesor_id: string;
    viaje_realizado: boolean;
}>();

async function removeProfesorViaje() {
    const res = await fetch(route('viajes.removeProfesor', { viaje_id: props.viaje_id }), {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ profesor_id: props.profesor_id }),
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
            description: `Profesor desvinculado con éxito`,
            duration: 1500,
        });
        router.reload();
        isOpen.value = false;
    }
}

const isOpen = ref(false);
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button variant="outline" :disabled="props.viaje_realizado"><ListMinus />Desvincular</Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle> Desvincular </DialogTitle>
                <DialogDescription> Está seguro de que desea desvincular este profesor </DialogDescription>
            </DialogHeader>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="removeProfesorViaje" class="bg-red-600"> Desvincular </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
