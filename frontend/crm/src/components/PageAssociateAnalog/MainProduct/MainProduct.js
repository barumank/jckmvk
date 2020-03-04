import {Button, Icon, Menu, Search, Segment} from "semantic-ui-react";
import React from "react";
import {NavLink, Route, Switch} from "react-router-dom";
import CategoryList from "../CategoryList/CategoryList";


const MainProduct = ()=>{
    return (
        <Segment attached='bottom'>
            <Menu tabular>
                <Menu.Item as={NavLink} to='/associate-analog/base'>
                    Базовые
                </Menu.Item>
                <Menu.Item as={NavLink} exact to='/associate-analog'>
                    Пользовательские
                </Menu.Item>
            </Menu>
            <Switch>
                <Route path='/associate-analog/base'>
                    <CategoryList/>
                </Route>
                <Route exect path='/associate-analog'>
                    <CategoryList/>
                </Route>
            </Switch>
        </Segment>
    );
};

export default MainProduct
