import {createAction} from "redux-actions";

const setProductOptionsAction = createAction('PageAssociateAnalog/Form/BindAnalogForm/setProductOptionsAction');
const setProductAnalogOptionsAction = createAction('PageAssociateAnalog/Form/BindAnalogForm/setProductAnalogOptionsAction');
const setProductAttributeGroupOptionsAction = createAction('PageAssociateAnalog/Form/BindAnalogForm/setProductAttributeGroupOptionsAction');

const initialState = {
    productOptions: [],
    productAnalogOptions: [],
    productAttributeGroupOptions: []
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case setProductOptionsAction.toString():
            state = {...state, productOptions: action.payload};
            break;
        case setProductAnalogOptionsAction.toString():
            state = {...state, productAnalogOptions: action.payload};
            break;
        case setProductAttributeGroupOptionsAction.toString():
            state = {...state, productAttributeGroupOptions: action.payload};
            break;
    }
    return state;
};

export default reducer;

export const onSearchChangeProduct = (event, {searchQuery}) => (dispatch) => {
    searchProduct(dispatch, searchQuery, setProductOptionsAction);
};

export const onSearchAnalogChangeProduct = (event, {searchQuery}) => (dispatch) => {
    searchProduct(dispatch, searchQuery, setProductAnalogOptionsAction);
};

const searchProduct = (dispatch, searchQuery, setOptions) => {
    appApi.product.searchProduct(searchQuery, appApi.category.TYPE_BASE)
        .then((response) => {
            let products = response.products.map(item => {
                return {
                    text: item.name,
                    value: item.id,
                    key: item.id
                };
            });
            dispatch(setOptions(products));
        });
};

export const fetchProductAttributeGroups = (event, data) => (dispatch) => {
    appApi.product.getProductAttributeGroups()
        .then((response) => {
            let productAttributes = response.productAttributeGroups.map(item => {
                return {
                    value: item.id,
                    text: item.value,
                    key: item.id
                }
            });
            dispatch(setProductAttributeGroupOptionsAction(productAttributes));
        });
};

export const onClearValues = () => (dispatch) => {
    dispatch(setProductAttributeGroupOptionsAction([]));
    dispatch(setProductOptionsAction([]));
    dispatch(setProductAnalogOptionsAction([]));
};
