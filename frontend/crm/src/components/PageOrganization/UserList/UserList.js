import React from 'react'
import style from './UserList.module.css'
import {Button, Header, Segment, Table} from "semantic-ui-react";

const UserList = (props) => {
    return (
        <Segment>
            <Header as='h4'>
                Сотрудники
                <Button positive floated='right'>Добавить</Button>
            </Header>
            <Table singleLine>
                <Table.Header>
                    <Table.Row>
                        <Table.HeaderCell/>
                        <Table.HeaderCell>Имя пользователя</Table.HeaderCell>
                        <Table.HeaderCell>Должность</Table.HeaderCell>
                        <Table.HeaderCell>Email</Table.HeaderCell>
                    </Table.Row>
                </Table.Header>
                <Table.Body>
                    <Table.Row>
                        <Table.Cell>John Lilki</Table.Cell>
                        <Table.Cell>September 14, 2013</Table.Cell>
                        <Table.Cell>jhlilk22@yahoo.com</Table.Cell>
                        <Table.Cell>No</Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Cell>Jamie Harington</Table.Cell>
                        <Table.Cell>January 11, 2014</Table.Cell>
                        <Table.Cell>jamieharingonton@yahoo.com</Table.Cell>
                        <Table.Cell>Yes</Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Cell>Jill Lewis</Table.Cell>
                        <Table.Cell>May 11, 2014</Table.Cell>
                        <Table.Cell>jilsewris22@yahoo.com</Table.Cell>
                        <Table.Cell>Yes</Table.Cell>
                    </Table.Row>
                </Table.Body>
            </Table>
        </Segment>
    );
};

export default UserList