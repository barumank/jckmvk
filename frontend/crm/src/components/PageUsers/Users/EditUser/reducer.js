import {getUserId} from "./selectors";
import {findUsers} from "../reducer";
import {createAction} from "redux-actions";

const showAction = createAction('PageUsers/Users/EditUser/showAction');
const setUserIdAction = createAction('PageUsers/Users/EditUser/setUserIdAction');
const setUserDataAction = createAction('PageUsers/Users/EditUser/setUserDataAction');
const setUserDataLoadingAction = createAction('PageUsers/Users/EditUser/setUserDataLoadingAction');


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
        case setUserIdAction.toString():
            state = {...state, userId: action.payload.userId};
            break;
        case setUserDataAction.toString():
            state = {...state, userData: action.payload.userData};
            break;
        case setUserDataLoadingAction.toString():
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
    dispatch(setUserDataLoadingAction({
        isLoading: true
    }));
    appApi.user.getUserById(userId).then((user) => {
        dispatch(setUserDataAction({
            userData: user
        }));
        dispatch(setUserDataLoadingAction({
            isLoading: false
        }));
    })
};

export const modalOpen = (userId) => (dispatch, getState) => {
    dispatch(setUserIdAction({
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
    dispatch(setUserDataAction({
        userData: null
    }));
    dispatch(setUserIdAction({
        userId: 0
    }));
};

export const updateUsers = () => (dispatch, getState) => {
    dispatch(findUsers());
};




