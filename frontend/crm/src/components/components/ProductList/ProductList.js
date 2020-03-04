import React from "react";
import {Icon, Menu, Table} from "semantic-ui-react";
import style from './ProductList.module.css'
import {Link} from "react-router-dom";
import {createSelectorCreator, defaultMemoize} from "reselect";

const renderProductListSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    if (typeof object === 'function') {
        return true;
    }
    return object === other;
});

const renderProductList = renderProductListSelectorCreator([
        (props) => props.openProductPage,
        (props) => props.productsPage,
        (props) => props.pagination,
        (props) => props.productTableHeader,
        (props) => props.productTableBody
    ], (openProductPage, productsPage, pagination, productTableHeader, productTableBody) => {

        const getHeader = () => {
            let [idCol, nameCol, ...list] = productTableHeader;
            return (
                <Table.Row>
                    <Table.HeaderCell key={idCol.key} className={style.colTableId}>{idCol.value}</Table.HeaderCell>
                    <Table.HeaderCell key={nameCol.key} className={style.colTableName}>{nameCol.value}</Table.HeaderCell>
                    {list.map((item) => (
                        <Table.HeaderCell key={item.key} title={item.value}>{item.value}</Table.HeaderCell>))}
                </Table.Row>
            );
        };

        if (productTableBody.length === 0) {
            return (<></>);
        }
        return (
            <Table className={style.tableContainer} celled selectable>
                <Table.Header>
                    {getHeader()}
                </Table.Header>
                <Table.Body>
                    {productTableBody.map((row) => {
                        let [idCol, nameCol, ...list] = row.list;
                        return (
                            <Table.Row key={row.id}>
                                <Table.Cell key={idCol.key} className={style.colTableId}>{idCol.value}</Table.Cell>
                                <Table.Cell key={nameCol.key} className={style.colTableName} title={nameCol.value}>
                                    {nameCol.value}
                                </Table.Cell>
                                {list.map((item) => (
                                    <Table.Cell key={item.key} title={item.value}>{item.value}</Table.Cell>
                                ))}
                            </Table.Row>
                        )
                    })}
                </Table.Body>
                <Table.Footer>
                    {pagination.length > 0 ?
                        (<Table.Row>
                            <Table.HeaderCell colSpan={productTableHeader.length}>
                                <Menu pagination>
                                    {pagination.map((item) => {
                                        if (item.type === 'prev') {
                                            return (
                                                <Menu.Item icon key={`${item.page}_p`} as={Link}
                                                           to={openProductPage(item.page)}>
                                                    <Icon name='chevron left'/>
                                                </Menu.Item>
                                            );
                                        }
                                        if (item.type === 'next') {
                                            return (
                                                <Menu.Item icon key={`${item.page}_n`} as={Link}
                                                           to={openProductPage(item.page)}>
                                                    <Icon name='chevron right'/>
                                                </Menu.Item>
                                            );
                                        }
                                        return (
                                            <Menu.Item key={item.page} as={Link} active={productsPage == item.page}
                                                       to={openProductPage(item.page)}>{item.label}</Menu.Item>);
                                    })}
                                </Menu>
                            </Table.HeaderCell>
                        </Table.Row>) : (<></>)
                    }
                </Table.Footer>
            </Table>
        );
    }
);

const ProductList = (props) => {
    return renderProductList(props);
};
export default ProductList;
