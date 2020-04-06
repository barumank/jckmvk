const getPath = (state) => state.pagePageAssociateAnalog.bindAnalogForm;
export const getProductOptions = (state) => getPath(state).productOptions;
export const getProductAnalogOptions = (state) => getPath(state).productAnalogOptions;
export const getProductAttributeGroupOptions = (state) => getPath(state).productAttributeGroupOptions;