import React from 'react'
import style from "./UsersTable.module.css";
import {Icon, Menu, Table} from "semantic-ui-react";
import * as moment from "moment";
import {Link} from "react-router-dom";
import {connect} from "react-redux";
import {getPage, getPagination, getSearch, getSearchLoading, getUsers} from "../selectors";
import {modalOpen} from "../EditUser/reducer";


const pageUrlCreator = (search) => {
    if (search !== null && search !== '') {
        return (page) => `/users?search=${search}&page=${page}`;
    }
    return (page) => `/users?page=${page}`;
};

const UserStatus = (props) => {
    const {user} = props;
    switch (Number.parseInt(user.status)) {
        case appApi.user.STATUS_USER_BLOCKED:
            return (<Table.Cell error textAlign='right'>Заблокирован</Table.Cell>);
        case appApi.user.STATUS_USER:
            return (<Table.Cell positive textAlign='right'><Icon name='checkmark'/> Активирован</Table.Cell>);
        default:
            return (<></>)
    }
};


let UserPagination = (props) => {
    const {pagination, currentPage, pageUrl} = props;
    if (pagination.length < 1) {
        return (<></>);
    }
    return (<Table.Row>
        <Table.HeaderCell colSpan='4'>
            <Menu floated='right' pagination>
                {pagination.map((item) => {
                    if (item.type === 'prev') {
                        return (<Menu.Item icon key={`${item.page}_p`} as={Link} to={pageUrl(item.page)}>
                            <Icon name='chevron left'/>
                        </Menu.Item>);
                    }
                    if (item.type === 'next') {
                        return (<Menu.Item icon key={`${item.page}_n`} as={Link} to={pageUrl(item.page)}>
                            <Icon name='chevron right'/>
                        </Menu.Item>);
                    }
                    return (
                        <Menu.Item key={item.page} as={Link} active={currentPage == item.page} to={pageUrl(item.page)}>
                            {item.label}
                        </Menu.Item>);
                })}
            </Menu>
        </Table.HeaderCell>
    </Table.Row>);
};
UserPagination = connect(
    state => ({
        currentPage: getPage(state),
        pagination: getPagination(state),
        pageUrl:pageUrlCreator(getSearch(state))
    }))(UserPagination);


const UsersTable = (props)=>{

    const {users,onEditModal} = props;

    return(
        <Table selectable celled striped className={style.table}>
            <Table.Header>
                <Table.Row>
                    <Table.HeaderCell>Имя</Table.HeaderCell>
                    <Table.HeaderCell>E-mail</Table.HeaderCell>
                    <Table.HeaderCell>Дата</Table.HeaderCell>
                    <Table.HeaderCell>Статус</Table.HeaderCell>
                </Table.Row>
            </Table.Header>
            <Table.Body>
                {users.map((user) => (
                    <Table.Row key={user.id} className='pointer' onClick={() => onEditModal(user.id)}>
                        <Table.Cell>{user.name}</Table.Cell>
                        <Table.Cell>{user.email}</Table.Cell>
                        <Table.Cell>{moment(user.date_create, 'YYYY-MM-DD HH:mm:ss').format('DD.MM.YYYY')}</Table.Cell>
                        <UserStatus user={user}/>
                    </Table.Row>
                ))}
            </Table.Body>
            <Table.Footer>
                <UserPagination />
            </Table.Footer>
        </Table>
    );
};

export default connect(
    state => ({
        users: getUsers(state),
    }),
    dispatch => ({
        onEditModal(userId) {
            dispatch(modalOpen(userId));
        }
    })
)(UsersTable);