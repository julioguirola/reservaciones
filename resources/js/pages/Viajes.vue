<script setup lang="ts">
import DestinosViajes from '@/components/DestinosViajes.vue';
import ProfesoresCant from '@/components/ProfesoresCant.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { CalendarDays, UserCog } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Viajes',
        href: '/viajes',
    },
];
</script>

<template>
    <Head title="Viajes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid h-full gap-4 rounded-xl p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <Card v-for="viaje in $page.props.viajes" :key="viaje.id">
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle>Viaje {{ viaje.id }}</CardTitle>
                        <ProfesoresCant :viaje_id="viaje.id" />
                    </CardHeader>
                    <CardContent class="flex items-center gap-1"><UserCog /> {{ viaje.chofer_nombre }} </CardContent>
                    <CardContent class="flex items-center gap-1"> <CalendarDays />{{ viaje.fecha }}</CardContent>
                    <CardContent><DestinosViajes :viaje_id="viaje.id"></DestinosViajes></CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
