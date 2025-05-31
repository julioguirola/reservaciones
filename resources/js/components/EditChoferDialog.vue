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
import { Pencil } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from './InputError.vue';
import { useToast } from './ui/toast';

const { toast } = useToast();
const props = defineProps<{
    nombre: string;
    carnet_identidad: string;
    licencia_numero: string;
    persona_id: string;
    chofer_id: string;
    change_page: () => void;
}>();

const errors = ref<{
    carnet_identidad?: string[];
    nombre?: string[];
    licencia_numero?: string[];
}>({});

const nombre = ref(props.nombre);
const carnet_identidad = ref(props.carnet_identidad);
const licencia_numero = ref(props.licencia_numero);

const isOpen = ref(false);

const submit = async () => {
    const res = await fetch(route('choferes.editar', props.chofer_id), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            nombre: nombre.value,
            carnet_identidad: carnet_identidad.value,
            licencia_numero: licencia_numero.value,
            persona_id: props.persona_id,
        }),
    });
    const data = await res.json();
    if (data.errors) {
        errors.value = data.errors;
    } else {
        isOpen.value = false;
        errors.value = {};
        toast({
            title: '✅ Operación realizada',
            description: 'Chofer modificado con éxito',
            duration: 1500,
        });
        props.change_page();
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
                <DialogTitle>Editar chofer</DialogTitle>
                <DialogDescription> Modifica el chofer aquí. Guarda los cambios cuando termines. </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="nombre" class="text-right"> Nombre </Label>
                    <Input id="nombre" v-model="nombre" class="col-span-3" />
                    <InputError v-if="errors.nombre" :message="errors.nombre[0]" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="carnet_identidad" class="text-right"> Carnet de Identidad </Label>
                    <Input id="carnet_identidad" v-model="carnet_identidad" class="col-span-3" />
                    <InputError v-if="errors.carnet_identidad" :message="errors.carnet_identidad[0]" />
                </div>

                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="licencia_numero" class="text-right"> Número de licencia </Label>
                    <Input id="licencia_numero" v-model="licencia_numero" class="col-span-3" />
                    <InputError v-if="errors.licencia_numero" :message="errors.licencia_numero[0]" />
                </div>
            </div>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="submit"> Guardar Cambios</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
