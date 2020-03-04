import React from 'react'
import {connect} from 'react-redux'
import {Route, Switch} from "react-router-dom";
import Bid from "./Bids/Bids";
import Users from "./Users/Users";

const PageUsers = (props) => {

    return (
        <Switch>
            <Route path="/users/bid">
                <Bid/>
            </Route>
            <Route exact path="/users">
                <Users/>
            </Route>
        </Switch>
    );
};

export default PageUsers;