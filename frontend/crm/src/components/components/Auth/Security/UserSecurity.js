import React from 'react'
import {connect} from "react-redux";
import {getRole} from "../selectors";

const UserSecurity = ({ children, role, ...props }) => {

    if (role === 'admin' || role === 'user') {
        return children;
    }
    return (<></>);
};

export default connect(state=>({
    role:getRole(state)
}),null)(UserSecurity);