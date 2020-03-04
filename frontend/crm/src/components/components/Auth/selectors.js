export const isLoading = (state) => state.auth.isLoading;
export const getRole = (state) => state.auth.currentUser !== null ? state.auth.currentUser.role : null;
export const getUser = (state) => state.auth.currentUser;