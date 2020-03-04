import {createAction} from "redux-actions";
import {getUpdateFunction} from "./selectors";

const showAction = createAction('PageProducts/EditCategoryModal/showAction');
const setParentCategoryIdAction = createAction('PageProducts/EditCategoryModal/setParentCategoryIdAction');
const setCategoryDataAction = createAction('PageProducts/EditCategoryModal/setCategoryDataAction');
const setCategoryDataLoadingAction = createAction('PageProducts/EditCategoryModal/setCategoryDataLoadingAction');
const setCategoryTypeAction = createAction('PageProducts/EditCategoryModal/setCategoryTypeAction');
const setCategoriesAction = createAction('PageProducts/EditCategoryModal/setCategoriesAction');
const setUpdateFunction = createAction('PageProducts/EditCategoryModal/setUpdateFunction');

const initialState = {
    isShow: false,
    parentCategoryId: 0,
    categoryId: 0,
    userId: 0,
    type: 0,
    categoryData: null,
    categories: null,
    isLoading: false,
    categoryImage: '',
    updateFunction: () => {
    }
};

const reducer = (state = initialState, action) => {

    switch (action.type) {
        case showAction.toString():
            state = {...state, isShow: action.payload.isShow};
            break;
        case setParentCategoryIdAction.toString():
            state = {...state, parentCategoryId: action.payload.categoryId};
            break;
        case setCategoryDataAction.toString():
            state = {
                ...state,
                categoryData: action.payload.categoryData,
                categoryId: action.payload.categoryId,
                parentCategoryId: action.payload.parentCategoryId,
                userId: action.payload.userId,
                categoryImage: action.payload.image
            };
            break;
        case setCategoryDataLoadingAction.toString():
            state = {...state, isLoading: action.payload.isLoading};
            break;
        case setCategoryTypeAction.toString():
            state = {...state, type: action.payload.type};
            break;
        case setCategoriesAction.toString():
            state = {...state, categories: action.payload.list};
            break;
        case setUpdateFunction.toString():
            state = {...state, updateFunction: action.payload.function};
            break;
    }
    return state;
};
export default reducer;

export const modalOpen = (categoryId) => (dispatch, getState) => {
    dispatch(setParentCategoryIdAction({
        categoryId
    }));
    dispatch(showAction({
        isShow: true
    }));
};

export const newCategory = (type, parentCategoryId = 0, updateFunction = null) => (dispatch, getState) => {
    dispatch(setCategoryTypeAction({
        type
    }));
    dispatch(setParentCategoryIdAction({
        categoryId: parentCategoryId
    }));
    if (updateFunction !== null) {
        dispatch(setUpdateFunction({
            function: updateFunction
        }));
    }
    dispatch(showAction({
        isShow: true
    }));
    dispatch(setCategoryDataLoadingAction({
        isLoading: true
    }));
    appApi.category.getCategoriesGetByType(type).then((list) => {
        dispatch(setCategoriesAction({
            list
        }));
        dispatch(setCategoryDataLoadingAction({
            isLoading: false
        }));
    });
};

export const editCategory = (categoryId, updateFunction = null) => (dispatch, getState) => {
    dispatch(showAction({
        isShow: true
    }));
    dispatch(setCategoryDataLoadingAction({
        isLoading: true
    }));
    if (updateFunction !== null) {
        dispatch(setUpdateFunction({
            function: updateFunction
        }));
    }
    appApi.category.getCategoryById(categoryId).then((category) => {
        dispatch(setCategoryTypeAction({
            type: category.type
        }));
        dispatch(setCategoryDataAction({
            categoryData: category,
            categoryId: category.id,
            parentCategoryId: (!category.parent_id) ? 0 : category.parent_id,
            userId: category.user_id,
            image: category.image,
        }));
        appApi.category.getCategoriesGetByType(category.type).then((list) => {
            dispatch(setCategoriesAction({
                list
            }));
            dispatch(setCategoryDataLoadingAction({
                isLoading: false
            }));
        });
    });
};

export const updateList = () => (dispatch, getState) => {
      dispatch(getUpdateFunction(getState())());
};

export const modalClose = () => (dispatch, getState) => {
    dispatch(showAction({
        isShow: false
    }));
    dispatch(setCategoryDataAction({
        categoryData: null,
        categoryId: 0,
        parentCategoryId: 0,
        userId: 0,
        image: '',
    }));
};
