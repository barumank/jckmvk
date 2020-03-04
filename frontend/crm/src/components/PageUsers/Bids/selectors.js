const getPath = (state)=>state.pageUsers.bidList.list;
export const getSearchLoading = (state)=> getPath(state).searchLoading;
export const getSearch = (state)=> getPath(state).search;
export const getLoading = (state)=> getPath(state).loading;
export const getBids = (state)=> getPath(state).bids;
export const getPage = (state)=> getPath(state).page;
export const getPagination = (state)=> getPath(state).pagination;
