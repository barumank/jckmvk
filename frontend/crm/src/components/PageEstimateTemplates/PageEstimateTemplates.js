import React,{useEffect} from 'react'
import style from "./PageEstimateTemplates.module.css";
import {Segment} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../components/Breadcrumb/reducer";

const PageEstimateTemplates = (props) => {

    const {setPath} = props;

    useEffect(() => {
        setPath([
            {link: '/', label: 'Главная', active: false},
            {link: '/estimate-templates', label: 'Шаблоны для сметы', active: true}
        ]);
    }, []);

    return (
        <Segment className={style.container} loading={false}>
        </Segment>
    )
};

export default connect(()=>{},{
    setPath
})(PageEstimateTemplates);