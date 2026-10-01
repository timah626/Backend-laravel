import {parse} from 'cookie-es'

export default defineNuxtPlugin(() => {
    const { $settings } = useNuxtApp();
    const apiClient = $fetch.create({
        baseURL: $settings.apiBase,
        credentials: 'include',
        headers: { Accept: 'application/json' },

        onRequest({ options }) {
            const method = (options.method ?? 'GET').toUpperCase()
            if (method !== 'GET' && method !== 'HEAD') {
                const token = parse(document.cookie || '')['XSRF-TOKEN']
                if (token) {
                const headers = new Headers(options.headers)
                headers.set('X-XSRF-TOKEN', token)
                options.headers = headers
                }
            }
        }

    });

    
    async function request<T>(path: string, options?: Parameters<typeof apiClient>[1]){
        try{
            const fullPath = `${$settings.apiPrefix}${path}`
            return await apiClient<T>(fullPath, options)
        }catch(e){
            throw toApiError(e)
        }
    }
    const csrf = () => {
        return apiClient('/sanctum/csrf-cookie')
    }


    return { provide: { api: { request, csrf } } };

})




