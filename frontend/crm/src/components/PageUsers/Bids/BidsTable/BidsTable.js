import React from 'react'
import style from "./BidsTable.module.css";
import {Icon, Menu, Table} from "semantic-ui-react";
import * as moment from "moment";
import {Link} from "react-router-dom";
import {connect} from "react-redux";
import {getPage, getPagination, getSearch, getSearchLoading, getBids} from "../selectors";
import {modalOpen} from "../EditBid/reducer";


const pageUrlCreator = (search) => {
    if (search !== null && search !== '') {
        return (page) => `/users/bid?search=${search}&page=${page}`;
    }
    return (page) => `/users/bid?page=${page}`;
};

const BidStatus = (props) => {
    const {bid} = props;
    switch (Number.parseInt(bid.status)) {
        case appApi.user.STATUS_BID:
            return (
                <Table.Cell error textAlign='right'>Не рассмотрена</Table.Cell>
            );
        case appApi.user.STATUS_BID_CANCEL:
            return (
                <Table.Cell error textAlign='right'>Отменена</Table.Cell>
            );
        case appApi.user.STATUS_USER_BLOCKED:
        case appApi.user.STATUS_USER:
            return (
                <Table.Cell positive textAlign='right'><Icon name='checkmark'/> Подтверждена</Table.Cell>
            );
        default:
            return (<></>)
    }
};


let BidsPagination = (props) => {
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
BidsPagination = connect(
    state => ({
        currentPage: getPage(state),
        pagination: getPagination(state),
        pageUrl:pageUrlCreator(getSearch(state))
    }))(BidsPagination);


const UsersTable = (props)=>{

    const {bids,onEditModal} = props;

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
                {bids.map((item) => (
                    <Table.Row className='pointer' key={item.id} onClick={()=>onEditModal(item.id)}>
                        <Table.Cell>{item.name}</Table.Cell>
                        <Table.Cell>{item.email}</Table.Cell>
                        <Table.Cell>{moment(item.date_create,'YYYY-MM-DD HH:mm:ss').format('DD.MM.YYYY')}</Table.Cell>
                        <BidStatus bid={item}/>
                    </Table.Row>
                ))}
            </Table.Body>
            <Table.Footer>
                <BidsPagination/>
            </Table.Footer>
        </Table>
    );
};

export default connect(
    state => ({
        bids: getBids(state),
    }),
    dispatch => ({
        onEditModal(userId) {
            dispatch(modalOpen(userId));
        }
    })
)(UsersTable);