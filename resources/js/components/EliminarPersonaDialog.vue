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
    persona_cargo_id: string;
    change_page: () => void;
    persona_cargo: string;
}>();

async function deletePersona(persona_cargo_id: string) {
    await fetch(
        route(
            `${props.persona_cargo === 'profesor' ? 'profesores' : 'choferes'}.eliminar`,
            props.persona_cargo === 'profesor' ? { profesor_id: persona_cargo_id } : { chofer_id: persona_cargo_id },
        ),
        {
            method: 'DELETE',
        },
    );
    toast({
        title: '✅ Operación realizada',
        description: `${props.persona_cargo === 'profesor' ? 'Profesor' : 'Chofer'} eliminado con éxito`,
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
            <Button class="bg-red-600"><Trash></Trash></Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Eliminar {{ props.persona_cargo }}</DialogTitle>
                <DialogDescription> Está seguro de que desea eliminar este {{ props.persona_cargo }} </DialogDescription>
            </DialogHeader>
            <DialogFooter
                ><DialogClose> <Button variant="outline"> Cancelar </Button></DialogClose>

                <Button @click="deletePersona(props.persona_cargo_id)" class="bg-red-600"> Eliminar</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
