import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import style from './PageProductDetail.module.css'
import {setPath} from '../components/Breadcrumb/reducer'
import {Button, Form, Grid, Header, Icon, Image, Message, Segment} from "semantic-ui-react";
import AnalogsTemplate from "./AnalogsTemplate/AnalogsTemplate";
import {Link, useParams} from "react-router-dom";
import {
    getProductData,
    getIsLoading,
    getProductsLoading,
    getProductTableHeader,
    getProductTableBody,
    getProductBrandData,
    getProductsBrandLoading,
    getProductTableHeaderBrand,
    getProductTableBodyBrand


} from "./selectors";
import {fetchProduct, fetchProductAnalogs, fetchProductAnalogsBrand} from "./reducer";
import ProductList from "../components/ProductList/ProductList";
import ProductContainer from "./components/ProductContainer/ProductContainer";

const ProductDetail = (props) => {
    const {product} = props;
    return (
        <Grid>
            <Grid.Row>
                <Grid.Column width='6'>
                    <strong>Название:</strong>
                </Grid.Column>
                <Grid.Column width='6'>
                    {product.name}
                </Grid.Column>
            </Grid.Row>
        </Grid>
    );
};

const PageProductDetail = (props) => {

    const {
        onSetPath,
        fetchProduct,
        productData,
        isLoading,
        onFetchProductAnalogs,
        productsLoading,
        productTableHeader,
        productTableBody,
        onFetchProductAnalogsBrand,
        productBrandTableHeader,
        productBrandTableBody,
        productsBrandLoading
    } = props;
    const params = new URLSearchParams(props.location.search);
    const productId = params.get('productId');
    useEffect(() => {
        fetchProduct(productId);
        onFetchProductAnalogs(productId, 17);
        onFetchProductAnalogsBrand(productId, 2);
    }, []);

    useEffect(() => {
        onSetPath([
            {link: '/', label: 'Главная', active: false},
            {link: '/products', label: 'Товары', active: false},
            {link: '/product/new', label: 'Товар', active: true},
        ]);
    },[]);

    return (
        <>
            <Segment className={style.containerPage} loading={isLoading}>
                <div className={style.topContainer}>
                    <Header as='h4'>Товар</Header>
                    <div className={style.buttonGroup}>
                        <Button as={Link} to='/associate-analog' className={style.attachAnalog}><Icon name='attach'/> Привязать аналог</Button>
                    </div>
                </div>

                <Grid columns={16} container divided className={style.container}>
                    <Grid.Row>
                        <Grid.Column width={4} className={style.leftColumn}>
                            { productData !== null &&  <ProductDetail product={productData.product}/>}
                        </Grid.Column>
                        <Grid.Column width={12} className={style.rightColumn}>
                            <Header as='h4'>Аналоги</Header>

                            <ProductContainer categoryName={'Материал'} loading={productsLoading}>
                                <ProductList
                                    productTableHeader={productTableHeader}
                                    productTableBody={productTableBody}
                                    pagination={0}
                                />
                            </ProductContainer>
                            <br/>
                            <ProductContainer categoryName={'Бренд'} loading={productsBrandLoading}>
                                <ProductList
                                    productTableHeader={productBrandTableHeader}
                                    productTableBody={productBrandTableBody}
                                    pagination={0}
                                />
                            </ProductContainer>
                        </Grid.Column>
                    </Grid.Row>
                </Grid>
            </Segment>
        </>
    );
};

export default connect(
    state => ({
        isLoading: getIsLoading(state),

        productData: getProductData(state),
        productsLoading: getProductsLoading(state),
        productTableHeader: getProductTableHeader(state),
        productTableBody: getProductTableBody(state),

        productBrandData: getProductBrandData(state),
        productsBrandLoading: getProductsBrandLoading(state),
        productBrandTableHeader: getProductTableHeaderBrand(state),
        productBrandTableBody: getProductTableBodyBrand(state),
    }),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
        fetchProduct(productId) {
            dispatch(fetchProduct(productId));
        },
        onFetchProductAnalogs(productId, groupId) {
            dispatch(fetchProductAnalogs(productId,groupId));
        },
        onFetchProductAnalogsBrand(productId, groupId) {
            dispatch(fetchProductAnalogsBrand(productId,groupId));
        }

    })
)(PageProductDetail);
