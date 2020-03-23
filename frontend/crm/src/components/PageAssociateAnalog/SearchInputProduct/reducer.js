import {createAction} from "redux-actions";

const setIsLoadingAction = createAction('PageAssociateAnalog/SearchInputProduct/setIsLoadingAction');
const setResultsAction = createAction('PageAssociateAnalog/SearchInputProduct/setResultsAction');
const setValueAction = createAction('PageAssociateAnalog/SearchInputProduct/setValueAction');

const initialState = {
    isLoading: false,
    results: [],
    value: ''
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case setIsLoadingAction.toString():
            state = {...state, isLoading: action.payload};
            break;
        case setResultsAction.toString():
            state = {...state, results: action.payload};
            break;
        case setValueAction.toString():
            state = {...state, value: action.payload};
            break;
    }
    return state;
};

export default reducer;

export const onSearchChange = (event, data) => (dispatch) => {
    dispatch(setIsLoadingAction(true));
    dispatch(setValueAction(data.value));

    appApi.product.searchProduct(data.value, appApi.category.TYPE_BASE)
        .then((response) => {
            let products = response.products.map(item => {
                return {
                    title: item.name,
                }
            });
            dispatch(setResultsAction(products));
            dispatch(setIsLoadingAction(false));
        });
};

export const onResultSelect = (event, {result}) => (dispatch) => {
    dispatch(setValueAction(result.title));
};