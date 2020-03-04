import {Menu, Segment} from "semantic-ui-react";
import React from "react";
import {NavLink, Route, Switch} from "react-router-dom";


const AnalogProduct = ()=>{
    return (
            <Segment attached='bottom'>
                <Menu tabular>
                    <Menu.Item as={NavLink} to='/associate-analog/analog/base'>
                        Базовые
                    </Menu.Item>
                    <Menu.Item as={NavLink} exact to='/associate-analog/analog'>
                        Пользовательские
                    </Menu.Item>
                </Menu>
                <Switch>
                    <Route path='/associate-analog/analog/base'>
                        base analog
                    </Route>
                    <Route exect path='/associate-analog/analog'>
                        user analog
                    </Route>
                </Switch>
            </Segment>
    );
};

export default AnalogProduct
