import type { Settings } from '~/types/api.ts'

export default defineNuxtPlugin(async () => {
    const response = await fetch('/config.json', { cache: 'no-store' });
    if (!response.ok) {
        throw new Error(`Failed to load config.json (${response.status})`);
    }
    const settings: Settings = await response.json();
    return { provide: { settings } };


 
})