<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Bus, BusFront, MapPin, UserCog, UserPen } from 'lucide-vue-next';
const { state } = useSidebar();

const mainNavItems: NavItem[] = [
    {
        title: 'Viajes',
        href: '/viajes',
        icon: Bus,
    },
    {
        title: 'Destinos',
        href: '/destinos',
        icon: MapPin,
    },
    {
        title: 'Profesores',
        href: '/profesores',
        icon: UserPen,
    },
    {
        title: 'Choferes',
        href: '/choferes',
        icon: UserCog,
    },
];

// const footerNavItems: NavItem[] = [
//     {
//         title: 'Github Repo',
//         href: 'https://github.com/laravel/vue-starter-kit',
//         icon: Folder,
//     },
//     {
//         title: 'Documentation',
//         href: 'https://laravel.com/docs/starter-kits',
//         icon: BookOpen,
//     },
// ];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="flex justify-center">
                        <Link :href="route('viajes')">
                            <component :is="BusFront" />
                            <span class="mb-0.5 truncate font-semibold leading-none" v-if="state === 'expanded'">Reservaciones</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
