import { http, HttpResponse} from 'msw'
const accounts: Record<string, any> ={
    'intern@demo.test':   {
        id: '01DEMOINTERN000000000000000',
        email: 'intern@demo.test',
        name: 'Demo Intern',
        role: 'intern',
        department: { id: '01DEMODEPT00000000000000000', name: 'Engineering' },
        temp_password_active: true,
    },
    'head@demo.test': {
        id: '01DEMOHEAD000000000000000',
        email: 'head@demo.test',
        name: 'Demo Head',
        role: 'intern_head',
        department: { id: '01DEMODEPT00000000000000000', name: 'Engineering' },
        temp_password_active: false,
    },
    'admin@demo.test': {
        id: '01DEMOADMIN000000000000000',
        email: 'admin@demo.test',
        name: 'Demo Admin',
        role: 'admin',
        department: null,
        temp_password_active: false,
    }

}
const passwords: Record<string, string> = {
    'intern@demo.test': 'Password1!',
    'head@demo.test': 'Password1!',
    'admin@demo.test': 'Password1!',
}
function currentUser() {
    const email = sessionStorage.getItem('mock-logged-in')
    return email? accounts[email] ?? null : null
}


const passwordRule = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/

export const handlers = [
    //csrf cookie handler(mocked)
    http.get('*/sanctum/csrf-cookie', () => {
        document.cookie = "XSRF-TOKEN=mocked_csrf_token; path=/";
        return new HttpResponse(null, { status: 204 })
    }),
    //login handler(mocked)
    http.post('*/api/v1/auth/login', async ({request}) => {
        const body = await request.json() as { email?: string; password?: string }
        if(!body.email || !body.password ){
            return HttpResponse.json({ error: { code: 'VALIDATION_FAILED', message: 'Email and password are required.' } }, { status: 422 })
        }
        if(!accounts[body.email] || body.password !== passwords[body.email]){
            return HttpResponse.json({ error: { code: 'INVALID_CREDENTIALS', message: 'Email or password is incorrect.' } }, { status: 401 })
        }
        sessionStorage.setItem('mock-logged-in', body.email)
        return HttpResponse.json({
            user: accounts[body.email],
            }, { status: 200 })
    }),
    //me handler(mocked)
    http.get('*/api/v1/auth/me', () => {
        const user = currentUser()
        if(!user){
            return HttpResponse.json({ error: {code: 'UNAUTHENTICATED', message: 'User is not authenticated.'} }, { status: 401 })
        }
        return HttpResponse.json({
            user: accounts[user.email],
        }, { status: 200 })
    }),
    //logout handler
    http.post('*/api/v1/auth/logout', () => {
        sessionStorage.removeItem('mock-logged-in')
        return new HttpResponse(null, { status: 204 })
    }),
    //change password handler(mocked)
    http.post('*/api/v1/auth/password', async ({request}) => {
        const user = currentUser()
        const body = await request.json() as { current_password?: string; new_password?: string }
        
        if(!user){
            return HttpResponse.json({ error: {code: 'UNAUTHENTICATED', message: 'User is not authenticated.'} }, { status: 401 })
        }
        if(!body.current_password || !body.new_password){
            return HttpResponse.json({ error: { code: 'VALIDATION_FAILED', message: 'Current and new passwords are required.' } }, { status: 422 })
        }
        if(body.current_password !== passwords[user.email]){
            return HttpResponse.json({ error: { code: 'WRONG_CURRENT_PASSWORD', message: 'Current password is incorrect.' } }, { status: 422 })
        }
        if(body.current_password === body.new_password){
            return HttpResponse.json({ error: { code: 'SAME_AS_CURRENT', message: 'New password must be different from the current password.' } }, { status: 422 })
        }
        if (!passwordRule.test(body.new_password)) {
            return HttpResponse.json(
                { error: { code: 'VALIDATION_FAILED', message: 'Password must be 8+ characters with upper case, lower case, a number and a symbol.' } },
                { status: 422 }
            )
        }
        
        passwords[user.email] = body.new_password
        user.temp_password_active = false
        return new HttpResponse(null, { status: 204 })
        
    }),
    //reset handler
    http.post('*/api/v1/users/:id/reset-password', async ({params}) => {
        const user = currentUser()
        const target = Object.values(accounts).find(a => a.id === params.id)
        if(!user){
            return HttpResponse.json({ error: {code: 'UNAUTHENTICATED', message: 'User is not authenticated.'} }, { status: 401 })
        }
        if(user.role !== 'admin'){
            return HttpResponse.json({ error: {code: 'FORBIDDEN', message: 'User not authorized'} }, { status: 403 })
        }
        if(!target){
            return HttpResponse.json({ error: {code: 'NOT_FOUND', message: 'account not found'} }, { status: 404 })
        }
        passwords[target.email] = 'TempPass@2026'
        target.temp_password_active = true
        return  HttpResponse.json({ temporary_password: 'TempPass@2026' })
    })
    

]
