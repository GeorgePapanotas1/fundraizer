export type OAuthTokenResponse = {
  token_type: 'Bearer' | string;
  expires_in: number; // seconds
  access_token: string;
  refresh_token: string;
};

export type Credentials = {
  username: string; // email
  password: string;
  scope?: string;
};
