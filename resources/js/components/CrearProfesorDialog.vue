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
import { onMounted, ref } from 'vue';

interface ProfesorCampos {
    id?: number;
    nombre?: string;
}

const nombre = ref('');
const carnet_identidad = ref('');
const destino_seleccionado = ref('');
const asignatura_seleccionada = ref('');
const facultad_seleccionada = ref('');

let destinos: ProfesorCampos[] = [];
let asignaturas: ProfesorCampos[] = [];
let facultades: ProfesorCampos[] = [];

onMounted(async () => {
    let res = await fetch(route('destinos.data'));
    let data = await res.json();
    destinos = data;
    res = await fetch(route('profesores.asignaturas'));
    data = await res.json();
    asignaturas = data;
    res = await fetch(route('profesores.facultades'));
    data = await res.json();
    facultades = data;
});

const submit = async () => {
    await fetch(route('profesores.crear'), {
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
};
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button class="self-end" variant="outline">Registrar nuevo profesor<Plus /></Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Registrar nuevo profesor</DialogTitle>
                <DialogDescription> Introducir informació del nuevo profesor </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="nombre" class="text-right"> Nombre </Label>
                    <Input id="nombre" v-model="nombre" class="col-span-3" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="carnet_identidad" class="text-right"> Carnet de Identidad </Label>
                    <Input id="carnet_identidad" v-model="carnet_identidad" class="col-span-3" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="origen" class="text-right"> Origen</Label>

                    <Select id="origen" v-model="destino_seleccionado">
                        <SelectTrigger class="col-span-3">
                            <SelectValue placeholder="Selecciona una provincia" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="destino in destinos" :value="destino.id!" :key="destino.id"> {{ destino.nombre }} </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="asignatura" class="text-right"> Asignatura</Label>

                    <Select id="asignatura" v-model="asignatura_seleccionada">
                        <SelectTrigger class="col-span-3">
                            <SelectValue placeholder="Selecciona una asignatura" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="asignatura in asignaturas" :value="asignatura.id!" :key="asignatura.id">
                                    {{ asignatura.nombre }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="facultad" class="text-right"> Facultad</Label>

                    <Select id="facultad" v-model="facultad_seleccionada">
                        <SelectTrigger class="col-span-3">
                            <SelectValue placeholder="Selecciona una facultad" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="facultad in facultades" :value="facultad.id!" :key="facultad.id">
                                    {{ facultad.nombre }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="submit"> Guardar </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
