import React from "react";
import 'semantic-ui-css/semantic.min.css'
import '../styles/style.css'
import styles from './App.module.css'
import Header from './components/Header/Header'
import Footer from './components/Footer/Footer'
import Breadcrumb from './components/Breadcrumb/Breadcrumb'
import {
    Container,
} from 'semantic-ui-react'

import {Switch, Route} from "react-router-dom"

import PageObjectsAndEstimates from "./PageObjectsAndEstimates/PageObjectsAndEstimates";
import PageProducts from './PageProducts/PageProducts'
import PageProduct from './PageProduct/PageProduct'
import PageDeleteObjects from './PageDeleteObjects/PageDeleteObjects'
import PageObject from './PageObject/PageObject'
import PageUsers from './PageUsers/PageUsers'
import PageAssociateAnalog from "./PageAssociateAnalog/PageAssociateAnalog";
import PageAuth from "./PageAuth/PageAuth";
import Auth from "./components/Auth/Auth";
import {AdminRoute, UserRoute} from "./components/Auth/Route";
import PageOrganization from "./PageOrganization/PageOrganization";
import PageUserSettings from "./PageUserSettings/PageUserSettings";
import PageEstimateTemplates from "./PageEstimateTemplates/PageEstimateTemplates";
import PageProductDetail from "./PageProductDetail/PageProductDetail";


const App = () => (
    <Auth>
        <Header/>
        <Container className={styles.container}>
            <Breadcrumb/>
            <Switch>
                <Route path="/auth">
                    <PageAuth/>
                </Route>
                <UserRoute exact path="/">
                    <PageObjectsAndEstimates/>
                </UserRoute>
                <AdminRoute path="/users">
                    <PageUsers/>
                </AdminRoute>
                <UserRoute path="/objects/delete">
                    <PageDeleteObjects/>
                </UserRoute>
                <UserRoute path="/object/:id?">
                    <PageObject/>
                </UserRoute>
                <UserRoute path="/products">
                    <PageProducts/>
                </UserRoute>
                <UserRoute path="/product/new">
                    <PageProduct/>
                </UserRoute>
                <UserRoute path="/product/:productId">
                    <PageProductDetail/>
                </UserRoute>
                <UserRoute path="/associate-analog">
                    <PageAssociateAnalog/>
                </UserRoute>
                <UserRoute path="/organization">
                    <PageOrganization/>
                </UserRoute>
                <UserRoute path="/settings">
                    <PageUserSettings/>
                </UserRoute>
                <UserRoute path="/estimate-templates">
                    <PageEstimateTemplates/>
                </UserRoute>
            </Switch>
        </Container>
        <Footer/>
    </Auth>
);

export default App;