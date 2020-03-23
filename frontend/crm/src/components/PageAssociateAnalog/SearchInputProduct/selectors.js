const getPath = (state) => state.pagePageAssociateAnalog.searchInputProduct;
export const getIsLoading = (state) => getPath(state).isLoading;
export const getResults = (state) => getPath(state).results;
export const getValue = (state) => getPath(state).value;