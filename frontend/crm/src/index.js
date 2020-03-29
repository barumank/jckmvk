import React from "react";
import {render} from "react-dom";
import {createStore, combineReducers, applyMiddleware, compose} from 'redux'
import App from "./components/App.js";
import {Provider} from "react-redux";
import thunk from 'redux-thunk'
import {BrowserRouter} from 'react-router-dom';
import {reducer as formReducer} from 'redux-form';
import breadcrumb from './components/components/Breadcrumb/reducer'
import pageProducts from './components/PageProducts/reducer'
import pageDeleteObjects from './components/PageDeleteObjects/reducer'
import pageObject from './components/PageObject/reducer'
import AuthReducer from './components/components/Auth/reducer'
import pageAuth from './components/PageAuth/reducer'
import pageProduct from './components/PageProduct/reducer'
import pageUsers from './components/PageUsers/reducer'
import pageOrganization from './components/PageOrganization/reducer'
import pageUserSettings from './components/PageUserSettings/reducer'
import pagePageAssociateAnalog from './components/PageAssociateAnalog/reducer'
import pageProductDetail from './components/PageProductDetail/reducer'

const initialState = {};

const composeEnhancers = (typeof window !== 'undefined' && window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__) || compose;

let reducers = {
    auth:AuthReducer,
    form:formReducer,
    breadcrumb,
    pageProducts,
    pageDeleteObjects,
    pageObject,
    pageAuth,
    pageUsers,
    pageOrganization,
    pageUserSettings,
    pageProduct,
    pagePageAssociateAnalog,
    pageProductDetail
};

let storeFactory = (initialState = {}) => applyMiddleware(thunk)(createStore)(combineReducers(reducers), initialState);
if (process.env.NODE_ENV === 'development') {
    storeFactory = (initialState = {}) => composeEnhancers(applyMiddleware(thunk))(createStore)(combineReducers(reducers), initialState);
}

const store = storeFactory(initialState);

render(
    (<Provider store={store}>
        <BrowserRouter>
            <App/>
        </BrowserRouter>
    </Provider>), document.getElementById("root")
);
