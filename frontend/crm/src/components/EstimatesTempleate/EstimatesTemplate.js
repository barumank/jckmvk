import React from 'react'
import style from './EstimatesTemplate.module.css'
import {Button, Header, Icon, Segment, Table} from "semantic-ui-react";
import AddRow from "./AddRow/AddRow";

const EstimatesTemplate = (props) => {

    return (
        <Segment className='colorItem'>
            <div className={style.header}>
                <Header as='h4'>Радиаторы</Header>
                <div className={style.headerControl}>
                    <Button><Icon name='save'/></Button>
                    <Button><Icon name='copy'/></Button>
                    <Button><Icon name='angle double up'/></Button>
                    <Button><Icon name='trash alternate'/></Button>
                </div>
            </div>
            <Table selectable celled className={style.table}>
                <Table.Header>
                    <Table.Row>
                        <Table.HeaderCell>№</Table.HeaderCell>
                        <Table.HeaderCell>Наименование</Table.HeaderCell>
                        <Table.HeaderCell>Количество</Table.HeaderCell>
                        <Table.HeaderCell>РРЦ</Table.HeaderCell>
                        <Table.HeaderCell className={style.calcColumn}>Сумма закупа</Table.HeaderCell>
                        <Table.HeaderCell className={style.calcColumn}>Скидка в КП</Table.HeaderCell>
                        <Table.HeaderCell className={style.calcColumn}>Наценка</Table.HeaderCell>
                        <Table.HeaderCell>Действия</Table.HeaderCell>
                    </Table.Row>
                </Table.Header>
                <Table.Body>
                    <AddRow offset={250} trigger={<Table.Row>
                        <Table.Cell> тест тест</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell className={style.tableControl}>
                            <Icon name='exchange'/><Icon name='copy'/><Icon name='trash alternate'/>
                        </Table.Cell>
                    </Table.Row>
                    }/>

                    <AddRow offset={250} trigger={<Table.Row>
                        <Table.Cell> тест тест</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell>232</Table.Cell>
                        <Table.Cell className={style.tableControl}>
                            <Icon name='exchange'/><Icon name='copy'/><Icon name='trash alternate'/>
                        </Table.Cell>
                    </Table.Row>
                    }/><AddRow offset={250} trigger={<Table.Row>
                    <Table.Cell> тест тест</Table.Cell>
                    <Table.Cell>232</Table.Cell>

                    <Table.Cell>232</Table.Cell>
                    <Table.Cell>232</Table.Cell>
                    <Table.Cell>232</Table.Cell>
                    <Table.Cell>232</Table.Cell>
                    <Table.Cell>232</Table.Cell>
                    <Table.Cell className={style.tableControl}>
                        <Icon name='exchange'/><Icon name='copy'/><Icon name='trash alternate'/>
                    </Table.Cell>
                </Table.Row>
                }/>
                </Table.Body>
            </Table>
            <Table celled className={`${style.table} ${style.calcTable} colorItem`}>
                <Table.Body>
                    <Table.Row>
                        <Table.Cell colSpan={4} rowSpan={2} verticalAlign='top' className={style.addButtonColl}>
                            <Button className='buttonAddRow'>Добавить позицию</Button>
                        </Table.Cell>
                        <Table.Cell className={style.calcColumn}>Итог</Table.Cell>
                        <Table.Cell className={style.calcColumn}>Итог</Table.Cell>
                        <Table.Cell className={style.calcColumn}>Итог</Table.Cell>
                        <Table.Cell className={style.tableControl}>
                        </Table.Cell>
                    </Table.Row>
                    <Table.Row>
                        <Table.Cell className={`${style.calcColumn} ${style.border}`}>232</Table.Cell>
                        <Table.Cell className={`${style.calcColumn} ${style.border}`}>232</Table.Cell>
                        <Table.Cell className={`${style.calcColumn} ${style.border}`}>232</Table.Cell>
                        <Table.Cell className={style.tableControl}>
                        </Table.Cell>
                    </Table.Row>
                </Table.Body>
            </Table>
        </Segment>
    );
};

export default EstimatesTemplate;