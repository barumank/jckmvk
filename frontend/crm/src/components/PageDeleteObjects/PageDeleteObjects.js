import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import style from './PageDeleteObjects.module.css'
import {setPath} from '../components/Breadcrumb/reducer'
import {Search, Header, Segment, Tab} from "semantic-ui-react";
import Object from "./Object/Object";

const PageDeleteObjects = (props) => {

    const {onSetPath} = props;
    useEffect(() => {
        onSetPath([
            {link: '/', label: 'Главная', active: false},
            {link:'/',label:'Объекты и сметы',active:false},
            {link:'/objects/delete',label:'Корзина объектов',active:true}
        ]);
    });

    return (
        <>
            <Segment className={style.searchContainer}>
                <Header as='h4'>Корзина объектов</Header>
                <Search
                    className={style.searchInput}
                    loading={false}
                    placeholder="Поиск товара по артиклу / наименованию / бренду..."
                    /*onResultSelect={this.handleResultSelect}
                    onSearchChange={_.debounce(this.handleSearchChange, 500, {
                        leading: true,
                    })}
                    results={results}
                    value={value}
                    resultRenderer={resultRenderer}
                    {...this.props}*/
                />
            </Segment>
            <Segment className={style.collection}>
                <Object/> <Object/><Object/><Object/>
            </Segment>
        </>
    );
};

export default connect(
    state => ({}),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        }
    })
)(PageDeleteObjects);
