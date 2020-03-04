import {createAction} from "redux-actions";

const loadingOrganizationsAction = createAction('PageOrganization/loadingOrganizationsAction');
const organizationsAction = createAction('PageOrganization/organizationsAction');
const loadingUsersAction = createAction('PageOrganization/loadingUsersAction');
const usersAction = createAction('PageOrganization/usersAction');
const selectOrganizationAction = createAction('PageOrganization/selectOrganizationAction');

const initState = {
    loadingOrganizations: false,
    selectOrganization: 0,
    organizations: [],
    loadingUsers: false,
    users: [],
};

const reducer = (state = initState, action) => {
    switch (action.type) {
        case loadingOrganizationsAction.toString():
            state = {...state, loadingOrganizations: loadingOrganizationsAction.payload.loading};
            break;
        case organizationsAction.toString():
            state = {...state, organizations: loadingOrganizationsAction.payload.organizations};
            break;
        case loadingUsersAction.toString():
            state = {...state, loadingUsers: loadingUsersAction.payload.loading};
            break;
        case usersAction.toString():
            state = {...state, users: usersAction.payload.users};
            break;
    }
    return state;
};

export default reducer;

export const getOrganizations = () => (dispatch, getState) => {
    
};
