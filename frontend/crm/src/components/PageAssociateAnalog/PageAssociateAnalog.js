import React from 'react'
import style from './PageAssociateAnalog.module.css'
import {Menu, Header, Segment, Button, Dropdown, Input, Search, Icon} from "semantic-ui-react";
import {NavLink, Route, Switch} from "react-router-dom";
import MainProduct from "./MainProduct/MainProduct";
import AnalogProduct from "./AnalogProduct/AnalogProduct";

const PageAssociateAnalog = (props) => {
    const isActiveMainLink = (match, location) => {
        return ['/associate-analog', '/associate-analog/base'].includes(location.pathname)
    };
    return (
        <>
            <Segment>
                <Header as='h4'>Сопоставление аналогов</Header>
                <Menu attached='top' tabular>
                    <Menu.Item as={NavLink} isActive={isActiveMainLink} className={style.tabItemColumn}
                               to='/associate-analog'>
                        <div>Основной товар</div>
                        <Search fluid/>
                    </Menu.Item>
                    <Menu.Item as={NavLink} className={style.tabItemColumn} to='/associate-analog/analog'>
                        <div>Товар анатлог</div>
                        <Search fluid/>
                    </Menu.Item>
                    <Menu.Item as={NavLink} className={style.tabItemColumn} to='/associate-analog/property'>
                        Свойство аналогичнсти
                    </Menu.Item>
                    <Menu.Item>
                        <Button positive className={style.bindButton}>
                            <Icon name='checkmark'/> Связать товар
                        </Button>
                    </Menu.Item>
                </Menu>
                <Switch>
                    <Route exact path={['/associate-analog', '/associate-analog/base']}>
                        <MainProduct/>
                    </Route>
                    <Route exact path={['/associate-analog/analog', '/associate-analog/analog/base']}>
                        <AnalogProduct/>
                    </Route>
                </Switch>
            </Segment>
        </>
    );
};

export default PageAssociateAnalog;