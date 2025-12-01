import axios from 'axios';
import type {Credentials, OAuthTokenResponse} from '@/types/auth';

const PASSWORD_CLIENT_ID = '019ac080-5e18-7249-91b4-90d8f84a2532';
const PASSWORD_CLIENT_SECRET = 'Aqr8WZSFub8eBauzZRC8xgiC1YYrfPRHmzOoEQLu';

export const AuthService = {
    async loginWithPassword({username, password, scope = '*'}: Credentials): Promise<OAuthTokenResponse> {
        const params = new URLSearchParams();
        params.append('grant_type', 'password');
        params.append('client_id', PASSWORD_CLIENT_ID);
        params.append('client_secret', PASSWORD_CLIENT_SECRET);
        params.append('username', username);
        params.append('password', password);
        params.append('scope', scope);

        const res = await axios.post<OAuthTokenResponse>('/oauth/token', params, {
            headers: {'Accept': 'application/json'},
        });
        return res.data;
    },

    async refreshToken(refreshToken: string, scope: string = '*'): Promise<OAuthTokenResponse> {
        const params = new URLSearchParams();
        params.append('grant_type', 'refresh_token');
        params.append('refresh_token', refreshToken);
        params.append('client_id', PASSWORD_CLIENT_ID);
        params.append('client_secret', PASSWORD_CLIENT_SECRET);
        params.append('scope', scope);

        const res = await axios.post<OAuthTokenResponse>('/oauth/token', params, {
            headers: {'Accept': 'application/json'},
        });
        return res.data;
    },
};

export default AuthService;
