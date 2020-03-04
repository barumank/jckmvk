import React from 'react'
import style from './TabContainer.module.css'
import {Menu} from "semantic-ui-react";
import {Route, NavLink, Switch} from "react-router-dom";
import Information from './Information/Information'
import Client from './Client/Client'
import Files from './Files/Files'
import EstimatesCreate from './EstimatesCreate/EstimatesCreate'
import EstimatesInProgress from './EstimatesInProgress/EstimatesInProgress'
import Offer from './Offer/Offer'
import {connect} from "react-redux";
import {setPath} from "../../components/Breadcrumb/reducer";

const TabContainer = (props) => {

    return (
        <>
            <Menu tabular className={style.menu}>
                <Menu.Item as={NavLink} exact to='/object'>Информация</Menu.Item>
                <Menu.Item as={NavLink} to='/object/client'>Заказчик</Menu.Item>
                <Menu.Item as={NavLink} to='/object/files'>Файлы</Menu.Item>
                <Menu.Item as={NavLink} to='/object/estimates-create'>Смета (создать)</Menu.Item>
                <Menu.Item as={NavLink} to='/object/offer'>КП</Menu.Item>
                <Menu.Item as={NavLink} to='/object/estimates-in-progress'>Смета в работе</Menu.Item>
            </Menu>
            <Switch>
                <Route path="/object/:id?/client">
                    <Client/>
                </Route>
                <Route path="/object/:id?/files">
                    <Files/>
                </Route>
                <Route path="/object/:id?/estimates-create">
                    <EstimatesCreate/>
                </Route>
                <Route path="/object/:id?/offer">
                    <Offer/>
                </Route>
                <Route path="/object/:id?/estimates-in-progress">
                    <EstimatesInProgress/>
                </Route>
                <Route path="/object/:id?" exact>
                    <Information/>
                </Route>
            </Switch>
        </>
    );


    /*const panes = [

        {
            menuItem: {
                as: NavLink,
                content: "Информация",
                to: "/object",
                key: "information",
                exact: true,
                active: false,
            },
            render: () => (
                <Route path="/object/:id?" exact>
                    <Information/>
                </Route>
            )
        },
        {
            menuItem: {
                as: NavLink,
                content: "Заказчик",
                to: "/object/client",
                key: "client",
                active: false,
            },
            render: () => (
                <Route path="/object/:id?/client">
                    <Client/>
                </Route>
            )
        },
        {
            menuItem: {
                as: NavLink,
                content: "Файлы",
                to: "/object/files",
                key: "files",
                active: false,
            },
            render: () => (
                <Route path="/object/:id?/files">
                    <Files/>
                </Route>
            )
        },
        {
            menuItem: {
                as: NavLink,
                content: "Смета (создать)",
                to: "/object/estimates-create",
                key: "estimatesCreate",
                active: false,
            },
            render: () => (
                <Route path="/object/:id?/estimates-create">
                    <EstimatesCreate/>
                </Route>
            )
        },
        {
            menuItem: {
                as: NavLink,
                content: "КП",
                to: "/object/offer",
                key: "offer",
                active: false,
            },
            render: () => (
                <Route path="/object/:id?/offer">
                    <Offer/>
                </Route>
            )
        },
        {
            menuItem: {
                as: NavLink,
                content: "Смета в работе",
                to: "/object/estimates-in-progress",
                key: "estimatesInProgress",
                active: false,
            },
            render: () => (
                <Route path="/object/:id?/estimates-in-progress">
                    <EstimatesInProgress/>
                </Route>
            )
        },
    ];

    const defaultActiveIndex = panes.findIndex(pane => {
        return !!matchPath(window.location.pathname, {
            path: pane.menuItem.to,
            exact: true
        });
    });

    return (
        <Tab defaultActiveIndex={defaultActiveIndex} panes={panes} className={style.tabContainer}/>
    )*/
};

export default connect(
    state => ({}),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        }
    })
)(TabContainer);