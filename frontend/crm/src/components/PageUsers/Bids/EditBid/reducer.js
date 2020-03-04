import {getUserId} from "./selectors";
import {createAction} from "redux-actions";
import {findBids} from "../reducer";

const showAction = createAction('PageUsers/Bids/EditBid/showAction');
const setBidIdAction = createAction('PageUsers/Bids/EditBid/setBidIdAction');
const setBidDataAction = createAction('PageUsers/Bids/EditBid/setBidDataAction');
const setBidDataLoadingAction = createAction('PageUsers/Bids/EditBid/setBidDataLoadingAction');

const initialState = {
    isShow: false,
    userId: 0,
    userData: null,
    isLoading: false,
};

const reducer = (state = initialState, action) => {

    switch (action.type) {
        case showAction.toString():
            state = {...state, isShow: action.payload.isShow};
            break;
        case setBidIdAction.toString():
            state = {...state, userId: action.payload.userId};
            break;
        case setBidDataAction.toString():
            state = {...state, userData: action.payload.userData};
            break;
        case setBidDataLoadingAction.toString():
            state = {...state, isLoading: action.payload.isLoading};
            break;
    }
    return state;
};
export default reducer;

export const getUserData = () => (dispatch, getState) => {
    let userId = getUserId(getState());
    if (userId === 0) {
        return;
    }
    dispatch(setBidDataLoadingAction({
        isLoading: true
    }));
    appApi.user.getUserById(userId).then((user) => {
        dispatch(setBidDataAction({
            userData: user
        }));
        dispatch(setBidDataLoadingAction({
            isLoading: false
        }));
    })
};

export const modalOpen = (userId) => (dispatch, getState) => {
    dispatch(setBidIdAction({
        userId
    }));
    dispatch(showAction({
        isShow: true
    }));

};

export const modalClose = () => (dispatch, getState) => {
    dispatch(showAction({
        isShow: false
    }));
    dispatch(setBidDataAction({
        userData: null
    }));
    dispatch(setBidIdAction({
        userId: 0
    }));
};

export const setBidCancel = () => (dispatch, getState) => {
    let store = getState();
    let userId = getUserId(store);
    if (userId === 0) {
        return;
    }
    dispatch(setBidDataLoadingAction({
        isLoading: true
    }));
    appApi.user.save(userId, {id: userId, status: appApi.user.STATUS_BID_CANCEL})
        .then((user) => {
           dispatch(modalClose());
            dispatch(findBids());
        });
};

export const setBidAccept = () => (dispatch, getState) => {
    let store = getState();
    let userId = getUserId(store);
    if (userId === 0) {
        return;
    }
    dispatch(setBidDataLoadingAction({
        isLoading: true
    }));
    appApi.user.save(userId, {id: userId, status: appApi.user.STATUS_USER})
        .then((user) => {
            dispatch(modalClose());
            dispatch(findBids());
        });
};




