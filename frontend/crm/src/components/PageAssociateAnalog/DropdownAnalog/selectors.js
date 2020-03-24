const getPath = (state) => state.pagePageAssociateAnalog.dropdownAnalog;
export const getIsHide = (state) => getPath(state).isHide;
export const getOptions = (state) => getPath(state).options;
export const getProductId = (state) => getPath(state).productId;
export const getValue = (state) => getPath(state).value;