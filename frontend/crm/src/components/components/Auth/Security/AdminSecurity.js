import React from 'react'
import {connect} from "react-redux";
import {getRole} from "../selectors";

const AdminSecurity = ({ children, role, ...props }) => {

    if (role === 'admin') {
        return children;
    }
    return (<></>);
};

export default connect(state=>({
    role:getRole(state)
}),null)(AdminSecurity);