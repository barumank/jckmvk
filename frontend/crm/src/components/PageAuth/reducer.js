import {createAction} from "redux-actions";
const registrationUserAction = createAction('PageAuth/registrationUserAction');

const initialState = {
    isAcceptRegistration: false
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case registrationUserAction.toString():
            state = {...state, isAcceptRegistration: action.payload.isAccept};
            break;
    }
    return state;
};

export default reducer;

export const setIsAcceptRegistration = (isAccept) => (dispatch, getState) => {
    dispatch(registrationUserAction({
        isAccept: isAccept
    }));
};
