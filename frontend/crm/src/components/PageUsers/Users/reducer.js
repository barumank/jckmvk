import {getPage, getSearch} from "./selectors";
import {combineReducers} from "redux";
import EditUserReducer from './EditUser/reducer'
import {createAction} from "redux-actions";

const searchLoadingAction = createAction('PageUsers/Users/searchLoading');
const searchAction = createAction('PageUsers/Users/search');
const usersLoadingAction = createAction('PageUsers/Users/userLoading');
const pageAction = createAction('PageUsers/Users/page');
const usersAction = createAction('PageUsers/Users/users');

const initialState = {
    searchLoading: false,
    search: '',
    loading: false,
    users: [],
    page: 1,
    pagination: []
};

const reducer = (state = initialState, action) => {

    switch (action.type) {
        case searchLoadingAction.toString():
            state = {...state, searchLoading: action.payload.loading};
            break;
        case searchAction.toString():
            state = {...state, search: action.payload.search};
            break;
        case usersLoadingAction.toString():
            state = {...state, loading: action.payload.loading};
            break;
        case pageAction.toString():
            state = {...state, page: action.payload.page};
            break;
        case usersAction.toString():
            state = {...state, users: action.payload.users, pagination: action.payload.pagination};
            break;
    }
    return state;
};

export default combineReducers({
    list: reducer,
    editUser: EditUserReducer
});

export const setPage = (page) => (dispatch, getState) => {
    dispatch(pageAction({
        page: page
    }));
};

export const setSearch = (search) => (dispatch, getState) => {
    dispatch(searchAction({
        search: search
    }));
};
export const findUsers = () => (dispatch, getState) => {

    let state = getState(),
        page = getPage(state),
        search = getSearch(state);
    dispatch(usersLoadingAction({
        loading: true
    }));
    appApi.user.getUsers({
        fields: ["id", "name", "email", "date_create", "status"],
        statuses: [appApi.user.STATUS_USER, appApi.user.STATUS_USER_BLOCKED],
        search: search,
        page: page,
        pageSize: 30}).then((result) => {
        if (result === null) {
            dispatch(usersAction({
                users: [],
                pagination: [],
            }));
            dispatch(usersLoadingAction({
                loading: false
            }));
            return;
        }
        dispatch(usersAction({
            users: result.users,
            pagination: result.pagination,
        }));
        dispatch(usersLoadingAction({
            loading: false
        }));
    });
};

export const searchUsers = () => (dispatch, getState) => {
    let state = getState(),
        search = getSearch(state);

    dispatch(searchLoadingAction({
        loading: true
    }));
    dispatch(usersLoadingAction({
        loading: true
    }));
    appApi.user.getUsers({
        fields: ["id", "name", "email", "date_create", "status"],
        statuses: [appApi.user.STATUS_USER, appApi.user.STATUS_USER_BLOCKED],
        search: search,
        pageSize: 30}).then((result) => {
        if (result === null) {
            dispatch(usersAction({
                users: [],
                pagination: [],
            }));
            dispatch(searchLoadingAction({
                loading: false
            }));
            dispatch(usersLoadingAction({
                loading: false
            }));
            return;
        }
        dispatch(usersAction({
            users: result.users,
            pagination: result.pagination,
        }));
        dispatch(searchLoadingAction({
            loading: false
        }));
        dispatch(usersLoadingAction({
            loading: false
        }));
    });
};
