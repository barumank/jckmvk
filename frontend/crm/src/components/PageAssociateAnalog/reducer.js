import {combineReducers} from "redux";
import searchInputProduct from './SearchInputProduct/reducer';
import searchInputProductAnalog from './SearchInputProductAnalog/reducer';
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
    searchInputProduct,
    searchInputProductAnalog,
    main: reducer
});


export const setPropertyDropdownShow = (value) => (dispatch) => {
    dispatch(setPropertyDropdownShowAction(value));
};