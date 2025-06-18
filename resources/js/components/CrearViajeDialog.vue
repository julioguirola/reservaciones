<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
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
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn, Errors } from '@/lib/utils';
import { Chofer } from '@/types';
import { DateFormatter, DateValue, getLocalTimeZone } from '@internationalized/date';
import { CalendarIcon, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from './InputError.vue';
import { useToast } from './ui/toast';

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
});

const { toast } = useToast();
const props = defineProps<{
    choferes?: Chofer[];
    change_page: () => void;
}>();
const date = ref<DateValue>();
const chofer_seleccionado = ref<string>();

const errors = ref<{
    fecha?: string[];
    chofer_id?: string[];
}>({});

const isOpen = ref(false);

const submit = async () => {
    const res = await fetch(route('viajes.crear'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            fecha: date.value?.toString(),
            chofer_id: chofer_seleccionado.value,
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
            description: 'Viaje creado con éxito',
            duration: 1500,
        });
        props.change_page();
    }
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button variant="outline">Crear Viaje <Plus /> </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Crear Viaje</DialogTitle>
                <DialogDescription> Introduce la información del viaje aquí. Guarda los cambios cuando termines. </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="calendarEdit" class="text-right">Fecha</Label>
                    <Popover>
                        <PopoverTrigger as-child>
                            <Button
                                id="calendarEdit"
                                variant="outline"
                                :class="cn('w-[280px] justify-start text-left font-normal', !date && 'text-muted-foreground')"
                            >
                                <CalendarIcon class="mr-2 h-4 w-4" />
                                {{ date ? df.format(date.toDate(getLocalTimeZone())) : 'Selecciona una fecha' }}
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0">
                            <Calendar v-model="date" initial-focus />
                        </PopoverContent>
                    </Popover>
                </div>
                <InputError v-if="errors.fecha" :message="Errors[errors.fecha[0]]" class="w-52" />
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="chofer" class="text-right"> Chofer </Label>

                    <Select v-model="chofer_seleccionado">
                        <SelectTrigger id="chofer" class="col-span-3">
                            <SelectValue placeholder="Selecciona un chofer" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="chofer in props.choferes" :value="chofer.id!" :key="chofer.id"> {{ chofer.nombre }} </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError v-if="errors.chofer_id" :message="Errors[errors.chofer_id[0]]" class="w-52" />
                </div>
            </div>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="submit"> Guardar </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
