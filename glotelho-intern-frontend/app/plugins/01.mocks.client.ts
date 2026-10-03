export default defineNuxtPlugin(async () => {
    const {$settings} = useNuxtApp()
    if(!$settings.useMocks) return

    const { worker } = await import('~/mocks/browser')
    await worker.start({onUnhandledRequest: 'bypass'})
    
})