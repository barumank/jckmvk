import {createAction} from "redux-actions";
import {
    getParentCategoryId,
    getProductsPage,
    getSearchProduct,
    getSelectCategoryId
} from "./selectors";

const categoryLoadingAction = createAction('PageProducts/CustomProducts/categoryLoading');
const categoriesAction = createAction('PageProducts/CustomProducts/categories');
const selectCategoryAction = createAction('PageProducts/CustomProducts/selectCategory');
const selectParentCategoryAction = createAction('PageProducts/CustomProducts/selectParentCategory');
const productsLoadingAction = createAction('PageProducts/CustomProducts/productsLoadingAction');
const productsAction = createAction('PageProducts/CustomProducts/productsAction');
const selectProductsPageAction = createAction('PageProducts/CustomProducts/selectProductsPageAction');
const searchProductAction = createAction('PageProducts/CustomProducts/searchProductAction');
const searchLoadingAction = createAction('PageProducts/CustomProducts/searchLoading');

const initialState = {
    categoryLoading: false,
    parentCategories: [],
    categories: [],
    selectCategoryId: "0",
    parentCategoryId: "0",
    productsLoading: false,
    products: [],
    productAttributes: [],
    attributeNames: [],
    pagination: [],
    searchProduct: '',
    searchLoading: false,
    productsPage: "1",
    categoryIsEdit: false,
    categoryModalIsOpen: false,
    categoryEditId: "0"
};

const reducer = (state = initialState, action) => {

    switch (action.type) {
        case categoryLoadingAction.toString():
            state = {...state, categoryLoading: action.payload.loading};
            break;
        case categoriesAction.toString():
            state = {...state, categories: action.payload.categories, parentCategories: action.payload.parent};
            break;
        case selectCategoryAction.toString():
            state = {...state, selectCategoryId: action.payload.categoryId, productsPage: "1"};
            break;
        case selectParentCategoryAction.toString():
            state = {...state, selectCategoryId: "0", productsPage: "1", parentCategoryId: action.payload.categoryId};
            break;
        case productsLoadingAction.toString():
            state = {...state, productsLoading: action.payload.loading};
            break;
        case productsAction.toString():
            let payload = {...action.payload};
            state = {
                ...state, products: payload.products, productAttributes: payload.productAttributes,
                attributeNames: payload.attributeNames, pagination: payload.pagination
            };
            break;
        case selectProductsPageAction.toString():
            state = {...state, productsPage: action.payload.page};
            break;
        case searchProductAction.toString():
            state = {...state, searchProduct: action.payload.search};
            break;
        case searchLoadingAction.toString():
            state = {...state, searchLoading: action.payload.loading};
            break;
    }
    return state;
};
export default reducer;


/**
 * Base
 * @returns {Function}
 */
export const findCategories = () => (dispatch, getState) => {
    dispatch(categoryLoadingAction({
        loading: true
    }));
    let parentCategoryId = getParentCategoryId(getState());
    let getCategories = appApi.category.getCategoryByParentId(appApi.category.TYPE_CUSTOM,parentCategoryId);
    let getParent = appApi.category.getParentCategoryById(parentCategoryId);

    Promise.all([getCategories, getParent]).then((values) => {
        dispatch(categoriesAction({
            categories: values[0],
            parent: values[1]
        }));
        dispatch(categoryLoadingAction({
            loading: false
        }));
    })
};


/**
 * @returns {Function}
 */
export const selectCategory = (categoryId) => (dispatch, getState) => {
    dispatch(selectCategoryAction({
        categoryId
    }));
};

/**
 * @returns {Function}
 */
export const selectParentCategory = (categoryId) => (dispatch, getState) => {
    dispatch(selectParentCategoryAction({
        categoryId
    }));
};

export const selectProductsPage = (page) => (dispatch, getState) => {
    dispatch(selectProductsPageAction({
        page
    }));
};


export const findProducts = () => (dispatch, getState) => {
    dispatch(productsLoadingAction({
        loading: true
    }));
    let state = getState(),
        selectCategoryId = getSelectCategoryId(state),
        parentCategoryId = getParentCategoryId(state),
        productsPage = getProductsPage(state),
        categoryId = "0";
    categoryId = (parentCategoryId === '0') ? categoryId : parentCategoryId;
    categoryId = (selectCategoryId === '0') ? categoryId : selectCategoryId;

    appApi.product.getProductsAndAttributes(categoryId, appApi.category.TYPE_CUSTOM, productsPage).then((response) => {
        dispatch(productsAction(response));
        dispatch(productsLoadingAction({
            loading: false
        }));
    });
};

export const searchProductsAndCategories = () => (dispatch, getState) => {

    let state = getState(),
        productsPage = getProductsPage(state),
        search = getSearchProduct(state),
        selectCategoryId = getSelectCategoryId(state);
    if (search === '' || search.length < 3) {
        return
    }
    dispatch(searchLoadingAction({
        loading: true
    }));
    dispatch(productsLoadingAction({
        loading: true
    }));
    dispatch(categoryLoadingAction({
        loading: true
    }));
    let searchCategory = appApi.category.searchCategory(appApi.category.TYPE_CUSTOM, search),
        searchProduct = appApi.product.searchProduct(search, appApi.category.TYPE_CUSTOM, selectCategoryId, productsPage);

    Promise.all([searchCategory, searchProduct]).then((values) => {
        dispatch(categoriesAction({
            categories: values[0],
            parent: []
        }));
        dispatch(categoryLoadingAction({
            loading: false
        }));
        dispatch(productsAction(values[1]));
        dispatch(productsLoadingAction({
            loading: false
        }));
        dispatch(searchLoadingAction({
            loading: false
        }));
    });
};

export const searchProducts = () => (dispatch, getState) => {
    let state = getState(),
        productsPage = getProductsPage(state),
        search = getSearchProduct(state),
        selectCategoryId = getSelectCategoryId(state);
    if (search === '' || search.length < 3) {
        return
    }
    dispatch(productsLoadingAction({
        loading: true
    }));
    appApi.product.searchProduct(search, appApi.category.TYPE_CUSTOM, selectCategoryId, productsPage).then((response) => {
        dispatch(productsAction(response));
        dispatch(productsLoadingAction({
            loading: false
        }));
    });
};

//const onSearch = debounce(searchProductsAndCategories, 3000);
export const setSearchProducts = (search) => (dispatch, getState) => {
    dispatch(searchProductAction({
        search: search
    }));
};