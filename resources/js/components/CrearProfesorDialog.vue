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
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from './InputError.vue';
import { useToast } from './ui/toast';

const { toast } = useToast();

interface ProfesorCampos {
    id?: number;
    nombre?: string;
}

const errors = ref<{
    carnet_identidad?: string[];
    nombre?: string[];
    facultad_id?: string[];
    asignatura_id?: string[];
    destino_id?: string[];
}>({});

const props = defineProps<{
    destinos: ProfesorCampos[];
    asignaturas: ProfesorCampos[];
    facultades: ProfesorCampos[];
    change_page: () => void;
}>();

const isOpen = ref(false);

const nombre = ref('');
const carnet_identidad = ref('');
const destino_seleccionado = ref('');
const asignatura_seleccionada = ref('');
const facultad_seleccionada = ref('');

const submit = async () => {
    errors.value = {};

    const res = await fetch(route('profesores.crear'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            nombre: nombre.value,
            carnet_identidad: carnet_identidad.value,
            facultad_id: facultad_seleccionada.value,
            asignatura_id: asignatura_seleccionada.value,
            destino_id: destino_seleccionado.value,
        }),
    });
    const data = await res.json();
    if (data.errors) {
        errors.value = data.errors;
    } else {
        errors.value = {};
        isOpen.value = false;
        nombre.value = '';
        carnet_identidad.value = '';
        facultad_seleccionada.value = '';
        asignatura_seleccionada.value = '';
        destino_seleccionado.value = '';
        toast({
            title: '✅ Operación realizada',
            description: 'Nuevo profesor registrado con éxito',
            duration: 1500,
        });
        props.change_page();
    }
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button class="self-end" variant="outline">Registrar nuevo profesor<Plus /></Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Registrar nuevo profesor</DialogTitle>
                <DialogDescription> Introducir información del nuevo profesor </DialogDescription>
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
                    <Label for="origen" class="text-right"> Origen</Label>

                    <Select id="origen" v-model="destino_seleccionado">
                        <SelectTrigger class="col-span-3">
                            <SelectValue placeholder="Selecciona una provincia" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="destino in props.destinos" :value="destino.id!" :key="destino.id">
                                    {{ destino.nombre }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError v-if="errors.destino_id" :message="errors.destino_id[0]" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="asignatura" class="text-right"> Asignatura</Label>

                    <Select id="asignatura" v-model="asignatura_seleccionada">
                        <SelectTrigger class="col-span-3">
                            <SelectValue placeholder="Selecciona una asignatura" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="asignatura in props.asignaturas" :value="asignatura.id!" :key="asignatura.id">
                                    {{ asignatura.nombre }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError v-if="errors.asignatura_id" :message="errors.asignatura_id[0]" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="facultad" class="text-right"> Facultad</Label>

                    <Select id="facultad" v-model="facultad_seleccionada">
                        <SelectTrigger class="col-span-3">
                            <SelectValue placeholder="Selecciona una facultad" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="facultad in props.facultades" :value="facultad.id!" :key="facultad.id">
                                    {{ facultad.nombre }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError v-if="errors.facultad_id" :message="errors.facultad_id[0]" />
                </div>
            </div>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="submit"> Guardar </Button>
                <DialogClose as-child>
                    <button ref="hiddenCloseBtn" style="display: none"></button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
