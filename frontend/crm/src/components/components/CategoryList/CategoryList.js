import React from 'react'
import ProductCategory from "../ProductCategory/ProductCategory";
import {createSelectorCreator, defaultMemoize} from "reselect";

const renderCategorySelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    if (typeof object === 'function') {
        return true;
    }
    return object === other;
});

const renderCategory = renderCategorySelectorCreator([
        (props) => props.categories,
        (props) => props.currentCategoryId,
        (props) => props.onSelectCategory,
        (props) => props.onOpenCategory,
        (props) => props.onEditCategory,
        (props) => props.onDeleteCategory,
    ],
    (categories,
     currentCategoryId,
     onSelectCategory,
     onOpenCategory,
     onEditCategory,
     onDeleteCategory) => {

        return (<> {categories.map((item) => {
            return (
                <ProductCategory
                    key={item.id}
                    id={item.id}
                    name={item.name}
                    hasChildren={item.hasChildren}
                    userId={item.user_id}
                    image={item.image}
                    currentCategoryId={currentCategoryId}
                    onSelectCategory={onSelectCategory}
                    onOpenCategory={onOpenCategory}
                    onEditCategory={onEditCategory}
                    onDeleteCategory={onDeleteCategory}
                />
            );
        })}</>)
    });

const CategoryList = (props) => {
    return renderCategory(props)
};

export default CategoryList