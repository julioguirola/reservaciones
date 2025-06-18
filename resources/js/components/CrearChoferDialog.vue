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
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from './InputError.vue';
import { useToast } from './ui/toast/use-toast';

const { toast } = useToast();

const props = defineProps<{
    change_page: () => void;
}>();

const errors = ref<{
    carnet_identidad?: string[];
    nombre?: string[];
    licencia_numero?: string[];
}>({});

const isOpen = ref(false);

const nombre = ref('');
const carnet_identidad = ref('');
const licencia_numero = ref('');

const submit = async () => {
    errors.value = {};

    const res = await fetch(route('choferes.crear'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            nombre: nombre.value,
            licencia_numero: licencia_numero.value,
            carnet_identidad: carnet_identidad.value,
        }),
    });
    const data = await res.json();
    if (data.errors) {
        errors.value = data.errors;
    } else {
        errors.value = {};
        isOpen.value = false;
        nombre.value = '';
        licencia_numero.value = '';
        carnet_identidad.value = '';
        toast({
            title: '✅ Operación realizada',
            description: 'Nuevo chofer registrado con éxito',
            duration: 1500,
        });
        props.change_page();
    }
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button class="self-end" variant="outline">Registrar nuevo chofer <Plus /></Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Registrar nuevo chofer</DialogTitle>
                <DialogDescription> Introducir información del nuevo chofer. </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="nombre" class="text-right"> Nombre </Label>
                    <Input id="nombre" v-model="nombre" class="col-span-3" />
                    <InputError v-if="errors.nombre" :message="Errors[errors.nombre[0]]" class="w-52" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="carnet_identidad" class="text-right"> Carnet de Identidad </Label>
                    <Input id="carnet_identidad" v-model="carnet_identidad" class="col-span-3" />
                    <InputError v-if="errors.carnet_identidad" :message="Errors[errors.carnet_identidad[0]]" class="w-52" />
                </div>

                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="licencia_numero" class="text-right"> Número de licencia </Label>
                    <Input id="licencia_numero" v-model="licencia_numero" class="col-span-3" />
                    <InputError v-if="errors.licencia_numero" :message="Errors[errors.licencia_numero[0]]" class="w-52" />
                </div>
            </div>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="submit"> Guardar Cambios</Button>
                <DialogClose as-child>
                    <button ref="hiddenCloseBtn" style="display: none"></button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
