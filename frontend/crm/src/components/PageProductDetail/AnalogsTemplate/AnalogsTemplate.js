import React from 'react'
import style from './AnalogsTemplate.module.css'
import {Button, Header, Icon, Segment, Table} from "semantic-ui-react";

const AnalogsTemplate = (props) => {

    return (
        <Segment>
            <div className={style.header}>
                <Header as='h4'>Материал</Header>
                <div className={style.headerControl}>
                    <Button><Icon name='angle double up'/></Button>
                </div>
            </div>
            <Table selectable celled striped className={style.table}>
                <Table.Header>
                    <Table.Row>
                        <Table.HeaderCell>№</Table.HeaderCell>
                        <Table.HeaderCell>Наименование</Table.HeaderCell>
                        <Table.HeaderCell>Диаметр</Table.HeaderCell>
                        <Table.HeaderCell>Материал</Table.HeaderCell>
                        <Table.HeaderCell>Бренд</Table.HeaderCell>
                    </Table.Row>
                </Table.Header>
                <Table.Body>
                    <Table.Row>
                        <Table.Cell> тест тест</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Cell> тест тест</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Cell> тест тест</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                    </Table.Row>
                </Table.Body>
            </Table>
        </Segment>
    );
};

export default AnalogsTemplate;
