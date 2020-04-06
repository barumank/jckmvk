import {createAction} from "redux-actions";
import {onClearValues} from "../PageAssociateAnalog/Form/reducer";
import {SubmissionError} from "redux-form";
import {getUserId} from "../PageUsers/Bids/EditBid/selectors";
import {getParentCategoryId, getProductsPage, getSelectCategoryId} from "../PageProducts/BaseProducts/selectors";

const setProductDataAction = createAction('PageProductDetail/PageProductDetail/setProductDataAction');
const setProductDataLoadingAction = createAction('PageProductDetail/PageProductDetail/setProductDataLoadingAction');
const productsAction = createAction('PageProductDetail/PageProductDetail/productsAction');
const productsLoadingAction = createAction('PageProductDetail/PageProductDetail/productsLoadingAction');
const productsBrandAction = createAction('PageProductDetail/PageProductDetail/productsBrandAction');
const productsLoadingBrandAction = createAction('PageProductDetail/PageProductDetail/productsLoadingBrandAction');


const initialState = {
    productData: null,
    isLoading: false,
    productsLoading: false,
    products: [],
    productAttributes: [],
    attributeNames: [],

    productsLoadingBrand: false,
    productsBrand: [],
    productAttributesBrand: [],
    attributeNamesBrand: [],
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case setProductDataAction.toString():
            state = {...state, productData: action.payload.productData};
            break;
        case setProductDataLoadingAction.toString():
            state = {...state, isLoading: action.payload.isLoading};
            break;
        case productsLoadingAction.toString():
            state = {...state, productsLoading: action.payload.loading};
            break;
        case productsAction.toString():
            let payload = {...action.payload};
            state = {
                ...state, products: payload.products, productAttributes: payload.productAttributes,
                attributeNames: payload.attributeNames
            };
            break;
        case productsBrandAction.toString():
            let pay = {...action.payload};
            state = {
                ...state, productsBrand: pay.products, productAttributesBrand: pay.productAttributes,
                attributeNamesBrand: pay.attributeNames
            };
            break;
    }
    return state;
};

export default reducer;


export const fetchProduct = (productId) => (dispatch) => {
    dispatch(setProductDataLoadingAction({
        isLoading: true
    }));
    appApi.product.getProduct(productId).then((product) => {
        dispatch(setProductDataAction({
            productData: product
        }));
        dispatch(setProductDataLoadingAction({
            isLoading: false
        }));
    })
};





export const fetchProductAnalogs = (productId, groupId) => (dispatch, getState) => {
    dispatch(productsLoadingAction({
        loading: true
    }));
    appApi.product.getAnalogProductsAndAttributes(productId, groupId).then((response) => {
        dispatch(productsAction(response));
        dispatch(productsLoadingAction({
            loading: false
        }));
    });
};

export const fetchProductAnalogsBrand = (productId, groupId) => (dispatch, getState) => {
    dispatch(productsLoadingBrandAction({
        loading: true
    }));
    appApi.product.getAnalogProductsAndAttributes(productId, groupId).then((response) => {
        dispatch(productsBrandAction(response));
        dispatch(productsLoadingBrandAction({
            loading: false
        }));
    });
};



