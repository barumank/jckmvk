const getPath = (state)=>state.pageUsers.userList.editUser;
export const getIsLoading =(state)=>getPath(state).isLoading;
export const getUserDate =(state)=>getPath(state).userData;
export const getIsShow =(state)=>getPath(state).isShow;
export const getUserId =(state)=>getPath(state).userId;