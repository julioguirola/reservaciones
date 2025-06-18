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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Errors } from '@/lib/utils';
import { router } from '@inertiajs/vue3';
import { Pencil } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from './InputError.vue';
import { useToast } from './ui/toast';

const { toast } = useToast();
const props = defineProps<{
    precio: number;
    destino_id: number;
    nombre: string;
}>();

const errors = ref<{
    precio?: string[];
}>({});

const isOpen = ref(false);

const precio = ref(props.precio);

const submit = async () => {
    const res = await fetch(route('destinos.editar', props.destino_id), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            precio: precio.value,
        }),
    });
    const data = await res.json();
    if (data.errors) {
        errors.value = data.errors;
    } else {
        isOpen.value = false;
        toast({
            title: '✅ Operación realizada',
            description: 'Destino modificado con éxito',
            duration: 1500,
        });
        router.reload();
    }
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button class="bg-green-600"> <Pencil /> </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Editar precio para {{ props.nombre }}</DialogTitle>
                <DialogDescription> Modifica el precio del destino aquí. Guarda los cambios cuando termines. </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="nombre" class="text-right"> Precio </Label>
                    <Input id="nombre" v-model="precio" class="col-span-3" />
                    <InputError v-if="errors.precio" :message="Errors[errors.precio[0]]" class="w-52" />
                </div>
            </div>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="submit"> Guardar Cambios </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
