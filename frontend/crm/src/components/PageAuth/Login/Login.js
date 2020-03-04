import React from 'react'
import {Segment} from "semantic-ui-react";
import LoginForm from "./LoginForm/LoginForm";

const Login = (props) => {

    return (
        <Segment attached='bottom'>
            <LoginForm />
        </Segment>
    );
};

export default Login;
