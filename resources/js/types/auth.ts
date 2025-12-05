export type OAuthTokenResponse = {
    token_type: 'Bearer' | string;
    expires_in: number;
    access_token: string;
    refresh_token: string;
};

export type Credentials = {
    username: string;
    password: string;
    scope?: string;
};
