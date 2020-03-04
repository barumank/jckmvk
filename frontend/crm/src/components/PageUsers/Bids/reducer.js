import {combineReducers} from "redux";
import {createAction} from "redux-actions";
import EditBidReducer from "./EditBid/reducer";
import {getPage, getSearch} from "./selectors";

const searchLoadingAction = createAction('PageUsers/Bids/searchLoadingAction');
const searchAction = createAction('PageUsers/Bids/searchAction');
const bidsLoadingAction = createAction('PageUsers/Bids/bidsLoadingAction');
const pageAction = createAction('PageUsers/Bids/pageAction');
const bidsAction = createAction('PageUsers/Bids/bidsAction');

const initialState = {
    searchLoading: false,
    search: '',
    loading: false,
    bids: [],
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
        case bidsLoadingAction.toString():
            state = {...state, loading: action.payload.loading};
            break;
        case pageAction.toString():
            state = {...state, page: action.payload.page};
            break;
        case bidsAction.toString():
            state = {...state, bids: action.payload.bids, pagination: action.payload.pagination};
            break;
    }
    return state;
};

export default combineReducers({
    list: reducer,
    editBid: EditBidReducer
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
export const findBids = () => (dispatch, getState) => {

    let state = getState(),
        page = getPage(state),
        search = getSearch(state);
    dispatch(bidsLoadingAction({
        loading: true
    }));
    appApi.user.getUsers({
        fields: ["id", "name", "email", "date_create", "status"],
        statuses: [appApi.user.STATUS_BID, appApi.user.STATUS_BID_CANCEL],
        search: search,
        page: page,
        pageSize: 30}).then((result) => {
        if (result === null) {
            dispatch(bidsAction({
                bids: [],
                pagination: [],
            }));
            dispatch(bidsLoadingAction({
                loading: false
            }));
            return;
        }
        dispatch(bidsAction({
            bids: result.users,
            pagination: result.pagination,
        }));
        dispatch(bidsLoadingAction({
            loading: false
        }));
    });
};

export const searchBids = () => (dispatch, getState) => {
    let state = getState(),
        search = getSearch(state);

    dispatch(searchLoadingAction({
        loading: true
    }));
    dispatch(bidsLoadingAction({
        loading: true
    }));
    appApi.user.getUsers({
        fields: ["id", "name", "email", "date_create", "status"],
        statuses: [appApi.user.STATUS_BID, appApi.user.STATUS_BID_CANCEL],
        search: search,
        pageSize: 30}).then((result) => {
        if (result === null) {
            dispatch(bidsAction({
                bids: [],
                pagination: [],
            }));
            dispatch(searchLoadingAction({
                loading: false
            }));
            dispatch(bidsLoadingAction({
                loading: false
            }));
            return;
        }
        dispatch(bidsAction({
            bids: result.users,
            pagination: result.pagination,
        }));
        dispatch(searchLoadingAction({
            loading: false
        }));
        dispatch(bidsLoadingAction({
            loading: false
        }));
    });
};
