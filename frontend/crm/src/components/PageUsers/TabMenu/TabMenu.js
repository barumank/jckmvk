import React from 'react'
import {Menu} from "semantic-ui-react";
import style from "./TabMenu.module.css";
import {NavLink} from "react-router-dom";

const TabMenu = (props)=>{
    return (
        <Menu tabular className={style.menu}>
            <Menu.Item as={NavLink} to='/users/bid'>Заявки</Menu.Item>
            <Menu.Item as={NavLink} exact to='/users'>Пользователи</Menu.Item>
        </Menu>
    );
};
export default TabMenu;