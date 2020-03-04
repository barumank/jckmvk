const getPath = (state)=>state.pageUsers.bidList.editBid;
export const getIsLoading =(state)=>getPath(state).isLoading;
export const getUserDate =(state)=>getPath(state).userData;
export const getIsShow =(state)=>getPath(state).isShow;
export const getUserId =(state)=>getPath(state).userId;