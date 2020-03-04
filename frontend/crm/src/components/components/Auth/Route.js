import React from 'react'
import {Redirect, Route} from "react-router-dom";
import {connect} from "react-redux";
import {getRole} from "./selectors";

const ARoute  = ({children, role, ...props}) => {
    return (<Route {...props} render={(props) => (
            (role === 'admin')
                ? children
                : <Redirect to='/auth/login'/>
        )}/>
    )
};

export const AdminRoute = connect(state => ({
    role: getRole(state)
}), null)(ARoute);


const URoute = ({children, role, ...props}) => {
    return (<Route {...props} render={(props) => (
            (role === 'user' || role === 'admin')
                ? children
                : <Redirect to='/auth/login'/>
        )}/>
    )
};

export const UserRoute = connect(state => ({
    role: getRole(state)
}), null)(URoute);