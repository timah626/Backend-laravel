import type { ErrorCode } from '~/types/api'

export class ApiError extends Error {
    status: number
    code: ErrorCode
    fields?: Record<string, string[]>

    constructor(message: string, status: number, code: ErrorCode, fields?: Record<string, string[]>) {
        super(message)
        this.status = status
        this.code = code
        this.fields = fields
    }
}

export function toApiError(err: any): ApiError {

    if(err?.data?.error?.code){
        return new ApiError(
            err.data.error.message,
            err.status,
            err.data.error.code,
            err.data.error.fields
        )
    }

    if (!err?.status){
        return new ApiError('Network connection error', 0, 'NETWORK_ERROR')
    }

    return new ApiError(err.message || 'Unknown error', err.status, 'UNKNOWN')
}