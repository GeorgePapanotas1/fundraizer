export type UserProfile = {
  id: string;
  name: string;
  email: string;
};

export type MeResponse = {
  id: string;
  name: string;
  email: string;
  roles: string[];
  permissions: string[];
};
