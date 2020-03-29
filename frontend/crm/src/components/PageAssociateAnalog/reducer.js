import {combineReducers} from "redux";
import bindAnalogForm from './Form/reducer';
import {createAction} from "redux-actions";

const setPropertyDropdownShowAction = createAction('PageAssociateAnalog/setPropertyDropdownShowAction');


const initialState = {
    propertyDropdownShow: false
};

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case setPropertyDropdownShowAction.toString():
            state = {...state, propertyDropdownShow: action.payload};
            break;
    }
    return state;
};


export default combineReducers({
    main: reducer,
    bindAnalogForm
});


export const setPropertyDropdownShow = (value) => (dispatch) => {
    dispatch(setPropertyDropdownShowAction(value));
};