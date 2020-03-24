import {createAction} from "redux-actions";

const setIsHideAction = createAction('PageAssociateAnalog/DropdownAnalog/setIsHideAction');
const setOptionsAction = createAction('PageAssociateAnalog/DropdownAnalog/setOptionsAction');
const setProductIdAction = createAction('PageAssociateAnalog/DropdownAnalog/setProductIdAction');
const setValueAction = createAction('PageAssociateAnalog/DropdownAnalog/setValueAction');

const initialState = {
    isHide: false,
    options: [],
    productId: '',
    value: ''
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case setIsHideAction.toString():
            state = {...state, isHide: action.payload};
            break;
        case setOptionsAction.toString():
            state = {...state, options: action.payload};
            break;
        case setProductIdAction.toString():
            state = {...state, productId: action.payload};
            break;
        case setValueAction.toString():
            state = {...state, value: action.payload};
            break;
    }
    return state;
};

export default reducer;

export const onChange = (event, data) => (dispatch) => {
    event.preventDefault();
    dispatch(setValueAction(data.value));
};

export const setFetchAnalogProperty = (props) => (dispatch) => {
    const {productId} = props;
    dispatch(setProductIdAction(productId));

    appApi.product.getProductAttributes(productId)
        .then((response) => {
            let productAttributes = response.productAttributes.map(item => {
                return {
                    value: item.id,
                    text: item.value,
                    key: item.id
                }
            });
            dispatch(setOptionsAction(productAttributes));
        });
};


