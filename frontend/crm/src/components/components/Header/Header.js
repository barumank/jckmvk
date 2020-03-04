import React from 'react'
import {Button, Container, Menu, Image, Item, Icon} from "semantic-ui-react";
import styles from './Header.module.css'
import {NavLink} from "react-router-dom"
import {connect} from "react-redux";
import UserSecurity from "../Auth/Security/UserSecurity";
import {getUser} from "../Auth/selectors";
import AdminSecurity from "../Auth/Security/AdminSecurity";
import {setCurrentUser} from "../Auth/reducer";

const UserImage = (props) => {
    const {user} = props;
    if (user.image === null || user.image === '') {
        return (<Image circular src='/img/no_image.png'/>)
    }
    return (<Image circular src={`/upload/users/${user.id}/${user.image}`}/>)
};

const Header = ({user, logout}, ...props) => {

    return (
        <Menu pointing secondary className={styles.header}>
            <Container className={styles.container}>
                <Menu.Menu className={styles.logoContainer} as={NavLink} to='/'>
                    <img src='/img/logo.png' className={styles.logo}/>
                </Menu.Menu>
                <AdminSecurity>
                    <Menu.Item as={NavLink} to="/users" className={styles.menuItem}>Пользователи</Menu.Item>
                </AdminSecurity>
                <UserSecurity>
                    <Menu.Item as={NavLink} to="/" exact className={styles.menuItem}>Объекты и сметы</Menu.Item>
                </UserSecurity>
               {/* <AdminSecurity>
                    <Menu.Item
                        name='prise' className={styles.menuItem}
                        onClick={function () {

                        }}>
                        Прайсы
                    </Menu.Item>
                </AdminSecurity>*/}
                <UserSecurity>
                    <Menu.Item as={NavLink} to="/products" className={styles.menuItem}>Товары</Menu.Item>
                    {((user) => {
                        if (user === null) {
                            return (<></>)
                        }
                        return (<Menu.Menu position='right' className={styles.menuRight}>
                            <Button.Group basic vertical className={styles.buttonGroup}>
                                <Button className={styles.button} as={NavLink} to="/settings">
                                    <Icon name='setting'/>
                                    Настройки профиля
                                </Button>
                                <Button className={styles.button} as={NavLink} to="/organization">
                                    <Icon name='trophy'/>
                                    Организация
                                </Button>
                                <Button className={styles.button} as={NavLink} to="/estimate-templates">
                                    <Icon name='file alternate'/>
                                    Шаблоны для сметы
                                </Button>
                            </Button.Group>
                            <Item className={styles.user}>
                                <UserImage user={user}/>
                                <Item.Content>
                                    <Item.Meta>{user.name}</Item.Meta>
                                    <Item.Meta>{user.last_name}</Item.Meta>
                                    <Button onClick={() => logout()}>
                                        <Icon name='sign out'/>
                                        logout
                                    </Button>
                                </Item.Content>
                            </Item>
                        </Menu.Menu>);
                    })(user)}
                </UserSecurity>
            </Container>
        </Menu>
    );
};
export default connect(
    store => ({
        user: getUser(store),
    }),
    dispatch => ({
        logout() {
            appApi.auth.logout().then((response) => {
                dispatch(setCurrentUser(null))
            });
        }
    })
)(Header);
