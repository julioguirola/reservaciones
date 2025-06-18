import { ValidationErrors } from '@/types';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const Errors: ValidationErrors = {
    'validation.min.string': 'Nombre demasiado corto (5-30)',
    'validation.digits': 'Formato incorrecto',
    'validation.max.string': 'Nombre demasiado largo (5-30)',
    'validation.numeric': 'El campo debe ser numérico',
    'validation.min.numeric': 'El precio debe estar entre $1 y $300',
    'validation.max.numeric': 'El precio debe estar entre $1 y $300',
    'validation.required': 'Campo requerido',
};
