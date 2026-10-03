export interface Settings {
    apiBase: string;
    apiPrefix: string;
    useMocks: boolean;
    appName: string;
}

export type ErrorCode =
  | 'VALIDATION_FAILED' | 'INVALID_CREDENTIALS' | 'ACCOUNT_INACTIVE'
  | 'TOO_MANY_ATTEMPTS' | 'UNAUTHENTICATED' | 'FORBIDDEN' | 'NOT_FOUND'
  | 'WRONG_CURRENT_PASSWORD' | 'SAME_AS_CURRENT'
  | 'NETWORK_ERROR' | 'UNKNOWN'