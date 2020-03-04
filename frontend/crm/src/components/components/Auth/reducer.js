import {createAction} from "redux-actions";

const authUserAction = createAction('components/Auth/authUserAction');

const initialState = {
    currentUser: null,
    isLoading: true,
};

const reducer = (state = initialState, action) => {

    switch (action.type) {
        case authUserAction.toString():
            state = {...state, currentUser: action.payload.user, isLoading: action.payload.isLoading};
            break;
    }
    return state;
};

export default reducer;

/**
 * Custom
 * @returns {Function}
 */
export const getCurrentUser = () => (dispatch, getState) => {
    appApi.user.getCurrentUser().then((user) => {
        dispatch(setCurrentUser(user))
    });
};

export const setCurrentUser = (user, isLoading = false) => (dispatch, getState) => {

   dispatch(authUserAction({
       user: user,
       isLoading: isLoading,
   }));
};










