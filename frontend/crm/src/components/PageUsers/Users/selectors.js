const getPath = (state)=>state.pageUsers.userList.list;
export const getSearchLoading = (state)=> getPath(state).searchLoading;
export const getSearch = (state)=> getPath(state).search;
export const getLoading = (state)=> getPath(state).loading;
export const getUsers = (state)=> getPath(state).users;
export const getPage = (state)=> getPath(state).page;
export const getPagination = (state)=> getPath(state).pagination;

