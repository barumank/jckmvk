import React from 'react'
import {Menu, Segment} from "semantic-ui-react";
import {NavLink, Route, Switch} from "react-router-dom";
import Login from "./Login/Login";
import Registration from "./Registration/Registration";
import {connect} from "react-redux";
import {getUser} from "../components/Auth/selectors";
import {Redirect} from "react-router-dom";

const PageAuth = (props)=>{

    if(props.user !==null){
        return(
          <Redirect to='/'/>
        );
    }

    return(
        <Segment>
            <Menu attached='top' tabular>
                <Menu.Item as={NavLink} to='/auth/login'>Войти</Menu.Item>
                <Menu.Item as={NavLink} to='/auth/registration'>Регистрация</Menu.Item>
            </Menu>
            <Switch>
                <Route path="/auth/login">
                    <Login/>
                </Route>
                <Route path="/auth/registration">
                    <Registration/>
                </Route>
            </Switch>
        </Segment>
    )
};

export default connect((state)=>({
    user:getUser(state)
}),null)(PageAuth)
