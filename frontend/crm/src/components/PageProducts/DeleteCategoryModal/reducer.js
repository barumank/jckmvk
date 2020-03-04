import {createAction} from "redux-actions";
import {getCategoryData, getUpdateFunction} from "./selectors";

const showAction = createAction('PageProducts/DeleteCategoryModal/showAction');
const setDataAction = createAction('PageProducts/DeleteCategoryModal/setDataAction');
const setDataLoadingAction = createAction('PageProducts/DeleteCategoryModal/setDataLoadingAction');
const setUpdateFunction = createAction('PageProducts/DeleteCategoryModal/setUpdateFunction');
const setError = createAction('PageProducts/DeleteCategoryModal/setError');

const initialState = {
    isShow: false,
    categoryData: null,
    isLoading: false,
    error: '',
    updateFunction: () => {
    },
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case showAction.toString():
            state = {...state, isShow: action.payload.isShow};
            break;
        case setDataAction.toString():
            state = {...state, categoryData: action.payload.categoryData,};
            break;
        case setDataLoadingAction.toString():
            state = {...state, isLoading: action.payload.isLoading};
            break;
        case setUpdateFunction.toString():
            state = {...state, updateFunction: action.payload.function};
            break;
        case setError.toString():
            state = {...state, error: action.payload.error};
            break;
    }
    return state;
};
export default reducer;


export const showDeleteCategory = (categoryId, updateFunction = null) => (dispatch, getState) => {
    dispatch(showAction({
        isShow: true
    }));
    dispatch(setDataLoadingAction({
        isLoading: true
    }));
    if (updateFunction !== null) {
        dispatch(setUpdateFunction({
            function: updateFunction
        }));
    }
    appApi.category.getCategoryById(categoryId).then((category) => {
        if (!category) {
            dispatch(modalClose());
            dispatch(updateList());
            return;
        }
        dispatch(setDataAction({
            categoryData: category,
        }));
        dispatch(setDataLoadingAction({
            isLoading: false
        }));
    });
};

export const deleteCategory = () => (dispatch, getState) => {
    let state = getState(),
    categoryId = getCategoryData(state).id;
    appApi.category.deleteById(categoryId).then((response)=>{
        if('error' in response){
            dispatch(setError({
                error:response.error
            }));
            return;
        }
        dispatch(modalClose());
        dispatch(updateList());
    })
};

export const updateList = () => (dispatch, getState) => {
    dispatch(getUpdateFunction(getState())());
};

export const modalClose = () => (dispatch, getState) => {
    dispatch(showAction({
        isShow: false
    }));
    dispatch(setDataAction({
        categoryData: null,
    }));
    dispatch(setError({
        error: '',
    }));
};
