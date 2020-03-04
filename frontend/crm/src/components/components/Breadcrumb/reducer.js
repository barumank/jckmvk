import {createAction} from "redux-actions";

const breadcrumbSetPath = createAction('Breadcrumb/breadcrumbSetPath');

const initialState = [
    {link: '/', label: 'Главная', active: false},
    {link: '/', label: 'Объекты и сметы', active: true}
];

const reducer = (state = initialState, action) => {

    switch (action.type) {
        case breadcrumbSetPath.toString():
            return action.payload.list;
    }

    return state;
};

export default reducer;

export const setPath = (list) => (dispatch, getState) => {
    dispatch(breadcrumbSetPath({
        list
    }));
};