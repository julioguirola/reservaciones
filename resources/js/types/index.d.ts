import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

interface ProfesorCampos {
    id?: number;
    nombre?: string;
}

interface Profesor {
    id: string;
    persona_id: string;
    nombre: string;
    carnet_identidad: string;
    destino: string;
    facultad: string;
    asignatura: string;
    destino_id: number;
    facultad_id: number;
    asignatura_id: number;
}

interface Chofer {
    id: string;
    persona_id: string;
    licencia_numero: string;
    carnet_identidad: string;
    nombre: string;
    cant_viajes: number;
}

interface Viaje {
    id: number;
    chofer_nombre: string;
    fecha: string;
    destinos: string[];
    profesores_count: number;
    recaudado: number;
    realizado: boolean;
}
export type BreadcrumbItemType = BreadcrumbItem;
