import React from 'react'
import style from "./TabMenu.module.css";
import {Menu} from "semantic-ui-react";
import {NavLink} from "react-router-dom";

const TabMenu = (props)=>{
    return (
        <Menu tabular className={style.menu}>
            <Menu.Item as={NavLink} to='/products/base'>Базовые</Menu.Item>
            <Menu.Item as={NavLink} exact to='/products'>Пользовательские</Menu.Item>
        </Menu>
    )
};

export default TabMenu;