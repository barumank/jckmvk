import style from './PageObjectsAndEstimates.module.css'
import React,{useEffect} from 'react'
import {connect} from "react-redux";
import {Segment, Header, Button, Divider, Icon, Form, Checkbox,Breadcrumb} from 'semantic-ui-react'
import ObjectDirectory from './ObjectDirectory/ObjectDirectory'
import Object from './Object/Object'
import {setPath} from "../components/Breadcrumb/reducer";
import {Link} from "react-router-dom";

const PageObjectsAndEstimates = (props) => {

    const {onSetPath} = props;
    useEffect(() => {
        onSetPath([
            {link:'/',label:'Главная',active:false},
            {link:'/',label:'Объекты и сметы',active:true}
        ]);
    });

    return (
        <>
            <Segment className={style.panel}>
                <Header as='h4'>Объекты и сметы</Header>
                <div className={style.buttonContainer}>
                    <Button as={Link} to='/object' ><Icon name='home'/> Добавить объект</Button>
                    <Button><Icon name='cloud download'/> Загрузить файл</Button>
                    <Button><Icon name='folder open'/> Создать папку</Button>
                    <Button><Icon name='plus circle'/> Добавить смету</Button>
                    <Button><Icon name='file alternate'/> Создать КП</Button>
                    <Button as={Link} to='/objects/delete'><Icon name='trash alternate'/> Корзина объектов</Button>
                </div>
                <Divider/>
                <Form className={style.checkboxContainer}>
                    <Form.Field>
                        <Checkbox
                            label='Предварительные'
                            name='checkboxRadioGroup'
                            value='this'
                        />
                    </Form.Field>
                    <Form.Field>
                        <Checkbox
                            label='В работе'
                            name='checkboxRadioGroup'
                            value='that'
                        />
                    </Form.Field>
                    <Form.Field>
                        <Checkbox
                            label='Закрытые'
                            name='checkboxRadioGroup'
                            value='that'
                        />
                    </Form.Field>
                </Form>
            </Segment>
            <Segment className={style.collection}>
                <ObjectDirectory/>
                <ObjectDirectory/><Object/> <Object/><Object/><Object/>
            </Segment>
        </>
    );
};



export default connect(
    state=>({}),
    dispatch=>({
        onSetPath(list){
            dispatch(setPath(list));
        }
    })
)(PageObjectsAndEstimates);
