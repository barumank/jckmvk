import {createAction} from "redux-actions";

const saveProductAction = createAction('PageProduct/saveProductAction');

const initialState = {
    isSaveProduct: false
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case saveProductAction.toString():
            state = {...state, isSaveProduct: action.payload.isSave};
            break;
    }
    return state;
};

export default reducer;

export const setIsSaveProduct = (isSave) => (dispatch, getState) => {
    dispatch(saveProductAction({
        isSave: isSave
    }));
};









