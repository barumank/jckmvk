import React from 'react'
import style from './ProductsTemplate.module.css'
import {Segment} from "semantic-ui-react";
import TabMenu from "../TabMenu/TabMenu";

const ProductsTemplate = (props) => {

    return (
        <Segment className={style.productContainer}>
            <TabMenu/>
            {props.children}
        </Segment>

    );
};
export default ProductsTemplate;
