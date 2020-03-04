const getPath = (state) => state.pageProducts.deleteCategoryModal;
export const getIsShow = (state) => getPath(state).isShow;
export const getCategoryData = (state) => getPath(state).categoryData;
export const getIsLoading = (state) => getPath(state).isLoading;
export const getUpdateFunction = (state) => getPath(state).updateFunction;
export const getError = (state) => getPath(state).error;