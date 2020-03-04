import React from 'react'
import {NavLink, Route, Switch} from "react-router-dom";
import BaseProducts from "./BaseProducts/BaseProducts";
import CustomProducts from "./CustomProducts/CustomProducts";
import EditCategoryModal from "./EditCategoryModal/EditCategoryModal";
import DeleteCategoryModal from "./DeleteCategoryModal/DeleteCategoryModal";

const PageProducts = (props) => {


    return (
        <>
            <Switch>
                <Route path="/products/base">
                    <BaseProducts/>
                </Route>
                <Route path="/products">
                    <CustomProducts/>
                </Route>
            </Switch>
            <EditCategoryModal/>
            <DeleteCategoryModal/>
        </>
    );
};

export default PageProducts;
