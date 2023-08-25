import { usePage } from '@inertiajs/vue3'

export const Can = {
    install: (v) => {
        const can = (permission) => {
            return usePage().props.auth.permissions.includes(permission)
        }
        
        v.mixin({
            methods: { can }
        })
    }
}