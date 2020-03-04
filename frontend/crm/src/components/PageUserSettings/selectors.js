import {createSelector} from 'reselect'

export const getUser  =createSelector(
    (state)=>state.auth.currentUser,
    (user)=>{
        const {role,date_create,...date} = user;
        return date;
    });