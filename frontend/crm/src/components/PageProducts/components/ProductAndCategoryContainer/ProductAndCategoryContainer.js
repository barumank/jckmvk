import React from 'react'
import style from "./ProductAndCategoryContainer.module.css";

const ProductAndCategoryContainer = (props)=>{
    const {children} = props;

    return(
        <div className={style.container}>
            {children}
        </div>
    )
};
export default ProductAndCategoryContainer;